<?php

namespace Tests\Unit;

use App\Models\School;
use App\Models\User;
use App\Models\UserProfile;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_roles(): void
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'school_admin']);
        $user->assignRole($role);

        $this->assertTrue($user->hasRole('school_admin'));
        $this->assertCount(1, $user->roles);
    }

    public function test_user_belongs_to_school(): void
    {
        $school = School::factory()->create();
        $user = User::factory()->create(['school_id' => $school->id]);

        $this->assertInstanceOf(School::class, $user->school);
        $this->assertEquals($school->id, $user->school->id);
    }

    public function test_user_has_profile(): void
    {
        $user = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(UserProfile::class, $user->profile);
    }

    public function test_user_can_have_permissions(): void
    {
        $user = User::factory()->create();
        $permission = Permission::create(['name' => 'students.create']);
        $user->givePermissionTo($permission);

        $this->assertTrue($user->hasPermissionTo('students.create'));
    }
}
