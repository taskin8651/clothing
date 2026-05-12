@extends('layouts.admin')
@section('page-title', 'Add Return Request')
@section('content')
<div class="admin-page-head"><div><a href="{{ route('admin.return-requests.index') }}" class="admin-back-link">&larr; {{ trans('global.back_to_list') }}</a><h2 class="admin-page-title">Add Return Request</h2><p class="admin-page-subtitle">Create return only for return eligible order items</p></div></div>
<form method="POST" action="{{ route('admin.return-requests.store') }}">@csrf<div class="admin-form-grid">@include('admin.returnRequests.partials.form', ['returnRequest' => null])</div><div class="form-actions"><button class="btn-primary"><i class="fas fa-check"></i> Save Return</button><a href="{{ route('admin.return-requests.index') }}" class="btn-ghost">Cancel</a></div></form>
@endsection
@section('scripts')@parent<script>
function showEligibility(select){const opt=select.options[select.selectedIndex];const note=document.getElementById('eligibilityNote');if(!opt||!opt.value){note.textContent='Only return eligible items can be selected.';note.style.color='';return;}if(opt.dataset.eligible==='0'||opt.dataset.try==='1'){note.textContent='This item is not eligible for return because Try Cloth was selected.';note.style.color='#B91C1C';}else{note.textContent='Selected item is eligible for return.';note.style.color='#166534';}}
document.getElementById('order_id').addEventListener('change',function(){const orderId=this.value;document.querySelectorAll('#order_item_id option').forEach(function(opt){if(!opt.value){opt.hidden=false;return;}opt.hidden=orderId&&opt.dataset.order!==orderId;});document.getElementById('order_item_id').value='';showEligibility(document.getElementById('order_item_id'));});showEligibility(document.getElementById('order_item_id'));
</script>@endsection
