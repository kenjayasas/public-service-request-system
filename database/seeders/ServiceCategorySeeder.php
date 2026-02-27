<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Road Maintenance',
                'description' => 'Issues related to road repairs, potholes, and street maintenance'
            ],
            [
                'name' => 'Waste Management',
                'description' => 'Garbage collection, recycling, and waste disposal services'
            ],
            [
                'name' => 'Water Supply',
                'description' => 'Water connection, leakage, and supply issues'
            ],
            [
                'name' => 'Street Lighting',
                'description' => 'Problems with street lights and public lighting'
            ],
            [
                'name' => 'Public Parks',
                'description' => 'Maintenance of public parks and gardens'
            ],
            [
                'name' => 'Sewage System',
                'description' => 'Drainage and sewage related issues'
            ]
        ];

        foreach ($categories as $category) {
            ServiceCategory::create($category);
        }
    }
}