<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Subcategory;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Software & Apps',
                'description' => 'Digital software applications and tools',
                'subcategories' => ['Mobile Apps', 'Desktop Software', 'Plugins', 'Extensions', 'SaaS Tools']
            ],
            [
                'name' => 'Graphics & Design',
                'description' => 'Visual design assets and resources',
                'subcategories' => ['Templates', 'Icons', 'Fonts', 'UI Kits', 'Illustrations', 'Mockups']
            ],
            [
                'name' => 'Audio & Music',
                'description' => 'Sound and music files',
                'subcategories' => ['Beats', 'Sound Effects', 'Loops', 'Samples', 'Full Tracks']
            ],
            [
                'name' => 'Video & Animation',
                'description' => 'Video content and motion graphics',
                'subcategories' => ['Stock Footage', 'Motion Graphics', 'Video Templates', 'Animations', 'Intros & Outros']
            ],
            [
                'name' => 'eBooks & Courses',
                'description' => 'Educational content and learning materials',
                'subcategories' => ['Business', 'Technology', 'Design', 'Marketing', 'Personal Development']
            ],
            [
                'name' => 'Consulting Services',
                'description' => 'Professional consulting and advisory services',
                'subcategories' => ['Business Consulting', 'Tech Consulting', 'Marketing Strategy', 'Legal Advisory', 'Financial Planning']
            ],
            [
                'name' => 'Creative Services',
                'description' => 'Creative and artistic services',
                'subcategories' => ['Graphic Design', 'Content Writing', 'Video Editing', 'Photography', 'Voice Over']
            ],
            [
                'name' => 'Merchandise',
                'description' => 'Physical products and branded items',
                'subcategories' => ['Clothing', 'Accessories', 'Prints', 'Stickers', 'Home Decor']
            ],
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and components',
                'subcategories' => ['Gadgets', 'Components', 'Accessories', 'Smart Devices']
            ],
            [
                'name' => 'Events & Tickets',
                'description' => 'Event tickets and registrations',
                'subcategories' => ['Concerts', 'Workshops', 'Conferences', 'Masterclasses', 'Webinars']
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name'],
                'description' => $categoryData['description'],
                'is_active' => true
            ]);

            foreach ($categoryData['subcategories'] as $subcategoryName) {
                Subcategory::create([
                    'category_id' => $category->id,
                    'name' => $subcategoryName,
                    'description' => null
                ]);
            }
        }
    }
}
