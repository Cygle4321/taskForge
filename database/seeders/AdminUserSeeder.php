<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Créer l'admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@taskforge.com'],
            [
                'name' => 'Admin TaskForge',
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ]
        );

        // 2. Créer des utilisateurs de test
        $users = User::factory(3)->create();

        // 3. Pour chaque utilisateur, créer 2 catégories et 3 todos
        $users->each(function ($user) {
            $categories = Category::factory(2)->create(['user_id' => $user->id]);
            $categories->each(function ($category) use ($user) {
                Todo::factory(3)->create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                ]);
            });
        });

        $this->command->info('✅ Admin créé : admin@taskforge.com / password123');
        $this->command->info('✅ 3 utilisateurs de test créés avec leurs todos');
    }
}
