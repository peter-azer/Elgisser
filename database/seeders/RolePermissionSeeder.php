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
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $artistRole = Role::firstOrCreate(['name' => 'artist']);
        $galleryRole = Role::firstOrCreate(['name' => 'gallery']);
        $permissions = [
            // create permissions
            'create admin' => 'create',
            'create editor' => 'create',
            'create user' => 'create',
            'create artist' => 'create',
            'create gallery' => 'create',
        
            // read permissions
            'read admins table' => 'read',
            'read users table' => 'read',
            'read artists table' => 'read',
            'read galleries table' => 'read',
        
            // update permissions
            'update admin' => 'update',
            'update user' => 'update',
            'update artist' => 'update',
            'update gallery' => 'update',
            'approve rent request' => 'update',
            'disapprove rent request' => 'update',
            'approve event request' => 'update',
            'disapprove event request' => 'update',
        
            // delete permissions
            'delete admin' => 'delete',
            'delete editor' => 'delete',
            'delete user' => 'delete',
            'delete artist' => 'delete',
            'delete artwork' => 'delete',
            'delete portfolio image' => 'delete',
            'delete gallery' => 'delete',
        ];
        
        foreach (array_keys($permissions) as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }
        $superAdminRole->givePermissionTo([                        
            'create admin' ,
            'create editor' ,
            'create user' ,
            'create artist' ,
            'create gallery' ,
            'read admins table' ,
            'read users table' ,
            'read artists table' ,
            'read galleries table' ,
            'update admin' ,
            'update user' ,
            'update artist' ,
            'update gallery' ,
            'approve rent request' ,
            'disapprove rent request' ,
            'approve event request' ,
            'disapprove event request' ,
            'delete admin' ,
            'delete editor' ,
            'delete user' ,
            'delete artist' ,
            'delete artwork' ,
            'delete portfolio image' ,
            'delete gallery' ,
        ]);
        $superAdmin = User::firstOrCreate([
            'name' => 'Super Admin',
            'name_ar' => 'Super Admin',
            'email' => 'super_admin@elgisser.com',
            'phone' => '01100000000',
            'address' => 'fake address for testing',
            'address_ar' => 'fake address for testing',
            'image' => 'image.png',
            'gender' => 'male',
            'role' => 'super-admin',
            'password' => Hash::make('adminadmin'),
        ]);
        $superAdmin->assignRole('super-admin');
    }
}
