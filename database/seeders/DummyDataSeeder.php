<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Candidate;
use App\Models\Student;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Posts
        $posts = [
            [
                'name' => 'President',
                'description' => 'The head of the Student Union, representing the entire student body.',
                'display_order' => 1,
            ],
            [
                'name' => 'Vice President',
                'description' => 'Assists the President and leads in their absence.',
                'display_order' => 2,
            ],
            [
                'name' => 'Secretary',
                'description' => 'Responsible for record-keeping and communications.',
                'display_order' => 3,
            ],
            [
                'name' => 'Joint Secretary',
                'description' => 'Assists the Secretary in official student activities.',
                'display_order' => 4,
            ],
        ];

        $postModels = [];
        foreach ($posts as $postData) {
            $postModels[$postData['name']] = Post::updateOrCreate(
                ['name' => $postData['name']],
                $postData
            );
        }

        // 2. Seed Candidates
        $candidates = [
            // President Candidates (Usually senior Degree students - 5th Sem)
            [
                'name' => 'Aarav Sharma',
                'post_id' => $postModels['President']->id,
                'semester' => '5th Sem',
                'photo' => 'candidates/candidate_male_1.png',
            ],
            [
                'name' => 'Priya Patel',
                'post_id' => $postModels['President']->id,
                'semester' => '5th Sem',
                'photo' => 'candidates/candidate_female_1.png',
            ],
            [
                'name' => 'Rahul Verma',
                'post_id' => $postModels['President']->id,
                'semester' => '3rd Sem',
                'photo' => 'candidates/candidate_male_2.png',
            ],

            // Vice President Candidates (Usually senior/mid Degree students - 3rd Sem / 5th Sem)
            [
                'name' => 'Ananya Iyer',
                'post_id' => $postModels['Vice President']->id,
                'semester' => '3rd Sem',
                'photo' => 'candidates/candidate_female_2.png',
            ],
            [
                'name' => 'Aditya Rao',
                'post_id' => $postModels['Vice President']->id,
                'semester' => '3rd Sem',
                'photo' => 'candidates/candidate_male_1.png',
            ],
            [
                'name' => 'Sneha Reddy',
                'post_id' => $postModels['Vice President']->id,
                'semester' => '1st Sem',
                'photo' => 'candidates/candidate_female_1.png',
            ],

            // Secretary Candidates (Usually +2 Class 12 or junior Degree students - 1st Sem)
            [
                'name' => 'Kabir Malhotra',
                'post_id' => $postModels['Secretary']->id,
                'semester' => '12',
                'photo' => 'candidates/candidate_male_2.png',
            ],
            [
                'name' => 'Meera Nair',
                'post_id' => $postModels['Secretary']->id,
                'semester' => '12',
                'photo' => 'candidates/candidate_female_2.png',
            ],
            [
                'name' => ' Rohan Das',
                'post_id' => $postModels['Secretary']->id,
                'semester' => '1st Sem',
                'photo' => 'candidates/candidate_male_1.png',
            ],

            // Joint Secretary Candidates (Usually +2 Class 11/12)
            [
                'name' => 'Ishaan Gupta',
                'post_id' => $postModels['Joint Secretary']->id,
                'semester' => '11',
                'photo' => 'candidates/candidate_male_2.png',
            ],
            [
                'name' => 'Diya Sen',
                'post_id' => $postModels['Joint Secretary']->id,
                'semester' => '11',
                'photo' => 'candidates/candidate_female_1.png',
            ],
        ];

        foreach ($candidates as $cand) {
            Candidate::updateOrCreate(
                ['name' => $cand['name'], 'post_id' => $cand['post_id']],
                $cand
            );
        }

        // 3. Seed Students (across all classes/semesters)
        
        // --- Class 11 Students (10 students) ---
        for ($i = 1; $i <= 10; $i++) {
            Student::updateOrCreate(
                ['phone' => '91100000' . str_pad($i, 2, '0', STR_PAD_LEFT)],
                [
                    'name' => 'Student Eleven ' . $i,
                    'class' => '11',
                    'semester' => null,
                    'roll_no' => 'R11' . str_pad($i, 2, '0', STR_PAD_LEFT),
                ]
            );
        }

        // --- Class 12 Students (10 students) ---
        for ($i = 1; $i <= 10; $i++) {
            Student::updateOrCreate(
                ['phone' => '91200000' . str_pad($i, 2, '0', STR_PAD_LEFT)],
                [
                    'name' => 'Student Twelve ' . $i,
                    'class' => '12',
                    'semester' => null,
                    'roll_no' => 'R12' . str_pad($i, 2, '0', STR_PAD_LEFT),
                ]
            );
        }

        // --- Degree 1st Sem Students (10 students) ---
        for ($i = 1; $i <= 10; $i++) {
            Student::updateOrCreate(
                ['phone' => '90100000' . str_pad($i, 2, '0', STR_PAD_LEFT)],
                [
                    'name' => 'Student FirstSem ' . $i,
                    'class' => null,
                    'semester' => '1st Sem',
                    'roll_no' => 'R01' . str_pad($i, 2, '0', STR_PAD_LEFT),
                ]
            );
        }

        // --- Degree 3rd Sem Students (10 students) ---
        for ($i = 1; $i <= 10; $i++) {
            Student::updateOrCreate(
                ['phone' => '90300000' . str_pad($i, 2, '0', STR_PAD_LEFT)],
                [
                    'name' => 'Student ThirdSem ' . $i,
                    'class' => null,
                    'semester' => '3rd Sem',
                    'roll_no' => 'R03' . str_pad($i, 2, '0', STR_PAD_LEFT),
                ]
            );
        }

        // --- Degree 5th Sem Students (10 students) ---
        for ($i = 1; $i <= 10; $i++) {
            Student::updateOrCreate(
                ['phone' => '90500000' . str_pad($i, 2, '0', STR_PAD_LEFT)],
                [
                    'name' => 'Student FifthSem ' . $i,
                    'class' => null,
                    'semester' => '5th Sem',
                    'roll_no' => 'R05' . str_pad($i, 2, '0', STR_PAD_LEFT),
                ]
            );
        }
    }
}
