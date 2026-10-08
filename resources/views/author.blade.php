@extends('layout')
@section('content')
@include('partials.static-page-style')
<style>.author-head{display:flex;align-items:center;gap:18px;margin-bottom:6px}.author-head .author-mark{width:64px;height:64px;display:grid;place-items:center;flex:0 0 64px;border-radius:18px;background:linear-gradient(135deg,#276df6,#764ff0);box-shadow:0 10px 22px rgba(45,102,236,.22);color:#fff;font-family:'Space Grotesk',sans-serif;font-size:19px;font-weight:800}.author-posts{display:grid;gap:10px;margin:16px 0 8px}.author-post{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:14px 16px;border:1px solid #dce7f3;border-radius:12px;background:#f8fbff;color:#1d3b62;font-weight:800;text-decoration:none!important}.author-post:hover{border-color:#91baff;background:#edf5ff;color:#2166f3}.author-post time{flex:0 0 auto;color:#7084a0;font-size:11px;font-weight:700;text-transform:uppercase}.author-areas{display:flex;flex-wrap:wrap;gap:8px;margin:12px 0 4px}.author-areas a{padding:7px 11px;border-radius:8px;background:#edf5ff;color:#2166f3;font-size:12px;font-weight:800;text-decoration:none!important}</style>
<main>
<section class="static-hero"><div class="wrap"><nav class="platform-breadcrumb" aria-label="Breadcrumb" style="display:flex;align-items:center;gap:8px;margin-bottom:14px;color:#637895;font-size:12px;font-weight:700"><a href="{{ route('home') }}" style="color:#2166f3">Home</a><i class="bi bi-chevron-right" style="font-size:10px;color:#9aabc0"></i><a href="{{ route('blog') }}" style="color:#2166f3">Blog</a><i class="bi bi-chevron-right" style="font-size:10px;color:#9aabc0"></i><span>Author</span></nav><div class="author-head"><span class="author-mark">SF</span><div><div class="eyebrow"><i class="bi bi-pencil-square"></i>&nbsp; Author</div><h1 style="margin:10px 0 0">Save-Froms Editorial Team</h1></div></div><p>The people who build, operate and support the Save-Froms downloader, and who write every platform page, tool page and guide on this site.</p><div class="static-meta"><span><i class="bi bi-journal-text"></i> {{ $posts->count() }} published guides</span><span><i class="bi bi-globe2"></i> {{ $platforms->count() }} platform pages maintained</span><span><i class="bi bi-envelope"></i> <a href="{{ url('/contact') }}">Corrections via Contact</a></span></div></div></section>
<section class="static-stage"><div class="wrap static-layout">
<aside class="static-toc"><strong><i class="bi bi-list-nested"></i> On this page</strong><a href="#role">Role</a><a href="#expertise">Areas of expertise</a><a href="#method">How guides are tested</a><a href="#articles">All articles</a></aside>
<div class="static-card"><div class="static-content">
<h2 id="role">Role</h2>
<p>The Editorial Team is not a separate content department. It is the same small group that maintains the downloader's platform integrations, watches the provider logs when a platform changes its links or delivery format, and answers support mail. Articles are written when a recurring support question or a platform change makes one necessary, which is why most guides are about link formats, availability and errors rather than general topics. The team publishes under a collective name because pages are revised by whoever handled the latest platform change; the <a href="{{ url('/about') }}#editorial">editorial principles</a> on the About page apply to every author.</p>

<h2 id="expertise">Areas of expertise</h2>
<p>The team maintains the downloader page and the guides for each supported platform:</p>
<div class="author-areas">@foreach($platforms as $site)<a href="{{ url('/'.$site->slug) }}">{{ $site->name }}</a>@endforeach<a href="{{ url('/download-troubleshooting') }}">Troubleshooting</a><a href="{{ url('/how-to-download-videos') }}">How-to</a></div>
<ul>
<li><strong>Platform URL formats</strong>: which link patterns identify a single video on each platform, and which never do.</li>
<li><strong>Media delivery</strong>: adaptive streams, video-only renditions, container and codec differences (MP4, WEBM, MP3), and why quality options vary per link.</li>
<li><strong>Availability rules</strong>: privacy settings, age and region gates, VOD retention and temporary CDN addresses.</li>
<li><strong>Mobile workflows</strong>: saving files in Chrome on Android and Safari on iPhone, and where downloads end up.</li>
<li><strong>Responsible use</strong>: what a public link does and does not permit.</li>
</ul>

<h2 id="method">How guides are tested</h2>
<p>Every link pattern or format claim in a guide is tried with the live downloader before publication and re-checked when a platform changes. Resolution and file-size figures are given as ranges observed across real links, not as guarantees. If a reader reports an error through the <a href="{{ url('/contact') }}">Contact page</a>, the page is corrected and its updated date changes. Guides never promise watermark removal, access to private content or resolutions a source does not offer.</p>

<h2 id="articles">All articles</h2>
<div class="author-posts">
@foreach($posts as $post)
<a class="author-post" href="{{ route('blog.show', $post->slug) }}"><span>{{ $post->title }}</span><time datetime="{{ optional($post->published_at)->toDateString() }}">{{ optional($post->published_at)->format('M d, Y') }}</time></a>
@endforeach
</div>
</div></div>
</div></section>
</main>
@endsection
@push('head')
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'ProfilePage', 'url' => route('author'), 'mainEntity' => ['@type' => 'Organization', 'name' => 'Save-Froms Editorial Team', 'url' => route('author'), 'parentOrganization' => ['@id' => url('/').'#organization'], 'knowsAbout' => ['Video download link formats', 'MP4, WEBM and MP3 media formats', 'Platform privacy and availability rules', 'Mobile browser downloads']]], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('blog')], ['@type' => 'ListItem', 'position' => 3, 'name' => 'Save-Froms Editorial Team', 'item' => route('author')]]], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
