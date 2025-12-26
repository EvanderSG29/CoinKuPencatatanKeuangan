<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@coinku.com',
            'password' => bcrypt('admin123'),
            'no_telp' => '081234567890',
            'lokasi' => 'Jakarta',
            'jenis_kelamin' => 'laki-laki',
            'alamat' => 'Jl. Admin No. 1, Jakarta Pusat',
            'path_foto' => 'users/profil.png',
        ]);

        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'no_telp' => '081987654321',
            'lokasi' => 'Bandung',
            'jenis_kelamin' => 'perempuan',
            'alamat' => 'Jl. Test No. 2, Bandung',
            'path_foto' => 'users/profil.png',
        ]);

        // Create additional random users
        User::factory(5)->create();
    }
}
