<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\PengaturanAbsensi;
use App\Models\Absensi;
use App\Models\JabatanOrganisasi;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Logika Filter
        if ($request->ajax()) {
            $date = $request->input('date');
            $month = $request->input('month');
            $year = $request->input('year');

            /*Log::info("Tanggal yang diterima: " . $date);*/

            $query = Absensi::query();

            /*// Log Query
            Log::info("Query sebelum filter: " . $query->toSql());*/

            if ($date) {
                $tanggal_mulai_input = date('Y-m-d 00:00:00', strtotime($date));
                $tanggal_terakhir_akhir = date('Y-m-d 23:59:59', strtotime($date));

                /*Log::info("Tanggal mulai: " . $tanggal_mulai_input . " Tanggal terakhir: " . $tanggal_terakhir_akhir);*/
                $query->whereBetween('created_at', [$tanggal_mulai_input, $tanggal_terakhir_akhir]);
            }
            if ($month) {
                $tanggal_mulai_bulan = date('Y-m-01 00:00:00', strtotime($month));
                $tanggal_akhir_bulan = date('Y-m-t 23:59:59', strtotime($month));
                $query->whereBetween('created_at', [$tanggal_mulai_bulan, $tanggal_akhir_bulan]);
            }

            if ($year) {
                $tanggal_mulai_tahun = "{$year}-01-01 00:00:00";
                $tanggal_akhir_tahun = "{$year}-12-31 23:59:59";

                $query->whereBetween('created_at', [$tanggal_mulai_tahun, $tanggal_akhir_tahun]);
            }

            $pengguna_aktif = auth()->user();
            if ($pengguna_aktif->role === 'pegawai') {
                $query->where('user_id', $pengguna_aktif->id);
            }

            // Log Query setelah filter
            /*Log::info("Query setelah filter: " . $query->toSql());*/

            // Dapatkan hasilnya
            $filteredData = $query->with('user')->get();

            /*Log::info("Data hasil filter: " . $filteredData->toJson());*/

            return response()->json(['data' => $filteredData]);
        }


        // 1. SYARAT WAKTU
        $waktu_sekarang = Carbon::now();
        $pengaturan_absensis = PengaturanAbsensi::first();
        $waktu_masuk_buka = Carbon::parse($pengaturan_absensis->waktu_buka);
        $waktu_masuk_tutup = Carbon::parse($pengaturan_absensis->waktu_buka)->addMinutes(30);
        $waktu_pulang_buka = Carbon::parse($pengaturan_absensis->waktu_tutup);
        $waktu_pulang_tutup = Carbon::parse($pengaturan_absensis->waktu_tutup)->addMinutes(30);

        // 2. SYARAT ALAMAT IP
        $ip_pengguna = $request->ip();
        $hasil_cek_ip = PengaturanAbsensi::where('rentang_awal_IP', '<=', $ip_pengguna)
            ->where('rentang_akhir_IP', '>=', $ip_pengguna)
            ->exists();

        // 3. SYARAT TIDAK MELAKUKAN DOUBLE ABSENSI PADA HARI YANG SAMA
        $absensi_hari_ini = Absensi::where('user_id', auth()->id())
            ->whereDate('created_at', Carbon::today())
            ->first();
        $hasil_cek_double_absensi = $absensi_hari_ini !== null;

        // 4. DISABLE KONDISI
        // Disable
        // 4. DISABLE KONDISI
        $disableMasuk = !$waktu_sekarang->between($waktu_masuk_buka, $waktu_masuk_tutup);
        $disablePulang = !$waktu_sekarang->between($waktu_pulang_buka, $waktu_pulang_tutup);

        $disableAll = !$hasil_cek_ip || $disableMasuk && $disablePulang;


        // 5. MENGAMBIL DATA ABSENSI SESUAI PENGGUNA
        $pengguna_aktif = auth()->user();
        if ($pengguna_aktif->email == 'admin@material.com') {
            $absensis = Absensi::all();
        } else {
            $absensis = $pengguna_aktif->absensis;
        }


        // 6. FITUR PENCARIAN (jika ada, tambahkan logika di sini)

        return view('Absensi.index', compact(
            'hasil_cek_ip',
            'hasil_cek_double_absensi',
            'disableMasuk',
            'disablePulang',
            'disableAll',
            'absensis'
        ));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $request->validate([
            'status_absensi_masuk' => 'nullable|string|in:Hadir,Izin,Aplha',
            'status_absensi_pulang' => 'nullable|string|in:Pulang',
        ]);

        $userId = auth()->id();
        $today = now()->toDateString();

        // Cari data absensi untuk hari ini
        $absensi = Absensi::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->first();

        // Absensi Masuk
        if ($request->status_absensi_masuk) {
            if ($absensi) {
                return redirect()->back()->with('error', 'Anda sudah melakukan absensi masuk hari ini.');
            }

            $absensi = new Absensi();
            $absensi->user_id = $userId;
            $absensi->waktu_masuk = now(); // Waktu masuk
            $absensi->status_absensi_masuk = $request->status_absensi_masuk;
            $absensi->save();

            return redirect()->back()->with('sukses', 'Absensi masuk berhasil!');
        }

        // Absensi Pulang
        if ($request->status_absensi_pulang) {
            if (!$absensi || !$absensi->waktu_masuk || $absensi->status_absensi_masuk != 'Hadir') {
                return redirect()->back()->with('error', 'Anda belum melakukan absensi masuk dengan status "hadir".');
            }

            if ($absensi->waktu_pulang) {
                return redirect()->back()->with('error', 'Anda sudah melakukan absensi pulang hari ini.');
            }


            $absensi->waktu_pulang = now(); // Waktu pulang
            $hour = $absensi->waktu_pulang->hour; // Ambil jam dari waktu pulang

            // Tentukan keterangan berdasarkan waktu pulang
            if ($hour < 18) {
                $keterangan = 'Pulang Cepat';
            } elseif ($hour >= 20) {
                $keterangan = 'Lembur';
            } else {
                $keterangan = 'Pulang'; // Default
            }

            $absensi->status_absensi_pulang = $keterangan;
            $absensi->save();

            return redirect()->back()->with('sukses', 'Absensi pulang berhasil!');
        }

        return redirect()->back()->with('error', 'Permintaan absensi tidak valid.');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function downloadPDF(Request $request)
    {
        $query = Absensi::query();

        // Menambahkan filter berdasarkan waktu
        if ($request->filter == 'date') {
            $query->whereDate('created_at', $request->value);
        } elseif ($request->filter == 'month') {
            $query->whereYear('created_at', substr($request->value, 0, 4))
                ->whereMonth('created_at', substr($request->value, 5, 2));
        } elseif ($request->filter == 'year') {
            $query->whereYear('created_at', $request->value);
        }

        // Mengecek apakah pengguna aktif adalah admin atau bukan
        $penggunaAktif = auth()->user();
        if ($penggunaAktif->email !== 'admin@material.com') {
            $query->where('user_id', $penggunaAktif->id);
        }

        // Mengambil data absensi dengan eager loading
        $absensis = $query->with('user.data_pribadi.jabatan_organisasi')->orderBy('created_at', 'desc')->get();

        $laporanGaji = [];
        $groupedAbsensi = $absensis->groupBy('user.id');

        foreach ($groupedAbsensi as $userId => $userAbsensi) {
            $user = $userAbsensi->first()->user;
            $jabatan = $user->data_pribadi ? $user->data_pribadi->jabatan_organisasi : null;

            $gajiPokok = $jabatan ? $jabatan->besaran_gaji : 0;
            $jumlahAlpha = 0;
            $jumlahIzin = 0;
            $jumlahPulangCepat = 0;

            $pinalti = 50000; // Penalti per alpha, izin lebih dari 3x, dan pulang cepat lebih dari 3x

            foreach ($userAbsensi as $absensi) {
                if ($absensi->status_absensi_masuk === 'Alpha') {
                    $jumlahAlpha++;
                } elseif ($absensi->status_absensi_masuk === 'Izin') {
                    $jumlahIzin++;
                }
                if ($absensi->status_absensi_pulang === 'Pulang Cepat') {
                    $jumlahPulangCepat++;
                }
            }

            // Hitung penalti masuk (izin lebih dari 3x dan alpha langsung kena penalti)
            $totalPinaltiMasuk = max(0,$jumlahAlpha - 3) * $pinalti + max(0, $jumlahIzin - 3) * $pinalti;

            // Hitung penalti pulang cepat (>3 kali dalam sebulan kena penalti)
            $totalPinaltiPulang = max(0, $jumlahPulangCepat - 3) * $pinalti;

            // Total penalti = penalti masuk + penalti pulang
            $totalPinalti = $totalPinaltiMasuk + $totalPinaltiPulang;

            // Hitung gaji akhir setelah penalti
            $gajiAkhir = max(0, $gajiPokok - $totalPinalti);

            $laporanGaji[] = [
                'nama' => $user->name,
                'jabatan' => $jabatan ? $jabatan->nama_jabatan : 'Tidak Diketahui',
                'jumlah_alpha' => $jumlahAlpha,
                'jumlah_izin' => $jumlahIzin,
                'jumlah_pulang_cepat' => $jumlahPulangCepat,
                'total_pinalti_masuk' => $totalPinaltiMasuk,
                'total_pinalti_pulang' => $totalPinaltiPulang,
                'total_pinalti' => $totalPinalti,
                'gaji_pokok' => $gajiPokok,
                'gaji_akhir' => $gajiAkhir,
            ];
        }

        // Generate PDF dengan data laporan gaji
        $pdf = Pdf::loadView('Absensi.laporan', compact('absensis', 'laporanGaji'));
        return $pdf->download('laporan_absensi.pdf');
    }



}


