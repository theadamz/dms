<?php

namespace Database\Seeders;

use App\Models\Config\UserAccess;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get roles
        $users = (array) config('access.userIdExceptions');

        // delete all access for userIdExceptions
        UserAccess::whereIn('user_id', $users)->delete();

        // loop $users
        foreach ($users as $user) {

            // get permission list by code
            $userAccessList = collect(config('access.userList'));

            // looping $userAccessList
            foreach ($userAccessList as $permissionList) {

                // get permissions
                $permissions = $permissionList['permissions'];

                // looping $permissionList
                foreach ($permissions as $permission) {

                    // create data
                    UserAccess::create([
                        'user_id' => $user,
                        'code' => $permissionList['code'],
                        'permission' => $permission,
                        'is_allowed' => true,
                    ]);
                }
            }
        }
    }
}
