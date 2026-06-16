<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        Permission::create(['name' => 'view-project']);
        Permission::create(['name' => 'add-project']);
        Permission::create(['name' => 'edit-project']);
        Permission::create(['name' => 'delete-project']);
        Permission::create(['name' => 'view-bid']);
        Permission::create(['name' => 'add-bid']);
        Permission::create(['name' => 'edit-bid']);
        Permission::create(['name' => 'delete-bid']);
        Permission::create(['name' => 'award-bid']);
        Permission::create(['name' => 'view-audit-logs']);
        Permission::create(['name' => 'edit-docs']);
        Permission::create(['name' => 'print-docs']);


        // Create roles and attach permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $staffRole = Role::create(['name' => 'staff']);
        $staffRole->givePermissionTo([
            'view-project', 
            'add-project',
            'edit-project',
            'delete-project',
            'view-bid',
            'add-bid',
            'edit-bid',
            'delete-bid',
            'award-bid',
            'edit-docs',
            'print-docs'
        ]);

        // admin
        $admin = User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin12345')
        ]);

        $admin->assignRole('admin');


        $this->command->info('Roles and Permission seeded successfully.');
    }
}
