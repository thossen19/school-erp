<?php

namespace Tests\Unit;

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\Academic\ClassModel;
use App\Models\Attendance\Attendance;
use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;
    protected ClassModel $class;
    protected AcademicYear $academicYear;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::factory()->create();
        $this->class = ClassModel::factory()->create(['school_id' => $this->school->id]);
        $this->academicYear = AcademicYear::factory()->create(['school_id' => $this->school->id, 'is_current' => true]);
    }

    public function test_student_belongs_to_school(): void
    {
        $student = Student::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $this->assertInstanceOf(School::class, $student->school);
        $this->assertEquals($this->school->id, $student->school->id);
    }

    public function test_student_belongs_to_class(): void
    {
        $student = Student::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $this->assertInstanceOf(ClassModel::class, $student->class);
        $this->assertEquals($this->class->id, $student->class->id);
    }

    public function test_student_has_attendances(): void
    {
        $student = Student::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        Attendance::factory(3)->create([
            'school_id' => $this->school->id,
            'student_id' => $student->id,
            'class_id' => $this->class->id,
        ]);

        $this->assertCount(3, $student->attendances);
    }

    public function test_student_active_scope(): void
    {
        Student::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
            'status' => 'active',
        ]);
        Student::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
            'status' => 'inactive',
        ]);

        $count = Student::active()->count();

        $this->assertEquals(1, $count);
    }

    public function test_student_full_name_attribute(): void
    {
        $student = Student::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->assertEquals('John Doe', $student->full_name);
    }

    public function test_student_belongs_to_academic_year(): void
    {
        $student = Student::factory()->create([
            'school_id' => $this->school->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $this->assertInstanceOf(AcademicYear::class, $student->academicYear);
        $this->assertEquals($this->academicYear->id, $student->academicYear->id);
    }
}
