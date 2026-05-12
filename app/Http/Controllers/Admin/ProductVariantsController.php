<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductVariantRequest;
use App\Http\Requests\UpdateProductVariantRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductVariantsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('product_variant_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productVariants = ProductVariant::with(['product'])
            ->latest()
            ->get();

        return view('admin.productVariants.index', compact('productVariants'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_variant_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::where('status', 1)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->prepend('Please Select', '');

        return view('admin.productVariants.create', compact('products'));
    }

    public function store(StoreProductVariantRequest $request)
    {
        $data = $request->all();

        $data['stock_quantity'] = $request->stock_quantity ?? 0;
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['sort_order'] = $request->sort_order ?? 0;

        ProductVariant::create($data);

        return redirect()
            ->route('admin.product-variants.index')
            ->with('message', 'Product variant created successfully.');
    }

    public function show(ProductVariant $productVariant)
    {
        abort_if(Gate::denies('product_variant_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productVariant->load(['product']);

        return view('admin.productVariants.show', compact('productVariant'));
    }

    public function edit(ProductVariant $productVariant)
    {
        abort_if(Gate::denies('product_variant_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::where('status', 1)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->prepend('Please Select', '');

        return view('admin.productVariants.edit', compact('productVariant', 'products'));
    }

    public function update(UpdateProductVariantRequest $request, ProductVariant $productVariant)
    {
        $data = $request->all();

        $data['stock_quantity'] = $request->stock_quantity ?? 0;
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['sort_order'] = $request->sort_order ?? 0;

        $productVariant->update($data);

        return redirect()
            ->route('admin.product-variants.index')
            ->with('message', 'Product variant updated successfully.');
    }

    public function destroy(ProductVariant $productVariant)
    {
        abort_if(Gate::denies('product_variant_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productVariant->delete();

        return back()->with('message', 'Product variant deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('product_variant_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ProductVariant::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}