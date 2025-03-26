<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $artistRole = Role::firstOrCreate(['name' => 'artist']);
        $galleryRole = Role::firstOrCreate(['name' => 'gallery']);
        $permissions = [
            'create' => 'create',
            'read' => 'read',
            'update' => 'update',
            'delete' => 'delete',
        ];
        $permissions = array_map(function ($permission) {
            return Permission::firstOrCreate(['name' => $permission]);
        }, $permissions);// create permissions

        $superAdmin = User::firstOrCreate([
            'name' => 'Super Admin',
            'email' => 'super_admin@elgisser.com',
            'phone' => '01100000000',
            'address' => 'fake address for testing',
            'image' => 'image.png',
            'gender' => 'male',
            'password' => Hash::make('adminadmin'),
        ]);

    }
}
