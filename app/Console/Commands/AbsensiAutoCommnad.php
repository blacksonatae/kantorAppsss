<?php

namespace App\Console\Commands;
use App\Models\Absensi;
use App\Models\PengaturanAbsensi;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AbsensiAutoCommnad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Tugas absensi otomatis dimulai');


        $waktu_sekarang = Carbon::now();
        $pengaturan_absensis = PengaturanAbsensi::first();
        $waktu_masuk_buka = Carbon::parse($pengaturan_absensis->waktu_buka);
        $waktu_masuk_tutup = Carbon::parse($pengaturan_absensis->waktu_buka)->addMinutes(30);
        $waktu_pulang_buka = Carbon::parse($pengaturan_absensis->waktu_tutup);
        $waktu_pulang_tutup = Carbon::parse($pengaturan_absensis->waktu_tutup)->addMinutes(30);

        // **1. Absensi Masuk Otomatis (Alpha)**
        if ($waktu_sekarang->greaterThan($waktu_masuk_tutup)) {
            $userWithoutAbsensiMasuk = User::whereDoesntHave('absensis', function ($query) use ($waktu_sekarang) {
                $query->whereDate('created_at', $waktu_sekarang->toDateString())
                    ->whereNotNull('waktu_masuk');
            })->get();

            foreach ($userWithoutAbsensiMasuk as $user) {
                $absensi = new Absensi();
                $absensi->user_id = $user->id;
                $absensi->waktu_masuk = null; // Tidak ada waktu masuk
                $absensi->status_absensi_masuk = 'alpha';
                $absensi->status_absensi_pulang = '-'; // Pulang tidak bisa diisi
                $absensi->save();
                $this->info("User {$user->name} diberikan status absensi masuk 'alpha'.");
            }
        }

        // **2. Absensi Pulang Otomatis**
        if ($waktu_sekarang->greaterThan($waktu_pulang_tutup)) {
            $absensisMasukHadir = Absensi::whereDate('created_at', $waktu_sekarang->toDateString())
                ->whereNotNull('waktu_masuk') // User sudah absen masuk
                ->where('status_absensi_masuk', 'hadir') // Hanya berlaku untuk status hadir
                ->whereNull('waktu_pulang') // Belum ada absensi pulang
                ->get();

            foreach ($absensisMasukHadir as $absensi) {
                $absensi->waktu_pulang = $waktu_sekarang; // Waktu pulang otomatis
                $absensi->status_absensi_pulang = 'pulang';
                $absensi->save();
                $this->info("User ID {$absensi->user_id} diberikan status absensi pulang 'pulang'.");
            }
        }

        $this->info("Proses absensi otomatis selesai!");
        return Command::SUCCESS;
    }
}
