@extends('layout')
@section('content')
<style>
.blog-hero{padding:68px 0;text-align:center;background:linear-gradient(150deg,#eef8ff,#f8fbff)}.blog-hero h1{margin:15px auto 10px;font-size:50px}.blog-list{padding:58px 0}.blog-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}.post-card{overflow:hidden;padding:0}.post-image{display:block;width:100%;aspect-ratio:16/9;object-fit:cover;background:#e9f3ff}.post-placeholder{display:grid;place-items:center;color:#2871f5;font-size:38px}.post-body{padding:21px}.post-date{color:#2166f3;font-size:11px;font-weight:800;text-transform:uppercase}.post-card h2{margin:9px 0;font-size:20px;line-height:1.35;text-align:left}.post-card p{margin:0 0 16px}.read-more{color:#2166f3;font-size:13px;font-weight:800}.blog-empty{text-align:center;padding:60px 20px}.blog-empty i{font-size:45px;color:#90add0}@media(max-width:850px){.blog-grid{grid-template-columns:repeat(2,1fr)}}@media(max-width:560px){.blog-grid{grid-template-columns:1fr}.blog-hero{padding:44px 0}.blog-hero h1{font-size:36px;line-height:1.12}.blog-list{padding:40px 0}.post-body{padding:18px}.post-card h2{font-size:19px}}
</style>
<main>
    <section class="blog-hero"><div class="wrap"><div class="eyebrow">Save-Froms Journal</div><h1>Guides for Smarter Downloads</h1><p class="sub">Practical media guides, platform tips and product updates.</p></div></section>
    <section class="blog-list"><div class="wrap">
        @if($posts->count())
            <div class="blog-grid">
                @foreach($posts as $post)
                    <article class="card post-card">
                        @if($post->featured_image)
                            <a href="{{ route('blog.show', $post->slug) }}" aria-label="Read {{ $post->title }}"><img class="post-image" src="{{ Str::startsWith($post->featured_image, ['http://','https://']) ? $post->featured_image : asset($post->featured_image) }}" alt="Illustration for {{ $post->title }}" title="{{ $post->title }}" width="1280" height="720" loading="lazy" decoding="async"></a>
                        @else
                            <div class="post-image post-placeholder"><i class="bi bi-journal-richtext"></i></div>
                        @endif
                        <div class="post-body"><div class="post-date">{{ optional($post->published_at)->format('M d, Y') ?: $post->created_at->format('M d, Y') }}</div><h2><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h2><p>{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 135) }}</p><a class="read-more" href="{{ route('blog.show', $post->slug) }}">Read article <i class="bi bi-arrow-right"></i></a></div>
                    </article>
                @endforeach
            </div>
            <div style="margin-top:30px">{{ $posts->links() }}</div>
        @else
            <div class="card blog-empty"><i class="bi bi-journal-text"></i><h2>Articles coming soon</h2><p>New download guides and platform tips will appear here.</p></div>
        @endif
    </div></section>
</main>
@endsection
