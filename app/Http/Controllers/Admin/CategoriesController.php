<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class CategoriesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::with(['parent', 'children', 'products', 'media'])->latest()->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        abort_if(Gate::denies('category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $parents = Category::where('status', 1)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->prepend('No Parent', '');

        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'parent_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug'],
            'status' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'category_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data = $request->except(['category_image']);
        $data['slug'] = $request->slug ?: Str::slug($request->name);
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['sort_order'] = $request->sort_order ?? 0;

        $category = Category::create($data);

        if ($request->hasFile('category_image')) {
            $category
                ->addMediaFromRequest('category_image')
                ->toMediaCollection('category_image');
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('message', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        abort_if(Gate::denies('category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $category->load(['parent', 'children', 'products', 'media']);

        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $parents = Category::where('id', '!=', $category->id)
            ->where('status', 1)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->prepend('No Parent', '');

        $category->load('media');

        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'parent_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug,' . $category->id],
            'status' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'category_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_category_image' => ['nullable', 'boolean'],
        ]);

        $data = $request->except(['category_image', 'remove_category_image']);
        $data['slug'] = $request->slug ?: Str::slug($request->name);
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['sort_order'] = $request->sort_order ?? 0;

        $category->update($data);

        if ($request->remove_category_image == 1) {
            $category->clearMediaCollection('category_image');
        }

        if ($request->hasFile('category_image')) {
            $category->clearMediaCollection('category_image');

            $category
                ->addMediaFromRequest('category_image')
                ->toMediaCollection('category_image');
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('message', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        abort_if(Gate::denies('category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $category->delete();

        return back()->with('message', 'Category deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Category::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function removeMedia(Category $category, Media $media)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($media->model_type !== Category::class || (int) $media->model_id !== (int) $category->id) {
            abort(404);
        }

        $media->delete();

        return back()->with('message', 'Image removed successfully.');
    }
}