<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ShopsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('shop_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shops = Shop::with(['media', 'products'])->latest()->get();

        return view('admin.shops.index', compact('shops'));
    }

    public function create()
    {
        abort_if(Gate::denies('shop_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.shops.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('shop_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'shop_name' => ['required', 'string', 'max:255'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:20'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'opening_time' => ['nullable'],
            'closing_time' => ['nullable'],
            'status' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'shop_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data = $request->except(['shop_image']);
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['sort_order'] = $request->sort_order ?? 0;

        $shop = Shop::create($data);

        if ($request->hasFile('shop_image')) {
            $shop
                ->addMediaFromRequest('shop_image')
                ->toMediaCollection('shop_image');
        }

        return redirect()
            ->route('admin.shops.index')
            ->with('message', 'Shop created successfully.');
    }

    public function show(Shop $shop)
    {
        abort_if(Gate::denies('shop_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shop->load(['media', 'products']);

        return view('admin.shops.show', compact('shop'));
    }

    public function edit(Shop $shop)
    {
        abort_if(Gate::denies('shop_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shop->load('media');

        return view('admin.shops.edit', compact('shop'));
    }

    public function update(Request $request, Shop $shop)
    {
        abort_if(Gate::denies('shop_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'shop_name' => ['required', 'string', 'max:255'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:20'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'opening_time' => ['nullable'],
            'closing_time' => ['nullable'],
            'status' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'shop_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_shop_image' => ['nullable', 'boolean'],
        ]);

        $data = $request->except(['shop_image', 'remove_shop_image']);
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['sort_order'] = $request->sort_order ?? 0;

        $shop->update($data);

        if ($request->remove_shop_image == 1) {
            $shop->clearMediaCollection('shop_image');
        }

        if ($request->hasFile('shop_image')) {
            $shop->clearMediaCollection('shop_image');

            $shop
                ->addMediaFromRequest('shop_image')
                ->toMediaCollection('shop_image');
        }

        return redirect()
            ->route('admin.shops.index')
            ->with('message', 'Shop updated successfully.');
    }

    public function destroy(Shop $shop)
    {
        abort_if(Gate::denies('shop_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shop->delete();

        return back()->with('message', 'Shop deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('shop_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Shop::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function removeMedia(Shop $shop, Media $media)
    {
        abort_if(Gate::denies('shop_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($media->model_type !== Shop::class || (int) $media->model_id !== (int) $shop->id) {
            abort(404);
        }

        $media->delete();

        return back()->with('message', 'Image removed successfully.');
    }
}