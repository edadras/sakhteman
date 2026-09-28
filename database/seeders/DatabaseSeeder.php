<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@tahournian.ir')],
            [
                'name' => env('ADMIN_NAME', 'مدیر سایت'),
                'password' => env('ADMIN_PASSWORD', 'Tahournian@1405'),
            ]
        );

        $this->call(ContentSeeder::class);
    }
}
