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
                $tanggal_mulai_tahun = date('Y-01-01 00:00:00', strtotime($year));
                $tanggal_akhir_bulan = date('Y-12-31 23:59:59', strtotime($year));

                $query->whereBetween('created_at', [$tanggal_mulai_tahun, $tanggal_akhir_bulan]);
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
            'status_absensi_masuk' => 'nullable|string|in:hadir,izin,alpha',
            'status_absensi_pulang' => 'nullable|string|in:pulang',
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
            if (!$absensi || !$absensi->waktu_masuk || $absensi->status_absensi_masuk != 'hadir') {
                return redirect()->back()->with('error', 'Anda belum melakukan absensi masuk dengan status "hadir".');
            }

            if ($absensi->waktu_pulang) {
                return redirect()->back()->with('error', 'Anda sudah melakukan absensi pulang hari ini.');
            }

            $absensi->waktu_pulang = now(); // Waktu pulang
            $absensi->status_absensi_pulang = $request->status_absensi_pulang;
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
        $filterType = $request->input('filter'); // hari, bulan, atau tahun
        $filterValue = $request->input('value'); // nilai filter

        $query = Absensi::query();

        // Menambahkan filter berdasarkan waktu
        if ($filterType === 'hari' && $filterValue) {
            $query->whereDate('created_at', $filterValue);
        } elseif ($filterType === 'bulan' && $filterValue) {
            $query->whereMonth('created_at', Carbon::parse($filterValue)->month)
                ->whereYear('created_at', Carbon::parse($filterValue)->year);
        } elseif ($filterType === 'tahun' && $filterValue) {
            $query->whereYear('created_at', $filterValue);
        }

        // Mengecek apakah pengguna aktif adalah admin atau bukan
        $penggunaAktif = auth()->user();
        if ($penggunaAktif->email !== 'admin@material.com') {
            $query->where('user_id', $penggunaAktif->id);
        }

        // Mengambil data absensi dengan eager loading relasi user dan jabatan_organisasi
        $absensis = $query->with('user.data_pribadi.jabatan_organisasi')->orderBy('created_at', 'desc')->get();

        $laporanGaji = [];
        $groupedAbsensi = $absensis->groupBy('user.id');

        foreach ($groupedAbsensi as $userId => $userAbsensi) {
            $user = $userAbsensi->first()->user;
            $jabatan = $user->data_pribadi ? $user->data_pribadi->jabatan_organisasi : null;

            $gajiPokok = $jabatan ? $jabatan->besaran_gaji : 0;
            /*$jumlahKeterlambatan = 0;*/
            $jumlahAlpha = 0;
            $jumlahIzin = 0;
            $pinalti = 50000; // Penalti per keterlambatan/alpha

            foreach ($userAbsensi as $absensi) {
                if ($absensi->status_absensi_masuk === 'alpha') {
                    $jumlahAlpha++;
                } elseif ($absensi->status_absensi_masuk === 'izin') {
                    $jumlahIzin++;
                }
            }

            // Batasi maksimal izin 3x per bulan; alpha dihitung sebagai penalti langsung
            $totalPinalti = ($jumlahAlpha + max(0, $jumlahIzin - 3)) * $pinalti;
            $gajiAkhir = $gajiPokok - $totalPinalti;

            $laporanGaji[] = [
                'nama' => $user->name,
                'jabatan' => $jabatan ? $jabatan->nama_jabatan : 'Tidak Diketahui',
                'gaji_pokok' => $gajiPokok,
                'jumlah_keterlambatan' => $jumlahAlpha + $jumlahIzin,
                'jumlah_alpha' => $jumlahAlpha,
                'jumlah_izin' => $jumlahIzin,
                'pinalti_per_keterlambatan' => $pinalti,
                'total_pinalti' => $totalPinalti,
                'gaji_akhir' => $gajiAkhir,
            ];
        }



        // Gabungkan absensi dan laporan gaji dalam PDF
        $pdf = Pdf::loadView('Absensi.laporan', compact('absensis', 'laporanGaji'));
        return $pdf->download('laporan_absensi.pdf');
    }


}


