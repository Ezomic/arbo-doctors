<?php

namespace Database\Seeders;

use App\Models\ApiClient;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $client = ApiClient::query()->firstOrCreate(['name' => 'admin-service']);

        $token = $client->createToken('admin-service', ['audit-logs:read'])->plainTextToken;

        $this->command->info("admin-service token (put in admin/.env as DOCTORS_SERVICE_TOKEN):\n{$token}");
    }
}
