<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Faker\Factory;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->createRoles();
        $this->createUsers();
        $this->createAdmins();

        $this->call([
            GovernorateSeeder::class,
            AreaSeeder::class
        ]);
    }

    public function createUsers(): void
    {
        User::firstOrCreate(
            [
                'email' => 'admin@klaksat.com',
                'name' => [
                    'ar' => 'كلاكسات',
                    'en' => 'klaksat'
                ],
                'phone' => '01032163233',
                'is_active' => true
            ]
        );
        
        $fakerAr = Faker::create('ar_EG');
        $fakerEn = Faker::create('en_US');

        foreach (range(1, 10) as $index) {
            User::firstOrCreate(
                [
                    'email' => $fakerEn->unique()->safeEmail, // Generates a unique fake email
                    'name' => [
                        'en' => $fakerEn->name, // Generates an English name
                        'ar' => $fakerAr->name
                    ], // Generates an Arabic name
                    'phone' => $fakerAr->numerify('0##########'), // Generates a fake phone number
                    'is_active' => $fakerEn->boolean(80) // Randomly set 'is_active' to 80% of true
                ]
            );
        }
    }
    public function createRoles(): void
    {
        Role::firstOrCreate(
            [
                'name' => 'super-admin',
                'guard_name' => 'admin',
            ]
        );
    }


    public function createAdmins(): void
    {
       $admin = Admin::firstOrCreate(
            [
                'email' => 'admin@example.com',
                'name_en' => 'Admin User',
                'name_ar' => 'الادمن الافتراضي',
                'password' => Hash::make('123456879'),
            ]
        );
       $adminKlaksat = Admin::firstOrCreate(
            [
                'email' => 'etharhesham327@gmail.com',
                'name_ar' => 'Admin User',
                'name_en' => 'Admin User',
                'password' => Hash::make('bQZu2/*sac)!fvd'),
            ]
        );

        $admin->assignRole('super-admin');
        $adminKlaksat->assignRole('super-admin');
        // Instantiate Faker with Arabic (Egypt) and English locales
        $fakerAr = Faker::create('ar_EG');
        $fakerEn = Faker::create('en_US');

        // Create 5 fake admins
        foreach (range(1, 5) as $index) {
            Admin::firstOrCreate(
                [
                    'email' => $fakerEn->unique()->safeEmail, // Generates a unique fake email
                    'name_ar' => $fakerAr->name, // Generates an Arabic name
                    'name_en' => $fakerEn->name, // Generates an English name
                    'password' => Hash::make('123456879'), // Password is always the same and hashed
                ]
            );
        }
    }
}
