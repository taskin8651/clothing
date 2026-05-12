<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeliveryBoyRequest;
use App\Http\Requests\UpdateDeliveryBoyRequest;
use App\Models\DeliveryBoy;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class DeliveryBoysController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('delivery_boy_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $deliveryBoys = DeliveryBoy::with(['media'])->latest()->get();

        return view('admin.deliveryBoys.index', compact('deliveryBoys'));
    }

    public function create()
    {
        abort_if(Gate::denies('delivery_boy_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.deliveryBoys.create');
    }

    public function store(StoreDeliveryBoyRequest $request)
    {
        $data = $request->except(['profile_image', 'id_proof_image']);
        $data['status'] = $request->has('status') ? 1 : 0;

        $deliveryBoy = DeliveryBoy::create($data);

        if ($request->hasFile('profile_image')) {
            $deliveryBoy
                ->addMediaFromRequest('profile_image')
                ->toMediaCollection('profile_image');
        }

        if ($request->hasFile('id_proof_image')) {
            $deliveryBoy
                ->addMediaFromRequest('id_proof_image')
                ->toMediaCollection('id_proof_image');
        }

        return redirect()
            ->route('admin.delivery-boys.index')
            ->with('message', 'Delivery boy created successfully.');
    }

    public function show(DeliveryBoy $deliveryBoy)
    {
        abort_if(Gate::denies('delivery_boy_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $deliveryBoy->load(['media']);

        return view('admin.deliveryBoys.show', compact('deliveryBoy'));
    }

    public function edit(DeliveryBoy $deliveryBoy)
    {
        abort_if(Gate::denies('delivery_boy_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $deliveryBoy->load(['media']);

        return view('admin.deliveryBoys.edit', compact('deliveryBoy'));
    }

    public function update(UpdateDeliveryBoyRequest $request, DeliveryBoy $deliveryBoy)
    {
        $data = $request->except([
            'profile_image',
            'id_proof_image',
            'remove_profile_image',
            'remove_id_proof_image',
        ]);

        if (! $request->filled('password')) {
            unset($data['password']);
        }

        $data['status'] = $request->has('status') ? 1 : 0;

        $deliveryBoy->update($data);

        if ($request->remove_profile_image == 1) {
            $deliveryBoy->clearMediaCollection('profile_image');
        }

        if ($request->remove_id_proof_image == 1) {
            $deliveryBoy->clearMediaCollection('id_proof_image');
        }

        if ($request->hasFile('profile_image')) {
            $deliveryBoy->clearMediaCollection('profile_image');

            $deliveryBoy
                ->addMediaFromRequest('profile_image')
                ->toMediaCollection('profile_image');
        }

        if ($request->hasFile('id_proof_image')) {
            $deliveryBoy->clearMediaCollection('id_proof_image');

            $deliveryBoy
                ->addMediaFromRequest('id_proof_image')
                ->toMediaCollection('id_proof_image');
        }

        return redirect()
            ->route('admin.delivery-boys.index')
            ->with('message', 'Delivery boy updated successfully.');
    }

    public function destroy(DeliveryBoy $deliveryBoy)
    {
        abort_if(Gate::denies('delivery_boy_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $deliveryBoy->delete();

        return back()->with('message', 'Delivery boy deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('delivery_boy_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DeliveryBoy::whereIn('id', request('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function removeMedia(DeliveryBoy $deliveryBoy, Media $media)
    {
        abort_if(Gate::denies('delivery_boy_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($media->model_type !== DeliveryBoy::class || (int) $media->model_id !== (int) $deliveryBoy->id) {
            abort(404);
        }

        $media->delete();

        return back()->with('message', 'Document removed successfully.');
    }
}
