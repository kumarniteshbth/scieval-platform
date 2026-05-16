<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Physics',
                'slug'        => 'physics',
                'description' => 'Laws of motion, thermodynamics, electromagnetism, optics, and modern physics.',
                'icon'        => '⚛️',
                'color'       => '#6366f1',
            ],
            [
                'name'        => 'Chemistry',
                'slug'        => 'chemistry',
                'description' => 'Atomic structure, chemical bonding, reactions, organic and inorganic chemistry.',
                'icon'        => '🧪',
                'color'       => '#10b981',
            ],
            [
                'name'        => 'Biology',
                'slug'        => 'biology',
                'description' => 'Cell biology, genetics, evolution, ecology, and human physiology.',
                'icon'        => '🧬',
                'color'       => '#f59e0b',
            ],
            [
                'name'        => 'Environment',
                'slug'        => 'environment',
                'description' => 'Ecology, climate science, environmental issues, and sustainability.',
                'icon'        => '🌍',
                'color'       => '#22c55e',
            ],
            [
                'name'        => 'Logical Thinking',
                'slug'        => 'logical-thinking',
                'description' => 'Deductive reasoning, pattern recognition, and analytical problem-solving.',
                'icon'        => '🧠',
                'color'       => '#ec4899',
            ],
            [
                'name'        => 'Scientific Reasoning',
                'slug'        => 'scientific-reasoning',
                'description' => 'Scientific method, hypothesis testing, data analysis, and research evaluation.',
                'icon'        => '🔬',
                'color'       => '#8b5cf6',
            ],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                array_merge($cat, ['is_active' => true])
            );
        }
    }
}
