@extends('layout')
@php($title = 'Privacy Policy | Save-Froms')
@php($description = 'How Save-Froms handles submitted links, request logs, cookies, analytics, temporary download links and third-party providers, how long data is kept and how to contact us.')
@php($supportEmail = config('app.support_email'))
@php($legalEmail = config('app.legal_email'))
@section('content')
@include('partials.static-page-style')
<main>
<section class="static-hero"><div class="wrap"><div class="eyebrow"><i class="bi bi-shield-lock"></i>&nbsp; Legal</div><h1>Privacy Policy</h1><p>This policy explains exactly what Save-Froms records when you visit the site or download a file, why, how long it is kept, and what we never collect. It is written to match how the service is actually built.</p><div class="static-meta"><span><i class="bi bi-calendar3"></i> Last updated: October 8, 2026</span><span><i class="bi bi-globe2"></i> Applies to save-froms.net</span></div></div></section>
<section class="static-stage"><div class="wrap static-layout">
<aside class="static-toc"><strong><i class="bi bi-list-nested"></i> On this page</strong><a href="#summary">Summary</a><a href="#collect">What we collect</a><a href="#links">Submitted links</a><a href="#files">Downloaded files</a><a href="#cookies">Cookies</a><a href="#third">Third parties</a><a href="#retention">Retention</a><a href="#rights">Your rights</a><a href="#children">Children</a><a href="#changes">Changes</a><a href="#contact">Contact</a></aside>
<div class="static-card"><div class="static-content">
<h2 id="summary">Summary</h2>
<ul>
<li>No account, name, email or password is required or collected to use the downloader.</li>
<li>We keep basic technical logs (IP address, browser, page, referrer, time) for security and analytics.</li>
<li>The link you submit is sent to our media-processing provider so it can read the public page; it is not published or shared further.</li>
<li>Downloaded files are streamed through our server to you and are <strong>not stored</strong>.</li>
<li>One first-party cookie counts a single visit per browser per day. There are no advertising cookies.</li>
</ul>

<h2 id="collect">What we collect and why</h2>
<table>
<thead><tr><th>Data</th><th>When</th><th>Why</th></tr></thead>
<tbody>
<tr><td>IP address</td><td>Every request</td><td>Rate limiting (for example 20 analyses per minute), abuse prevention, security logs</td></tr>
<tr><td>Browser user agent</td><td>Every request</td><td>Compatibility and abuse detection</td></tr>
<tr><td>Page URL and referrer</td><td>Page views</td><td>Understanding which pages and search terms bring visitors</td></tr>
<tr><td>Anonymous visitor key</td><td>Page views</td><td>Counting one visit and one page view per browser per day without identifying you</td></tr>
<tr><td>Platform, media title, format and quality</td><td>Completed downloads</td><td>Understanding which platforms and formats are used and which fail</td></tr>
<tr><td>Timestamp</td><td>All of the above</td><td>Ordering and expiring records</td></tr>
<tr><td>Error reports</td><td>When something breaks</td><td>Application logs may contain the request URL and error details for debugging</td></tr>
</tbody>
</table>
<p>These records are visible only inside the password-protected administrator area and are used in aggregate. We do not build profiles of individual visitors.</p>

<h2 id="links">Submitted links</h2>
<p>When you paste a link, Save-Froms checks that its domain belongs to a supported platform and then forwards the public URL to a third-party media-processing API so it can read the public page and report the available renditions. The provider receives the URL and the technical request; it does not receive your IP address directly from us for the analysis step. A temporary record of the result is held in our cache for about 20 minutes so your download buttons work, then it is discarded.</p>

<h2 id="files">Downloaded files</h2>
<p>When you click Download, our server fetches the file from the provider and streams it to your browser in real time. The file is not written to our disks, is not kept after your download finishes or fails, and cannot be retrieved later by anyone. We record only that a download of a given platform, format and quality took place.</p>

<h2 id="cookies">Cookies and local storage</h2>
<table>
<thead><tr><th>Cookie</th><th>Purpose</th><th>Lifetime</th></tr></thead>
<tbody>
<tr><td><code>sf_visitor</code></td><td>Random identifier used to count one visit per browser per day. It is hashed before storage and contains no personal data.</td><td>1 year</td></tr>
<tr><td>Laravel session cookie</td><td>Keeps your download result and form input between the analysis request and the result page; also carries the CSRF token that protects forms.</td><td>Browser session (up to 2 hours)</td></tr>
<tr><td><code>XSRF-TOKEN</code></td><td>Security token for form submissions.</td><td>Browser session</td></tr>
</tbody>
</table>
<p>Save-Froms currently uses no third-party analytics scripts and no advertising network. If that changes, this policy and a consent notice will be updated first. The language switcher may remember your choice in your browser's local storage; that value never leaves your device.</p>

<h2 id="third">Third-party services</h2>
<ul>
<li><strong>Media-processing provider:</strong> receives submitted public URLs and serves the media stream, as described above.</li>
<li><strong>Google Fonts and jsDelivr:</strong> deliver fonts and icon files; your browser requests them directly, so those services see your IP address and user agent under their own policies.</li>
<li><strong>Hosting provider:</strong> operates the server and may keep standard access logs.</li>
</ul>
<p>We do not sell, rent or trade any data, and we do not share logs with third parties except when legally required or necessary to investigate abuse of the service.</p>

<h2 id="retention">How long we keep data</h2>
<ul>
<li>Cached analysis results and download tokens: about 20 minutes.</li>
<li>Session data: until the browser session ends or 2 hours of inactivity.</li>
<li>Analytics and download records: up to 24 months, then deleted or fully anonymised.</li>
<li>Application error logs: rotated and deleted on a rolling basis, normally within 30 days.</li>
<li>Email correspondence: for as long as needed to resolve the request, then deleted.</li>
</ul>

<h2 id="rights">Your rights</h2>
<p>Depending on where you live, you may have the right to access, correct or delete personal data we hold, to object to processing, or to lodge a complaint with a supervisory authority. Because we hold almost no data that identifies you, a request usually involves deleting log entries for a given IP address and time range. Email <a href="mailto:{{ $legalEmail }}">{{ $legalEmail }}</a> and we will respond within 30 days.</p>

<h2 id="children">Children</h2>
<p>Save-Froms is not directed at children under 13 (or the higher age required by local law) and does not knowingly collect data from them. If you believe a child has provided us with personal data, contact us and we will delete it.</p>

<h2 id="changes">Changes to this policy</h2>
<p>We will update this page when the service changes. The date at the top shows the current version. Material changes, such as adding analytics or advertising, will be announced on the site before they take effect.</p>

<h2 id="contact">Contact</h2>
<p>Privacy questions and data requests: <a href="mailto:{{ $legalEmail }}">{{ $legalEmail }}</a>. General support: <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>. All channels are listed on the <a href="{{ url('/contact') }}">Contact page</a>. See also the <a href="{{ url('/terms-of-service') }}">Terms of Service</a>.</p>
</div></div>
</div></section>
</main>
@endsection
@push('head')
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'Privacy Policy', 'item' => url('/privacy-policy')]]], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
