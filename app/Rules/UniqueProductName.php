<?php

namespace App\Rules;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueProductName implements ValidationRule
{
    protected int $shopId;
    protected ?int $ignoreProductId;

    /**
     * @param int $shopId            The shop to check within
     * @param int|null $ignoreProductId  Product ID to exclude (for edit mode)
     */
    public function __construct(int $shopId, ?int $ignoreProductId = null)
    {
        $this->shopId = $shopId;
        $this->ignoreProductId = $ignoreProductId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || trim($value) === '') {
            return; // Let other rules handle empty values
        }

        $normalized = strtolower(trim($value));

        $query = Product::where('shop_id', $this->shopId)
            ->whereRaw('LOWER(TRIM(name)) = ?', [$normalized])
            ->withTrashed(); // ✅ Also block duplicates against soft-deleted products

        if ($this->ignoreProductId) {
            $query->where('id', '!=', $this->ignoreProductId);
        }

        if ($query->exists()) {
            $fail("A product named \"" . trim($value) . "\" already exists in your shop. Please use a different name.");
        }
    }
}
