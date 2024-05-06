<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(1234)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => \Illuminate\Support\Facades\Hash::make('testpassword'),
            'access_level' => 'admin',
            'status' => 'active',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Regular User',
            'email' => 'regular@example.com',
            'email_verified_at' => now(),
            'password' => \Illuminate\Support\Facades\Hash::make('testpassword'),
            'access_level' => 'regular',
            'status' => 'active',
        ]);

        $this->call([
            CustomerSeeder::class,
            ProductSeeder::class,
            QuotationSeeder::class,
            QuotationItemSeeder::class,
            PurchaseOrderSeeder::class,
        ]);
    }
}
