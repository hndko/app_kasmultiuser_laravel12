<?php

namespace App\Http\Controllers\Modules\CashCategory;

use App\Enums\CategoryType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Modules\CashCategory\StoreCashCategoryRequest;
use App\Http\Requests\Modules\CashCategory\UpdateCashCategoryRequest;
use App\Models\CashCategory;
use App\Services\Modules\CashCategory\CashCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CashCategoryController extends Controller
{
    public function __construct(
        protected CashCategoryService $categoryService
    ) {}

    /**
     * Display a listing of cash categories.
     */
    public function index(Request $request): View
    {
        $categories = $this->categoryService->getPaginatedCategories(
            $request->only('search', 'type', 'status'),
            10
        );

        return view('modules.cash-categories.index', [
            'categories' => $categories,
            'types' => CategoryType::cases(),
            'filters' => $request->all(),
            'title' => 'Kategori Kas',
        ]);
    }

    /**
     * Show the form for creating a new cash category.
     */
    public function create(): View
    {
        $suggestedCode = $this->categoryService->generateCodeSuggestion(CategoryType::INCOME->value);

        return view('modules.cash-categories.create', [
            'types' => CategoryType::cases(),
            'suggestedCode' => $suggestedCode,
            'title' => 'Tambah Kategori Kas',
        ]);
    }

    /**
     * Store a newly created cash category in storage.
     */
    public function store(StoreCashCategoryRequest $request): RedirectResponse
    {
        $this->categoryService->createCategory($request->validated());

        return redirect()->route('modules.cash.categories.index')
            ->with('success', 'Kategori kas berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified cash category.
     */
    public function edit(CashCategory $category): View
    {
        return view('modules.cash-categories.edit', [
            'category' => $category,
            'types' => CategoryType::cases(),
            'title' => 'Edit Kategori Kas: ' . $category->name,
        ]);
    }

    /**
     * Update the specified cash category in storage.
     */
    public function update(UpdateCashCategoryRequest $request, CashCategory $category): RedirectResponse
    {
        $this->categoryService->updateCategory($category, $request->validated());

        return redirect()->route('modules.cash.categories.index')
            ->with('success', 'Kategori kas berhasil diperbarui.');
    }

    /**
     * Remove the specified cash category from storage.
     */
    public function destroy(CashCategory $category): RedirectResponse
    {
        try {
            $this->categoryService->deleteCategory($category);
            return redirect()->route('modules.cash.categories.index')
                ->with('success', 'Kategori kas berhasil dihapus.');
        } catch (ValidationException $e) {
            return redirect()->route('modules.cash.categories.index')
                ->with('error', $e->getMessage());
        }
    }
}
