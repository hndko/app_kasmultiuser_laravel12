<?php

namespace App\Services\Modules\CashCategory;

use App\Enums\CategoryType;
use App\Models\CashCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class CashCategoryService
{
    /**
     * Get paginated cash categories with filters.
     */
    public function getPaginatedCategories(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = CashCategory::query()->withCount('transactions');

        // Search by keyword (name or code)
        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // Filter by status (active / inactive)
        if (isset($filters['status']) && $filters['status'] !== '') {
            $isActive = $filters['status'] === '1' || $filters['status'] === 'active' || $filters['status'] === true;
            $query->where('is_active', $isActive);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    /**
     * Create a new cash category.
     */
    public function createCategory(array $data): CashCategory
    {
        return CashCategory::create($data);
    }

    /**
     * Update an existing cash category.
     */
    public function updateCategory(CashCategory $category, array $data): CashCategory
    {
        $category->update($data);
        return $category;
    }

    /**
     * Delete cash category with integrity checks.
     *
     * @throws ValidationException
     */
    public function deleteCategory(CashCategory $category): bool
    {
        if ($category->transactions()->exists()) {
            throw ValidationException::withMessages([
                'category' => 'Kategori tidak dapat dihapus karena sudah memiliki transaksi terkait. Anda dapat menonaktifkan status kategori ini.',
            ]);
        }

        return (bool) $category->delete();
    }

    /**
     * Generate code suggestion based on type.
     */
    public function generateCodeSuggestion(string $type = 'income'): string
    {
        $prefix = match($type) {
            CategoryType::INCOME->value => 'CAT-IN',
            CategoryType::EXPENSE->value => 'CAT-OUT',
            default => 'CAT-BOTH',
        };

        $count = CashCategory::where('code', 'like', "{$prefix}-%")->withTrashed()->count() + 1;
        $code = sprintf('%s-%03d', $prefix, $count);

        while (CashCategory::where('code', $code)->withTrashed()->exists()) {
            $count++;
            $code = sprintf('%s-%03d', $prefix, $count);
        }

        return $code;
    }
}
