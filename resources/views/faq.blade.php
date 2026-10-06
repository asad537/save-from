@extends('layout')
@php($title = 'Frequently Asked Questions | Save-Froms')
@php($description = 'Find answers about supported websites, download formats, video quality, devices, public links, privacy and responsible media downloading with Save-Froms.')
@section('content')
<style>
.faq-hero{padding:64px 0 58px;text-align:center;background:radial-gradient(circle at 50% 20%,#dceeff,transparent 45%),linear-gradient(180deg,#f8fcff,#edf7ff);border-top:1px solid #edf2f8}.faq-hero h1{margin:15px 0 12px;font-size:48px;letter-spacing:-1.7px}.faq-hero p{max-width:690px;margin:auto;color:#607490;line-height:1.7}.faq-section{padding:58px 0 72px}.faq-list{max-width:900px;margin:auto}.public-faq{margin-bottom:13px;border:1px solid #dce7f3;border-radius:14px;background:#fff;box-shadow:0 8px 26px rgba(33,74,128,.05);overflow:hidden}.public-faq summary{position:relative;padding:20px 55px 20px 22px;cursor:pointer;list-style:none;color:#182e4e;font-weight:800}.public-faq summary::-webkit-details-marker{display:none}.public-faq summary:after{content:'+';position:absolute;right:20px;top:14px;width:32px;height:32px;display:grid;place-items:center;border-radius:9px;background:#edf4ff;color:#2166f3;font-size:21px}.public-faq[open] summary:after{content:'−'}.faq-answer{padding:0 22px 20px;color:#607490;line-height:1.75}.faq-answer p{margin:0 0 12px}.faq-answer p:last-child{margin-bottom:0}.faq-category{display:inline-block;margin-bottom:9px;padding:5px 9px;border-radius:20px;background:#edf5ff;color:#2166f3;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.6px}.faq-empty{text-align:center;padding:55px;color:#71849d}@media(max-width:600px){.faq-hero h1{font-size:37px}.faq-section{padding-top:38px}.public-faq summary{padding-left:17px}.faq-answer{padding-left:17px}}
</style>
<style>@media(max-width:600px){.faq-hero{padding:44px 0}.faq-hero h1{font-size:35px}.faq-hero p{font-size:15px}.faq-section{padding:38px 0 52px}.public-faq summary{padding:17px 50px 17px 16px}.public-faq summary:after{right:14px;top:12px}.faq-answer{padding:0 16px 17px;font-size:14px}}</style>
<main>
<section class="faq-hero"><div class="wrap"><div class="eyebrow"><i class="bi bi-patch-question-fill"></i>&nbsp; Help Center</div><h1>Frequently Asked Questions</h1><p>Everything you need to know about supported links, available formats, devices, troubleshooting and responsible downloads.</p></div></section>
<section class="faq-section"><div class="wrap"><div class="faq-list">
@forelse($faqs as $faq)
<details class="public-faq"><summary>@if($faq->category)<span class="faq-category">{{ $faq->category }}</span><br>@endif{{ $faq->question }}</summary><div class="faq-answer">{!! $faq->answer !!}</div></details>
@empty
<div class="faq-empty">FAQ content is being prepared.</div>
@endforelse
</div></div></section>
</main>
@endsection
@push('head')
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $faqs->map(function ($faq) { return ['@type' => 'Question', 'name' => $faq->question, 'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim(strip_tags($faq->answer))]]; })->values()], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
