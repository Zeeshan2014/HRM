<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        //permissions create
        Permission::create(['name' => 'create request']);
        Permission::create(['name' => 'approve request']);

        //roles create
        $role1 = Role::create(['name' => 'user']);
        $role1->givePermissionTo('create request');

        $role2 = Role::create(['name' => 'admin']);
        $role2->givePermissionTo('approve request');

       // $role3 = Role::create(['name' => 'super-admin']);
        

        //create a user
        $user = User::factory()->create([
            'name' => 'user',
            'email' => 'user@eg.com'
        ]);
        $user->assignRole($role1);

        $user = User::factory()->create([
            'name' => 'Zeeshan',
            'email' => 'zeeshan@eg.com'
        ]);
        $user->assignRole($role1);

        $user = User::factory()->create([
            'name' => 'Ali',
            'email' => 'ali@eg.com'
        ]);
        $user->assignRole($role1);

        $user = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@eg.com'
        ]);
        $user->assignRole([$role1, $role2]);

       /*  $user = User::factory()->create([
            'name' => 'example super-admin',
            'email' => 'super@eg.com'
        ]);
        $user->assignRole($role3);
 */
    }
}
