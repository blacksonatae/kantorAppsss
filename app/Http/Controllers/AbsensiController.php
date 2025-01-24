<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\PengaturanAbsensi;
use App\Models\Absensi;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Logika Filter
        if ($request->ajax()) {
            $filterType = $request->input('filter'); // hari, bulan, atau tahun
            $filterValue = $request->input('value'); // nilai filter

            $query = Absensi::query();

            // Terapkan filter berdasarkan parameter
            if ($filterType === 'hari' && $filterValue) {
                $query->whereDate('created_at', $filterValue);
            } elseif ($filterType === 'bulan' && $filterValue) {
                $query->whereMonth('created_at', Carbon::parse($filterValue)->month)
                    ->whereYear('created_at', Carbon::parse($filterValue)->year);
            } elseif ($filterType === 'tahun' && $filterValue) {
                $query->whereYear('created_at', $filterValue);
            }

            // Filter data sesuai peran pengguna
            $pengguna_aktif = auth()->user();
            if ($pengguna_aktif->role == 'pegawai') {
                $query->where('user_id', $pengguna_aktif->id);
            }

            // Tambahkan eager loading untuk relasi `user`
            $filteredData = $query->with('user')->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'user' => $item->user,
                    'status_absensi_masuk' => $item->status_absensi_masuk ?? '-',
                    'waktu_masuk' => $item->waktu_masuk ? $item->waktu_masuk->format('d-m-Y H:i:s') : '-',
                    'status_absensi_keluar' => $item->status_absensi_keluar ?? '-',
                    'waktu_keluar' => $item->waktu_masuk ? $item->waktu_keluar->format('d-m-Y H:i:s') : '-',
                ];
            });

            // Kembalikan respons JSON untuk AJAX
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

        if ($filterType === 'hari' && $filterValue) {
            $query->whereDate('created_at', $filterValue);
        } elseif ($filterType === 'bulan' && $filterValue) {
            $query->whereMonth('created_at', Carbon::parse($filterValue)->month)
                ->whereYear('created_at', Carbon::parse($filterValue)->year);
        } elseif ($filterType === 'tahun' && $filterValue) {
            $query->whereYear('created_at', $filterValue);
        }

        $penggunaAktif = auth()->user();
        if ($penggunaAktif->email !== 'admin@material.com') {
            $query->where('user_id', $penggunaAktif->id);
        }

        $absensis = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('Absensi.laporan', compact('absensis'));
        return $pdf->download('laporan_absensi.pdf');
    }


}


