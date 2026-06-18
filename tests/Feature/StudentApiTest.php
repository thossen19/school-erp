<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\User;
use App\Models\Academic\ClassModel;
use App\Models\Academic\Section;
use App\Models\Student\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StudentApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;
    protected School $school;
    protected ClassModel $class;
    protected Section $section;
    protected AcademicYear $academicYear;

    protected function setUp(): void
    {
        parent::setUp();
        $this->school = School::factory()->create();
        $this->user = User::factory()->create(['school_id' => $this->school->id]);
        $this->class = ClassModel::factory()->create(['school_id' => $this->school->id]);
        $this->section = Section::factory()->create(['school_id' => $this->school->id, 'class_id' => $this->class->id]);
        $this->academicYear = AcademicYear::factory()->create(['school_id' => $this->school->id, 'is_current' => true]);
    }

    protected function authHeaders(): array
    {
        $token = $this->user->createToken('api-token')->plainTextToken;
        return ['Authorization' => 'Bearer ' . $token];
    }

    public function test_can_list_students(): void
    {
        Student::factory(3)->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->withHeaders($this->authHeaders())
            ->getJson('/api/v1/students');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data', 'meta']);
    }

    public function test_can_create_student(): void
    {
        $response = $this->withHeaders($this->authHeaders())
            ->postJson('/api/v1/students', [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'date_of_birth' => '2010-01-15',
                'gender' => 'male',
                'class_id' => $this->class->id,
                'section_id' => $this->section->id,
                'academic_year_id' => $this->academicYear->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_can_show_student(): void
    {
        $student = Student::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->withHeaders($this->authHeaders())
            ->getJson("/api/v1/students/{$student->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_can_update_student(): void
    {
        $student = Student::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->withHeaders($this->authHeaders())
            ->putJson("/api/v1/students/{$student->id}", [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_can_delete_student(): void
    {
        $student = Student::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->withHeaders($this->authHeaders())
            ->deleteJson("/api/v1/students/{$student->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_can_search_students(): void
    {
        Student::factory()->create([
            'school_id' => $this->school->id,
            'first_name' => 'Searchable',
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->withHeaders($this->authHeaders())
            ->getJson('/api/v1/students/search?q=Searchable');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_can_get_students_by_class(): void
    {
        Student::factory(2)->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $response = $this->withHeaders($this->authHeaders())
            ->getJson("/api/v1/students/by-class/{$this->class->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
