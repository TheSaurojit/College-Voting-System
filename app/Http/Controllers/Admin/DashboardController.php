<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\ElectionSetting;
use App\Models\Post;
use App\Models\Student;
use App\Models\Vote;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with system statistics.
     */
    public function index()
    {
        $totalStudents   = Student::count();
        $totalPosts      = Post::count();
        $totalCandidates = Candidate::count();
        $totalVotes      = Vote::count();
        $settings        = ElectionSetting::current();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalPosts',
            'totalCandidates',
            'totalVotes',
            'settings'
        ));
    }
}
