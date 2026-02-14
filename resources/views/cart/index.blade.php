@extends('layouts.app')

@section('seo_noindex')
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('content')
<div class="bg-gray-50">
    <checkout-page-component></checkout-page-component>
</div>

<script>
    window.authUser = @json(auth()->user());
    window.currentLocale = "{{ app()->getLocale() }}";
</script>
@endsection