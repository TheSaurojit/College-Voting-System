@extends('layouts.admin')

@section('title', 'Edit Candidate')
@section('subtitle', 'Update candidate information')

@section('content')
    <div class="max-w-2xl" x-data="{ photoPreview: null }">
        {{-- Back Link --}}
        <a href="{{ route('admin.candidates.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-700 mb-6 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Candidates
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Edit Candidate</h3>
                <p class="text-sm text-gray-500 mt-1">Update the candidate information below.</p>
            </div>

            <form method="POST" action="{{ route('admin.candidates.update', $candidate) }}" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- Photo Upload --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Candidate Photo</label>
                    <div class="flex items-center gap-6">
                        <div class="relative">
                            <div class="w-24 h-24 rounded-2xl bg-gray-100 border-2 border-dashed border-gray-300 overflow-hidden flex items-center justify-center">
                                <template x-if="photoPreview">
                                    <img :src="photoPreview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!photoPreview">
                                    @if($candidate->photo)
                                        <img src="{{ asset('storage/' . $candidate->photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-400 to-violet-500 text-white text-2xl font-bold">
                                            {{ strtoupper(substr($candidate->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </template>
                            </div>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="photo" id="photo" accept="image/*" class="hidden" @change="
                                const file = $event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = (e) => { photoPreview = e.target.result; };
                                    reader.readAsDataURL(file);
                                }
                            ">
                            <label for="photo" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 cursor-pointer hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                Change Photo
                            </label>
                            <p class="text-xs text-gray-400 mt-1.5">JPG, PNG up to 2MB. Leave empty to keep current photo.</p>
                        </div>
                    </div>
                    @error('photo')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Candidate Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $candidate->name) }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200" placeholder="Enter candidate name">
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Post --}}
                <div>
                    <label for="post_id" class="block text-sm font-semibold text-gray-700 mb-2">Election Post <span class="text-red-500">*</span></label>
                    <select id="post_id" name="post_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200">
                        <option value="">Select Post</option>
                        @foreach($posts as $post)
                            <option value="{{ $post->id }}" {{ old('post_id', $candidate->post_id) == $post->id ? 'selected' : '' }}>{{ $post->name }}</option>
                        @endforeach
                    </select>
                    @error('post_id')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Semester / Class --}}
                <div>
                    <label for="semester" class="block text-sm font-semibold text-gray-700 mb-2">Semester / Class</label>
                    <input type="text" id="semester" name="semester" value="{{ old('semester', $candidate->semester) }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200" placeholder="e.g. 3rd Sem or Class 12">
                    @error('semester')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-semibold rounded-xl hover:from-indigo-700 hover:to-violet-700 shadow-lg shadow-indigo-200 transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Update Candidate
                    </button>
                    <a href="{{ route('admin.candidates.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
