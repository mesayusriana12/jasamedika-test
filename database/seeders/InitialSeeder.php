<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $su = User::create([
            'name' => 'root',
            'username' => 'su',
            'email' => 'root@local',
            'password' => $password = Hash::make('password'),
        ]);

        $su->email_verified_at = now();
        $su->save();

        $user = User::create([
            'name' => 'user',
            'username' => 'user',
            'email' => 'user',
            'password' => $password,
        ]);

        $user->email_verified_at = now();
        $user->save();

        Role::create([
            'name' => 'superuser',
            'guard_name' => 'web',
        ]);

        $su->assignRole('superuser');

        collect(['user', 'permission', 'role', 'menu', 'subdistrict', 'patient'])->each(function ($name) {
            collect(['create', 'read', 'update', 'delete'])->each(function ($ability) use ($name) {
                Permission::create([
                    'name' => sprintf('%s %s', $ability, $name),
                    'guard_name' => 'web',
                ]);
            });
        });

        // collect(['read activities', 'read login activities'])->each(function ($p) {
        //     Permission::create([
        //         'name' => $p,
        //         'guard_name' => 'web',
        //     ]);
        // });

        //=================== Seeder start here ===================

        $admin = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@local',
            'password' => $password = Hash::make('password'),
        ]);

        $admin->email_verified_at = now();
        $admin->save();

        $operator = User::create([
            'name' => 'Operator',
            'username' => 'operator',
            'email' => 'operator@local',
            'password' => $password = Hash::make('password'),
        ]);

        $operator->email_verified_at = now();
        $operator->save();

        $roleAdmin = Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $roleOperator = Role::create([
            'name' => 'operator',
            'guard_name' => 'web',
        ]);

        $admin->assignRole('admin');
        $operator->assignRole('operator');

        $roleAdmin->givePermissionTo([
            'create subdistrict', 'read subdistrict', 'update subdistrict', 'delete subdistrict',
            'create patient', 'read patient', 'update patient', 'delete patient',
        ]);

        $roleOperator->givePermissionTo([
            'create patient', 'read patient', 'update patient', 'delete patient',
        ]);
    }
}
