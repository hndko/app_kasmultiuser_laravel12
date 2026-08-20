<?php

namespace App\Http\Requests\Modules\CashTransaction;

use App\Enums\CategoryType;
use App\Enums\TransactionType;
use App\Models\CashCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class StoreCashTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transaction_date' => ['required', 'date'],
            'type' => ['required', new Enum(TransactionType::class)],
            'cash_category_id' => ['required', 'exists:cash_categories,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'description' => ['required', 'string', 'max:1000'],
            'reference' => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * Prepare inputs for validation.
     */
    protected function prepareForValidation(): void
    {
        // Strip non-digit characters if formatted rupiah string was passed (e.g. 100.000 -> 100000)
        if ($this->has('amount') && is_string($this->amount)) {
            $cleaned = preg_replace('/[^0-9.]/', '', str_replace(',', '.', str_replace('.', '', $this->amount)));
            $this->merge(['amount' => $cleaned]);
        }
    }

    /**
     * Additional validation logic for Category Type compatibility.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($this->cash_category_id && $this->type) {
                $category = CashCategory::find($this->cash_category_id);
                if ($category && !$category->is_active) {
                    $validator->errors()->add('cash_category_id', 'Kategori kas yang dipilih sedang nonaktif.');
                } elseif ($category && $category->type->value !== CategoryType::BOTH->value && $category->type->value !== $this->type) {
                    $validator->errors()->add(
                        'cash_category_id',
                        "Kategori ini hanya untuk transaksi {$category->type->label()}, tidak cocok dengan tipe transaksi {$this->type}."
                    );
                }
            }
        });
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
            'transaction_date.date' => 'Format tanggal transaksi tidak valid.',
            'type.required' => 'Tipe transaksi wajib dipilih.',
            'cash_category_id.required' => 'Kategori kas wajib dipilih.',
            'cash_category_id.exists' => 'Kategori kas yang dipilih tidak valid.',
            'amount.required' => 'Nominal transaksi wajib diisi.',
            'amount.numeric' => 'Nominal transaksi harus berupa angka.',
            'amount.min' => 'Nominal transaksi minimal Rp 1.',
            'description.required' => 'Keterangan transaksi wajib diisi.',
        ];
    }
}
