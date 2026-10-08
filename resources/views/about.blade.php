@extends('layout')
@php($title = 'About Save-Froms – Who We Are & How the Downloader Works')
@php($description = 'Learn who operates Save-Froms, what the public-link video downloader does and does not do, how our guides are written and reviewed, and how we handle privacy.')
@section('content')
@include('partials.static-page-style')
<main>
<section class="static-hero"><div class="wrap"><div class="eyebrow"><i class="bi bi-info-circle"></i>&nbsp; About</div><h1>About Save-Froms</h1><p>Save-Froms is a small, independent web tool that lets you paste a public video link and see which formats you can actually save. This page explains what the service is, what it refuses to do, and how the content on this site is produced.</p><div class="static-meta"><span><i class="bi bi-calendar3"></i> Online since 2026</span><span><i class="bi bi-globe2"></i> {{ \App\Models\SupportedSite::active()->count() }} supported platforms</span><span><i class="bi bi-journal-text"></i> {{ \App\Models\BlogPost::published()->count() }} published guides</span></div></div></section>
<section class="static-stage"><div class="wrap static-layout">
<aside class="static-toc"><strong><i class="bi bi-list-nested"></i> On this page</strong><a href="#what">What Save-Froms does</a><a href="#not">What it does not do</a><a href="#how">How it works</a><a href="#editorial">Editorial principles</a><a href="#privacy">Privacy principles</a>@if(config('app.operator_name'))<a href="#operator">Operator</a>@endif
<a href="#independent">Independence</a><a href="#contact">Contact</a></aside>
<div class="static-card"><div class="static-content">
<h2 id="what">What Save-Froms does</h2>
<p>Save-Froms is a browser-based <strong>public video downloader</strong>. You paste one complete link from a supported platform, such as YouTube, Instagram, TikTok, Facebook, X, Vimeo, Dailymotion or Twitch, and the service asks its media provider which video and audio resources exist for that exact source. It then lists them with format, quality and estimated size so you can choose the file that fits your device, and streams the chosen file to your browser.</p>
<p>The service is free, has no account system, and works on Android, iPhone, iPad, Windows and macOS without an app or extension. The <a href="{{ url('/supported-sites') }}">supported sites hub</a> shows what each platform can return.</p>

<h2 id="not">What it does not do</h2>
<ul>
<li><strong>It does not bypass privacy.</strong> Private accounts, password-protected videos, subscriber-only content, age gates and regional blocks stay blocked. If a link needs a login to open, it cannot be processed.</li>
<li><strong>It does not invent quality.</strong> If the source offers 720p, you will not see 1080p. Every option shown is one the provider actually returned.</li>
<li><strong>It does not host or keep files.</strong> Media is streamed from the provider to you during the request and the request expires after about 20 minutes.</li>
<li><strong>It does not grant rights.</strong> Being able to download a file says nothing about whether you may use it. Our <a href="{{ url('/terms-of-service') }}">Terms of Service</a> require you to download only content you own or have permission to save.</li>
<li><strong>It does not ask for passwords.</strong> No page on this site requests a social-media login, and no staff member will ever ask for one.</li>
</ul>

<h2 id="how">How the downloader works</h2>
<ol>
<li>You submit a URL. Save-Froms checks the domain against the list of active platforms and rejects anything else.</li>
<li>The public URL is sent to a third-party media-processing provider, which inspects the public page and reports the available renditions.</li>
<li>Save-Froms shows the renditions with format, quality and size. Some higher qualities are assembled on demand, which is why a button can show <em>Preparing</em>.</li>
<li>When you click Download, Save-Froms fetches the file from the provider and streams it straight to your browser without storing it.</li>
</ol>
<p>The step-by-step user view is in the <a href="{{ url('/how-to-download-videos') }}">how-to guide</a>, and every error message is explained in the <a href="{{ url('/download-troubleshooting') }}">troubleshooting guide</a>.</p>

<h2 id="editorial">Editorial principles</h2>
<p>Everything in the <a href="{{ route('blog') }}">blog</a>, the platform pages and the FAQ is written by the Save-Froms Editorial Team, the same small group that builds and supports the downloader. We follow five rules:</p>
<div class="principles">
<div><strong>Tested against the live tool</strong><span>Every link pattern and format claim is checked with the real downloader before it is published, and re-checked when a platform changes its URLs or delivery.</span></div>
<div><strong>Honest about limits</strong><span>We say when something cannot be downloaded, when a quality is often missing and when a short link may expire. We do not promise watermark removal or resolutions the source does not have.</span></div>
<div><strong>One page, one purpose</strong><span>Each platform has one downloader page, each format one tool page, and each guide one question. We would rather improve an existing page than publish a near-duplicate.</span></div>
<div><strong>Dated and maintained</strong><span>Pages carry an updated date. When a reader reports an error through the Contact page we correct it and note the change.</span></div>
<div><strong>Responsible use first</strong><span>Guides explain how to check whether a link is public and remind readers that technical availability is not permission. We do not publish content whose only purpose is circumventing a platform rule.</span></div>
<div><strong>No paid placement</strong><span>Platform pages and guides are not sponsored. If that ever changes it will be labelled on the page.</span></div>
</div>

<h2 id="privacy">Privacy principles</h2>
<p>We keep the minimum needed to run and protect the service: basic request logs, an anonymous visitor cookie that counts one visit per browser per day, and a record of which platform, format and quality completed downloads used. We do not sell data, do not run advertising networks on the site today, and do not keep the media you download. The full <a href="{{ url('/privacy-policy') }}">Privacy Policy</a> lists every data point and how long it is kept.</p>

@if(config('app.operator_name'))<h2 id="operator">Who operates Save-Froms</h2><table><tbody><tr><th>Operated by</th><td>{{ config('app.operator_name') }}</td></tr>@if(config('app.operator_location'))<tr><th>Location</th><td>{{ config('app.operator_location') }}</td></tr>@endif
<tr><th>Contact</th><td><a href="mailto:{{ config('app.support_email') }}">{{ config('app.support_email') }}</a></td></tr></tbody></table>@endif
<h2 id="independent">Independence and naming</h2>
<p>Save-Froms (save-froms.net) is an independent service. It is <strong>not</strong> affiliated with, endorsed by or operated by SaveFrom.net, and it is not affiliated with YouTube, Meta, TikTok, X, Vimeo, Dailymotion, Twitch or any other platform mentioned on this site. Platform names are used only to describe which links the tool supports. Our media processing is provided by a third-party API partner; Save-Froms does not scrape platforms itself.</p>

<h2 id="contact">Contact</h2>
<p>Questions, corrections, copyright concerns and security reports all go through the <a href="{{ url('/contact') }}">Contact page</a>, which lists a separate address for each. We usually reply within two business days.</p>
</div></div>
</div></section>
</main>
@endsection
@push('head')
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'AboutPage', 'name' => 'About Save-Froms', 'url' => url('/about'), 'mainEntity' => ['@id' => url('/').'#organization']], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'About', 'item' => url('/about')]]], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
