<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Criar perfil de Administrador se não existir
        $adminProfileId = DB::table('profiles')
            ->where('profile', 'Administrador')
            ->value('id');

        if (!$adminProfileId) {
            $adminProfileId = DB::table('profiles')->insertGetId([
                'profile' => 'Administrador',
                'hash' => Str::uuid(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 2. Criar usuário administrador
        DB::table('users')->insert([
            'profile_id' => $adminProfileId,
            'hash' => Uuid::uuid4(),
            'name' => 'Administrador Padrão',
            'email' => 'admin@esfera.com',
            'email_verified_at' => now(),
            'password' => Hash::make('SenhaSegura123@'), // Senha forte
            'status' => true,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $this->command->info('Usuário administrador criado:');
        $this->command->info('Email: admin@esfera.com');
        $this->command->info('Senha: SenhaSegura123@');
    }
}
