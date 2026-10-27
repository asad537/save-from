@extends('layout')
@php($title = 'Page Not Found | Save-Froms')
@php($description = 'That page does not exist on Save-Froms. Paste a public video link to download it, or open one of the platform downloaders and guides below.')
@php($robots = 'noindex,follow')
@section('content')
<style>
.nf-hero{padding:70px 0 40px;text-align:center;background:linear-gradient(180deg,#f8fcff,#edf7ff)}.nf-hero h1{margin:14px 0 10px;font-size:44px;letter-spacing:-1.4px}.nf-hero p{max-width:620px;margin:auto;color:#5d7390;line-height:1.7}.nf-search{max-width:640px;margin:26px auto 0}.nf-links{padding:44px 0 70px}.nf-links h2{text-align:center;margin:0 0 18px;font-size:26px}.nf-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;max-width:900px;margin:auto}.nf-grid a{display:flex;align-items:center;justify-content:space-between;padding:15px 17px;border:1px solid #dce7f3;border-radius:12px;background:#f8fbff;color:#1d3b62;font-weight:800}.nf-grid a:hover{border-color:#91baff;background:#edf5ff;color:#2166f3}@media(max-width:700px){.nf-hero h1{font-size:32px}.nf-grid{grid-template-columns:1fr}}
</style>
<main>
<section class="nf-hero"><div class="wrap"><div class="eyebrow"><i class="bi bi-x-circle"></i>&nbsp; Error 404</div><h1>That page is not here</h1><p>The address may be mistyped, or the page was moved. If you came to download a video, paste its public link below and the result appears in a few seconds.</p>
<div class="nf-search"><form class="search" method="POST" action="{{ route('downloader.analyze') }}">@csrf<input name="url" type="url" placeholder="Paste a public video link..." required><button class="btn"><i class="bi bi-download"></i>&nbsp; Download</button></form></div></div></section>
<section class="nf-links"><div class="wrap"><h2>Popular downloaders and guides</h2><div class="nf-grid">
<a href="{{ url('/youtube-downloader') }}"><span>YouTube Video Downloader</span><i class="bi bi-arrow-right"></i></a>
<a href="{{ url('/instagram-downloader') }}"><span>Instagram Video Downloader</span><i class="bi bi-arrow-right"></i></a>
<a href="{{ url('/tiktok-downloader') }}"><span>TikTok Video Downloader</span><i class="bi bi-arrow-right"></i></a>
<a href="{{ url('/facebook-downloader') }}"><span>Facebook Video Downloader</span><i class="bi bi-arrow-right"></i></a>
<a href="{{ url('/youtube-to-mp4') }}"><span>YouTube to MP4</span><i class="bi bi-arrow-right"></i></a>
<a href="{{ url('/youtube-mp3-downloader') }}"><span>YouTube to MP3</span><i class="bi bi-arrow-right"></i></a>
<a href="{{ route('supported-sites') }}"><span>All supported sites</span><i class="bi bi-arrow-right"></i></a>
<a href="{{ route('blog') }}"><span>Download guides</span><i class="bi bi-arrow-right"></i></a>
<a href="{{ url('/download-troubleshooting') }}"><span>Download not working?</span><i class="bi bi-arrow-right"></i></a>
</div></div></section>
</main>
@endsection
