<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Jabatan;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => Hash::make('123456'), // ganti password sesuai kebutuhan
            'jabatan_id' => Jabatan::factory(),
            'shift_id' => Shift::factory(),
            'remember_token' => Str::random(10),
        ];
    }

    // Factory untuk User dengan jabatan dan shift tertentu
    public function gatewayOperatorShift2()
    {
        return $this->state(function () {
            return [
                'email' => 'gateway@gmail.com',
                'password' => Hash::make('123456'),
                'jabatan_id' => Jabatan::where('jabatan', 'Gateway Operator')->first()->id,
                'shift_id' => Shift::where('shift', 'Shift 2')->first()->id,
            ];
        });
    }

    public function nonShiftUser()
    {
        return $this->state(function () {
            return [
                'email' => 'nonshift@gmail.com',
                'password' => Hash::make('123456'),
                'jabatan_id' => Jabatan::where('jabatan', '1 Non Shift')->first()->id,
                'shift_id' => Shift::where('shift', 'Non Shift')->first()->id,
            ];
        });
    }

    public function adminNonShift()
    {
        return $this->state(function () {
            return [
                'email' => 'test@gmail.com',
                'password' => Hash::make('123456'),
                'jabatan_id' => Jabatan::where('jabatan', 'Admin')->first()->id,
                'shift_id' => Shift::where('shift', 'Non Shift')->first()->id,
            ];
        });
    }
}
