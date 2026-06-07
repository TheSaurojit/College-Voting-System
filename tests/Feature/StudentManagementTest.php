<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);
});

test('admin can create a student with +2 program type and only class is stored', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.students.store'), [
        'name' => 'John Doe',
        'phone' => '1234567890',
        'program_type' => '+2',
        'class' => '11',
        'semester' => '1st Sem', // browser might send it, but it should be ignored/nulled
        'roll_no' => 'R001',
    ]);

    $response->assertRedirect(route('admin.students.index'));

    $this->assertDatabaseHas('students', [
        'name' => 'John Doe',
        'phone' => '1234567890',
        'class' => '11',
        'semester' => null,
        'roll_no' => 'R001',
    ]);
});

test('admin can create a student with Degree program type and only semester is stored', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.students.store'), [
        'name' => 'Jane Doe',
        'phone' => '0987654321',
        'program_type' => 'Degree',
        'class' => '12', // browser might send it, but it should be ignored/nulled
        'semester' => '3rd Sem',
        'roll_no' => 'R002',
    ]);

    $response->assertRedirect(route('admin.students.index'));

    $this->assertDatabaseHas('students', [
        'name' => 'Jane Doe',
        'phone' => '0987654321',
        'class' => null,
        'semester' => '3rd Sem',
        'roll_no' => 'R002',
    ]);
});

test('admin can update a student from +2 to Degree program type and the class is cleared', function () {
    $student = Student::create([
        'name' => 'Test Student',
        'phone' => '9999999999',
        'class' => '11',
        'semester' => null,
        'roll_no' => 'T101',
    ]);

    // Update to Degree
    $response = $this->actingAs($this->admin)->put(route('admin.students.update', $student), [
        'name' => 'Updated Student',
        'phone' => '9999999999',
        'program_type' => 'Degree',
        'class' => '11', // hidden input might still hold '11' in form, must be cleared
        'semester' => '5th Sem',
        'roll_no' => 'T101',
    ]);

    $response->assertRedirect(route('admin.students.index'));

    $this->assertDatabaseHas('students', [
        'id' => $student->id,
        'name' => 'Updated Student',
        'class' => null,
        'semester' => '5th Sem',
    ]);
});

test('admin can update a student from Degree to +2 program type and the semester is cleared', function () {
    $student = Student::create([
        'name' => 'Test Student 2',
        'phone' => '8888888888',
        'class' => null,
        'semester' => '3rd Sem',
        'roll_no' => 'T102',
    ]);

    // Update to +2
    $response = $this->actingAs($this->admin)->put(route('admin.students.update', $student), [
        'name' => 'Updated Student 2',
        'phone' => '8888888888',
        'program_type' => '+2',
        'class' => '12',
        'semester' => '3rd Sem', // hidden input might still hold '3rd Sem' in form, must be cleared
        'roll_no' => 'T102',
    ]);

    $response->assertRedirect(route('admin.students.index'));

    $this->assertDatabaseHas('students', [
        'id' => $student->id,
        'name' => 'Updated Student 2',
        'class' => '12',
        'semester' => null,
    ]);
});
