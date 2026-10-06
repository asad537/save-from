@extends('admin.layout')
@section('content')
<div class="page-head"><div><h1>{{ $pageTitle }}</h1><p>Create helpful answers for the public FAQ page.</p></div></div>
<form class="panel form-panel" method="POST" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}">
@csrf @if($faq->exists)@method('PUT')@endif
<div class="form-grid">
    <div class="field full"><label>Question *</label><input name="question" maxlength="255" value="{{ old('question', $faq->question) }}" required></div>
    <div class="field"><label>Category</label><input name="category" maxlength="100" value="{{ old('category', $faq->category) }}" placeholder="Getting Started"></div>
    <div class="field"><label>Display order</label><input name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order', $faq->sort_order ?: 0) }}"></div>
    <div class="field full"><label>Answer *</label><textarea id="faq-answer-editor" name="answer" required>{{ old('answer', $faq->answer) }}</textarea><span class="hint">Use clear paragraphs, lists and links where helpful.</span></div>
    <div class="field full check-field"><input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $faq->is_active) ? 'checked' : '' }}><label for="is_active" style="margin:0">Show this FAQ on the public website</label></div>
</div>
<div class="form-actions"><a class="secondary-button" href="{{ route('admin.faqs.index') }}">Cancel</a><button class="primary-button" type="submit"><i class="bi bi-check2-circle"></i>{{ $faq->exists ? 'Update FAQ' : 'Add FAQ' }}</button></div>
</form>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@5.10.9/tinymce.min.js"></script>
<script>tinymce.init({selector:'#faq-answer-editor',height:300,menubar:false,plugins:'advlist autolink lists link preview code wordcount',toolbar:'undo redo | formatselect | bold italic underline | bullist numlist | link | blockquote code | removeformat',content_style:'body{font-family:Arial,sans-serif;font-size:16px;line-height:1.7;padding:15px}',relative_urls:false,remove_script_host:false});</script>
@endpush
