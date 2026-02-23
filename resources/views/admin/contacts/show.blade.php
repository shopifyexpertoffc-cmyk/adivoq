@extends('layouts.admin')

@section('title', 'View Enquiry')
@section('page-title', 'Contact Enquiry')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="admin-card">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold">{{ $contact->subject }}</h2>
                    <p class="text-gray-400 mt-1">From: {{ $contact->name }} &lt;{{ $contact->email }}&gt;</p>
                </div>
                <span class="badge badge-{{ $contact->status == 'new' ? 'danger' : ($contact->status == 'replied' ? 'success' : 'secondary') }}">
                    {{ ucfirst($contact->status) }}
                </span>
            </div>
            
            <div class="bg-white/5 rounded-xl p-6 mb-6">
                <p class="whitespace-pre-wrap">{{ $contact->message }}</p>
            </div>
            
            <div class="border-t border-white/10 pt-6">
                <h3 class="font-semibold mb-4">Reply</h3>
                <form action="{{ route('admin.contacts.reply', $contact) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="reply_message" class="form-textarea" rows="5" placeholder="Type your reply..."></textarea>
                        @error('reply_message')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="send"></i> Send Reply
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div>
        <div class="admin-card">
            <h3 class="card-title">Details</h3>
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Name</span>
                    <span class="info-value">{{ $contact->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $contact->email }}</span>
                </div>
                @if($contact->phone)
                <div class="info-item">
                    <span class="info-label">Phone</span>
                    <span class="info-value">{{ $contact->phone }}</span>
                </div>
                @endif
                <div class="info-item">
                    <span class="info-label">Received</span>
                    <span class="info-value">{{ $contact->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">IP Address</span>
                    <span class="info-value font-mono text-sm">{{ $contact->ip_address ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
        
        <div class="admin-card mt-6">
            <h3 class="card-title">Actions</h3>
            <div class="space-y-3">
                <a href="mailto:{{ $contact->email }}" class="btn btn-outline btn-block">
                    <i data-lucide="mail"></i> Email Directly
                </a>
                <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Delete this enquiry?')">
                        <i data-lucide="trash-2"></i> Delete Enquiry
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection