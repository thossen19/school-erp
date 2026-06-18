<?php

namespace Tests\Feature;

use App\Models\School;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;

    protected function setUp(): void
    {
        parent::setUp();
        $this->school = School::factory()->create();
    }

    public function test_super_admin_has_all_permissions(): void
    {
        $user = User::factory()->superAdmin()->create();
        $role = Role::create(['name' => 'super_admin']);
        $user->assignRole($role);
        Permission::create(['name' => 'students.create']);
        Permission::create(['name' => 'students.edit']);
        Permission::create(['name' => 'students.delete']);

        $allPermissions = Permission::all();
        $role->syncPermissions($allPermissions);

        $this->assertTrue($user->hasRole('super_admin'));
        $this->assertTrue($user->can('students.create'));
        $this->assertTrue($user->can('students.edit'));
        $this->assertTrue($user->can('students.delete'));
    }

    public function test_teacher_cannot_access_admin_functions(): void
    {
        $user = User::factory()->create();
        $teacherRole = Role::create(['name' => 'teacher']);
        $user->assignRole($teacherRole);

        Permission::create(['name' => 'students.create']);
        Permission::create(['name' => 'settings.edit']);

        $teacherRole->givePermissionTo('students.create');

        $this->assertTrue($user->can('students.create'));
        $this->assertFalse($user->can('settings.edit'));
    }

    public function test_role_based_middleware_works(): void
    {
        $user = User::factory()->create();
        $adminRole = Role::create(['name' => 'school_admin']);
        $user->assignRole($adminRole);

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson('/api/v1/auth/me');

        $response->assertStatus(200);
    }

    public function test_permission_based_access_works(): void
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'teacher']);
        $user->assignRole($role);

        $permission = Permission::create(['name' => 'students.view']);
        $role->givePermissionTo($permission);

        $this->assertTrue($user->hasPermissionTo('students.view'));
        $this->assertFalse($user->hasPermissionTo('settings.edit'));
    }
}
