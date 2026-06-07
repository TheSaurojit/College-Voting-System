<?php

namespace App\Http\Middleware;

use App\Models\ElectionSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VotingOpenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Ensure that voting is currently open before allowing access.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $settings = ElectionSetting::current();

        $now = now();
        $isClosed = ! $settings->voting_open || 
                    ($settings->voting_start && $now->lt($settings->voting_start)) || 
                    ($settings->voting_end && $now->gt($settings->voting_end));

        if ($isClosed) {
            $message = 'Voting is currently closed.';
            if ($settings->voting_start && $now->lt($settings->voting_start)) {
                $message = 'Voting has not started yet.';
            } elseif ($settings->voting_end && $now->gt($settings->voting_end)) {
                $message = 'Voting has already ended.';
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 403);
            }

            if (session()->has('student_id')) {
                return redirect()->route('student.results')->with('error', $message);
            }

            return redirect('/')->with('error', $message);
        }

        return $next($request);
    }
}
