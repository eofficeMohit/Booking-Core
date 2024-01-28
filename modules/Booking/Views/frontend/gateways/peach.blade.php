@extends('layouts.app')
@push('css')
    <link href="{{ asset('module/booking/css/checkout.css?_ver=' . config('app.asset_version')) }}" rel="stylesheet">
@endpush
@section('content')
    <div id="payment-form"></div>
@endsection
@push('js')
    <script src="{{ config('peach-payment.' . config('peach-payment.environment') . '.embedded_checkout_url') }}"></script>
    <script>
        const checkout = Checkout.initiate({
            key: "{{ $entityId }}",
            checkoutId: "{{ $checkoutId }}",
        });

        checkout.render("#payment-form");
    </script>
@endpush
