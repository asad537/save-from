@extends('admin.layout')
@php($pageTitle = 'Homepage SEO Content')
@section('content')
<div class="page-head"><div><h1>Homepage SEO Content</h1><p>Edit the helpful content and Google search snippet shown for the homepage.</p></div><a class="secondary-button" href="{{ route('home') }}" target="_blank"><i class="bi bi-box-arrow-up-right"></i>&nbsp; Preview Homepage</a></div>
<form class="panel form-panel" method="POST" action="{{ route('admin.homepage.update') }}">@csrf @method('PUT')
<div class="form-grid">
<div class="field full"><label>Section heading *</label><input name="heading" value="{{ old('heading', $homepage->heading) }}" maxlength="190" required></div>
<div class="field full"><label>Short introduction</label><textarea name="intro" maxlength="1000">{{ old('intro', $homepage->intro) }}</textarea></div>
<div class="field full"><label>Homepage content *</label><textarea id="homepage-content-editor" name="content">{{ old('content', $homepage->content) }}</textarea><span class="hint">Use headings, short paragraphs, lists, tables and internal links. Keep the text useful and original.</span></div>
<div class="field"><label>Homepage meta title</label><input name="meta_title" value="{{ old('meta_title', $homepage->meta_title) }}" maxlength="70"><span class="hint">Recommended: 50–60 characters.</span></div>
<div class="field"><label>Homepage meta description</label><textarea name="meta_description" maxlength="180">{{ old('meta_description', $homepage->meta_description) }}</textarea><span class="hint">Recommended: 140–160 characters.</span></div>
<div class="field full check-field"><input id="is-active" name="is_active" type="checkbox" value="1" {{ old('is_active', $homepage->is_active) ? 'checked' : '' }}><label for="is-active" style="margin:0">Show this content on the homepage</label></div>
</div>
<div class="form-actions"><button class="primary-button" type="submit"><i class="bi bi-check2-circle"></i> Save Homepage Content</button></div>
</form>
@endsection
@push('scripts')<script src="https://cdn.jsdelivr.net/npm/tinymce@5.10.9/tinymce.min.js"></script><script>tinymce.init({selector:'#homepage-content-editor',height:620,menubar:true,plugins:'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste help wordcount',toolbar:'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image media table | blockquote code fullscreen | removeformat',content_style:'body{font-family:Arial,sans-serif;font-size:16px;line-height:1.75;padding:15px}',relative_urls:false,remove_script_host:false});</script>@endpush
