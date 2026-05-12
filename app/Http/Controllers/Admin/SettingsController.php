<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingsRequest;
use App\Models\SystemSetting;
use Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $setting = SystemSetting::current();

        return view('admin.settings.index', compact('setting'));
    }

    public function update(UpdateSettingsRequest $request)
    {
        abort_if(Gate::denies('setting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $setting = SystemSetting::current();

        $data = $request->except([
            'site_logo',
            'site_favicon',
            'invoice_logo',
            'remove_site_logo',
            'remove_site_favicon',
            'remove_invoice_logo',
        ]);

        $data['default_tax_percent'] = $request->default_tax_percent ?? 0;
        $data['default_delivery_charge'] = $request->default_delivery_charge ?? 0;
        $data['free_delivery_min_amount'] = $request->free_delivery_min_amount ?? 0;
        $data['return_window_days'] = $request->return_window_days ?? 0;

        $data['allow_return_if_try_cloth'] = $request->has('allow_return_if_try_cloth') ? 1 : 0;
        $data['cod_enabled'] = $request->has('cod_enabled') ? 1 : 0;
        $data['online_payment_enabled'] = $request->has('online_payment_enabled') ? 1 : 0;

        $setting->update($data);

        $this->handleFileRemove($request, $setting, 'site_logo');
        $this->handleFileRemove($request, $setting, 'site_favicon');
        $this->handleFileRemove($request, $setting, 'invoice_logo');

        $this->handleFileUpload($request, $setting, 'site_logo');
        $this->handleFileUpload($request, $setting, 'site_favicon');
        $this->handleFileUpload($request, $setting, 'invoice_logo');

        return redirect()
            ->route('admin.settings.index')
            ->with('message', 'Settings updated successfully.');
    }

    private function handleFileUpload($request, SystemSetting $setting, string $field): void
    {
        if (! $request->hasFile($field)) {
            return;
        }

        if ($setting->{$field} && Storage::disk('public')->exists($setting->{$field})) {
            Storage::disk('public')->delete($setting->{$field});
        }

        $file = $request->file($field);
        $filename = $field . '_' . time() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('settings', $filename, 'public');

        $setting->update([
            $field => $path,
        ]);
    }

    private function handleFileRemove($request, SystemSetting $setting, string $field): void
    {
        $removeField = 'remove_' . $field;

        if (! $request->has($removeField)) {
            return;
        }

        if ($setting->{$field} && Storage::disk('public')->exists($setting->{$field})) {
            Storage::disk('public')->delete($setting->{$field});
        }

        $setting->update([
            $field => null,
        ]);
    }
}