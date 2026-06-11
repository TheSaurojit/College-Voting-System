<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
    ]);
});

test('admin can view the password change page', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.password.edit'));

    $response->assertStatus(200);
    $response->assertSee('Change Password');
    $response->assertSee('Security Credentials');
});

test('guest cannot view password change page', function () {
    $response = $this->get(route('admin.password.edit'));

    $response->assertRedirect('/admin/login');
});

test('non-admin user cannot view password change page', function () {
    $studentUser = User::create([
        'name' => 'Student User',
        'email' => 'student@example.com',
        'password' => Hash::make('password123'),
        'role' => 'student',
    ]);

    $response = $this->actingAs($studentUser)->get(route('admin.password.edit'));

    $response->assertRedirect('/admin/login');
});

test('admin can successfully change password with valid details', function () {
    $response = $this->actingAs($this->admin)->put(route('admin.password.update'), [
        'current_password'      => 'password123',
        'password'              => 'newsecurepass123',
        'password_confirmation' => 'newsecurepass123',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Password updated successfully.');

    // Verify password has changed in db
    $this->admin->refresh();
    $this->assertTrue(Hash::check('newsecurepass123', $this->admin->password));
});

test('admin cannot change password if current password is wrong', function () {
    $response = $this->actingAs($this->admin)->put(route('admin.password.update'), [
        'current_password'      => 'wrongpassword',
        'password'              => 'newsecurepass123',
        'password_confirmation' => 'newsecurepass123',
    ]);

    $response->assertSessionHasErrors(['current_password']);
    
    // Verify password is still the original
    $this->admin->refresh();
    $this->assertTrue(Hash::check('password123', $this->admin->password));
});

test('admin cannot change password if confirmation does not match', function () {
    $response = $this->actingAs($this->admin)->put(route('admin.password.update'), [
        'current_password'      => 'password123',
        'password'              => 'newsecurepass123',
        'password_confirmation' => 'differentconf123',
    ]);

    $response->assertSessionHasErrors(['password']);
    
    // Verify password is still the original
    $this->admin->refresh();
    $this->assertTrue(Hash::check('password123', $this->admin->password));
});

test('admin cannot change password if new password is too short', function () {
    $response = $this->actingAs($this->admin)->put(route('admin.password.update'), [
        'current_password'      => 'password123',
        'password'              => 'short',
        'password_confirmation' => 'short',
    ]);

    $response->assertSessionHasErrors(['password']);
});

test('admin cannot change password to the same password', function () {
    $response = $this->actingAs($this->admin)->put(route('admin.password.update'), [
        'current_password'      => 'password123',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors(['password']);
});

test('guest cannot access password update route', function () {
    $response = $this->put(route('admin.password.update'), [
        'current_password'      => 'password123',
        'password'              => 'newsecurepass123',
        'password_confirmation' => 'newsecurepass123',
    ]);

    $response->assertRedirect('/admin/login');
});

test('non-admin user cannot access password update route', function () {
    $studentUser = User::create([
        'name' => 'Student User',
        'email' => 'student@example.com',
        'password' => Hash::make('password123'),
        'role' => 'student',
    ]);

    $response = $this->actingAs($studentUser)->put(route('admin.password.update'), [
        'current_password'      => 'password123',
        'password'              => 'newsecurepass123',
        'password_confirmation' => 'newsecurepass123',
    ]);

    $response->assertRedirect('/admin/login');
});
