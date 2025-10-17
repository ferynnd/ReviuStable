<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === Permissions ===
        $permissions = ['create', 'edit', 'delete', 'view'];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // === Roles ===
        $roles = [
            'superadmin' => Permission::all(),
            'admin' => Permission::whereIn('name', ['view'])->get(),
            'staff' => Permission::all(),
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($perms);
        }

        // === Users ===
        $users = [
            [
                'email' => 'superadmin@gmail.com',
                'fullname' => 'Super Admin',
                'username' => 'superadmin',
                'role' => 'superadmin',
            ],
            [
                'email' => 'admin@gmail.com',
                'fullname' => 'Admin',
                'username' => 'admin',
                'role' => 'admin',
            ],
            [
                'email' => 'staff@gmail.com',
                'fullname' => 'Staff',
                'username' => 'staff',
                'role' => 'staff',
            ],
        ];

        foreach ($users as $data) {
            // Gunakan withTrashed agar bisa restore jika pernah dihapus
            $user = User::withTrashed()->where('email', $data['email'])->first();

            if ($user) {
                if ($user->trashed()) {
                    $user->restore();
                }

                $user->update([
                    'fullname' => $data['fullname'],
                    'username' => $data['username'],
                    'password' => Hash::make('123'),
                    'is_active' => true,
                ]);
            } else {
                $user = User::create([
                    'fullname' => $data['fullname'],
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password' => Hash::make('123'),
                    'is_active' => true,
                ]);
            }

            $user->assignRole($data['role']);
        }
    }
}
