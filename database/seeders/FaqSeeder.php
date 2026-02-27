<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run()
    {
        $faqs = [
            [
                'question' => 'How do I submit a service request?',
                'answer' => 'You can submit a service request by logging into your account and clicking on "New Request" from the dashboard. Fill in the required details and submit.',
                'order' => 1,
                'is_active' => true
            ],
            [
                'question' => 'How long does it take to process a request?',
                'answer' => 'Processing time varies depending on the type of request and current workload. You can track the status of your request in real-time from your dashboard.',
                'order' => 2,
                'is_active' => true
            ],
            [
                'question' => 'Can I upload images with my request?',
                'answer' => 'Yes, you can upload images to help us better understand the issue. Accepted formats are JPEG, PNG, and GIF (max 2MB).',
                'order' => 3,
                'is_active' => true
            ],
            [
                'question' => 'How can I contact staff about my request?',
                'answer' => 'You can use our messaging system to communicate directly with assigned staff members about your request.',
                'order' => 4,
                'is_active' => true
            ]
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}