<?php

Route::get('/', [\App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('frontend.home');
Route::get('/shops', [\App\Http\Controllers\Frontend\ShopsController::class, 'index'])->name('frontend.shops.index');
Route::get('/shops/{shop}', [\App\Http\Controllers\Frontend\ShopsController::class, 'show'])->name('frontend.shops.show');
Route::get('/categories', [\App\Http\Controllers\Frontend\CategoriesController::class, 'index'])->name('frontend.categories.index');
Route::get('/categories/{category:slug}', [\App\Http\Controllers\Frontend\CategoriesController::class, 'show'])->name('frontend.categories.show');
Route::get('/cart', [\App\Http\Controllers\Frontend\CartController::class, 'index'])->name('frontend.cart.index');
Route::post('/cart/{product}/add', [\App\Http\Controllers\Frontend\CartController::class, 'add'])->name('frontend.cart.add');
Route::patch('/cart/{product}', [\App\Http\Controllers\Frontend\CartController::class, 'update'])->name('frontend.cart.update');
Route::delete('/cart/{product}', [\App\Http\Controllers\Frontend\CartController::class, 'remove'])->name('frontend.cart.remove');
Route::get('/checkout', [\App\Http\Controllers\Frontend\CheckoutController::class, 'index'])->name('frontend.checkout.index');
Route::post('/checkout', [\App\Http\Controllers\Frontend\CheckoutController::class, 'store'])->name('frontend.checkout.store');
Route::get('/orders', [\App\Http\Controllers\Frontend\CustomerOrdersController::class, 'index'])->name('frontend.orders.index');
Route::get('/orders/{order}', [\App\Http\Controllers\Frontend\CustomerOrdersController::class, 'show'])->name('frontend.orders.show');
Route::post('/orders/{order}/items/{orderItem}/return', [\App\Http\Controllers\Frontend\CustomerOrdersController::class, 'storeReturn'])->name('frontend.orders.returns.store');
Route::get('/orders/{order}/success', [\App\Http\Controllers\Frontend\CheckoutController::class, 'success'])->name('frontend.orders.success');
Route::get('/products/{product:slug}', [\App\Http\Controllers\Frontend\ProductsController::class, 'show'])->name('frontend.products.show');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});
 
Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

      // Products
    Route::delete('products/destroy', 'ProductsController@massDestroy')->name('products.massDestroy');
    Route::delete('products/{product}/media/{media}', 'ProductsController@removeMedia')->name('products.removeMedia');
    Route::resource('products', 'ProductsController');

    // Shops
Route::delete('shops/destroy', 'ShopsController@massDestroy')->name('shops.massDestroy');
Route::delete('shops/{shop}/media/{media}', 'ShopsController@removeMedia')->name('shops.removeMedia');
Route::resource('shops', 'ShopsController');

// Categories
Route::delete('categories/destroy', 'CategoriesController@massDestroy')->name('categories.massDestroy');
Route::delete('categories/{category}/media/{media}', 'CategoriesController@removeMedia')->name('categories.removeMedia');
Route::resource('categories', 'CategoriesController');

// Product Variants
Route::delete('product-variants/destroy', 'ProductVariantsController@massDestroy')->name('product-variants.massDestroy');
Route::resource('product-variants', 'ProductVariantsController');

// Customers
Route::delete('customers/destroy', 'CustomersController@massDestroy')->name('customers.massDestroy');
Route::resource('customers', 'CustomersController');

// Customer Addresses
Route::delete('customer-addresses/destroy', 'CustomerAddressesController@massDestroy')->name('customer-addresses.massDestroy');
Route::resource('customer-addresses', 'CustomerAddressesController');

// Delivery Boys
Route::delete('delivery-boys/destroy', 'DeliveryBoysController@massDestroy')->name('delivery-boys.massDestroy');
Route::delete('delivery-boys/{deliveryBoy}/media/{media}', 'DeliveryBoysController@removeMedia')->name('delivery-boys.removeMedia');
Route::resource('delivery-boys', 'DeliveryBoysController')->parameters(['delivery-boys' => 'deliveryBoy']);

// Orders
Route::delete('orders/destroy', 'OrdersController@massDestroy')->name('orders.massDestroy');
Route::post('orders/{order}/update-status', 'OrdersController@updateStatus')->name('orders.updateStatus');
Route::post('orders/{order}/assign-delivery-boy', 'OrdersController@assignDeliveryBoy')->name('orders.assignDeliveryBoy');
Route::resource('orders', 'OrdersController');

// Delivery Trackings
Route::delete('delivery-trackings/destroy', 'DeliveryTrackingsController@massDestroy')->name('delivery-trackings.massDestroy');
Route::post('delivery-trackings/{deliveryTracking}/update-status', 'DeliveryTrackingsController@updateStatus')->name('delivery-trackings.updateStatus');
Route::post('delivery-trackings/{deliveryTracking}/assign-delivery-boy', 'DeliveryTrackingsController@assignDeliveryBoy')->name('delivery-trackings.assignDeliveryBoy');
Route::post('delivery-trackings/{deliveryTracking}/mark-cod-collected', 'DeliveryTrackingsController@markCodCollected')->name('delivery-trackings.markCodCollected');
Route::resource('delivery-trackings', 'DeliveryTrackingsController')->parameters(['delivery-trackings' => 'deliveryTracking']);

// Delivery Zones
Route::delete('delivery-zones/destroy', 'DeliveryZonesController@massDestroy')->name('delivery-zones.massDestroy');
Route::resource('delivery-zones', 'DeliveryZonesController')->parameters(['delivery-zones' => 'deliveryZone']);

// Homepage Sections
Route::delete('homepage-sections/destroy', 'HomepageSectionsController@massDestroy')->name('homepage-sections.massDestroy');
Route::resource('homepage-sections', 'HomepageSectionsController')->parameters(['homepage-sections' => 'homepageSection']);

// Payments
Route::delete('payments/destroy', 'PaymentsController@massDestroy')->name('payments.massDestroy');
Route::post('payments/{payment}/update-status', 'PaymentsController@updateStatus')->name('payments.updateStatus');
Route::resource('payments', 'PaymentsController')->except(['create', 'store']);

// Invoices
Route::delete('invoices/destroy', 'InvoicesController@massDestroy')->name('invoices.massDestroy');
Route::post('invoices/generate-from-order/{order}', 'InvoicesController@generateFromOrder')->name('invoices.generateFromOrder');
Route::get('invoices/{invoice}/print', 'InvoicesController@print')->name('invoices.print');
Route::resource('invoices', 'InvoicesController');

// Receipts
Route::delete('receipts/destroy', 'ReceiptsController@massDestroy')->name('receipts.massDestroy');
Route::post('receipts/generate-from-payment/{payment}', 'ReceiptsController@generateFromPayment')->name('receipts.generateFromPayment');
Route::get('receipts/{receipt}/print', 'ReceiptsController@print')->name('receipts.print');
Route::resource('receipts', 'ReceiptsController');

// Return Requests
Route::delete('return-requests/destroy', 'ReturnRequestsController@massDestroy')->name('return-requests.massDestroy');
Route::post('return-requests/{returnRequest}/update-status', 'ReturnRequestsController@updateStatus')->name('return-requests.updateStatus');
Route::resource('return-requests', 'ReturnRequestsController')->parameters(['return-requests' => 'returnRequest']);

// Settings
Route::get('settings', 'SettingsController@index')->name('settings.index');
Route::post('settings', 'SettingsController@update')->name('settings.update');

// Notifications
Route::delete('notifications/destroy', 'NotificationsController@massDestroy')->name('notifications.massDestroy');
Route::post('notifications/mark-all-read', 'NotificationsController@markAllRead')->name('notifications.markAllRead');
Route::post('notifications/{notification}/mark-read', 'NotificationsController@markRead')->name('notifications.markRead');
Route::resource('notifications', 'NotificationsController')->only(['index', 'show', 'destroy']);

    
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
