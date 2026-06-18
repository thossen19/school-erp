<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\User;
use App\Models\Academic\ClassModel;
use App\Models\Fee\FeeCategory;
use App\Models\Fee\FeeStructure;
use App\Models\Fee\FeeCollection;
use App\Models\Student\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FeeApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;
    protected School $school;
    protected ClassModel $class;
    protected AcademicYear $academicYear;
    protected FeeCategory $feeCategory;
    protected Student $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->school = School::factory()->create();
        $this->user = User::factory()->create(['school_id' => $this->school->id]);
        $this->class = ClassModel::factory()->create(['school_id' => $this->school->id]);
        $this->academicYear = AcademicYear::factory()->create(['school_id' => $this->school->id, 'is_current' => true]);
        $this->feeCategory = FeeCategory::factory()->create(['school_id' => $this->school->id]);
        $this->student = Student::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
        ]);
    }

    protected function authHeaders(): array
    {
        $token = $this->user->createToken('api-token')->plainTextToken;
        return ['Authorization' => 'Bearer ' . $token];
    }

    public function test_can_create_fee_structure(): void
    {
        $response = $this->withHeaders($this->authHeaders())
            ->postJson('/api/v1/fee-structures', [
                'fee_category_id' => $this->feeCategory->id,
                'class_id' => $this->class->id,
                'academic_year_id' => $this->academicYear->id,
                'name' => 'Annual Tuition Fee',
                'amount' => 50000,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_can_collect_fee(): void
    {
        $feeStructure = FeeStructure::factory()->create([
            'school_id' => $this->school->id,
            'fee_category_id' => $this->feeCategory->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
            'amount' => 50000,
        ]);

        $response = $this->withHeaders($this->authHeaders())
            ->postJson('/api/v1/fee-collections', [
                'student_id' => $this->student->id,
                'fee_structure_id' => $feeStructure->id,
                'fee_category_id' => $this->feeCategory->id,
                'amount' => 50000,
                'total_amount' => 50000,
                'paid_amount' => 50000,
                'balance_amount' => 0,
                'payment_method' => 'cash',
                'payment_date' => now()->format('Y-m-d'),
                'academic_year_id' => $this->academicYear->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_can_get_student_due(): void
    {
        $response = $this->withHeaders($this->authHeaders())
            ->getJson("/api/v1/fee-collections/by-student/{$this->student->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data' => ['collections', 'summary']]);
    }

    public function test_can_get_fee_report(): void
    {
        $response = $this->withHeaders($this->authHeaders())
            ->getJson('/api/v1/fee-collections/report?' . http_build_query([
                'date_from' => now()->subMonth()->format('Y-m-d'),
                'date_to' => now()->format('Y-m-d'),
            ]));

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
