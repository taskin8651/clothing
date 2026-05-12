<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::with(['shop', 'category', 'media'])
            ->latest()
            ->get();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shops = Shop::where('status', 1)
            ->orderBy('shop_name')
            ->pluck('shop_name', 'id')
            ->prepend('Please Select', '');

        $categories = Category::where('status', 1)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->prepend('Please Select', '');

        return view('admin.products.create', compact('shops', 'categories'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'shop_id' => ['nullable', 'exists:shops,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'sku' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'brand' => ['nullable', 'string', 'max:255'],
            'fabric' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'try_cloth_available' => ['nullable', 'boolean'],
            'return_available' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'main_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data = $request->except([
            'main_image',
            'gallery_images',
        ]);

        $data['slug'] = $request->slug ?: Str::slug($request->name);
        $data['try_cloth_available'] = $request->has('try_cloth_available') ? 1 : 0;
        $data['return_available'] = $request->has('return_available') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['stock_quantity'] = $request->stock_quantity ?? 0;
        $data['sort_order'] = $request->sort_order ?? 0;

        $product = Product::create($data);

        if ($request->hasFile('main_image')) {
            $product
                ->addMediaFromRequest('main_image')
                ->toMediaCollection('main_image');
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $product
                    ->addMedia($image)
                    ->toMediaCollection('gallery_images');
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('message', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        abort_if(Gate::denies('product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->load(['shop', 'category', 'media']);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shops = Shop::where('status', 1)
            ->orderBy('shop_name')
            ->pluck('shop_name', 'id')
            ->prepend('Please Select', '');

        $categories = Category::where('status', 1)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->prepend('Please Select', '');

        $product->load('media');

        return view('admin.products.edit', compact('product', 'shops', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'shop_id' => ['nullable', 'exists:shops,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug,' . $product->id],
            'sku' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'brand' => ['nullable', 'string', 'max:255'],
            'fabric' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'try_cloth_available' => ['nullable', 'boolean'],
            'return_available' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'main_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_main_image' => ['nullable', 'boolean'],
        ]);

        $data = $request->except([
            'main_image',
            'gallery_images',
            'remove_main_image',
        ]);

        $data['slug'] = $request->slug ?: Str::slug($request->name);
        $data['try_cloth_available'] = $request->has('try_cloth_available') ? 1 : 0;
        $data['return_available'] = $request->has('return_available') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['stock_quantity'] = $request->stock_quantity ?? 0;
        $data['sort_order'] = $request->sort_order ?? 0;

        $product->update($data);

        if ($request->remove_main_image == 1) {
            $product->clearMediaCollection('main_image');
        }

        if ($request->hasFile('main_image')) {
            $product->clearMediaCollection('main_image');

            $product
                ->addMediaFromRequest('main_image')
                ->toMediaCollection('main_image');
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $product
                    ->addMedia($image)
                    ->toMediaCollection('gallery_images');
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('message', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->delete();

        return back()->with('message', 'Product deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Product::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function removeMedia(Product $product, Media $media)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($media->model_type !== Product::class || (int) $media->model_id !== (int) $product->id) {
            abort(404);
        }

        $media->delete();

        return back()->with('message', 'Image removed successfully.');
    }
}