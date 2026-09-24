<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
    public function brands()
    {
        $search = trim((string) request('search', ''));
        $status = request('status', 'all');

        $brands = Brand::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%');
                });
            })
            ->when($status === 'active', fn ($query) => $query->where('status', true))
            ->when($status === 'inactive', fn ($query) => $query->where('status', false))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.brands', compact('brands'));
    }

    public function brandAdd()
    {
        return view('admin.brand-add');
    }

    public function brandStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);

        $brand = new Brand();
        $brand->name = trim($request->input('name'));
        $brand->slug = trim($request->input('slug')) ?: \Str::slug($request->input('name'));
        $brand->status = $request->boolean('status');

        if ($request->hasFile('image')) {
            $brand->image = $this->storeBrandImage($request->file('image'));
        }

        $brand->save();

        return redirect()->route('admin.brands')->with('success', 'Brand added successfully.');
    }

    public function brandEdit(Brand $brand)
    {
        return view('admin.brand-edit', compact('brand'));
    }

    public function brandUpdate(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);

        $brand->name = trim($request->input('name'));
        $brand->slug = trim($request->input('slug')) ?: \Str::slug($request->input('name'));
        $brand->status = $request->boolean('status');

        if ($request->hasFile('image')) {
            if ($brand->image && file_exists(public_path($brand->image))) {
                unlink(public_path($brand->image));
            }

            $brand->image = $this->storeBrandImage($request->file('image'));
        }

        $brand->save();

        return redirect()->route('admin.brands')->with('success', 'Brand updated successfully.');
    }

    public function brandDestroy($id)
    {
        $brand = Brand::findOrFail($id);
        if ($brand->image && file_exists(public_path($brand->image))) {
            unlink(public_path($brand->image));
        }

        $brand->delete();

        return redirect()->route('admin.brands')->with('success', 'Brand deleted successfully.');
    }

    protected function storeBrandImage($file)
    {
        $targetDir = public_path('uploads/brands');

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . '-' . \Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $file->move($targetDir, $fileName);

        return 'uploads/brands/' . $fileName;
    }

    public function generateThumbnailImage($image, $imageName,$imagePath, $width=124, $height=124)
    {
        $thembnailPath = $imagePath . '/thumbnails';
        if (!file_exists($thembnailPath)) {
            mkdir($thembnailPath, 0755, true);}

        image::decode($image)->resize($width, $height)->save($thembnailPath . '/' . $imageName);
    }
}
