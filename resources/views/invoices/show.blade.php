<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Invoice {{ $invoice->invoice_no }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $invoice->invoice_date->format('d M Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('invoices.pdf', $invoice) }}?print=1" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-50">
                    <i class="fa-solid fa-print"></i> Print
                </a>
                <a href="{{ route('invoices.pdf', $invoice) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-50">
                    <i class="fa-solid fa-file-pdf"></i> Download PDF
                </a>
                <form method="POST" action="{{ route('invoices.status', $invoice) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700">
                        <i class="fa-solid fa-money-bill-wave"></i>
                        {{ $invoice->status === 'paid' ? 'Mark Unpaid' : 'Mark Paid' }}
                    </button>
                </form>
                <a href="{{ route('invoices.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}</div>
        @endif

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Bill To</p>
                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ $invoice->patient_name }}</p>
                    @if ($invoice->patient?->phone)
                        <p class="text-sm text-gray-500">{{ $invoice->patient->phone }}</p>
                    @endif
                </div>
                <x-status-badge :status="$invoice->status" />
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-100">
                            <th class="py-2">#</th>
                            <th class="py-2">Description</th>
                            <th class="py-2 text-right">Qty</th>
                            <th class="py-2 text-right">Rate</th>
                            <th class="py-2 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->items as $index => $item)
                            <tr class="border-b border-gray-50">
                                <td class="py-2">{{ $index + 1 }}</td>
                                <td class="py-2">{{ $item['service'] }}</td>
                                <td class="py-2 text-right">{{ $item['qty'] }}</td>
                                <td class="py-2 text-right">₹{{ number_format($item['price'], 2) }}</td>
                                <td class="py-2 text-right font-medium">
                                    ₹{{ number_format($item['qty'] * $item['price'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mt-6">
                <dl class="w-full max-w-xs space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Subtotal</dt>
                        <dd class="text-gray-800">₹{{ number_format($invoice->subtotal, 2) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Discount ({{ $invoice->discount_pct }}%)</dt>
                        <dd class="text-red-600">- ₹{{ number_format($invoice->discount_amount, 2) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">GST ({{ $invoice->tax_pct }}%)</dt>
                        <dd class="text-gray-800">+ ₹{{ number_format($invoice->tax_amount, 2) }}</dd>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-100 text-base font-semibold">
                        <dt class="text-gray-900">Grand Total</dt>
                        <dd style="color:#465fff;">{{ $invoice->formatted_total }}</dd>
                    </div>
                </dl>
            </div>

            @if ($invoice->notes)
                <div class="mt-6 rounded-lg bg-amber-50 border border-amber-200 px-4 py-3 text-sm text-amber-800">
                    <p class="font-medium mb-1">Notes</p>
                    <p>{{ $invoice->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
