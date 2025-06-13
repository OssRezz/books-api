<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_one = Role::where('name', 'Admin')->first();
        if (!isset($role_one)) {
            $role_one = Role::create(["name" => "Admin"]);
        }
        $role_two = Role::create(["name" => "User"]);

        //Users
        Permission::create(["name" => "admin.users.index", "description" => "View", "module" => "Administration"])->syncRoles([$role_one, $role_two]);
        Permission::create(["name" => "admin.users.create", "description" => "Create", "module" => "Administration"])->syncRoles([$role_one, $role_two]);
        Permission::create(["name" => "admin.users.show", "description" => "View details", "module" => "Administration"])->syncRoles([$role_one, $role_two]);
        Permission::create(["name" => "admin.users.edit", "description" => "edit", "module" => "Administration"])->syncRoles([$role_one, $role_two]);

        //Roles
        Permission::create(["name" => "admin.roles.index", "description" => "View", "module" => "Administration"])->syncRoles([$role_one, $role_two]);
        Permission::create(["name" => "admin.roles.create", "description" => "Create", "module" => "Administration"])->syncRoles([$role_one, $role_two]);
        Permission::create(["name" => "admin.roles.show", "description" => "View details", "module" => "Administration"])->syncRoles([$role_one, $role_two]);
        Permission::create(["name" => "admin.roles.edit", "description" => "Edit", "module" => "Administration"])->syncRoles([$role_one, $role_two]);
    }
}
