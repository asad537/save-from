@extends('layout')
@section('content')
@include('partials.blog-style')
<style>.topic-nav{display:flex;flex-wrap:wrap;justify-content:center;gap:8px;margin:18px auto 0}.topic-nav a{padding:8px 13px;border:1px solid #cfe0f5;border-radius:999px;background:rgba(255,255,255,.85);color:#205fd2;font-size:12px;font-weight:800}.topic-nav a:hover{background:#2166f3;border-color:#2166f3;color:#fff}</style>
<main>
    <section class="blog-hero"><div class="wrap"><div class="eyebrow"><i class="bi bi-journal-richtext"></i>&nbsp; Save-Froms Guides</div><h1>Video Download Guides{{ $posts->currentPage() > 1 ? ' – Page '.$posts->currentPage() : '' }}</h1><p class="sub">Step-by-step guides for downloading public videos from YouTube, Instagram, TikTok, Facebook and more: which link to copy, which format to pick, and what to do when a download fails.</p>
    <div class="topic-nav">@foreach(\App\Support\GuideTopics::all() as $slug => $t)<a href="{{ route('guides.topic', $slug) }}">{{ $t['name'] }}</a>@endforeach</div></div></section>
    <section class="blog-list"><div class="wrap">
        @if($posts->count())
            <div class="blog-grid">
                @foreach($posts as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            </div>
            {{ $posts->links('vendor.pagination.save-froms') }}
        @else
            <div class="card blog-empty"><i class="bi bi-journal-text"></i><h2>Articles coming soon</h2><p>New download guides and platform tips will appear here.</p></div>
        @endif
    </div></section>
</main>
@endsection
