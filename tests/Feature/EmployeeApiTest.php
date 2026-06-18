<?php

namespace Tests\Feature;

use App\Models\School;
use App\Models\User;
use App\Models\Hr\Employee;
use App\Models\Hr\Department;
use App\Models\Hr\Designation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;
    protected School $school;
    protected Department $department;
    protected Designation $designation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->school = School::factory()->create();
        $this->user = User::factory()->create(['school_id' => $this->school->id]);
        $this->department = Department::factory()->create(['school_id' => $this->school->id]);
        $this->designation = Designation::factory()->create(['school_id' => $this->school->id]);
    }

    protected function authHeaders(): array
    {
        $token = $this->user->createToken('api-token')->plainTextToken;
        return ['Authorization' => 'Bearer ' . $token];
    }

    public function test_can_list_employees(): void
    {
        Employee::factory(3)->create([
            'school_id' => $this->school->id,
            'department_id' => $this->department->id,
            'designation_id' => $this->designation->id,
        ]);

        $response = $this->withHeaders($this->authHeaders())
            ->getJson('/api/v1/employees');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data', 'meta']);
    }

    public function test_can_create_employee(): void
    {
        $response = $this->withHeaders($this->authHeaders())
            ->postJson('/api/v1/employees', [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'employee_no' => 'EMP-2024-00001',
                'email' => 'jane.smith@school.com',
                'phone' => '1234567890',
                'date_of_birth' => '1990-05-15',
                'gender' => 'female',
                'department_id' => $this->department->id,
                'designation_id' => $this->designation->id,
                'joining_date' => '2024-01-01',
                'employment_type' => 'permanent',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_can_get_staff_directory(): void
    {
        Employee::factory(3)->create([
            'school_id' => $this->school->id,
            'department_id' => $this->department->id,
            'designation_id' => $this->designation->id,
            'status' => 'active',
        ]);

        $response = $this->withHeaders($this->authHeaders())
            ->getJson('/api/v1/employees/directory');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
