<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@tahournian.ir')],
            [
                'name' => env('ADMIN_NAME', 'مدیر سایت'),
                'password' => env('ADMIN_PASSWORD', 'Tahournian@1405'),
            ]
        );
        $admin->forceFill(['is_admin' => true])->save();

        $this->call(ContentSeeder::class);
    }
}
