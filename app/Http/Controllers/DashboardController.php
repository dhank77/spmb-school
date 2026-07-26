<?php

namespace App\Http\Controllers;

use App\Models\CbtExam;
use App\Models\CbtSubject;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View|RedirectResponse
    {
        $user = Auth::user();

        // If accessing admin dashboard route specifically
        if ($request->routeIs('admin.dashboard')) {
            $stats = [
                'total' => User::students()->count(),
                'pending' => User::students()->where('verification_status', 'menunggu_verifikasi')->count(),
                'verified' => User::students()->where('verification_status', 'terverifikasi')->count(),
                'rejected' => User::students()->where('verification_status', 'ditolak')->count(),
            ];

            return view('admin.dashboard', compact('stats'));
        }

        $activeSubjects = CbtSubject::withCount('questions')->take(4)->get();
        $upcomingExams = CbtExam::orderBy('date')->orderBy('session')->take(5)->get();

        // If accessing general /dashboard route
        if ($user->hasRole('student')) {
            return view('dashboard', compact('activeSubjects', 'upcomingExams'));
        }

        if ($user->can('access.admin_portal')) {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard', compact('activeSubjects', 'upcomingExams'));
    }
}
