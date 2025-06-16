<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $client = Role::firstOrCreate(['name' => 'Client']);

        $permissions = [
            'users.index',
            'users.create',
            'users.show',
            'users.edit',
            'users.delete',

            'roles.index',
            'roles.create',
            'roles.show',
            'roles.edit',
            'roles.delete',

            'books.index',
            'books.create',
            'books.show',
            'books.edit',
            'books.delete',

            'authors.index',

            'book_statuses.index',

            'borrowings.index',
            'borrowings.filter',
            'borrowings.borrow',
            'borrowings.return',
            'borrowings.current_borrower',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm])->assignRole($admin);
        }

        $clientPermissions = [
            'borrowings.index',
            'borrowings.filter',
            'borrowings.borrow',
            'borrowings.return',
            'borrowings.current_borrower',
        ];

        foreach ($clientPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm])->assignRole($client);
        }
    }
}
