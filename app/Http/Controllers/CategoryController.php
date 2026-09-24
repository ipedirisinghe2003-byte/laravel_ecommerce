<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories');
    }

    public function apiIndex(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status', 'active');
        $perPage = min(max((int) $request->query('limit', 10), 1), 50);

        $categories = Category::query()
            ->withCount('products')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when(in_array($status, ['active', 'inactive'], true), fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return response()->json([
            'data' => $categories->getCollection()->map(fn (Category $category) => $this->categoryData($category))->values(),
            'meta' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
            ],
        ]);
    }

    public function apiShow(Category $category): JsonResponse
    {
        return response()->json(['data' => $this->categoryData($category->loadCount('products'))]);
    }

    public function apiStore(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $category = DB::transaction(function () use ($data, $request) {
            $category = Category::create($data);
            $this->storeLogo($request, $category);

            return $category->fresh();
        });

        return response()->json([
            'message' => 'Category added successfully',
            'data' => $this->categoryData($category->loadCount('products')),
        ], 201);
    }

    public function apiUpdate(Request $request, Category $category): JsonResponse
    {
        $data = $this->validated($request, $category);
        $oldLogo = $category->logo;
        $removeLogo = $request->boolean('remove_logo');

        $category = DB::transaction(function () use ($data, $request, $category, $removeLogo) {
            unset($data['logo']);
            $category->update($data);

            if ($request->hasFile('logo')) {
                $this->storeLogo($request, $category);
            } elseif ($removeLogo) {
                $category->update(['logo' => null]);
            }

            return $category->fresh();
        });

        if (($request->hasFile('logo') || $removeLogo) && $oldLogo && $oldLogo !== $category->logo) {
            $this->deleteLogoFile($oldLogo);
        }

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $this->categoryData($category->fresh()->loadCount('products')),
        ]);
    }

    public function apiDestroy(Category $category): JsonResponse
    {
        if ($category->products()->exists()) {
            return response()->json([
                'message' => 'This category cannot be deleted while products are attached to it.',
            ], 409);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }

    private function validated(Request $request, ?Category $category = null): array
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category?->id)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category?->id)],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
        ]);

        $data = $validator->validate();
        $data['name'] = trim($data['name']);
        $data['slug'] = trim($data['slug'] ?? '') ?: Str::slug($data['name']);
        Validator::make($data, [
            'slug' => ['required', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category?->id)],
        ])->validate();
        unset($data['remove_logo']);

        return $data;
    }

    private function storeLogo(Request $request, Category $category): void
    {
        if (!$request->hasFile('logo')) {
            return;
        }

        if ($category->logo && file_exists(public_path($category->logo))) {
            unlink(public_path($category->logo));
        }

        $directory = public_path('uploads/categories');
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $filename = time() . '-' . Str::random(8) . '.' . $request->file('logo')->getClientOriginalExtension();
        $request->file('logo')->move($directory, $filename);
        $category->update(['logo' => 'uploads/categories/' . $filename]);
    }

    private function deleteLogoFile(string $logo): void
    {
        $path = public_path($logo);
        if (file_exists($path)) {
            unlink($path);
        }
    }

    private function categoryData(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'logo' => $category->logo ? asset($category->logo) : null,
            'description' => $category->description,
            'status' => $category->status,
            'product_count' => (int) ($category->products_count ?? 0),
        ];
    }
}
