@extends('layouts.admin')

@section('page-title', 'Payments')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Payments</h2>
        <p class="admin-page-subtitle">Manage COD and online payment transactions</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card"><p class="stat-label">Total Payments</p><p class="stat-value">{{ $payments->count() }}</p></div>
    <div class="stat-card"><p class="stat-label">Paid Amount</p><p class="stat-value">Rs. {{ number_format($payments->where('status', 'paid')->sum('amount'), 2) }}</p></div>
    <div class="stat-card"><p class="stat-label">Pending Payments</p><p class="stat-value">{{ $payments->where('status', 'pending')->count() }}</p></div>
    <div class="stat-card"><p class="stat-label">COD Payments</p><p class="stat-value">{{ $payments->where('payment_method', 'cod')->count() }}</p></div>
    <div class="stat-card"><p class="stat-label">Online Payments</p><p class="stat-value">{{ $payments->where('payment_method', 'online')->count() }}</p></div>
    <div class="stat-card"><p class="stat-label">Refunded Payments</p><p class="stat-value">{{ $payments->where('status', 'refunded')->count() }}</p></div>
</div>

<div class="page-card">
    <div class="page-card-header">
        <p class="page-card-title">All Payments</p>
        <span class="page-card-note"><i class="fas fa-info-circle"></i> Payment status updates sync with related orders</span>
    </div>

    <div class="page-card-table">
        <table class="min-w-full datatable datatable-Payment">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>ID</th>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Method</th>
                    <th>Gateway</th>
                    <th>Transaction ID</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Paid At</th>
                    <th>Date</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                    <tr data-entry-id="{{ $payment->id }}">
                        <td></td>
                        <td><span class="id-text">#{{ $payment->id }}</span></td>
                        <td><span class="code-pill">{{ optional($payment->order)->order_number ?: '-' }}</span></td>
                        <td>
                            <p class="table-main-text">{{ optional(optional($payment->order)->customer)->name ?: optional($payment->order)->customer_name ?: '-' }}</p>
                            <p class="table-sub-text">{{ optional(optional($payment->order)->shop)->shop_name ?: '-' }}</p>
                        </td>
                        <td><span class="status-pill success">{{ strtoupper($payment->payment_method ?: '-') }}</span></td>
                        <td>{{ $payment->payment_gateway ?: '-' }}</td>
                        <td><span class="code-pill">{{ $payment->transaction_id ?: '-' }}</span></td>
                        <td>Rs. {{ number_format($payment->amount, 2) }}</td>
                        <td><span class="status-pill {{ $payment->status === 'paid' ? 'success' : 'warning' }}">{{ \App\Models\Payment::STATUSES[$payment->status] ?? $payment->status }}</span></td>
                        <td>{{ optional($payment->paid_at)->format('d M Y, H:i') ?: '-' }}</td>
                        <td>{{ optional($payment->created_at)->format('d M Y') }}</td>
                        <td>
                            <div class="action-row">
                                @can('payment_show')
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn-outline"><i class="fas fa-eye"></i> View</a>
                                @endcan
                                @can('payment_edit')
                                    <a href="{{ route('admin.payments.edit', $payment->id) }}" class="btn-outline btn-outline-edit"><i class="fas fa-pencil-alt"></i> Edit</a>
                                @endcan
                                @can('payment_delete')
                                    <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn-outline btn-outline-danger"><i class="fas fa-trash-alt"></i> Delete</button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
$(function () {
    initAdminDataTable('.datatable-Payment', {
        canDelete: @can('payment_delete') true @else false @endcan,
        massDeleteUrl: "{{ route('admin.payments.massDestroy') }}",
        deleteText: "{{ trans('global.datatables.delete') }}",
        zeroSelectedText: "{{ trans('global.datatables.zero_selected') }}",
        confirmText: "{{ trans('global.areYouSure') }}",
        searchPlaceholder: 'Search payments...',
        infoText: 'Showing _START_-_END_ of _TOTAL_ payments'
    });
});
</script>
@endsection
