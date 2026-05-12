<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::with([
                'shop',
                'category',
                'media',
                'variants',
            ])
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

    public function store(StoreProductRequest $request)
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->except([
            'main_image',
            'gallery_images',
            'variants',
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

        $this->syncProductVariants($request, $product);

        return redirect()
            ->route('admin.products.index')
            ->with('message', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        abort_if(Gate::denies('product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->load([
            'shop',
            'category',
            'media',
            'variants',
        ]);

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

        $product->load([
            'media',
            'variants',
        ]);

        return view('admin.products.edit', compact('product', 'shops', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->except([
            'main_image',
            'gallery_images',
            'remove_main_image',
            'variants',
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

        $this->syncProductVariants($request, $product);

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

    private function syncProductVariants(Request $request, Product $product): void
    {
        if (! $request->has('variants')) {
            return;
        }

        foreach ($request->variants as $variant) {
            $variantId = $variant['id'] ?? null;
            $deleteVariant = isset($variant['delete']) && (int) $variant['delete'] === 1;

            if ($variantId && $deleteVariant) {
                $product->variants()
                    ->where('id', $variantId)
                    ->delete();

                continue;
            }

            $hasVariantData = !empty($variant['size'])
                || !empty($variant['color'])
                || !empty($variant['sku'])
                || !empty($variant['price'])
                || !empty($variant['discount_price'])
                || !empty($variant['stock_quantity']);

            if (! $hasVariantData) {
                continue;
            }

            $variantData = [
                'size' => $variant['size'] ?? null,
                'color' => $variant['color'] ?? null,
                'sku' => $variant['sku'] ?? null,
                'price' => $variant['price'] ?? null,
                'discount_price' => $variant['discount_price'] ?? null,
                'stock_quantity' => $variant['stock_quantity'] ?? 0,
                'sort_order' => $variant['sort_order'] ?? 0,
                'status' => isset($variant['status']) ? 1 : 0,
            ];

            if ($variantId) {
                $product->variants()
                    ->where('id', $variantId)
                    ->update($variantData);
            } else {
                $product->variants()
                    ->create($variantData);
            }
        }
    }
}