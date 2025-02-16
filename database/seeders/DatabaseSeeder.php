<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Shift;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Buat data jabatan
        Jabatan::create(['jabatan' => 'Admin']);
        Jabatan::create(['jabatan' => 'Gateway Operator']);

        // Buat data shift
        Shift::create(['shift' => 'Non Shift', 'mulai' => '09:00', 'selesai' => '18:00', 'jabatan_id' => 1]);
        Shift::create(['shift' => 'Shift 1', 'mulai' => '07:00', 'selesai' => '15:00', 'jabatan_id' => 2]);
        Shift::create(['shift' => 'Shift 2', 'mulai' => '15:00', 'selesai' => '23:00', 'jabatan_id' => 2]);
        Shift::create(['shift' => 'Shift 3', 'mulai' => '23:00', 'selesai' => '07:00',  'jabatan_id' => 2]);

        User::factory()->gatewayOperator()->create();
        User::factory()->adminNonShift()->create();
    }
}
