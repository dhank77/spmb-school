<?php

namespace App\Http\Controllers;

use App\Models\AdmissionWave;
use App\Models\Faq;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    /**
     * Display the landing page with admission waves and FAQs.
     */
    public function index(): View
    {
        $waves = AdmissionWave::query()
            ->orderBy('sort_order')
            ->get();

        $faqs = Faq::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('welcome', compact('waves', 'faqs'));
    }
}
