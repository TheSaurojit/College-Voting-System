<?php

use App\Models\Student;
use App\Models\Otp;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('student login validation requires phone, roll_no, program_type', function () {
    $response = $this->post(route('student.send-otp'), []);

    $response->assertSessionHasErrors(['phone', 'roll_no', 'program_type']);
});

test('student login validation requires class if program_type is +2', function () {
    $response = $this->post(route('student.send-otp'), [
        'phone' => '1234567890',
        'roll_no' => 'R001',
        'program_type' => '+2',
    ]);

    $response->assertSessionHasErrors(['class']);
    $response->assertSessionDoesntHaveErrors(['semester']);
});

test('student login validation requires semester if program_type is Degree', function () {
    $response = $this->post(route('student.send-otp'), [
        'phone' => '1234567890',
        'roll_no' => 'R001',
        'program_type' => 'Degree',
    ]);

    $response->assertSessionHasErrors(['semester']);
    $response->assertSessionDoesntHaveErrors(['class']);
});

test('student login validation rejects invalid class for +2', function () {
    $response = $this->post(route('student.send-otp'), [
        'phone' => '1234567890',
        'roll_no' => 'R001',
        'program_type' => '+2',
        'class' => '10', // invalid class
    ]);

    $response->assertSessionHasErrors(['class']);
});

test('student login validation rejects invalid semester for Degree', function () {
    $response = $this->post(route('student.send-otp'), [
        'phone' => '1234567890',
        'roll_no' => 'R001',
        'program_type' => 'Degree',
        'semester' => '7th Sem', // invalid semester
    ]);

    $response->assertSessionHasErrors(['semester']);
});

test('student can successfully request OTP with valid +2 credentials', function () {
    $student = Student::create([
        'name' => 'John PlusTwo',
        'phone' => '1234567890',
        'class' => '11',
        'semester' => null,
        'roll_no' => 'R001',
    ]);

    $response = $this->post(route('student.send-otp'), [
        'phone' => '1234567890',
        'roll_no' => 'R001',
        'program_type' => '+2',
        'class' => '11',
    ]);

    $response->assertRedirect(route('student.otp'));
    $this->assertDatabaseHas('otps', [
        'phone' => '1234567890',
        'verified' => false,
    ]);
});

test('student can successfully request OTP with valid Degree credentials', function () {
    $student = Student::create([
        'name' => 'Jane Degree',
        'phone' => '0987654321',
        'class' => null,
        'semester' => '3rd Sem',
        'roll_no' => 'R002',
    ]);

    $response = $this->post(route('student.send-otp'), [
        'phone' => '0987654321',
        'roll_no' => 'R002',
        'program_type' => 'Degree',
        'semester' => '3rd Sem',
    ]);

    $response->assertRedirect(route('student.otp'));
    $this->assertDatabaseHas('otps', [
        'phone' => '0987654321',
        'verified' => false,
    ]);
});

test('student cannot request OTP with mismatched credentials', function () {
    // Student is +2, but logs in as Degree
    $student = Student::create([
        'name' => 'John PlusTwo',
        'phone' => '1234567890',
        'class' => '11',
        'semester' => null,
        'roll_no' => 'R001',
    ]);

    $response = $this->post(route('student.send-otp'), [
        'phone' => '1234567890',
        'roll_no' => 'R001',
        'program_type' => 'Degree',
        'semester' => '1st Sem',
    ]);

    $response->assertSessionHasErrors(['phone']);
});

test('student can verify OTP and log in', function () {
    $student = Student::create([
        'name' => 'John PlusTwo',
        'phone' => '1234567890',
        'class' => '11',
        'semester' => null,
        'roll_no' => 'R001',
    ]);

    $this->post(route('student.send-otp'), [
        'phone' => '1234567890',
        'roll_no' => 'R001',
        'program_type' => '+2',
        'class' => '11',
    ]);

    $otp = Otp::where('phone', '1234567890')->first();
    expect($otp)->not->toBeNull();

    $response = $this->post(route('student.verify-otp'), [
        'otp' => $otp->otp_code,
    ]);

    $response->assertRedirect(route('student.vote'));
    expect(session('student_id'))->toBe($student->id);
});

test('student can resend OTP using session data', function () {
    $student = Student::create([
        'name' => 'John PlusTwo',
        'phone' => '1234567890',
        'class' => '11',
        'semester' => null,
        'roll_no' => 'R001',
    ]);

    // Initial OTP request to populate session
    $this->post(route('student.send-otp'), [
        'phone' => '1234567890',
        'roll_no' => 'R001',
        'program_type' => '+2',
        'class' => '11',
    ]);

    $firstOtp = Otp::where('phone', '1234567890')->first();
    expect($firstOtp)->not->toBeNull();

    // Now call resend (send-otp route with no parameters, but session is active)
    $response = $this->post(route('student.send-otp'), []);

    $response->assertRedirect(route('student.otp'));

    // Check that a new OTP was created and the old one was deleted
    $newOtp = Otp::where('phone', '1234567890')->first();
    expect($newOtp)->not->toBeNull();
    expect($newOtp->otp_code)->not->toBe($firstOtp->otp_code);
});
