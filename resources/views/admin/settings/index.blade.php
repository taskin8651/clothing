@extends('layouts.admin')

@section('page-title', 'System Settings')

@section('content')

@php
    $get = function ($key, $default = '') use ($setting) {
        return old($key, $setting->{$key} ?? $default);
    };

    $fileUrl = function ($key) use ($setting) {
        return !empty($setting->{$key}) ? asset('storage/' . $setting->{$key}) : null;
    };
@endphp

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">System Settings</h2>

        <p class="admin-page-subtitle">
            Manage company, branding, invoice, order, delivery, payment and SEO settings
        </p>
    </div>
</div>

<form method="POST"
      action="{{ route('admin.settings.update') }}"
      enctype="multipart/form-data">
    @csrf

    <div class="admin-form-grid">

        {{-- COMPANY DETAILS --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-building"></i>
                </div>

                <div>
                    <p class="form-card-title">Company Details</p>
                    <p class="form-card-subtitle">Marketplace name and contact details</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="company_name">Company Name</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-building icon"></i>

                        <input type="text"
                               name="company_name"
                               id="company_name"
                               value="{{ $get('company_name') }}"
                               placeholder="Enter company name"
                               class="field-input {{ $errors->has('company_name') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('company_name'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('company_name') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="site_title">Site Title</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-heading icon"></i>

                        <input type="text"
                               name="site_title"
                               id="site_title"
                               value="{{ $get('site_title') }}"
                               placeholder="Marketplace site title"
                               class="field-input {{ $errors->has('site_title') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('site_title'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('site_title') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="support_email">Support Email</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-envelope icon"></i>

                        <input type="email"
                               name="support_email"
                               id="support_email"
                               value="{{ $get('support_email') }}"
                               placeholder="support@example.com"
                               class="field-input {{ $errors->has('support_email') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('support_email'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('support_email') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="support_phone">Support Phone</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-phone icon"></i>

                        <input type="text"
                               name="support_phone"
                               id="support_phone"
                               value="{{ $get('support_phone') }}"
                               placeholder="+91 99999 99999"
                               class="field-input {{ $errors->has('support_phone') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('support_phone'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('support_phone') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="whatsapp_number">WhatsApp Number</label>

                    <div class="input-icon-wrap">
                        <i class="fab fa-whatsapp icon"></i>

                        <input type="text"
                               name="whatsapp_number"
                               id="whatsapp_number"
                               value="{{ $get('whatsapp_number') }}"
                               placeholder="+91 99999 99999"
                               class="field-input {{ $errors->has('whatsapp_number') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('whatsapp_number'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('whatsapp_number') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="gst_number">GST Number</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-file-invoice icon"></i>

                        <input type="text"
                               name="gst_number"
                               id="gst_number"
                               value="{{ $get('gst_number') }}"
                               placeholder="GSTIN"
                               class="field-input {{ $errors->has('gst_number') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('gst_number'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('gst_number') }}
                        </p>
                    @endif
                </div>

            </div>
        </div>

        {{-- ADDRESS DETAILS --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>

                <div>
                    <p class="form-card-title">Address Details</p>
                    <p class="form-card-subtitle">Business address information</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="address">Address</label>

                    <textarea name="address"
                              id="address"
                              rows="4"
                              placeholder="Full business address"
                              class="field-input {{ $errors->has('address') ? 'error' : '' }}">{{ $get('address') }}</textarea>

                    @if($errors->has('address'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('address') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="city">City</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-city icon"></i>

                        <input type="text"
                               name="city"
                               id="city"
                               value="{{ $get('city') }}"
                               placeholder="City"
                               class="field-input {{ $errors->has('city') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('city'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('city') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="state">State</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-map icon"></i>

                        <input type="text"
                               name="state"
                               id="state"
                               value="{{ $get('state') }}"
                               placeholder="State"
                               class="field-input {{ $errors->has('state') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('state'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('state') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="country">Country</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-globe icon"></i>

                        <input type="text"
                               name="country"
                               id="country"
                               value="{{ $get('country', 'India') }}"
                               placeholder="Country"
                               class="field-input {{ $errors->has('country') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('country'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('country') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="pincode">Pincode</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-map-pin icon"></i>

                        <input type="text"
                               name="pincode"
                               id="pincode"
                               value="{{ $get('pincode') }}"
                               placeholder="800001"
                               class="field-input {{ $errors->has('pincode') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('pincode'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('pincode') }}
                        </p>
                    @endif
                </div>

            </div>
        </div>

        {{-- BRANDING --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-image"></i>
                </div>

                <div>
                    <p class="form-card-title">Branding</p>
                    <p class="form-card-subtitle">Logo, favicon and invoice logo</p>
                </div>
            </div>

            <div class="form-card-body">

                {{-- SITE LOGO --}}
                <div class="field-group">
                    <label class="field-label" for="site_logo">Site Logo</label>

                    @if($fileUrl('site_logo'))
                        <div class="setting-image-preview">
                            <img src="{{ $fileUrl('site_logo') }}" alt="Site Logo">

                            <label class="role-checkbox-item">
                                <input type="checkbox"
                                       name="remove_site_logo"
                                       value="1"
                                       class="role-checkbox">
                                <div class="check-icon"></div>
                                <span class="checkbox-text">Remove Logo</span>
                            </label>
                        </div>
                    @endif

                    <input type="file"
                           name="site_logo"
                           id="site_logo"
                           accept="image/*"
                           onchange="previewSettingImage(this, 'siteLogoPreview')"
                           class="field-input {{ $errors->has('site_logo') ? 'error' : '' }}">

                    @if($errors->has('site_logo'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('site_logo') }}
                        </p>
                    @else
                        <p class="field-hint">JPG, PNG, WEBP, SVG. Max 4MB.</p>
                    @endif

                    <div id="siteLogoPreview" class="setting-live-preview"></div>
                </div>

                {{-- FAVICON --}}
                <div class="field-group">
                    <label class="field-label" for="site_favicon">Site Favicon</label>

                    @if($fileUrl('site_favicon'))
                        <div class="setting-image-preview">
                            <img src="{{ $fileUrl('site_favicon') }}" alt="Site Favicon">

                            <label class="role-checkbox-item">
                                <input type="checkbox"
                                       name="remove_site_favicon"
                                       value="1"
                                       class="role-checkbox">
                                <div class="check-icon"></div>
                                <span class="checkbox-text">Remove Favicon</span>
                            </label>
                        </div>
                    @endif

                    <input type="file"
                           name="site_favicon"
                           id="site_favicon"
                           accept="image/*,.ico"
                           onchange="previewSettingImage(this, 'siteFaviconPreview')"
                           class="field-input {{ $errors->has('site_favicon') ? 'error' : '' }}">

                    @if($errors->has('site_favicon'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('site_favicon') }}
                        </p>
                    @else
                        <p class="field-hint">JPG, PNG, WEBP, ICO. Max 2MB.</p>
                    @endif

                    <div id="siteFaviconPreview" class="setting-live-preview"></div>
                </div>

                {{-- INVOICE LOGO --}}
                <div class="field-group">
                    <label class="field-label" for="invoice_logo">Invoice Logo</label>

                    @if($fileUrl('invoice_logo'))
                        <div class="setting-image-preview">
                            <img src="{{ $fileUrl('invoice_logo') }}" alt="Invoice Logo">

                            <label class="role-checkbox-item">
                                <input type="checkbox"
                                       name="remove_invoice_logo"
                                       value="1"
                                       class="role-checkbox">
                                <div class="check-icon"></div>
                                <span class="checkbox-text">Remove Invoice Logo</span>
                            </label>
                        </div>
                    @endif

                    <input type="file"
                           name="invoice_logo"
                           id="invoice_logo"
                           accept="image/*"
                           onchange="previewSettingImage(this, 'invoiceLogoPreview')"
                           class="field-input {{ $errors->has('invoice_logo') ? 'error' : '' }}">

                    @if($errors->has('invoice_logo'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('invoice_logo') }}
                        </p>
                    @else
                        <p class="field-hint">Invoice print page me use hoga.</p>
                    @endif

                    <div id="invoiceLogoPreview" class="setting-live-preview"></div>
                </div>

            </div>
        </div>

        {{-- NUMBER PREFIX SETTINGS --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-hashtag"></i>
                </div>

                <div>
                    <p class="form-card-title">Number Prefix Settings</p>
                    <p class="form-card-subtitle">Order, invoice, receipt, return and tracking prefixes</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="order_prefix">Order Prefix</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-shopping-bag icon"></i>

                        <input type="text"
                               name="order_prefix"
                               id="order_prefix"
                               value="{{ $get('order_prefix', 'ORD') }}"
                               placeholder="ORD"
                               class="field-input {{ $errors->has('order_prefix') ? 'error' : '' }}">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="invoice_prefix">Invoice Prefix</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-file-invoice icon"></i>

                        <input type="text"
                               name="invoice_prefix"
                               id="invoice_prefix"
                               value="{{ $get('invoice_prefix', 'INV') }}"
                               placeholder="INV"
                               class="field-input {{ $errors->has('invoice_prefix') ? 'error' : '' }}">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="receipt_prefix">Receipt Prefix</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-receipt icon"></i>

                        <input type="text"
                               name="receipt_prefix"
                               id="receipt_prefix"
                               value="{{ $get('receipt_prefix', 'RCP') }}"
                               placeholder="RCP"
                               class="field-input {{ $errors->has('receipt_prefix') ? 'error' : '' }}">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="return_prefix">Return Prefix</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-undo icon"></i>

                        <input type="text"
                               name="return_prefix"
                               id="return_prefix"
                               value="{{ $get('return_prefix', 'RET') }}"
                               placeholder="RET"
                               class="field-input {{ $errors->has('return_prefix') ? 'error' : '' }}">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="tracking_prefix">Tracking Prefix</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-truck icon"></i>

                        <input type="text"
                               name="tracking_prefix"
                               id="tracking_prefix"
                               value="{{ $get('tracking_prefix', 'TRK') }}"
                               placeholder="TRK"
                               class="field-input {{ $errors->has('tracking_prefix') ? 'error' : '' }}">
                    </div>
                </div>

            </div>
        </div>

        {{-- INVOICE SETTINGS --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>

                <div>
                    <p class="form-card-title">Invoice Settings</p>
                    <p class="form-card-subtitle">Invoice terms and footer note</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="invoice_terms">Invoice Terms</label>

                    <textarea name="invoice_terms"
                              id="invoice_terms"
                              rows="5"
                              placeholder="Invoice terms and conditions"
                              class="field-input {{ $errors->has('invoice_terms') ? 'error' : '' }}">{{ $get('invoice_terms') }}</textarea>

                    @if($errors->has('invoice_terms'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('invoice_terms') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="invoice_footer_note">Invoice Footer Note</label>

                    <textarea name="invoice_footer_note"
                              id="invoice_footer_note"
                              rows="4"
                              placeholder="Thank you note / footer note"
                              class="field-input {{ $errors->has('invoice_footer_note') ? 'error' : '' }}">{{ $get('invoice_footer_note') }}</textarea>

                    @if($errors->has('invoice_footer_note'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('invoice_footer_note') }}
                        </p>
                    @endif
                </div>

            </div>
        </div>

        {{-- ORDER / DELIVERY SETTINGS --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>

                <div>
                    <p class="form-card-title">Order / Delivery Settings</p>
                    <p class="form-card-subtitle">Default charges, return window and payment options</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="default_tax_percent">Default Tax Percent</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-percent icon"></i>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="default_tax_percent"
                               id="default_tax_percent"
                               value="{{ $get('default_tax_percent', 0) }}"
                               class="field-input {{ $errors->has('default_tax_percent') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('default_tax_percent'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('default_tax_percent') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="default_delivery_charge">Default Delivery Charge</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-rupee-sign icon"></i>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="default_delivery_charge"
                               id="default_delivery_charge"
                               value="{{ $get('default_delivery_charge', 0) }}"
                               class="field-input {{ $errors->has('default_delivery_charge') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('default_delivery_charge'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('default_delivery_charge') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="free_delivery_min_amount">Free Delivery Min Amount</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-truck icon"></i>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="free_delivery_min_amount"
                               id="free_delivery_min_amount"
                               value="{{ $get('free_delivery_min_amount', 0) }}"
                               class="field-input {{ $errors->has('free_delivery_min_amount') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('free_delivery_min_amount'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('free_delivery_min_amount') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="return_window_days">Return Window Days</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-undo icon"></i>

                        <input type="number"
                               min="0"
                               name="return_window_days"
                               id="return_window_days"
                               value="{{ $get('return_window_days', 7) }}"
                               class="field-input {{ $errors->has('return_window_days') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('return_window_days'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('return_window_days') }}
                        </p>
                    @endif
                </div>

                <div class="checkbox-grid">

                    <label class="role-checkbox-item {{ $get('allow_return_if_try_cloth', 0) ? 'checked' : '' }}">
                        <input type="checkbox"
                               name="allow_return_if_try_cloth"
                               value="1"
                               class="role-checkbox"
                               {{ $get('allow_return_if_try_cloth', 0) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Allow Return If Try Cloth</span>
                    </label>

                    <label class="role-checkbox-item {{ $get('cod_enabled', 1) ? 'checked' : '' }}">
                        <input type="checkbox"
                               name="cod_enabled"
                               value="1"
                               class="role-checkbox"
                               {{ $get('cod_enabled', 1) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">COD Enabled</span>
                    </label>

                    <label class="role-checkbox-item {{ $get('online_payment_enabled', 1) ? 'checked' : '' }}">
                        <input type="checkbox"
                               name="online_payment_enabled"
                               value="1"
                               class="role-checkbox"
                               {{ $get('online_payment_enabled', 1) ? 'checked' : '' }}>
                        <div class="check-icon"></div>
                        <span class="checkbox-text">Online Payment Enabled</span>
                    </label>

                </div>

                <div class="form-info-box">
                    <p>
                        <i class="fas fa-info-circle"></i>
                        SOW ke according Try Cloth selected hone par product non-returnable rahega. Is setting ko default false rakhna best hai.
                    </p>
                </div>

            </div>
        </div>

        {{-- PAYMENT GATEWAY --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-credit-card"></i>
                </div>

                <div>
                    <p class="form-card-title">Payment Gateway Settings</p>
                    <p class="form-card-subtitle">Gateway name, key and secret</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="payment_gateway_name">Gateway Name</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-credit-card icon"></i>

                        <input type="text"
                               name="payment_gateway_name"
                               id="payment_gateway_name"
                               value="{{ $get('payment_gateway_name') }}"
                               placeholder="Razorpay / Cashfree / PayU"
                               class="field-input {{ $errors->has('payment_gateway_name') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('payment_gateway_name'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('payment_gateway_name') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="payment_gateway_key">Gateway Key</label>

                    <div class="input-icon-wrap">
                        <i class="fas fa-key icon"></i>

                        <input type="text"
                               name="payment_gateway_key"
                               id="payment_gateway_key"
                               value="{{ $get('payment_gateway_key') }}"
                               placeholder="Gateway public/key id"
                               class="field-input {{ $errors->has('payment_gateway_key') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('payment_gateway_key'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('payment_gateway_key') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="payment_gateway_secret">Gateway Secret</label>

                    <div class="input-icon-wrap has-eye">
                        <i class="fas fa-lock icon"></i>

                        <input type="password"
                               name="payment_gateway_secret"
                               id="payment_gateway_secret"
                               value="{{ $get('payment_gateway_secret') }}"
                               placeholder="Gateway secret"
                               class="field-input {{ $errors->has('payment_gateway_secret') ? 'error' : '' }}">

                        <button type="button"
                                class="eye-toggle"
                                onclick="togglePassword('payment_gateway_secret', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    @if($errors->has('payment_gateway_secret'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('payment_gateway_secret') }}
                        </p>
                    @endif
                </div>

            </div>
        </div>

        {{-- SOCIAL LINKS --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-share-alt"></i>
                </div>

                <div>
                    <p class="form-card-title">Social Links</p>
                    <p class="form-card-subtitle">Social media profile URLs</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="facebook_url">Facebook URL</label>

                    <div class="input-icon-wrap">
                        <i class="fab fa-facebook icon"></i>

                        <input type="url"
                               name="facebook_url"
                               id="facebook_url"
                               value="{{ $get('facebook_url') }}"
                               class="field-input {{ $errors->has('facebook_url') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('facebook_url'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('facebook_url') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="instagram_url">Instagram URL</label>

                    <div class="input-icon-wrap">
                        <i class="fab fa-instagram icon"></i>

                        <input type="url"
                               name="instagram_url"
                               id="instagram_url"
                               value="{{ $get('instagram_url') }}"
                               class="field-input {{ $errors->has('instagram_url') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('instagram_url'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('instagram_url') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="youtube_url">YouTube URL</label>

                    <div class="input-icon-wrap">
                        <i class="fab fa-youtube icon"></i>

                        <input type="url"
                               name="youtube_url"
                               id="youtube_url"
                               value="{{ $get('youtube_url') }}"
                               class="field-input {{ $errors->has('youtube_url') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('youtube_url'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('youtube_url') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="linkedin_url">LinkedIn URL</label>

                    <div class="input-icon-wrap">
                        <i class="fab fa-linkedin icon"></i>

                        <input type="url"
                               name="linkedin_url"
                               id="linkedin_url"
                               value="{{ $get('linkedin_url') }}"
                               class="field-input {{ $errors->has('linkedin_url') ? 'error' : '' }}">
                    </div>

                    @if($errors->has('linkedin_url'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('linkedin_url') }}
                        </p>
                    @endif
                </div>

            </div>
        </div>

        {{-- SEO --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-search"></i>
                </div>

                <div>
                    <p class="form-card-title">Default SEO Settings</p>
                    <p class="form-card-subtitle">Future frontend ke default meta details</p>
                </div>
            </div>

            <div class="form-card-body">

                <div class="field-group">
                    <label class="field-label" for="default_meta_title">Default Meta Title</label>

                    <input type="text"
                           name="default_meta_title"
                           id="default_meta_title"
                           value="{{ $get('default_meta_title') }}"
                           placeholder="Default SEO title"
                           class="field-input {{ $errors->has('default_meta_title') ? 'error' : '' }}">

                    @if($errors->has('default_meta_title'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('default_meta_title') }}
                        </p>
                    @endif
                </div>

                <div class="field-group">
                    <label class="field-label" for="default_meta_description">Default Meta Description</label>

                    <textarea name="default_meta_description"
                              id="default_meta_description"
                              rows="4"
                              placeholder="Default SEO description"
                              class="field-input {{ $errors->has('default_meta_description') ? 'error' : '' }}">{{ $get('default_meta_description') }}</textarea>

                    @if($errors->has('default_meta_description'))
                        <p class="field-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $errors->first('default_meta_description') }}
                        </p>
                    @endif
                </div>

            </div>
        </div>

    </div>

    <div class="form-actions">
        @can('setting_edit')
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i>
                Save Settings
            </button>
        @endcan

        <a href="{{ route('admin.home') }}" class="btn-ghost">
            Cancel
        </a>
    </div>

</form>

@endsection

@section('scripts')
@parent

<script>
function previewSettingImage(input, targetId) {
    const target = document.getElementById(targetId);
    target.innerHTML = '';

    if (!input.files || !input.files[0]) {
        return;
    }

    const file = input.files[0];

    if (!file.type.startsWith('image/')) {
        target.innerHTML = '<p class="field-error"><i class="fas fa-exclamation-circle"></i> Please select a valid image.</p>';
        input.value = '';
        return;
    }

    const reader = new FileReader();

    reader.onload = function(e) {
        target.innerHTML = `
            <div class="setting-image-preview mt-2">
                <img src="${e.target.result}" alt="Preview">
            </div>
        `;
    };

    reader.readAsDataURL(file);
}

function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (!input) return;

    if (input.type === 'password') {
        input.type = 'text';

        if (icon) {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    } else {
        input.type = 'password';

        if (icon) {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
}

document.querySelectorAll('.role-checkbox-item input[type="checkbox"]').forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        const label = this.closest('.role-checkbox-item');

        if (this.checked) {
            label.classList.add('checked');
        } else {
            label.classList.remove('checked');
        }
    });
});
</script>

<style>
.setting-image-preview{
    display:flex;
    align-items:center;
    gap:14px;
    flex-wrap:wrap;
    margin-bottom:12px;
}

.setting-image-preview img{
    width:150px;
    height:95px;
    object-fit:contain;
    border-radius:16px;
    border:1px solid #E2E8F0;
    background:#F8FAFC;
    padding:10px;
}

.setting-live-preview{
    margin-top:12px;
}

.mt-2{
    margin-top:8px;
}
</style>

@endsection