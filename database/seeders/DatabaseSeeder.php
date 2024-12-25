<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Accounts\Database\Seeders\AccountsDatabaseSeeder;
use Modules\HumanResource\Database\Seeders\HumanResourceDatabaseSeeder;
use Modules\Localize\Database\Seeders\LocalizeDatabaseSeeder;
use Modules\UserManagement\Database\Seeders\UserManagementDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ApplicationsSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(UserManagementDatabaseSeeder::class);
        $this->call(LocalizeDatabaseSeeder::class);
        $this->call(HumanResourceDatabaseSeeder::class);
        $this->call(AccountsDatabaseSeeder::class);
    }
}
