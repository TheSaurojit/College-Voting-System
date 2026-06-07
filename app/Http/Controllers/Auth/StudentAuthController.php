<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Contracts\SmsServiceInterface;
use App\Models\Otp;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentAuthController extends Controller
{
    /**
     * The SMS service instance.
     */
    protected SmsServiceInterface $smsService;

    /**
     * Create a new controller instance.
     */
    public function __construct(SmsServiceInterface $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Show the student login form.
     */
    public function showLoginForm()
    {
        if (session()->has('student_id')) {
            return redirect()->route('student.vote');
        }

        return view('auth.student-login');
    }

    /**
     * Validate student credentials and send OTP via SMS.
     */
    public function sendOtp(Request $request)
    {
        // Handle Resend OTP if request is from the OTP verification page
        if (!$request->has('phone') && (session()->has('_otp_student_id') || session()->has('otp_student_id'))) {
            $studentId = session('_otp_student_id') ?? session('otp_student_id');
            $student = Student::find($studentId);
            if ($student) {
                // Generate a 6-digit OTP
                $otpCode = rand(100000, 999999);

                // Delete any existing OTPs for this phone number
                Otp::where('phone', $student->phone)->delete();

                // Create new OTP record
                Otp::create([
                    'phone'      => $student->phone,
                    'otp_code'   => $otpCode,
                    'expires_at' => now()->addMinutes(5),
                    'verified'   => false,
                ]);

                // Send OTP via SMS service
                $this->smsService->sendOtp($student->phone, $otpCode);

                // Flash data for OTP verification page
                session()->flash('otp_phone', $student->phone);
                session()->flash('otp_student_id', $student->id);
                session()->put('_otp_phone', $student->phone);
                session()->put('_otp_student_id', $student->id);

                return redirect()->route('student.otp');
            }
        }

        $request->validate([
            'phone'        => ['required', 'string'],
            'roll_no'      => ['required', 'string'],
            'program_type' => ['required', 'in:+2,Degree'],
            'class'        => ['nullable', 'required_if:program_type,+2', 'in:11,12'],
            'semester'     => ['nullable', 'required_if:program_type,Degree', 'in:1st Sem,3rd Sem,5th Sem'],
        ]);

        $query = Student::where('phone', $request->phone)
            ->where('roll_no', $request->roll_no);

        if ($request->program_type === '+2') {
            $query->where('class', $request->class)
                  ->whereNull('semester');
        } else {
            $query->where('semester', $request->semester)
                  ->whereNull('class');
        }

        $student = $query->first();

        if (! $student) {
            return back()
                ->withInput()
                ->withErrors(['phone' => 'Student not found. Please check your details and try again.']);
        }

        // Generate a 6-digit OTP
        $otpCode = rand(100000, 999999);

        // Delete any existing OTPs for this phone number
        Otp::where('phone', $request->phone)->delete();

        // Create new OTP record
        Otp::create([
            'phone'      => $request->phone,
            'otp_code'   => $otpCode,
            'expires_at' => now()->addMinutes(2),
            'verified'   => false,
        ]);

        // Send OTP via SMS service
        $this->smsService->sendOtp($request->phone, $otpCode);

        // Flash data for OTP verification page
        session()->flash('otp_phone', $request->phone);
        session()->flash('otp_student_id', $student->id);
        session()->put('_otp_phone', $request->phone);
        session()->put('_otp_student_id', $student->id);

        return redirect()->route('student.otp');
    }

    /**
     * Show the OTP verification form.
     */
    public function showOtpForm(Request $request)
    {
        $phone = session('otp_phone') ?? session('_otp_phone');

        if (! $phone) {
            return redirect()->route('student.login')
                ->withErrors(['phone' => 'Please enter your details first.']);
        }

        return view('auth.student-otp', ['phone' => $phone]);
    }

    /**
     * Verify the OTP and log the student in.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $phone = session('otp_phone') ?? session('_otp_phone');

        if (! $phone) {
            return redirect()->route('student.login')
                ->withErrors(['phone' => 'Session expired. Please try again.']);
        }

        $otp = Otp::where('phone', $phone)
            ->where('otp_code', $request->otp)
            ->where('verified', false)
            ->first();

        if (! $otp) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        if ($otp->expires_at->isPast()) {
            $otp->delete();
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        // Mark OTP as verified
        $otp->update(['verified' => true]);

        // Get the student
        $studentId = session('otp_student_id') ?? session('_otp_student_id');
        $student = Student::find($studentId);

        if (! $student) {
            // Fallback: find student by phone
            $student = Student::where('phone', $phone)->first();
        }

        if (! $student) {
            return redirect()->route('student.login')
                ->withErrors(['phone' => 'Student record not found. Please contact administrator.']);
        }

        // Set session data for the authenticated student
        session()->put('student_id', $student->id);
        session()->put('student_name', $student->name);

        // Clean up temporary session keys
        session()->forget(['_otp_phone', '_otp_student_id']);

        $request->session()->regenerate();

        return redirect()->route('student.vote');
    }

    /**
     * Log the student out.
     */
    public function logout(Request $request)
    {
        session()->forget(['student_id', 'student_name', '_otp_phone', '_otp_student_id']);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login');
    }
}
