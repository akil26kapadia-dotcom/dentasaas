<?php

namespace App\Http\Controllers;

use App\Exceptions\PlanLimitException;
use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use App\Services\InvoiceService;
use App\Services\PlanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class InvoiceController extends Controller
{
    public function __construct(
        protected PlanService $planService,
        protected InvoiceService $invoiceService,
    ) {}

    public function index(Request $request): View
    {
        $clinic = tenant();

        $invoices = Invoice::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->input('q');
                $query->where(function ($query) use ($q) {
                    $query->where('patient_name', 'like', "%{$q}%")
                        ->orWhere('invoice_no', 'like', "%{$q}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->latest('invoice_date')
            ->get();

        $stats = [
            'paid_total' => $clinic->invoices()->where('status', 'paid')->sum('grand_total'),
            'unpaid_total' => $clinic->invoices()->where('status', 'unpaid')->sum('grand_total'),
            'count' => $clinic->invoices()->count(),
        ];

        return view('invoices.index', compact('invoices', 'stats'));
    }

    public function create(): View
    {
        $clinic = tenant();

        $patients = $clinic->patients()->orderBy('name')->get(['id', 'name', 'phone']);
        $services = $clinic->services()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'price']);

        return view('invoices.create', compact('patients', 'services'));
    }

    public function store(InvoiceRequest $request): RedirectResponse
    {
        $clinic = tenant();

        try {
            $this->planService->checkLimit($clinic, 'invoices');
        } catch (PlanLimitException $e) {
            return redirect()->back()->withInput()->withErrors(['plan_limit' => $e->getMessage()]);
        }

        $items = $request->input('items');

        $normalizedItems = collect($items)->map(fn ($item) => [
            'service' => $item['service'],
            'qty' => (int) $item['qty'],
            'price' => (float) $item['price'],
        ])->all();

        $totals = $this->invoiceService->calculateTotals($normalizedItems, (float) $request->input('tax_pct', 0), (float) $request->input('discount_pct', 0));

        $invoice = Invoice::create([
            'patient_id' => $request->input('patient_id'),
            'patient_name' => $request->input('patient_name'),
            'invoice_no' => $this->invoiceService->generateInvoiceNo($clinic),
            'invoice_date' => $request->input('invoice_date', today()),
            'items' => $normalizedItems,
            'tax_pct' => $request->input('tax_pct', 0),
            'discount_pct' => $request->input('discount_pct', 0),
            ...$totals,
            'status' => 'unpaid',
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load('patient');

        return view('invoices.show', compact('invoice'));
    }

    public function pdf(Invoice $invoice): Response
    {
        $clinic = tenant();

        abort_unless($this->planService->isFeatureAllowed($clinic, 'pdf'), 403, 'PDF export is not available on your current plan.');

        $invoice->load('patient');

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'clinic' => $clinic,
            'doctorName' => Auth::user()->name,
        ])->setPaper('a4');

        $filename = "invoice-{$invoice->invoice_no}.pdf";

        return request()->boolean('print')
            ? $pdf->stream($filename)
            : $pdf->download($filename);
    }

    public function updateStatus(Invoice $invoice): RedirectResponse
    {
        $invoice->update(['status' => $invoice->status === 'paid' ? 'unpaid' : 'paid']);

        return redirect()->back()->with('success', 'Invoice status updated.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
