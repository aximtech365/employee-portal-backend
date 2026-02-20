<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;

class DocumentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['title' => 'Policy', 'description' => 'Company policies and procedures'],
            ['title' => 'Report', 'description' => 'Business reports and analytics'],
            ['title' => 'Template', 'description' => 'Document templates'],
            ['title' => 'Guide', 'description' => 'How-to guides and manuals'],
            ['title' => 'Form', 'description' => 'Forms and applications'],
            ['title' => 'Other', 'description' => 'Miscellaneous documents'],
        ];

        foreach ($categories as $category) {
            DocumentCategory::create($category);
        }
    }
}
