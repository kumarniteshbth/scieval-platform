<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $quizzes = [
            // Physics
            ['category' => 'physics', 'title' => 'Fundamentals of Physics', 'difficulty' => 'easy', 'time_limit' => 15, 'description' => 'Test your basic knowledge of physics laws and concepts.'],
            ['category' => 'physics', 'title' => 'Advanced Mechanics & Waves', 'difficulty' => 'hard', 'time_limit' => 20, 'description' => 'Challenge yourself with mechanics, waves, and electromagnetism problems.'],

            // Chemistry
            ['category' => 'chemistry', 'title' => 'Chemical Bonds & Reactions', 'difficulty' => 'medium', 'time_limit' => 15, 'description' => 'Explore atomic structure, bonding, and chemical reactions.'],
            ['category' => 'chemistry', 'title' => 'Organic Chemistry Basics', 'difficulty' => 'hard', 'time_limit' => 20, 'description' => 'Test your understanding of organic compounds and reactions.'],

            // Biology
            ['category' => 'biology', 'title' => 'Cell Biology & Genetics', 'difficulty' => 'medium', 'time_limit' => 15, 'description' => 'Explore cell structure, DNA, and heredity.'],
            ['category' => 'biology', 'title' => 'Evolution & Ecology', 'difficulty' => 'easy', 'time_limit' => 12, 'description' => 'Understand evolutionary theory and ecological systems.'],

            // Environment
            ['category' => 'environment', 'title' => 'Climate Science & Ecology', 'difficulty' => 'medium', 'time_limit' => 15, 'description' => 'Evaluate your understanding of climate change and environmental science.'],

            // Logical Thinking
            ['category' => 'logical-thinking', 'title' => 'Critical Reasoning Challenge', 'difficulty' => 'medium', 'time_limit' => 20, 'description' => 'Sharpen your logical and analytical thinking skills.'],

            // Scientific Reasoning
            ['category' => 'scientific-reasoning', 'title' => 'Scientific Method & Analysis', 'difficulty' => 'medium', 'time_limit' => 15, 'description' => 'Test your understanding of the scientific method, hypothesis, and data analysis.'],
            ['category' => 'scientific-reasoning', 'title' => 'Myth vs Fact: Science Edition', 'difficulty' => 'easy', 'time_limit' => 12, 'description' => 'Separate scientific fact from popular myths.'],
        ];

        foreach ($quizzes as $quizData) {
            $category = Category::where('slug', $quizData['category'])->first();
            if (!$category) continue;

            Quiz::firstOrCreate(
                ['title' => $quizData['title'], 'category_id' => $category->id],
                [
                    'description'   => $quizData['description'],
                    'difficulty'    => $quizData['difficulty'],
                    'time_limit'    => $quizData['time_limit'],
                    'passing_score' => 60,
                    'is_active'     => true,
                    'created_by'    => 1, // admin
                ]
            );
        }
    }
}
