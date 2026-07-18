<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'What are the main requirements for registration?',
                'answer' => 'Applicants need to provide a digital copy of their previous school certificate, ID card (KTP/Passport), family card, and a recent professional photograph. International students may require additional documentation.',
                'sort_order' => 1,
            ],
            [
                'question' => 'Is there an entrance exam for all waves?',
                'answer' => 'Yes, all waves include a Computer Based Test (CBT) covering Logic, English Proficiency, and Basic Mathematics. Results are usually announced within 48 hours.',
                'sort_order' => 2,
            ],
            [
                'question' => 'Are scholarships available for new students?',
                'answer' => 'Absolutely. We offer Merit-Based, Need-Based, and Sports/Arts Achievement scholarships. Applications for scholarships are processed simultaneously with the admission wave.',
                'sort_order' => 3,
            ],
            [
                'question' => 'Can I change my chosen program after registration?',
                'answer' => 'Program changes are permitted before the CBT date. Please contact the admissions office for assistance with internal program transfers.',
                'sort_order' => 4,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create(array_merge($faq, ['is_active' => true]));
        }
    }
}
