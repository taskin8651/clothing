<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryZone;
use App\Models\Shop;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class DeliveryZonesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('delivery_zone_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $deliveryZones = DeliveryZone::with('shop')->latest()->get();

        return view('admin.deliveryZones.index', compact('deliveryZones'));
    }

    public function create()
    {
        abort_if(Gate::denies('delivery_zone_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shops = $this->shops();

        return view('admin.deliveryZones.create', compact('shops'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('delivery_zone_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $this->validated($request);

        DeliveryZone::create($data);

        return redirect()
            ->route('admin.delivery-zones.index')
            ->with('message', 'Delivery zone created successfully.');
    }

    public function show(DeliveryZone $deliveryZone)
    {
        abort_if(Gate::denies('delivery_zone_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $deliveryZone->load('shop');

        return view('admin.deliveryZones.show', compact('deliveryZone'));
    }

    public function edit(DeliveryZone $deliveryZone)
    {
        abort_if(Gate::denies('delivery_zone_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shops = $this->shops();

        return view('admin.deliveryZones.edit', compact('deliveryZone', 'shops'));
    }

    public function update(Request $request, DeliveryZone $deliveryZone)
    {
        abort_if(Gate::denies('delivery_zone_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $this->validated($request, $deliveryZone);

        $deliveryZone->update($data);

        return redirect()
            ->route('admin.delivery-zones.index')
            ->with('message', 'Delivery zone updated successfully.');
    }

    public function destroy(DeliveryZone $deliveryZone)
    {
        abort_if(Gate::denies('delivery_zone_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $deliveryZone->delete();

        return back()->with('message', 'Delivery zone deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('delivery_zone_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DeliveryZone::whereIn('id', request('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function validated(Request $request, ?DeliveryZone $deliveryZone = null): array
    {
        $request->validate([
            'shop_id' => ['nullable', 'exists:shops,id'],
            'city' => ['required', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
            'pincode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('delivery_zones')
                    ->where(fn ($query) => $query->where('shop_id', $request->shop_id))
                    ->ignore($deliveryZone),
            ],
            'min_delivery_minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'max_delivery_minutes' => ['required', 'integer', 'gte:min_delivery_minutes', 'max:1440'],
            'delivery_charge' => ['nullable', 'numeric', 'min:0'],
            'free_delivery_min_amount' => ['nullable', 'numeric', 'min:0'],
            'trial_wait_minutes' => ['nullable', 'integer', 'min:0', 'max:240'],
            'sort_order' => ['nullable', 'integer'],
            'try_first_enabled' => ['nullable', 'boolean'],
            'cod_enabled' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
        ]);

        return [
            'shop_id' => $request->shop_id,
            'city' => $request->city,
            'area' => $request->area,
            'pincode' => $request->pincode,
            'min_delivery_minutes' => $request->min_delivery_minutes,
            'max_delivery_minutes' => $request->max_delivery_minutes,
            'delivery_charge' => $request->delivery_charge ?? 0,
            'free_delivery_min_amount' => $request->free_delivery_min_amount,
            'trial_wait_minutes' => $request->trial_wait_minutes ?? 30,
            'sort_order' => $request->sort_order ?? 0,
            'try_first_enabled' => $request->has('try_first_enabled') ? 1 : 0,
            'cod_enabled' => $request->has('cod_enabled') ? 1 : 0,
            'status' => $request->has('status') ? 1 : 0,
        ];
    }

    private function shops()
    {
        return Shop::where('status', 1)
            ->orderBy('shop_name')
            ->pluck('shop_name', 'id')
            ->prepend('All Shops', '');
    }
}
