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
                'tag' => 'software, apps, digital tools, productivity software',
                'subcategories' => ['Mobile Apps', 'Desktop Software', 'Plugins', 'Extensions', 'SaaS Tools']
            ],
            [
                'name' => 'Graphics & Design',
                'description' => 'Visual design assets and resources',
                'tag' => 'graphics, design, templates, creative assets',
                'subcategories' => ['Templates', 'Icons', 'Fonts', 'UI Kits', 'Illustrations', 'Mockups']
            ],
            [
                'name' => 'Audio & Music',
                'description' => 'Sound and music files',
                'tag' => 'audio, music, beats, sound effects, loops',
                'subcategories' => ['Beats', 'Sound Effects', 'Loops', 'Samples', 'Full Tracks']
            ],
            [
                'name' => 'Video & Animation',
                'description' => 'Video content and motion graphics',
                'tag' => 'video, animation, motion graphics, stock footage',
                'subcategories' => ['Stock Footage', 'Motion Graphics', 'Video Templates', 'Animations', 'Intros & Outros']
            ],
            [
                'name' => 'eBooks & Courses',
                'description' => 'Educational content and learning materials',
                'tag' => 'ebooks, courses, education, learning, training',
                'subcategories' => ['Business', 'Technology', 'Design', 'Marketing', 'Personal Development']
            ],
            [
                'name' => 'Consulting Services',
                'description' => 'Professional consulting and advisory services',
                'tag' => 'consulting, business services, advisory, professional services',
                'subcategories' => ['Business Consulting', 'Tech Consulting', 'Marketing Strategy', 'Legal Advisory', 'Financial Planning']
            ],
            [
                'name' => 'Creative Services',
                'description' => 'Creative and artistic services',
                'tag' => 'creative services, design services, freelance, content creation',
                'subcategories' => ['Graphic Design', 'Content Writing', 'Video Editing', 'Photography', 'Voice Over']
            ],
            [
                'name' => 'Merchandise',
                'description' => 'Physical products and branded items',
                'tag' => 'merchandise, products, clothing, accessories, branded items',
                'subcategories' => ['Clothing', 'Accessories', 'Prints', 'Stickers', 'Home Decor']
            ],
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and components',
                'tag' => 'electronics, gadgets, devices, tech products',
                'subcategories' => ['Gadgets', 'Components', 'Accessories', 'Smart Devices']
            ],
            [
                'name' => 'Events & Tickets',
                'description' => 'Event tickets and registrations',
                'tag' => 'events, tickets, concerts, workshops, webinars',
                'subcategories' => ['Concerts', 'Workshops', 'Conferences', 'Masterclasses', 'Webinars']
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name'],
                'description' => $categoryData['description'],
                'tag' => $categoryData['tag'] ?? null,
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
