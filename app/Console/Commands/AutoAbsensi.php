<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoAbsensi extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:absensi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test untuk absensi otomatis';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Cetak pesan ke console untuk testing
        $this->info('Test: Command AutoAbsensi dijalankan.');

        // Contoh logika dummy
        $this->info('Cek absensi pengguna yang belum absen pulang...');
        $this->info('Tidak ada pengguna yang ditemukan.');

        return 0; // Return success
    }
}
