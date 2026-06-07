@extends('layouts.admin')

@section('title', 'Bulk Import Students')
@section('subtitle', 'Upload an Excel or CSV file to import multiple students at once')

@section('content')
<div
    class="max-w-5xl"
    x-data="bulkImport()"
    x-init="init()"
>
    {{-- ── Back link ─────────────────────────────────────── --}}
    <a href="{{ route('admin.students.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-700 mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Students
    </a>

    {{-- ── Step indicator ────────────────────────────────── --}}
    <div class="flex items-center gap-0 mb-8">
        @foreach([['1','Choose Class & File'],['2','Preview Data'],['3','Confirm Import']] as $i => $s)
        <div class="flex items-center {{ $i < 2 ? 'flex-1' : '' }}">
            <div class="flex items-center gap-2.5 shrink-0">
                <div
                    class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300"
                    :class="{
                        'bg-indigo-600 border-indigo-600 text-white': step > {{ $i + 1 }} || step === {{ $i + 1 }},
                        'bg-white border-gray-300 text-gray-400': step < {{ $i + 1 }}
                    }"
                >
                    <template x-if="step > {{ $i + 1 }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </template>
                    <template x-if="step <= {{ $i + 1 }}">
                        <span>{{ $i + 1 }}</span>
                    </template>
                </div>
                <span class="text-sm font-semibold hidden sm:block"
                    :class="step === {{ $i + 1 }} ? 'text-indigo-600' : (step > {{ $i + 1 }} ? 'text-gray-700' : 'text-gray-400')"
                >{{ $s[1] }}</span>
            </div>
            @if($i < 2)
            <div class="flex-1 mx-3 h-0.5 transition-all duration-500"
                :class="step > {{ $i + 1 }} ? 'bg-indigo-500' : 'bg-gray-200'"></div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- ══════════════════════════════════════════════════════
         STEP 1 — Choose class / semester + upload file
    ══════════════════════════════════════════════════════ --}}
    <div x-show="step === 1" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

        {{-- Template download tip --}}
        <div class="flex items-start gap-3 p-4 rounded-xl bg-indigo-50 border border-indigo-100 mb-6">
            <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="text-sm font-semibold text-indigo-800">Use the correct column format</p>
                <p class="text-xs text-indigo-600 mt-0.5">
                    Your file must have columns: <code class="font-mono bg-indigo-100 px-1 rounded">name</code>, <code class="font-mono bg-indigo-100 px-1 rounded">phone</code>, <code class="font-mono bg-indigo-100 px-1 rounded">roll_no</code>.
                    <a href="{{ route('admin.students.bulk-import.template') }}" class="underline font-semibold ml-1 hover:text-indigo-800">Download template →</a>
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Step 1 — Select Program & Upload File</h3>
            </div>

            <div class="p-6 space-y-6">
                {{-- Program type --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Program Type <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="program_type" value="+2" x-model="programType" class="peer sr-only">
                            <div class="flex items-center justify-center gap-2.5 px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white text-gray-600 font-semibold transition-all duration-200 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 peer-checked:text-indigo-700 hover:bg-gray-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                +2 (Class 11 / 12)
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="program_type" value="Degree" x-model="programType" class="peer sr-only">
                            <div class="flex items-center justify-center gap-2.5 px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white text-gray-600 font-semibold transition-all duration-200 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 peer-checked:text-indigo-700 hover:bg-gray-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                                Degree (Semester)
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Class picker (+2) --}}
                <div x-show="programType === '+2'" x-transition x-cloak>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Class <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <template x-for="cls in [11, 12]">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="class" :value="cls" x-model="selectedClass" class="peer sr-only">
                                <div class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 border-gray-200 bg-white text-gray-600 font-semibold text-sm transition-all duration-200 peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 hover:bg-gray-50"
                                     x-text="'Class ' + cls"></div>
                            </label>
                        </template>
                    </div>
                </div>

                {{-- Semester picker (Degree) --}}
                <div x-show="programType === 'Degree'" x-transition x-cloak>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Semester <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-3 gap-3">
                        <template x-for="sem in ['1st Sem', '3rd Sem', '5th Sem']">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="semester" :value="sem" x-model="selectedSemester" class="peer sr-only">
                                <div class="flex items-center justify-center px-3 py-3 rounded-xl border-2 border-gray-200 bg-white text-gray-600 font-semibold text-sm transition-all duration-200 peer-checked:bg-violet-50 peer-checked:border-violet-500 peer-checked:text-violet-700 hover:bg-gray-50"
                                     x-text="sem"></div>
                            </label>
                        </template>
                    </div>
                </div>

                {{-- File upload drop zone --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Excel / CSV File <span class="text-red-500">*</span></label>
                    <div
                        class="relative border-2 border-dashed rounded-2xl p-8 text-center transition-all duration-200 cursor-pointer group"
                        :class="fileSelected ? 'border-emerald-400 bg-emerald-50' : 'border-gray-300 hover:border-indigo-400 hover:bg-indigo-50/30'"
                        @dragover.prevent="dragOver = true"
                        @dragleave.prevent="dragOver = false"
                        @drop.prevent="handleDrop($event)"
                        @click="$refs.fileInput.click()"
                    >
                        <input
                            type="file"
                            x-ref="fileInput"
                            accept=".xlsx,.xls,.csv"
                            class="hidden"
                            @change="handleFile($event)"
                        >

                        <template x-if="!fileSelected">
                            <div>
                                <div class="w-14 h-14 rounded-2xl bg-gray-100 group-hover:bg-indigo-100 flex items-center justify-center mx-auto mb-4 transition-colors duration-200">
                                    <svg class="w-7 h-7 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-700">Drop your file here or <span class="text-indigo-600">browse</span></p>
                                <p class="text-xs text-gray-400 mt-1">.xlsx, .xls, .csv — max 5MB</p>
                            </div>
                        </template>

                        <template x-if="fileSelected">
                            <div class="flex items-center justify-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-bold text-gray-900" x-text="fileName"></p>
                                    <p class="text-xs text-gray-400" x-text="fileSize"></p>
                                </div>
                                <button type="button" class="ml-2 p-1.5 rounded-lg hover:bg-red-100 text-gray-400 hover:text-red-500 transition-colors" @click.stop="clearFile()">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Step 1 error --}}
                <div x-show="step1Error" x-transition class="flex items-center gap-2 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm" x-cloak>
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span x-text="step1Error"></span>
                </div>

                <div class="flex justify-end pt-2">
                    <button
                        type="button"
                        @click="loadPreview()"
                        :disabled="loading || !fileSelected || (!selectedClass && programType === '+2') || (!selectedSemester && programType === 'Degree') || !programType"
                        class="inline-flex items-center gap-2 px-7 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-bold rounded-xl hover:from-indigo-700 hover:to-violet-700 shadow-lg shadow-indigo-200 transition-all duration-200 transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                    >
                        <template x-if="loading">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        </template>
                        <template x-if="!loading">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </template>
                        <span x-text="loading ? 'Parsing file…' : 'Preview Students'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         STEP 2 — Preview table
    ══════════════════════════════════════════════════════ --}}
    <div x-show="step === 2" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>

        {{-- Summary cards --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-gray-900" x-text="previewData.total"></p>
                    <p class="text-xs font-medium text-gray-500">Total Rows</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-emerald-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-emerald-700" x-text="previewData.valid_count"></p>
                    <p class="text-xs font-medium text-gray-500">Ready to Import</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-red-600" x-text="previewData.invalid_count"></p>
                    <p class="text-xs font-medium text-gray-500">Will be Skipped</p>
                </div>
            </div>
        </div>

        {{-- Program badge --}}
        <div class="flex items-center gap-3 mb-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span x-text="programType === '+2' ? 'Class ' + selectedClass : selectedSemester"></span>
            </span>
            <span class="text-sm text-gray-500" x-text="'from ' + fileName"></span>
        </div>

        {{-- Preview table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900">Student Preview</h3>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span><span class="text-xs text-gray-500">Valid</span>
                    <span class="w-3 h-3 rounded-full bg-red-400 inline-block ml-2"></span><span class="text-xs text-gray-500">Will skip</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/60 border-b border-gray-100">
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3.5">Row</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3.5">Name</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3.5">Phone</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3.5">Roll No</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <template x-for="row in previewData.rows" :key="row.row">
                            <tr :class="row.valid ? 'hover:bg-gray-50/50' : 'bg-red-50/50'">
                                <td class="px-6 py-3.5 text-gray-400 font-mono text-xs" x-text="row.row"></td>
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0"
                                             :class="row.valid ? 'bg-gradient-to-br from-indigo-400 to-violet-500' : 'bg-red-300'"
                                             x-text="row.name ? row.name[0].toUpperCase() : '?'"></div>
                                        <span class="font-semibold text-gray-900" x-text="row.name || '—'"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5 text-gray-600" x-text="row.phone || '—'"></td>
                                <td class="px-6 py-3.5 font-mono text-gray-600 text-xs" x-text="row.roll_no || '—'"></td>
                                <td class="px-6 py-3.5">
                                    <template x-if="row.valid">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-semibold">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Ready
                                        </span>
                                    </template>
                                    <template x-if="!row.valid">
                                        <div>
                                            <template x-for="err in row.errors" :key="err">
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-red-100 text-red-600 text-xs font-semibold mb-0.5 mr-0.5">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    <span x-text="err"></span>
                                                </span>
                                            </template>
                                        </div>
                                    </template>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- No valid rows warning --}}
        <div x-show="previewData.valid_count === 0" class="flex items-start gap-3 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm mb-6" x-cloak>
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <p>No valid rows found. Please fix your file and try again.</p>
        </div>

        <div class="flex items-center justify-between">
            <button type="button" @click="step = 1" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back
            </button>
            <button
                type="button"
                @click="step = 3"
                :disabled="previewData.valid_count === 0"
                class="inline-flex items-center gap-2 px-7 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-bold rounded-xl hover:from-indigo-700 hover:to-violet-700 shadow-lg shadow-indigo-200 transition-all duration-200 transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
            >
                Continue to Confirm
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         STEP 3 — Final confirmation + POST form
    ══════════════════════════════════════════════════════ --}}
    <div x-show="step === 3" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-emerald-400 to-teal-500 w-full"></div>

            <div class="p-8 text-center">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center mx-auto mb-6 shadow-xl shadow-emerald-200">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                </div>

                <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Ready to Import!</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">
                    You are about to import
                    <span class="font-bold text-gray-900" x-text="previewData.valid_count"></span>
                    student(s) into
                    <span class="font-bold text-indigo-600" x-text="programType === '+2' ? 'Class ' + selectedClass : selectedSemester"></span>.
                    <template x-if="previewData.invalid_count > 0">
                        <span class="block mt-1 text-amber-600 text-sm">
                            <span x-text="previewData.invalid_count"></span> invalid row(s) will be automatically skipped.
                        </span>
                    </template>
                </p>

                {{-- Hidden form that actually submits --}}
                <form
                    method="POST"
                    action="{{ route('admin.students.bulk-import.store') }}"
                    enctype="multipart/form-data"
                    x-ref="importForm"
                >
                    @csrf
                    <input type="hidden" name="program_type" :value="programType">
                    <input type="hidden" name="class" :value="selectedClass">
                    <input type="hidden" name="semester" :value="selectedSemester">
                    {{-- The file is re-attached from the stored File object --}}
                    <input type="file" name="file" x-ref="hiddenFile" class="hidden" accept=".xlsx,.xls,.csv">

                    <div class="flex items-center justify-center gap-3">
                        <button type="button" @click="step = 2" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Back to Preview
                        </button>
                        <button
                            type="button"
                            @click="submitImport()"
                            class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-sm font-bold rounded-xl hover:from-emerald-600 hover:to-teal-700 shadow-lg shadow-emerald-200 transition-all duration-200 transform hover:-translate-y-0.5"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                            Import <span x-text="previewData.valid_count"></span> Students
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function bulkImport() {
    return {
        step: 1,
        programType: '',
        selectedClass: '',
        selectedSemester: '',
        fileSelected: false,
        fileName: '',
        fileSize: '',
        fileObject: null,
        loading: false,
        step1Error: '',
        previewData: { rows: [], total: 0, valid_count: 0, invalid_count: 0 },

        init() {},

        handleFile(e) {
            const file = e.target.files[0];
            if (file) this.setFile(file);
        },

        handleDrop(e) {
            const file = e.dataTransfer.files[0];
            if (file) this.setFile(file);
        },

        setFile(file) {
            this.fileObject   = file;
            this.fileSelected = true;
            this.fileName     = file.name;
            this.fileSize     = (file.size / 1024).toFixed(1) + ' KB';
            this.step1Error   = '';
        },

        clearFile() {
            this.fileObject   = null;
            this.fileSelected = false;
            this.fileName     = '';
            this.fileSize     = '';
            this.$refs.fileInput.value = '';
        },

        async loadPreview() {
            if (!this.fileObject) {
                this.step1Error = 'Please select a file.';
                return;
            }
            if (this.programType === '+2' && !this.selectedClass) {
                this.step1Error = 'Please select a class.';
                return;
            }
            if (this.programType === 'Degree' && !this.selectedSemester) {
                this.step1Error = 'Please select a semester.';
                return;
            }

            this.loading    = true;
            this.step1Error = '';

            const fd = new FormData();
            fd.append('file', this.fileObject);
            fd.append('program_type', this.programType);
            fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            if (this.programType === '+2')     fd.append('class',    this.selectedClass);
            if (this.programType === 'Degree') fd.append('semester', this.selectedSemester);

            try {
                const res = await fetch('{{ route("admin.students.bulk-import.preview") }}', {
                    method: 'POST',
                    body: fd,
                });
                const data = await res.json();

                if (!data.success) {
                    this.step1Error = data.message || 'Failed to parse file.';
                } else {
                    this.previewData = data;
                    this.step = 2;
                }
            } catch (err) {
                this.step1Error = 'Network error. Please try again.';
            } finally {
                this.loading = false;
            }
        },

        submitImport() {
            // Transfer the file to the hidden file input using DataTransfer
            const dt = new DataTransfer();
            dt.items.add(this.fileObject);
            this.$refs.hiddenFile.files = dt.files;
            this.$refs.importForm.submit();
        },
    };
}
</script>
@endpush
@endsection
