@extends('layout')
@section('content')
<style>
.blog-hero{position:relative;overflow:hidden;padding:76px 0 72px;text-align:center;background:radial-gradient(circle at 18% 15%,rgba(54,142,255,.15),transparent 28%),radial-gradient(circle at 84% 40%,rgba(102,78,255,.1),transparent 26%),linear-gradient(145deg,#f8fcff,#edf7ff)}
.blog-hero:after{content:"";position:absolute;left:50%;bottom:-84px;width:520px;height:170px;border:1px solid rgba(45,112,231,.12);border-radius:50%;transform:translateX(-50%)}
.blog-hero .wrap{position:relative;z-index:1}.blog-hero h1{max-width:760px;margin:15px auto 12px;font-size:52px;line-height:1.08;letter-spacing:-1.8px}.blog-hero .sub{max-width:620px;margin:auto;font-size:17px;line-height:1.65}
.blog-list{padding:52px 0 72px;background:#fff}.blog-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px;align-items:stretch}
.post-card{position:relative;display:flex;min-width:0;overflow:hidden;flex-direction:column;padding:0;border:1px solid #dce8f6;border-radius:20px;background:#fff;box-shadow:0 14px 38px rgba(26,67,121,.08);transition:transform .25s,border-color .25s,box-shadow .25s}.post-card:hover{transform:translateY(-5px);border-color:#b8d2f1;box-shadow:0 23px 50px rgba(26,67,121,.14)}
.post-media{position:relative;display:block;aspect-ratio:16/8.7;overflow:hidden;background:linear-gradient(135deg,#e7f2ff,#eeeaff)}.post-image{display:block;width:100%;height:100%;object-fit:cover;transition:transform .45s}.post-card:hover .post-image{transform:scale(1.035)}.post-placeholder{display:grid;place-items:center;width:100%;height:100%;color:#2871f5;font-size:38px}
.post-topic{position:absolute;left:14px;top:14px;padding:6px 10px;border:1px solid rgba(255,255,255,.55);border-radius:999px;background:rgba(13,37,72,.78);backdrop-filter:blur(8px);color:#fff;font-size:9px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}
.post-body{display:flex;flex:1;min-width:0;flex-direction:column;padding:18px}.post-meta{display:flex;align-items:center;gap:8px;color:#7488a3;font-size:9px;font-weight:800;letter-spacing:.05em;text-transform:uppercase}.post-meta time{color:#2166f3}.post-meta span{width:4px;height:4px;border-radius:50%;background:#becbdd}.post-card h2{display:-webkit-box;overflow:hidden;-webkit-box-orient:vertical;-webkit-line-clamp:3;margin:9px 0 10px;font-size:18px;line-height:1.33;letter-spacing:-.25px;text-align:left}.post-card h2 a{color:#142846}.post-card p{display:-webkit-box;overflow:hidden;-webkit-box-orient:vertical;-webkit-line-clamp:2;margin:0 0 15px;color:#607590;font-size:12px;line-height:1.58}.read-more{display:flex;align-items:center;justify-content:space-between;margin-top:auto;padding-top:13px;border-top:1px solid #edf2f8;color:#2166f3;font-size:11px;font-weight:800}.read-more i{transition:transform .2s}.post-card:hover .read-more i{transform:translateX(4px)}
.post-card:first-child{grid-column:auto;display:flex;grid-template-columns:none}.post-card:first-child .post-media{height:auto;aspect-ratio:16/8.7;min-height:0}.post-card:first-child .post-body{justify-content:flex-start;padding:18px}.post-card:first-child h2{font-size:18px;line-height:1.33;-webkit-line-clamp:3}.post-card:first-child p{font-size:12px;-webkit-line-clamp:2}.blog-empty{text-align:center;padding:60px 20px}.blog-empty i{font-size:45px;color:#90add0}
@media(max-width:940px){.blog-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.post-card:first-child{grid-column:auto}.post-card:first-child .post-body{padding:18px}.post-card:first-child h2{font-size:18px}}
@media(max-width:650px){.blog-hero{padding:48px 0 50px}.blog-hero h1{font-size:38px;line-height:1.1;letter-spacing:-1px}.blog-hero .sub{font-size:15px}.blog-list{padding:38px 0 58px}.blog-grid{grid-template-columns:1fr;gap:16px}.post-card,.post-card:first-child{grid-column:auto;display:grid;grid-template-columns:118px minmax(0,1fr);border-radius:16px}.post-card .post-media,.post-card:first-child .post-media{height:100%;min-height:178px;aspect-ratio:auto}.post-card .post-body,.post-card:first-child .post-body{justify-content:flex-start;padding:16px}.post-card h2,.post-card:first-child h2{margin:7px 0 9px;font-size:17px;line-height:1.32;-webkit-line-clamp:3}.post-card p,.post-card:first-child p{display:none}.post-meta{gap:6px;font-size:8px}.post-meta span,.post-meta .read-time{display:none}.post-topic{left:8px;top:8px;padding:4px 7px;font-size:7px}.read-more{padding-top:11px;font-size:10px}}
@media(max-width:390px){.post-card,.post-card:first-child{grid-template-columns:104px minmax(0,1fr)}.post-card .post-body,.post-card:first-child .post-body{padding:14px}.post-card h2,.post-card:first-child h2{font-size:16px}}
</style>
<main>
    <section class="blog-hero"><div class="wrap"><div class="eyebrow"><i class="bi bi-journal-richtext"></i>&nbsp; Save-Froms Journal</div><h1>Guides for Smarter Downloads</h1><p class="sub">Practical, easy-to-follow media guides for public links, formats, quality and mobile workflows.</p></div></section>
    <section class="blog-list"><div class="wrap">
        @if($posts->count())
            <div class="blog-grid">
                @foreach($posts as $post)
                    @php
                        $readingMinutes = max(3, (int) ceil(str_word_count(strip_tags($post->content)) / 220));
                        $topic = 'Media guide';
                        foreach (['youtube' => 'YouTube', 'instagram' => 'Instagram', 'tiktok' => 'TikTok', 'facebook' => 'Facebook', 'twitter' => 'X (Twitter)', 'x' => 'X (Twitter)', 'vimeo' => 'Vimeo', 'dailymotion' => 'Dailymotion', 'twitch' => 'Twitch', 'savefrom' => 'SaveFrom help', 'resolution' => 'Formats & quality', 'mp4' => 'Formats & quality'] as $token => $label) {
                            if (in_array($token, explode('-', $post->slug), true)) { $topic = $label; break; }
                        }
                    @endphp
                    <article class="post-card">
                        <a class="post-media" href="{{ route('blog.show', $post->slug) }}" aria-label="Read {{ $post->title }}">
                            @if($post->featured_image)
                                <img class="post-image" src="{{ Str::startsWith($post->featured_image, ['http://','https://']) ? $post->featured_image : asset($post->featured_image) }}" alt="Illustration for {{ $post->title }}" title="{{ $post->title }}" width="1280" height="720" loading="lazy" decoding="async">
                            @else
                                <span class="post-placeholder"><i class="bi bi-journal-richtext"></i></span>
                            @endif
                            <span class="post-topic">{{ $topic }}</span>
                        </a>
                        <div class="post-body">
                            <div class="post-meta"><time datetime="{{ optional($post->published_at)->toDateString() }}">{{ optional($post->published_at)->format('M d, Y') ?: $post->created_at->format('M d, Y') }}</time><span></span><small class="read-time">{{ $readingMinutes }} min read</small></div>
                            <h2><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h2>
                            <p>{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 135) }}</p>
                            <a class="read-more" href="{{ route('blog.show', $post->slug) }}"><span>Read full guide</span><i class="bi bi-arrow-right"></i></a>
                        </div>
                    </article>
                @endforeach
            </div>
            <div style="margin-top:34px">{{ $posts->links() }}</div>
        @else
            <div class="card blog-empty"><i class="bi bi-journal-text"></i><h2>Articles coming soon</h2><p>New download guides and platform tips will appear here.</p></div>
        @endif
    </div></section>
</main>
@endsection
