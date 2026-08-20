<?php

namespace Database\Factories;

use App\Enums\CategoryType;
use App\Models\CashCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CashCategory>
 */
class CashCategoryFactory extends Factory
{
    protected $model = CashCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement([CategoryType::INCOME, CategoryType::EXPENSE, CategoryType::BOTH]);
        $name = fake()->words(2, true);

        return [
            'name' => ucfirst($name),
            'code' => 'CAT-' . strtoupper(Str::random(6)),
            'type' => $type,
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
