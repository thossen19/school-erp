<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\User;
use App\Models\Academic\ClassModel;
use App\Models\Assessment\Exam;
use App\Models\Assessment\ExamResult;
use App\Models\Student\Student;
use App\Models\Academic\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExamApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;
    protected School $school;
    protected ClassModel $class;
    protected AcademicYear $academicYear;
    protected Student $student;
    protected Subject $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->school = School::factory()->create();
        $this->user = User::factory()->create(['school_id' => $this->school->id]);
        $this->class = ClassModel::factory()->create(['school_id' => $this->school->id]);
        $this->academicYear = AcademicYear::factory()->create(['school_id' => $this->school->id, 'is_current' => true]);
        $this->subject = Subject::factory()->create(['school_id' => $this->school->id]);
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

    public function test_can_create_exam(): void
    {
        $response = $this->withHeaders($this->authHeaders())
            ->postJson('/api/v1/exams', [
                'name' => 'Midterm Examination',
                'exam_type' => 'midterm',
                'class_id' => $this->class->id,
                'academic_year_id' => $this->academicYear->id,
                'start_date' => now()->addWeek()->format('Y-m-d'),
                'end_date' => now()->addWeeks(2)->format('Y-m-d'),
                'max_marks' => 100,
                'passing_marks' => 33,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_can_enter_results(): void
    {
        $exam = Exam::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
            'exam_type' => 'midterm',
            'start_date' => now()->addWeek(),
            'end_date' => now()->addWeeks(2),
            'max_marks' => 100,
            'passing_marks' => 33,
        ]);

        $response = $this->withHeaders($this->authHeaders())
            ->postJson('/api/v1/exam-results', [
                'exam_id' => $exam->id,
                'student_id' => $this->student->id,
                'subject_id' => $this->subject->id,
                'marks_obtained' => 85,
                'total_marks' => 100,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_can_get_results(): void
    {
        $exam = Exam::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
            'exam_type' => 'midterm',
            'start_date' => now()->addWeek(),
            'end_date' => now()->addWeeks(2),
            'max_marks' => 100,
            'passing_marks' => 33,
        ]);

        ExamResult::factory()->create([
            'school_id' => $this->school->id,
            'exam_id' => $exam->id,
            'student_id' => $this->student->id,
            'subject_id' => $this->subject->id,
            'marks_obtained' => 85,
            'total_marks' => 100,
        ]);

        $response = $this->withHeaders($this->authHeaders())
            ->getJson("/api/v1/exam-results/by-student/{$this->student->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_can_get_rankings(): void
    {
        $exam = Exam::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
            'exam_type' => 'midterm',
            'start_date' => now()->addWeek(),
            'end_date' => now()->addWeeks(2),
            'max_marks' => 100,
            'passing_marks' => 33,
        ]);

        $response = $this->withHeaders($this->authHeaders())
            ->getJson("/api/v1/exams/{$exam->id}/rankings");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
