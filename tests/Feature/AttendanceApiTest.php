<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\User;
use App\Models\Academic\ClassModel;
use App\Models\Academic\Section;
use App\Models\Student\Student;
use App\Models\Attendance\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AttendanceApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;
    protected School $school;
    protected ClassModel $class;
    protected Section $section;
    protected Student $student;
    protected AcademicYear $academicYear;

    protected function setUp(): void
    {
        parent::setUp();
        $this->school = School::factory()->create();
        $this->user = User::factory()->create(['school_id' => $this->school->id]);
        $this->class = ClassModel::factory()->create(['school_id' => $this->school->id]);
        $this->section = Section::factory()->create(['school_id' => $this->school->id, 'class_id' => $this->class->id]);
        $this->academicYear = AcademicYear::factory()->create(['school_id' => $this->school->id, 'is_current' => true]);
        $this->student = Student::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'academic_year_id' => $this->academicYear->id,
        ]);
    }

    protected function authHeaders(): array
    {
        $token = $this->user->createToken('api-token')->plainTextToken;
        return ['Authorization' => 'Bearer ' . $token];
    }

    public function test_can_mark_attendance(): void
    {
        $response = $this->withHeaders($this->authHeaders())
            ->postJson('/api/v1/attendance', [
                'student_id' => $this->student->id,
                'class_id' => $this->class->id,
                'section_id' => $this->section->id,
                'date' => now()->format('Y-m-d'),
                'status' => 'present',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_can_bulk_mark_attendance(): void
    {
        $student2 = Student::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->withHeaders($this->authHeaders())
            ->postJson('/api/v1/attendance/bulk', [
                'class_id' => $this->class->id,
                'section_id' => $this->section->id,
                'date' => now()->format('Y-m-d'),
                'records' => [
                    ['student_id' => $this->student->id, 'status' => 'present'],
                    ['student_id' => $student2->id, 'status' => 'absent'],
                ],
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_can_get_attendance_by_date(): void
    {
        Attendance::factory()->create([
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'class_id' => $this->class->id,
            'date' => now()->format('Y-m-d'),
            'status' => 'present',
        ]);

        $response = $this->withHeaders($this->authHeaders())
            ->getJson('/api/v1/attendance/by-date/' . now()->format('Y-m-d'));

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_can_get_attendance_by_student(): void
    {
        Attendance::factory(3)->create([
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'class_id' => $this->class->id,
        ]);

        $response = $this->withHeaders($this->authHeaders())
            ->getJson("/api/v1/attendance/by-student/{$this->student->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data' => ['records', 'summary']]);
    }

    public function test_can_get_attendance_report(): void
    {
        Attendance::factory(5)->create([
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'class_id' => $this->class->id,
            'section_id' => $this->section->id,
        ]);

        $response = $this->withHeaders($this->authHeaders())
            ->getJson('/api/v1/attendance/report?' . http_build_query([
                'class_id' => $this->class->id,
                'section_id' => $this->section->id,
                'start_date' => now()->subMonth()->format('Y-m-d'),
                'end_date' => now()->format('Y-m-d'),
            ]));

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
