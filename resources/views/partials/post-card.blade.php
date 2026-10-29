@php
    $readingMinutes = max(3, (int) ceil(str_word_count(strip_tags($post->content)) / 220));
    $topic = 'Media guide';
    foreach (['youtube' => 'YouTube', 'instagram' => 'Instagram', 'tiktok' => 'TikTok', 'facebook' => 'Facebook', 'twitter' => 'X (Twitter)', 'x' => 'X (Twitter)', 'vimeo' => 'Vimeo', 'dailymotion' => 'Dailymotion', 'twitch' => 'Twitch', 'savefrom' => 'SaveFrom help', 'resolution' => 'Formats & quality', 'mp4' => 'Formats & quality'] as $token => $label) {
        if (in_array($token, explode('-', $post->slug), true)) { $topic = $label; break; }
    }
@endphp
<article class="post-card" lang="{{ $post->language ?: 'en' }}" dir="{{ $post->language === 'ar' ? 'rtl' : 'ltr' }}">
    <a class="post-media" href="{{ route('blog.show', $post->slug) }}" aria-label="Read {{ $post->title }}">
        @if($post->featured_image)
            <img class="post-image" src="{{ Str::startsWith($post->featured_image, ['http://','https://']) ? $post->featured_image : asset($post->featured_image).'?v='.optional($post->updated_at)->timestamp }}" alt="Illustration for {{ $post->title }}" title="{{ $post->title }}" width="1280" height="720" loading="lazy" decoding="async">
        @else
            <span class="post-placeholder"><i class="bi bi-journal-richtext"></i></span>
        @endif
        <span class="post-topic">{{ $topic }}</span>
    </a>
    <div class="post-body">
        <div class="post-meta"><time datetime="{{ optional($post->published_at)->toDateString() }}">{{ optional($post->published_at)->format('M d, Y') ?: $post->created_at->format('M d, Y') }}</time>@if($post->language !== 'ar')<span></span><small class="read-time">{{ $readingMinutes }} min read</small>@endif</div>
        <h2><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h2>
        <p>{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 135) }}</p>
        <a class="read-more" href="{{ route('blog.show', $post->slug) }}"><span>{{ $post->language === 'ar' ? 'اقرأ الدليل' : 'Read full guide' }}</span><i class="bi bi-arrow-right"></i></a>
    </div>
</article>
