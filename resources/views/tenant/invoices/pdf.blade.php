<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            background: #fff;
            padding: 40px;
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        
        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            color: #22c55e;
        }
        
        .invoice-number {
            font-size: 18px;
            color: #666;
            margin-top: 5px;
        }
        
        .company-info {
            text-align: right;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
        }
        
        .company-details {
            font-size: 12px;
            color: #666;
        }
        
        .billing-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .billing-to h4,
        .invoice-details h4 {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        
        .client-name {
            font-size: 16px;
            font-weight: bold;
        }
        
        .invoice-details {
            text-align: right;
        }
        
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .invoice-table th {
            background: #f5f5f5;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #ddd;
        }
        
        .invoice-table th:last-child {
            text-align: right;
        }
        
        .invoice-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .invoice-table td:last-child {
            text-align: right;
        }
        
        .totals {
            float: right;
            width: 300px;
        }
        
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        
        .totals-row.total {
            border-top: 2px solid #333;
            font-weight: bold;
            font-size: 18px;
            margin-top: 10px;
            padding-top: 15px;
        }
        
        .total-amount {
            color: #22c55e;
        }
        
        .notes-section {
            clear: both;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .notes-section h4 {
            font-size: 12px;
            color: #999;
            margin-bottom: 10px;
        }
        
        .notes-section p {
            font-size: 12px;
            color: #666;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-paid {
            background: #dcfce7;
            color: #16a34a;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }
        
        @media print {
            body {
                padding: 20px;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 30px; background: #22c55e; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            Print / Download PDF
        </button>
        <p style="margin-top: 10px; color: #666; font-size: 12px;">Use your browser's print function to save as PDF</p>
    </div>
    
    <div class="invoice-header">
        <div>
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">{{ $invoice->invoice_number }}</div>
            <div style="margin-top: 10px;">
                <span class="status-badge {{ $invoice->status === 'paid' ? 'status-paid' : 'status-pending' }}">
                    {{ strtoupper($invoice->status) }}
                </span>
            </div>
        </div>
        <div class="company-info">
            <div class="company-name">{{ $tenant->company_name ?? $tenant->name }}</div>
            <div class="company-details">
                @if($tenant->address){{ $tenant->address }}<br>@endif
                @if($tenant->city){{ $tenant->city }}, @endif
                @if($tenant->state){{ $tenant->state }}@endif
                @if($tenant->country) - {{ $tenant->country }}<br>@endif
                @if($tenant->phone)Phone: {{ $tenant->phone }}<br>@endif
                @if($tenant->email){{ $tenant->email }}<br>@endif
                @if($tenant->gst_number)GST: {{ $tenant->gst_number }}@endif
            </div>
        </div>
    </div>
    
    <div class="billing-section">
        <div class="billing-to">
            <h4>Bill To</h4>
            <div class="client-name">{{ $invoice->client_name }}</div>
            <div class="company-details">
                @if($invoice->client_email){{ $invoice->client_email }}<br>@endif
                @if($invoice->client_address){{ $invoice->client_address }}<br>@endif
                @if($invoice->client_gst)GST: {{ $invoice->client_gst }}@endif
            </div>
        </div>
        <div class="invoice-details">
            <h4>Invoice Details</h4>
            <div><strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('M d, Y') }}</div>
            <div><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</div>
            @if($invoice->campaign)
                <div><strong>Campaign:</strong> {{ $invoice->campaign->name }}</div>
            @endif
        </div>
    </div>
    
    <table class="invoice-table">
        <thead>
            <tr>
                <th style="width: 50%">Description</th>
                <th style="width: 15%; text-align: center;">Qty</th>
                <th style="width: 17%; text-align: right;">Rate</th>
                <th style="width: 18%; text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item['description'] }}</td>
                    <td style="text-align: center;">{{ $item['quantity'] }}</td>
                    <td style="text-align: right;">{{ $invoice->currency }} {{ number_format($item['rate'], 2) }}</td>
                    <td style="text-align: right;">{{ $invoice->currency }} {{ number_format($item['amount'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="totals">
        <div class="totals-row">
            <span>Subtotal</span>
            <span>{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</span>
        </div>
        
        @if($invoice->discount > 0)
            <div class="totals-row">
                <span>Discount</span>
                <span>-{{ $invoice->currency }} {{ number_format($invoice->discount_type === 'percent' ? ($invoice->subtotal * $invoice->discount / 100) : $invoice->discount, 2) }}</span>
            </div>
        @endif
        
        @if($invoice->tax_enabled && $invoice->tax_amount > 0)
            <div class="totals-row">
                <span>{{ strtoupper($invoice->tax_type) }} ({{ $invoice->tax_rate }}%)</span>
                <span>{{ $invoice->currency }} {{ number_format($invoice->tax_amount, 2) }}</span>
            </div>
        @endif
        
        @if($invoice->tds_applicable && $invoice->tds_amount > 0)
            <div class="totals-row">
                <span>TDS ({{ $invoice->tds_rate }}%)</span>
                <span>-{{ $invoice->currency }} {{ number_format($invoice->tds_amount, 2) }}</span>
            </div>
        @endif
        
        <div class="totals-row total">
            <span>Total</span>
            <span class="total-amount">{{ $invoice->currency }} {{ number_format($invoice->total_amount, 2) }}</span>
        </div>
        
        @if($invoice->paid_amount > 0)
            <div class="totals-row">
                <span>Paid</span>
                <span>-{{ $invoice->currency }} {{ number_format($invoice->paid_amount, 2) }}</span>
            </div>
            <div class="totals-row" style="font-weight: bold;">
                <span>Balance Due</span>
                <span>{{ $invoice->currency }} {{ number_format($invoice->balance_amount, 2) }}</span>
            </div>
        @endif
    </div>
    
    <div style="clear: both;"></div>
    
    @if($invoice->notes || $invoice->terms)
        <div class="notes-section">
            @if($invoice->notes)
                <h4>Notes</h4>
                <p>{{ $invoice->notes }}</p>
            @endif
            
            @if($invoice->terms)
                <h4 style="margin-top: 20px;">Terms & Conditions</h4>
                <p>{{ $invoice->terms }}</p>
            @endif
        </div>
    @endif
</body>
</html>