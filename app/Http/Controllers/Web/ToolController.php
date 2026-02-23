<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function taxCalculator()
    {
        return view('web.tools.tax-calculator');
    }

    public function calculate(Request $request)
    {
        $data = $request->validate([
            'country' => 'required|string',
            'income'  => 'required|numeric|min:0',
            'expenses'=> 'nullable|numeric|min:0',
        ]);

        $income   = $data['income'];
        $expenses = $data['expenses'] ?? 0;
        $taxable  = max(0, $income - $expenses);

        // Very simple placeholder logic, you can improve:
        $rate = $data['country'] === 'AE' ? 5 : 18;
        $tax  = round($taxable * $rate / 100, 2);

        return back()->withInput()->with([
            'tax_result' => [
                'taxable' => $taxable,
                'rate'    => $rate,
                'tax'     => $tax,
            ],
        ]);
    }

    public function freeInvoice()
    {
        return view('web.tools.free-invoice');
    }

    public function generateFreeInvoice(Request $request)
    {
        $data = $request->validate([
            'your_name'    => 'required|string|max:255',
            'your_address' => 'nullable|string|max:500',
            'client_name'  => 'required|string|max:255',
            'client_email' => 'nullable|email',
            'items'        => 'required|array|min:1',
            'items.*.desc' => 'required|string|max:255',
            'items.*.qty'  => 'required|numeric|min:1',
            'items.*.rate' => 'required|numeric|min:0',
        ]);

        // Compute totals
        $subtotal = 0;
        foreach ($data['items'] as &$item) {
            $item['amount'] = $item['qty'] * $item['rate'];
            $subtotal += $item['amount'];
        }

        $total = $subtotal; // no tax here for free tool

        return view('web.tools.free-invoice-preview', [
            'data'     => $data,
            'items'    => $data['items'],
            'subtotal' => $subtotal,
            'total'    => $total,
        ]);
    }
}