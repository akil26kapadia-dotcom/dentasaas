<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Invoices</h2>
            <a href="{{ route('invoices.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                <i class="fa-solid fa-plus"></i> New Invoice
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        <!-- Stats bar -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Paid</p>
                <p class="text-2xl font-semibold text-green-600 mt-1">₹{{ number_format($stats['paid_total'], 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Unpaid</p>
                <p class="text-2xl font-semibold text-red-600 mt-1">₹{{ number_format($stats['unpaid_total'], 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Invoices</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white mt-1">{{ $stats['count'] }}</p>
            </div>
        </div>

        <form method="GET" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4 mb-6 flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <x-input-label for="q" value="Search" />
                <x-text-input id="q" name="q" class="block mt-1 w-full" placeholder="Patient name or invoice no." :value="request('q')" />
            </div>
            <div>
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" class="block mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 rounded-lg bg-gray-800 text-white text-sm font-medium hover:bg-gray-900">Filter</button>
            @if (request()->anyFilled(['q', 'status']))
                <a href="{{ route('invoices.index') }}" class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">Clear</a>
            @endif
        </form>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
            @if ($invoices->isEmpty())
                <div class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 dark:bg-gray-700 text-indigo-600 text-2xl mb-4">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">No invoices found.</p>
                    <a href="{{ route('invoices.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                        <i class="fa-solid fa-plus"></i> Create your first invoice
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table id="invoices-table" class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 dark:text-gray-400">
                                <th class="py-2">Invoice No</th>
                                <th class="py-2">Patient</th>
                                <th class="py-2">Date</th>
                                <th class="py-2">Items</th>
                                <th class="py-2">Grand Total</th>
                                <th class="py-2">Status</th>
                                <th class="py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $invoice)
                                <tr class="border-t border-gray-100 dark:border-gray-700">
                                    <td class="py-2">
                                        <a href="{{ route('invoices.show', $invoice) }}" class="font-bold" style="color:#465fff;">{{ $invoice->invoice_no }}</a>
                                    </td>
                                    <td class="py-2">{{ $invoice->patient_name }}</td>
                                    <td class="py-2">{{ $invoice->invoice_date->format('d M Y') }}</td>
                                    <td class="py-2">{{ count($invoice->items) }} item{{ count($invoice->items) === 1 ? '' : 's' }}</td>
                                    <td class="py-2 font-medium">{{ $invoice->formatted_total }}</td>
                                    <td class="py-2"><x-status-badge :status="$invoice->status" /></td>
                                    <td class="py-2">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('invoices.show', $invoice) }}" title="View" class="text-indigo-600 hover:text-indigo-800"><i class="fa-solid fa-eye"></i></a>
                                            <a href="{{ route('invoices.pdf', $invoice) }}" title="Download PDF" class="text-gray-500 hover:text-gray-700"><i class="fa-solid fa-file-pdf"></i></a>
                                            <form method="POST" action="{{ route('invoices.status', $invoice) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" title="{{ $invoice->status === 'paid' ? 'Mark Unpaid' : 'Mark Paid' }}" class="text-green-600 hover:text-green-800">
                                                    <i class="fa-solid fa-money-bill-wave"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('invoices.destroy', $invoice) }}" onsubmit="return confirm('Delete this invoice?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Delete" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (document.getElementById('invoices-table')) {
                    $('#invoices-table').DataTable({ pageLength: 10 });
                }
            });
        </script>
    @endpush
</x-app-layout>
