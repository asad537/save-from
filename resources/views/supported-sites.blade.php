@extends('layout')
@php($title = 'Supported Video Download Sites | Save-Froms')
@php($description = 'Browse the public media platforms supported by Save-Froms and open a dedicated downloader guide for each available website.')
@section('content')
<style>
.sites-hero{padding:64px 0 58px;text-align:center;background:radial-gradient(circle at 50% 10%,#dceeff,transparent 46%),linear-gradient(180deg,#f8fcff,#edf7ff);border-top:1px solid #edf2f8}.sites-hero h1{margin:15px 0 12px;font-size:48px;letter-spacing:-1.7px}.sites-hero p{max-width:690px;margin:auto;color:#607490;line-height:1.7}.sites-section{padding:58px 0 76px}.sites-list{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}.site-item{display:flex;align-items:center;gap:16px;padding:22px;border:1px solid #dce7f3;border-radius:16px;background:#fff;box-shadow:0 10px 28px rgba(33,74,128,.06);transition:.2s}.site-item:hover{transform:translateY(-4px);border-color:#9fc2ff;box-shadow:0 16px 38px rgba(33,74,128,.11)}.site-item-icon{flex:0 0 54px;width:54px;height:54px;display:grid;place-items:center;border:1px solid #e0e9f4;border-radius:13px;background:#f8fbff}.site-item-icon img{width:31px;height:31px;object-fit:contain}.site-item h2{margin:0 0 4px;font-size:18px}.site-item p{margin:0;color:#71849e;font-size:12px}.site-arrow{margin-left:auto;color:#2166f3;font-size:20px}.guide-block{margin-top:62px;padding-top:48px;border-top:1px solid #e1eaf4}.guide-block>h2{text-align:center;font-size:31px;margin:0 0 9px}.guide-block>.sub{text-align:center}.guide-list{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:27px}.guide-card{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:17px 18px;border:1px solid #dce7f3;border-radius:12px;background:#f8fbff;color:#1d3b62;font-weight:800}.guide-card:hover{border-color:#91baff;background:#edf5ff;color:#2166f3}@media(max-width:850px){.sites-list,.guide-list{grid-template-columns:repeat(2,1fr)}}@media(max-width:560px){.sites-hero h1{font-size:37px}.sites-list,.guide-list{grid-template-columns:1fr}}
</style>
<style>@media(max-width:560px){.sites-hero{padding:44px 0}.sites-hero h1{font-size:36px}.sites-section{padding:40px 0 55px}.sites-list,.guide-list{grid-template-columns:1fr}.site-item{padding:17px;gap:12px}.site-item-icon{flex-basis:48px;width:48px;height:48px}.guide-block{margin-top:45px;padding-top:38px}.guide-block>h2{font-size:26px}}</style>
<main>
<section class="sites-hero"><div class="wrap"><div class="eyebrow"><i class="bi bi-globe2"></i>&nbsp; Supported Platforms</div><h1>Supported Download Sites</h1><p>Select a platform to open its downloader, supported-link guidance, available-format information and frequently asked questions.</p></div></section>
<section class="sites-section"><div class="wrap"><div class="sites-list">
@foreach($platforms as $site)
<a class="site-item" href="{{ url('/'.$site->slug) }}">
    <span class="site-item-icon">@if($site->brandIcon())<i class="bi {{ $site->brandIcon() }}" style="font-size:30px;color:{{ $site->brandColor() }}"></i>@elseif($site->logo_url)<img src="{{ $site->logo_url }}" alt="{{ $site->name }} logo" title="{{ $site->name }} downloader">@else<i class="bi bi-globe2" style="font-size:28px;color:#2166f3"></i>@endif</span>
    <span><h2>{{ $site->name }}</h2><p>Open {{ $site->name }} downloader</p></span><i class="bi bi-arrow-right site-arrow"></i>
</a>
@endforeach
</div>@if($guides->isNotEmpty())<div class="guide-block"><h2>Popular Format & Download Guides</h2><p class="sub">Detailed help for specific formats, short videos and common download problems.</p><div class="guide-list">@foreach($guides as $guide)<a class="guide-card" href="{{ url('/'.$guide->slug) }}"><span>{{ $guide->title }}</span><i class="bi bi-arrow-right"></i></a>@endforeach</div></div>@endif</div></section>
</main>
@endsection
