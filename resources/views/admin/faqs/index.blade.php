@extends('admin.layout')
@php($pageTitle = 'FAQs')
@section('content')
<div class="page-head"><div><h1>Frequently Asked Questions</h1><p>Manage public FAQ content, display order and visibility.</p></div><a class="primary-button" href="{{ route('admin.faqs.create') }}"><i class="bi bi-plus-lg"></i>Add FAQ</a></div>
<section class="panel"><div class="table-wrap"><table><thead><tr><th>Order</th><th>Question</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead><tbody>
@forelse($faqs as $faq)
<tr><td>{{ $faq->sort_order }}</td><td><strong>{{ $faq->question }}</strong><span class="muted">{{ Str::limit(strip_tags($faq->answer), 95) }}</span></td><td><span class="muted">{{ $faq->category ?: 'General' }}</span></td><td><span class="status-badge {{ $faq->is_active ? 'active' : 'inactive' }}">{{ $faq->is_active ? 'Active' : 'Inactive' }}</span></td><td><div class="row-actions"><a class="icon-button" href="{{ route('admin.faqs.edit', $faq) }}" title="Edit"><i class="bi bi-pencil"></i></a><form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" onsubmit="return confirm('Delete this FAQ?')">@csrf @method('DELETE')<button class="icon-button danger" title="Delete"><i class="bi bi-trash"></i></button></form></div></td></tr>
@empty
<tr><td colspan="5"><div class="empty">No FAQs added yet.</div></td></tr>
@endforelse
</tbody></table></div></section>
@endsection
