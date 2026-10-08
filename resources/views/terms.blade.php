@extends('layout')
@php($title = 'Terms of Service | Save-Froms')
@php($description = 'The rules for using the Save-Froms public video downloader: acceptable use, copyright responsibility, prohibited uses, availability, temporary links, liability and contact.')
@php($legalEmail = config('app.legal_email'))
@section('content')
@include('partials.static-page-style')
<main>
<section class="static-hero"><div class="wrap"><div class="eyebrow"><i class="bi bi-file-earmark-text"></i>&nbsp; Legal</div><h1>Terms of Service</h1><p>By using save-froms.net you agree to these terms. They are short on purpose: the service is a tool for saving public media you are allowed to keep, and these rules exist to keep it that way.</p><div class="static-meta"><span><i class="bi bi-calendar3"></i> Last updated: October 8, 2026</span><span><i class="bi bi-globe2"></i> Applies to save-froms.net</span></div></div></section>
<section class="static-stage"><div class="wrap static-layout">
<aside class="static-toc"><strong><i class="bi bi-list-nested"></i> On this page</strong><a href="#service">The service</a><a href="#acceptable">Acceptable use</a><a href="#copyright">Copyright</a><a href="#prohibited">Prohibited uses</a><a href="#platforms">Third-party platforms</a><a href="#availability">Availability</a><a href="#liability">Liability</a><a href="#termination">Termination</a><a href="#law">Governing law</a><a href="#contact">Contact</a></aside>
<div class="static-card"><div class="static-content">
<h2 id="service">1. The service</h2>
<p>Save-Froms ("we", "the service") provides a browser-based tool that checks a publicly accessible media link, lists the video and audio renditions a third-party media provider returns for it, and streams the rendition you choose to your device. The service is free, requires no account, and is provided as a utility. It does not host, index or publish media.</p>

<h2 id="acceptable">2. Acceptable use</h2>
<p>You may use Save-Froms to save media that you created, that you own, that is in the public domain, that is released under a licence permitting download, or that you have explicit permission from the rights holder to save. Typical acceptable uses include backing up your own uploads, saving a creator's video with their consent, keeping a copy of public-domain footage, or saving material for uses permitted by applicable law.</p>

<h2 id="copyright">3. Copyright responsibility</h2>
<p><strong>You are solely responsible for the links you submit and the files you download.</strong> The fact that a file is technically available does not mean you have the right to copy, keep, share or republish it. Downloading copyrighted material without permission may violate the law and the source platform's terms. Save-Froms does not grant any rights in third-party content and does not check whether you have them.</p>
<p>Rights holders who believe the service is being used to infringe their work can contact <a href="mailto:{{ $legalEmail }}">{{ $legalEmail }}</a>; details of what to include are on the <a href="{{ url('/contact') }}">Contact page</a>. We can block individual URLs or domains from the downloader in response to valid reports.</p>

<h2 id="prohibited">4. Prohibited uses</h2>
<p>You must not use Save-Froms to:</p>
<ul>
<li>download, distribute or republish content in breach of copyright or other rights;</li>
<li>attempt to access private, password-protected, subscriber-only, age-gated or otherwise restricted content, or to circumvent any platform's access controls;</li>
<li>submit links in bulk, run automated scripts, scrapers or bots against the service, or evade rate limits;</li>
<li>interfere with the operation of the service, probe it for vulnerabilities without authorisation, or place unreasonable load on it;</li>
<li>misrepresent the origin of a downloaded file, remove attribution, or pass off another person's work as your own;</li>
<li>use the service for any unlawful purpose or in a way that harms the source platform, its creators or other users.</li>
</ul>

<h2 id="platforms">5. Third-party platforms and providers</h2>
<p>Save-Froms is independent of YouTube, Meta (Instagram, Facebook), TikTok, X, Vimeo, Dailymotion, Twitch and every other platform named on this site, and of SaveFrom.net. Platform names are used only to describe supported link types. Your use of those platforms is governed by their own terms, which continue to apply. Media processing is performed by a third-party provider; we do not control the platforms or guarantee how they will respond to a request.</p>

<h2 id="availability">6. Availability, formats and temporary links</h2>
<ul>
<li>The service is provided "as is" and "as available". Platforms change their systems frequently, and a link or format that worked yesterday may not work today.</li>
<li>We make no guarantee that any particular format, resolution, watermark state or audio track will be returned for a given link. The result lists only what the provider returns.</li>
<li>Download buttons and preparation requests are temporary and expire, normally within about 20 minutes. Expired requests cannot be restored; paste the link again.</li>
<li>We may change, limit, suspend or discontinue any part of the service at any time without notice, including by disabling individual platforms or URLs.</li>
</ul>

<h2 id="liability">7. Disclaimer and limitation of liability</h2>
<p>To the fullest extent permitted by law, Save-Froms, its operators and its providers disclaim all warranties, express or implied, including fitness for a particular purpose and non-infringement. We are not liable for any direct, indirect, incidental, consequential or special damages arising from your use of, or inability to use, the service, including loss of data, claims by third parties, or consequences of downloading content you were not entitled to download. Where liability cannot be excluded it is limited to the greatest extent the law allows. Nothing in these terms limits liability for fraud or for anything that cannot lawfully be limited.</p>
<p>You agree to indemnify and hold harmless Save-Froms and its operators from any claim, loss or expense (including reasonable legal fees) arising from your breach of these terms or your use of downloaded content.</p>

<h2 id="termination">8. Termination and enforcement</h2>
<p>We may block or restrict access from any IP address, network or user that breaches these terms, without notice. Rate limits apply to all users. Blocking of specific source URLs or domains may be applied in response to abuse or rights-holder reports.</p>

<h2 id="law">9. Governing law and changes</h2>
<p>These terms are governed by the laws applicable at the place of establishment of the service operator, without regard to conflict-of-law rules, and any dispute is subject to the courts there unless mandatory consumer law gives you the right to another forum. We may update these terms; the date at the top identifies the current version, and continued use after a change means you accept it.</p>

<h2 id="contact">10. Contact</h2>
<p>Questions about these terms, copyright reports and security disclosures: <a href="mailto:{{ $legalEmail }}">{{ $legalEmail }}</a>. All channels are listed on the <a href="{{ url('/contact') }}">Contact page</a>. See also the <a href="{{ url('/privacy-policy') }}">Privacy Policy</a> and the <a href="{{ url('/about') }}">About page</a>.</p>
</div></div>
</div></section>
</main>
@endsection
@push('head')
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'Terms of Service', 'item' => url('/terms-of-service')]]], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
