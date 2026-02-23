@extends('layouts.tenant')

@section('title', 'Invoice Settings')
@section('page-title', 'Invoice Settings')

@section('content')
<div class="max-w-2xl">
    <div class="admin-card">
        <h2 class="card-title">Invoice Preferences</h2>

        <form action="{{ route('settings.update') }}" method="POST">
            @csrf

            <input type="hidden" name="group" value="invoice">

            <div class="form-group">
                <label class="form-label">Invoice Prefix</label>
                <input type="text" name="settings[invoice_prefix]" class="form-input"
                       value="{{ $tenant->data['invoice_prefix'] ?? 'INV-' }}">
            </div>

            <div class="form-group">
                <label class="form-label">Starting Number</label>
                <input type="number" name="settings[invoice_starting_number]" class="form-input"
                       value="{{ $tenant->data['invoice_starting_number'] ?? 1001 }}">
            </div>

            <div class="form-group">
                <label class="form-label">Default Tax Rate (%)</label>
                <input type="number" step="0.01" name="settings[default_tax_rate]" class="form-input"
                       value="{{ $tenant->data['default_tax_rate'] ?? 18 }}">
            </div>

            <div class="form-group">
                <label class="form-label">Invoice Footer Text</label>
                <textarea name="settings[invoice_footer]" class="form-textarea" rows="3">{{ $tenant->data['invoice_footer'] ?? '' }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Save Invoice Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection