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
                    'created_at' => $item->created_at->format('d-m-Y H:i:s'), // Format tanggal
                    'status_absensi' => $item->status_absensi,
                ];
            });

            // Kembalikan respons JSON untuk AJAX
            return response()->json(['data' => $filteredData]);
        }
        // 1. SYARAT WAKTU
        $waktu_sekarang = Carbon::now();
        $pengaturan_absensis = PengaturanAbsensi::all();
        $waktu_buka = Carbon::parse($pengaturan_absensis[0]->waktu_buka);
        $waktu_tutup = Carbon::parse($pengaturan_absensis[0]->waktu_tutup);
        $hasil_cek_waktu = $waktu_sekarang->between($waktu_buka, $waktu_tutup);

        // 2. SYARAT ALAMAT IP
        $ip_pengguna = $request->ip();
        $hasil_cek_ip = PengaturanAbsensi::where('rentang_awal_IP', '<=', $ip_pengguna)
            ->where('rentang_akhir_IP', '>=', $ip_pengguna)
            ->exists();

        // 3. SYARAT TIDAK MELAKUKAN DOUBLE ABSENSI PADA HARI YANG SAMA
        $hasil_cek_double_absensi = Absensi::where('user_id', auth()->id())
            ->whereDate('created_at', Carbon::today())
            ->exists();

        // 4. MENGAMBIL DATA ABSENSI SESUAI PENGGUNA
        $pengguna_aktif = auth()->user();
        if ($pengguna_aktif->email == 'admin@material.com') {
            $absensis = Absensi::all();
        } else {
            $absensis = $pengguna_aktif->absensis;
        }

        // 5. FITUR PENCARIAN (jika ada, tambahkan logika di sini)

        return view('Absensi.index', compact('hasil_cek_waktu', 'hasil_cek_ip', 'hasil_cek_double_absensi', 'absensis'));
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
        $validasi = $request->validate([
            'status_absensi' => 'required'
        ]);

        $ip_pengguna = auth()->id();

        $absensi = new Absensi();
        $absensi->user_id = auth()->id();
        $absensi->status_absensi = $validasi['status_absensi'];
        $absensi->save();

        return redirect()->route('absensi.index')->with('sukses', 'Absensi berhasil dilakukan!');
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


