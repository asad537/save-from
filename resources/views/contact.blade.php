@extends('layout')
@php($title = 'Contact Save-Froms – Support, Copyright & Security')
@php($description = 'Contact Save-Froms for download support, copyright or abuse reports, security disclosures and general questions. Find the right address and what to include.')
@php($supportEmail = config('app.support_email'))
@php($legalEmail = config('app.legal_email'))
@section('content')
@include('partials.static-page-style')
<main>
<section class="static-hero"><div class="wrap"><div class="eyebrow"><i class="bi bi-envelope-paper"></i>&nbsp; Contact</div><h1>Contact Save-Froms</h1><p>Use the address that matches your request so it reaches the right person quickly. Every message is read by a human; there is no ticket bot.</p><div class="static-meta"><span><i class="bi bi-clock"></i> Typical reply: within 2 business days</span></div></div></section>
<section class="static-stage"><div class="wrap static-layout">
<aside class="static-toc"><strong><i class="bi bi-list-nested"></i> On this page</strong><a href="#support">Download support</a><a href="#copyright">Copyright and abuse</a><a href="#security">Security reports</a><a href="#business">Business and press</a><a href="#before">Before you write</a><a href="#never">What never to send</a></aside>
<div class="static-card"><div class="static-content">
<h2 id="support">Choose the right channel</h2>
<div class="contact-grid">
<div class="contact-card"><i class="bi bi-life-preserver"></i><strong>Download support</strong><p>A public link fails, a format stays on Preparing, a download expired, or something on the site looks broken.</p><a class="mail" href="mailto:{{ $supportEmail }}?subject=Download%20support">{{ $supportEmail }}</a></div>
<div class="contact-card" id="copyright"><i class="bi bi-shield-exclamation"></i><strong>Copyright, abuse and takedown</strong><p>You are a rights holder, or you believe Save-Froms is being used to infringe your content or to abuse a platform.</p><a class="mail" href="mailto:{{ $legalEmail }}?subject=Copyright%20or%20abuse%20report">{{ $legalEmail }}</a></div>
<div class="contact-card" id="security"><i class="bi bi-bug"></i><strong>Security reports</strong><p>You found a vulnerability in save-froms.net. Please give us a reasonable window to fix it before public disclosure.</p><a class="mail" href="mailto:{{ $legalEmail }}?subject=Security%20report">{{ $legalEmail }}</a></div>
<div class="contact-card" id="business"><i class="bi bi-briefcase"></i><strong>Business, press and corrections</strong><p>Partnership questions, media enquiries, or an error in one of our guides that you would like corrected.</p><a class="mail" href="mailto:{{ $supportEmail }}?subject=General%20enquiry">{{ $supportEmail }}</a></div>
</div>

<h2 id="copyright-detail">Copyright and abuse reports</h2>
<p>Save-Froms does not host media. It fetches a public file from the source platform on the user's request and streams it to that user; nothing is stored on our servers after the temporary request expires (about 20 minutes). If you own content that is being downloaded without permission, the most effective step is to change its audience or privacy setting on the source platform, because Save-Froms can only process links that are public there.</p>
<p>If you still want to report a specific case, email the copyright address with:</p>
<ul>
<li>the exact source URL(s) involved;</li>
<li>a description of the work and your relationship to it (owner, agent, licensee);</li>
<li>a contact address for follow-up;</li>
<li>a statement that the information is accurate and that you are authorised to act.</li>
</ul>
<p>We respond to complete reports within 2 business days and can block individual URLs or whole domains from the downloader. See the <a href="{{ url('/terms-of-service') }}">Terms of Service</a> for the acceptable-use rules every user agrees to.</p>

<h2 id="security-detail">Security disclosures</h2>
<p>Include the affected URL or endpoint, steps to reproduce, and the impact you observed. Please do not run automated scanners against the download endpoints at volume, test on other users' data, or attempt denial-of-service. We confirm receipt, keep you informed about the fix, and credit you publicly if you wish.</p>

<h2 id="before">Before you write about a failed download</h2>
<p>Most failures are caused by the source link rather than by Save-Froms. Checking these first often solves the problem immediately:</p>
<ol>
<li>Open the link in a private browser window. If the platform asks you to sign in, the content is not public and cannot be processed.</li>
<li>Make sure the link points to one video, not a profile, playlist, channel or search page.</li>
<li>Read the <a href="{{ url('/download-troubleshooting') }}">troubleshooting guide</a>; it explains every error message the downloader shows.</li>
</ol>
<p>If the problem remains, tell us the platform, the exact link, the error text, your device and browser, and the approximate time it happened. That lets us check the provider logs for your request.</p>

<h2 id="never">What never to send</h2>
<div class="note warn"><strong>Do not send passwords, login codes or private account credentials</strong> for any platform, and do not send links to private content expecting us to retrieve it. Save-Froms only works with public links, our staff will never ask for your social-media login, and any message containing credentials is deleted unread.</div>

<h2 id="operator">Who operates Save-Froms</h2>
<p>Save-Froms (save-froms.net) is an independent web service. It is not affiliated with SaveFrom.net or with any of the platforms it supports. Learn more about the service, the people behind it and how the guides are written on the <a href="{{ url('/about') }}">About page</a>, and read how we handle your data in the <a href="{{ url('/privacy-policy') }}">Privacy Policy</a>.</p>
</div></div>
</div></section>
</main>
@endsection
@push('head')
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'ContactPage', 'name' => 'Contact Save-Froms', 'url' => url('/contact'), 'mainEntity' => ['@type' => 'Organization', '@id' => url('/').'#organization', 'name' => 'Save-Froms', 'url' => url('/'), 'contactPoint' => [['@type' => 'ContactPoint', 'contactType' => 'customer support', 'email' => $supportEmail], ['@type' => 'ContactPoint', 'contactType' => 'copyright and abuse', 'email' => $legalEmail]]]], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'Contact', 'item' => url('/contact')]]], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
