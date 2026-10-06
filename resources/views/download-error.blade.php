@php($title = 'Download Unavailable | Save-Froms')
@php($description = 'This temporary download request is unavailable or has expired.')
@php($robots = 'noindex,follow')
@extends('layout')
@section('content')
<main><section class="hero"><div class="wrap"><div class="eyebrow">Download unavailable</div><h1>That format could not be<br><em>prepared right now.</em></h1><p>{{ $message }}</p><a class="btn" href="{{ url('/') }}#downloader">Try another link</a></div></section></main>
@endsection
