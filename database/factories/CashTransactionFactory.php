<?php

namespace Database\Factories;

use App\Enums\TransactionType;
use App\Models\CashCategory;
use App\Models\CashTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CashTransaction>
 */
class CashTransactionFactory extends Factory
{
    protected $model = CashTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement([TransactionType::INCOME, TransactionType::EXPENSE]);

        return [
            'transaction_number' => 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'transaction_date' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'type' => $type,
            'cash_category_id' => CashCategory::factory(),
            'amount' => fake()->numberBetween(10000, 5000000),
            'description' => fake()->sentence(),
            'reference' => 'REF-' . fake()->numerify('#####'),
            'created_by' => User::factory(),
            'updated_by' => null,
        ];
    }

    /**
     * Income state.
     */
    public function income(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TransactionType::INCOME,
        ]);
    }

    /**
     * Expense state.
     */
    public function expense(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TransactionType::EXPENSE,
        ]);
    }
}
