@extends('layout')
@section('content')
@include('partials.blog-style')
<style>.topic-intro{max-width:820px;margin:0 auto 34px;color:#344b68;font-size:16px;line-height:1.8}.topic-intro p{margin:0 0 14px}.topic-intro a{color:#2166f3;text-decoration:underline;text-underline-offset:3px}.topic-nav{display:flex;flex-wrap:wrap;justify-content:center;gap:8px;margin:18px auto 0}.topic-nav a{padding:8px 13px;border:1px solid #cfe0f5;border-radius:999px;background:rgba(255,255,255,.85);color:#205fd2;font-size:12px;font-weight:800}.topic-nav a.is-active{background:#2166f3;border-color:#2166f3;color:#fff}.topic-tool{display:inline-flex;align-items:center;gap:8px;margin:0 auto 30px;padding:12px 18px;border-radius:12px;background:#2166f3;color:#fff;font-weight:800}</style>
<main>
    <section class="blog-hero"><div class="wrap"><nav class="platform-breadcrumb" aria-label="Breadcrumb" style="justify-content:center"><a href="{{ route('home') }}">Home</a><i class="bi bi-chevron-right"></i><a href="{{ route('blog') }}">Guides</a><i class="bi bi-chevron-right"></i><span>{{ $topic['name'] }}</span></nav><h1>{{ $topic['h1'] }}</h1><p class="sub">{{ $posts->count() }} {{ Str::plural('guide', $posts->count()) }}, newest first.</p>
    <div class="topic-nav">@foreach($topics as $slug => $t)<a href="{{ route('guides.topic', $slug) }}" class="{{ $slug === $topic['slug'] ? 'is-active' : '' }}">{{ $t['name'] }}</a>@endforeach</div></div></section>
    <section class="blog-list"><div class="wrap">
        <div class="topic-intro">{!! $topic['intro'] !!}</div>
        <div style="text-align:center"><a class="topic-tool" href="{{ url('/'.$topic['tool']) }}"><i class="bi bi-download"></i> Open the {{ $topic['tool'] === 'supported-sites' ? 'supported sites list' : ($topic['tool'] === 'how-to-download-videos' ? 'step-by-step download guide' : ($topic['tool'] === 'download-troubleshooting' ? 'troubleshooting page' : $topic['name'].' downloader')) }}</a></div>
        @if($posts->count())
            <div class="blog-grid">
                @foreach($posts as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            </div>
        @else
            <div class="card blog-empty"><i class="bi bi-journal-text"></i><h2>Guides coming soon</h2><p>New {{ $topic['name'] }} guides will appear here.</p></div>
        @endif
    </div></section>
</main>
@endsection
@push('head')
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'Guides', 'item' => route('blog')], ['@type' => 'ListItem', 'position' => 3, 'name' => $topic['h1'], 'item' => route('guides.topic', $topic['slug'])]]], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'CollectionPage', 'name' => $topic['h1'], 'url' => route('guides.topic', $topic['slug']), 'description' => $description, 'hasPart' => $posts->map(function ($p) { return ['@type' => 'BlogPosting', 'headline' => $p->title, 'url' => route('blog.show', $p->slug)]; })->values()->all()], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
