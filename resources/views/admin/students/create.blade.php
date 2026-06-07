@extends('layouts.admin')

@section('title', 'Add Student')
@section('subtitle', 'Register a new student')

@section('content')
    <div class="max-w-2xl" x-data="{ programType: '{{ old('program_type', '') }}' }">
        {{-- Back Link --}}
        <a href="{{ route('admin.students.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-700 mb-6 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Students
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Student Details</h3>
                <p class="text-sm text-gray-500 mt-1">Fill in the student information below.</p>
            </div>

            <form method="POST" action="{{ route('admin.students.store') }}" class="p-6 space-y-5">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200" placeholder="Enter student's full name">
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200" placeholder="Enter phone number">
                    @error('phone')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Program Type --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Program Type <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="program_type" value="+2" x-model="programType" class="peer sr-only" {{ old('program_type') == '+2' ? 'checked' : '' }}>
                            <div class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 border-gray-200 bg-white text-gray-600 transition-all duration-200 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 peer-checked:text-indigo-700 hover:bg-gray-50">
                                <span class="font-semibold text-sm">+2</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="program_type" value="Degree" x-model="programType" class="peer sr-only" {{ old('program_type') == 'Degree' ? 'checked' : '' }}>
                            <div class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 border-gray-200 bg-white text-gray-600 transition-all duration-200 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 peer-checked:text-indigo-700 hover:bg-gray-50">
                                <span class="font-semibold text-sm">Degree</span>
                            </div>
                        </label>
                    </div>
                    @error('program_type')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Class (for +2) --}}
                <div x-show="programType === '+2'" x-transition x-cloak>
                    <label for="class" class="block text-sm font-semibold text-gray-700 mb-2">Class <span class="text-red-500">*</span></label>
                    <select id="class" name="class" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200">
                        <option value="">Select Class</option>
                        <option value="11" {{ old('class') == '11' ? 'selected' : '' }}>Class 11</option>
                        <option value="12" {{ old('class') == '12' ? 'selected' : '' }}>Class 12</option>
                    </select>
                    @error('class')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Semester (for Degree) --}}
                <div x-show="programType === 'Degree'" x-transition x-cloak>
                    <label for="semester" class="block text-sm font-semibold text-gray-700 mb-2">Semester <span class="text-red-500">*</span></label>
                    <select id="semester" name="semester" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200">
                        <option value="">Select Semester</option>
                        @foreach(['1st Sem', '3rd Sem', '5th Sem'] as $sem)
                            <option value="{{ $sem }}" {{ old('semester') == $sem ? 'selected' : '' }}>{{ $sem }}</option>
                        @endforeach
                    </select>
                    @error('semester')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Roll Number --}}
                <div>
                    <label for="roll_no" class="block text-sm font-semibold text-gray-700 mb-2">Roll Number <span class="text-red-500">*</span></label>
                    <input type="text" id="roll_no" name="roll_no" value="{{ old('roll_no') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200" placeholder="Enter roll number">
                    @error('roll_no')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-semibold rounded-xl hover:from-indigo-700 hover:to-violet-700 shadow-lg shadow-indigo-200 transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Student
                    </button>
                    <a href="{{ route('admin.students.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
