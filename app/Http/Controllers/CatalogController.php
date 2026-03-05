<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $brandIds = $this->resolveExistingIds(Brand::query(), $this->normalizeIds($request->query('brands')));
        $categoryIds = $this->resolveExistingIds(Category::query(), $this->normalizeIds($request->query('categories')));
        $subcategoryIds = $this->resolveExistingIds(
            Subcategory::query(),
            $this->normalizeIds($request->query('subcategories'))
        );

        $productsQuery = Product::query()
            ->with([
                'brand:id,name',
                'subcategory:id,category_id,name',
                'subcategory.category:id,name',
            ])
            ->orderByDesc('id');

        if ($brandIds !== []) {
            $productsQuery->whereIn('brand_id', $brandIds);
        }

        if ($categoryIds !== []) {
            $productsQuery->whereHas('subcategory', fn ($query) => $query->whereIn('category_id', $categoryIds));
        }

        if ($subcategoryIds !== []) {
            $productsQuery->whereIn('subcategory_id', $subcategoryIds);
        }

        $products = $productsQuery->get([
            'id',
            'title',
            'slug',
            'image',
            'short_description',
            'brand_id',
            'subcategory_id',
            'is_new',
        ]);

        $brands = Brand::query()
            ->orderBy('name')
            ->get(['id', 'name', 'image']);

        $categories = Category::query()
            ->with([
                'subcategories' => fn ($query) => $query
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->select(['id', 'category_id', 'name', 'sort_order']),
            ])
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'filters' => [
                'selected' => [
                    'brands' => $brandIds,
                    'categories' => $categoryIds,
                    'subcategories' => $subcategoryIds,
                ],
                'brands' => $brands->map(fn (Brand $brand): array => [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'image' => $brand->image,
                ])->values(),
                'categories' => $categories->map(fn (Category $category): array => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'subcategories' => $category->subcategories->map(fn ($subcategory): array => [
                        'id' => $subcategory->id,
                        'name' => $subcategory->name,
                    ])->values(),
                ])->values(),
            ],
            'products' => $products->map(fn (Product $product): array => [
                'id' => $product->id,
                'title' => $product->title,
                'slug' => $product->slug,
                'image' => $product->image,
                'short_description' => $product->short_description,
                'is_new' => (bool) $product->is_new,
                'brand' => $product->brand ? [
                    'id' => $product->brand->id,
                    'name' => $product->brand->name,
                ] : null,
                'category' => $product->subcategory?->category ? [
                    'id' => $product->subcategory->category->id,
                    'name' => $product->subcategory->category->name,
                ] : null,
                'subcategory' => $product->subcategory ? [
                    'id' => $product->subcategory->id,
                    'name' => $product->subcategory->name,
                ] : null,
            ])->values(),
        ]);
    }

    private function normalizeIds(mixed $rawValue): array
    {
        if ($rawValue === null || $rawValue === '') {
            return [];
        }

        $values = is_array($rawValue)
            ? $rawValue
            : explode(',', (string) $rawValue);

        return collect($values)
            ->map(static fn ($value): int => (int) $value)
            ->filter(static fn (int $value): bool => $value > 0)
            ->unique()
            ->values()
            ->all();
    }

    private function resolveExistingIds(Builder $query, array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        return $query
            ->whereIn('id', $ids)
            ->pluck('id')
            ->map(static fn ($id): int => (int) $id)
            ->values()
            ->all();
    }
}
