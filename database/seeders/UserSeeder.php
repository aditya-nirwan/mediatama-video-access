<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->make('Administrator', 'admin@mediatama.test', UserRole::Admin, '081200000001');

        $this->make('Aditya Nirwan', 'adit@customer.test', UserRole::Customer, '081200000002');
        $this->make('Sri Handayani', 'sri@customer.test', UserRole::Customer, '081200000003');
        $this->make('Irfan Faqih', 'irfan@customer.test', UserRole::Customer, '081200000004');
        $this->make('Baskoro Sulaksono', 'baskoro@customer.test', UserRole::Customer, '081200000005');
    }

    private function make(string $name, string $email, UserRole $role, string $phone): User
    {
        $user = new User([
            'name' => $name,
            'email' => $email,
            'password' => 'password',
            'phone' => $phone,
            'address' => 'Salatiga, Jawa Tengah',
            'is_active' => true,
        ]);

        $user->role = $role;
        $user->save();

        return $user;
    }
}
