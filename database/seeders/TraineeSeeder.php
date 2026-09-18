<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TraineeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->trainees() as $index => $name) {
            $trainee = User::query()->firstOrCreate(
                ['email' => 'trainee'.($index + 1).'@example.com'],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role' => UserRole::Trainee,
                ],
            );

            if ($trainee->email_verified_at === null) {
                $trainee->forceFill(['email_verified_at' => now()])->save();
            }
        }
    }

    /**
     * @return list<string>
     */
    private function trainees(): array
    {
        return [
            'أحمد محمد',
            'محمد عبدالله',
            'سارة أحمد',
            'خالد علي',
            'نورة محمد',
        ];
    }
}
