<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener usuarios existentes (creados por UserSeeder)
        $users = User::all();
        $admin = User::where('email', 'admin@beerfinder.com')->first();

        // Notificaciones específicas para el administrador (como las entradas específicas en UserSeeder)
        Notification::create([
            'user_id' => $admin->id,
            'from_user_id' => $users->random()->id,
            'type' => 'system',
            'related_id' => 1,
            'is_read' => true,
            'read_at' => now()->subDays(5),
        ]);

        Notification::create([
            'user_id' => $admin->id,
            'from_user_id' => $users->random()->id,
            'type' => 'mention',
            'related_id' => 2,
            'is_read' => false,
            'read_at' => null,
        ]);

        // Crear notificaciones para usuarios regulares
        // Similar a cómo UserSeeder crea usuarios con factory
        foreach ($users->take(10) as $user) {
            // Mezcla de notificaciones leídas y no leídas (similar a verified/unverified)
            Notification::factory()
                ->like()
                ->forUser($user)
                ->fromUser($users->random())
                ->read()
                ->count(2)
                ->create();

            Notification::factory()
                ->comment()
                ->forUser($user)
                ->fromUser($users->random())
                ->unread()
                ->count(3)
                ->create();
        }

        // Notificaciones masivas (similar a los 30 usuarios creados en UserSeeder)
        Notification::factory()
            ->count(50)
            ->create();
    }
}
