<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profiles = [
            [
                'id' => 1,
                'profile' => 'Usuário',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'profile' => 'Administrador',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Inserção dos perfis
        DB::table('profiles')->insert($profiles);

    }
}
