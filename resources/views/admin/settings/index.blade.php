@extends('layouts.admin')

@section('title', 'Settings')
@section('page-title', 'System Settings')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Sidebar Navigation -->
<div class="lg:col-span-1">
    <div class="admin-card">
        <h2 class="card-title">Settings</h2>

        <nav class="settings-nav">
            @foreach($groups as $key => $label)
                @php
                    $icon = $key === 'general' ? 'settings' :
                            ($key === 'mail' ? 'mail' :
                            ($key === 'payment' ? 'credit-card' :
                            ($key === 'sms' ? 'message-square' : 'file-text')));
                @endphp

                <a href="#{{ $key }}"
                   class="{{ $loop->first ? 'is-active' : '' }}"
                   data-settings-tab="{{ $key }}">
                    <i data-lucide="{{ $icon }}"></i>
                    <span>{{ $label }}</span>
                </a>
            @endforeach
        </nav>
    </div>
</div>
    
    <!-- Settings Content -->
    <div class="lg:col-span-3">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- General Settings -->
            <div class="admin-card mb-6" id="general">
                <h2 class="card-title">General Settings</h2>
                <input type="hidden" name="group" value="general">
                
                <div class="form-group">
                    <label class="form-label">Site Name</label>
                    <input type="text" name="settings[site_name]" class="form-input" value="{{ $settings['general']['site_name'] ?? 'CreatorPay' }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Site Description</label>
                    <textarea name="settings[site_description]" class="form-textarea" rows="2">{{ $settings['general']['site_description'] ?? '' }}</textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Support Email</label>
                        <input type="email" name="settings[support_email]" class="form-input" value="{{ $settings['general']['support_email'] ?? '' }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Support Phone</label>
                        <input type="text" name="settings[support_phone]" class="form-input" value="{{ $settings['general']['support_phone'] ?? '' }}">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Default Currency</label>
                        <select name="settings[default_currency]" class="form-select">
                            <option value="INR" {{ ($settings['general']['default_currency'] ?? 'INR') == 'INR' ? 'selected' : '' }}>INR (₹)</option>
                            <option value="USD" {{ ($settings['general']['default_currency'] ?? '') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="AED" {{ ($settings['general']['default_currency'] ?? '') == 'AED' ? 'selected' : '' }}>AED (د.إ)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Default Country</label>
                        <select name="settings[default_country]" class="form-select">
                            <option value="IN" {{ ($settings['general']['default_country'] ?? 'IN') == 'IN' ? 'selected' : '' }}>India</option>
                            <option value="AE" {{ ($settings['general']['default_country'] ?? '') == 'AE' ? 'selected' : '' }}>UAE</option>
                            <option value="US" {{ ($settings['general']['default_country'] ?? '') == 'US' ? 'selected' : '' }}>United States</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Mail Settings -->
            <div class="admin-card mb-6" id="mail">
                <h2 class="card-title">Email Settings</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Mail Driver</label>
                        <select name="settings[mail_driver]" class="form-select">
                            <option value="smtp" {{ ($settings['mail']['mail_driver'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="mailgun" {{ ($settings['mail']['mail_driver'] ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                            <option value="ses" {{ ($settings['mail']['mail_driver'] ?? '') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Mail Host</label>
                        <input type="text" name="settings[mail_host]" class="form-input" value="{{ $settings['mail']['mail_host'] ?? '' }}" placeholder="smtp.example.com">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Mail Port</label>
                        <input type="text" name="settings[mail_port]" class="form-input" value="{{ $settings['mail']['mail_port'] ?? '587' }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Mail Encryption</label>
                        <select name="settings[mail_encryption]" class="form-select">
                            <option value="tls" {{ ($settings['mail']['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ ($settings['mail']['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="" {{ ($settings['mail']['mail_encryption'] ?? '') == '' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Mail Username</label>
                        <input type="text" name="settings[mail_username]" class="form-input" value="{{ $settings['mail']['mail_username'] ?? '' }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Mail Password</label>
                        <input type="password" name="settings[mail_password]" class="form-input" value="{{ $settings['mail']['mail_password'] ?? '' }}">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">From Email</label>
                        <input type="email" name="settings[mail_from_address]" class="form-input" value="{{ $settings['mail']['mail_from_address'] ?? '' }}" placeholder="hello@example.com">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">From Name</label>
                        <input type="text" name="settings[mail_from_name]" class="form-input" value="{{ $settings['mail']['mail_from_name'] ?? 'CreatorPay' }}">
                    </div>
                </div>
            </div>
            
            <!-- Payment Settings -->
            <div class="admin-card mb-6" id="payment">
                <h2 class="card-title">Payment Settings</h2>
                
                <div class="form-group">
                    <label class="form-label">Payment Gateway</label>
                    <select name="settings[payment_gateway]" class="form-select">
                        <option value="razorpay" {{ ($settings['payment']['payment_gateway'] ?? 'razorpay') == 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                        <option value="stripe" {{ ($settings['payment']['payment_gateway'] ?? '') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Razorpay Key ID</label>
                        <input type="text" name="settings[razorpay_key_id]" class="form-input" value="{{ $settings['payment']['razorpay_key_id'] ?? '' }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Razorpay Key Secret</label>
                        <input type="password" name="settings[razorpay_key_secret]" class="form-input" value="{{ $settings['payment']['razorpay_key_secret'] ?? '' }}">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Stripe Public Key</label>
                        <input type="text" name="settings[stripe_public_key]" class="form-input" value="{{ $settings['payment']['stripe_public_key'] ?? '' }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Stripe Secret Key</label>
                        <input type="password" name="settings[stripe_secret_key]" class="form-input" value="{{ $settings['payment']['stripe_secret_key'] ?? '' }}">
                    </div>
                </div>
            </div>
            
            <!-- Invoice Settings -->
            <div class="admin-card mb-6" id="invoice">
                <h2 class="card-title">Invoice Settings</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Invoice Prefix</label>
                        <input type="text" name="settings[invoice_prefix]" class="form-input" value="{{ $settings['invoice']['invoice_prefix'] ?? 'INV-' }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Invoice Starting Number</label>
                        <input type="number" name="settings[invoice_starting_number]" class="form-input" value="{{ $settings['invoice']['invoice_starting_number'] ?? '1001' }}">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Default Tax Rate (%)</label>
                        <input type="number" step="0.01" name="settings[default_tax_rate]" class="form-input" value="{{ $settings['invoice']['default_tax_rate'] ?? '18' }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Invoice Due Days</label>
                        <input type="number" name="settings[invoice_due_days]" class="form-input" value="{{ $settings['invoice']['invoice_due_days'] ?? '30' }}">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Invoice Footer Text</label>
                    <textarea name="settings[invoice_footer]" class="form-textarea" rows="3">{{ $settings['invoice']['invoice_footer'] ?? '' }}</textarea>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i data-lucide="save"></i> Save All Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection