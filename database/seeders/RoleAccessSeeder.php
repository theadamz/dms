<?php

namespace Database\Seeders;

use App\Models\Config\RoleAccess;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get roles
        $roles =  (array) config('access.roleIdExceptions');

        // delete all access for roleIdExceptions
        RoleAccess::whereIn('role_id', $roles)->delete();

        // loop $roles
        foreach ($roles as $role) {

            // get permission list by code
            $roleAccessList = collect(config('access.roleList'));

            // looping $roleAccessList
            foreach ($roleAccessList as $permissionList) {

                // get permissions
                $permissions = $permissionList['permissions'];

                // looping $permissionList
                foreach ($permissions as $permission) {

                    // create data
                    RoleAccess::create([
                        'role_id' => $role,
                        'code' => $permissionList['code'],
                        'permission' => $permission,
                        'is_allowed' => true,
                    ]);
                }
            }
        }
    }
}
