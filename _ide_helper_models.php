<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\Basic{
/**
 * 
 *
 * @property string $id
 * @property string $code
 * @property string|null $name
 * @property bool $is_active
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedBy($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models\Config{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string|null $def_path
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDefPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedBy($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models\Config{
/**
 * 
 *
 * @property string $id
 * @property string $role_id
 * @property string $code
 * @property string $permission read,edit,delete,validation,etc
 * @property bool $is_allowed
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess whereIsAllowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess wherePermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess whereUpdatedBy($value)
 */
	class RoleAccess extends \Eloquent {}
}

namespace App\Models\Config{
/**
 * 
 *
 * @property string $id
 * @property string $ip
 * @property string|null $os
 * @property string|null $platform
 * @property string|null $browser
 * @property string|null $country
 * @property string|null $city
 * @property string $user_id
 * @property string $created_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory whereOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory whereUserId($value)
 */
	class SignInHistory extends \Eloquent {}
}

namespace App\Models\Config{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string $code
 * @property string $permission something specific only for user
 * @property bool $is_allowed
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess whereIsAllowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess wherePermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess whereUserId($value)
 */
	class UserAccess extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $email
 * @property string $name
 * @property string $password
 * @property string $role_id
 * @property string $timezone
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $last_change_password_at
 * @property string|null $last_login_at
 * @property bool $is_active
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Config\Role $role
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastChangePasswordAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedBy($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

