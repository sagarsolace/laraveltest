<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Invoice;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class Invoices extends Component
{
    public $invoice_number, $customer_email, $amount, $status = 'unpaid', $invoiceId;
    public $isModalOpen = false;
    public $statusFilter = '';

    protected $listeners = ['invoiceUpdated' => 'render'];

    protected $rules = [
        'invoice_number' => 'required',
        'customer_email' => 'required|email',
        'amount' => 'required|numeric',
        'status' => 'required',
    ];


public function render()
{
    $query = Invoice::query();

    // Apply filter dynamically
    if (!empty($this->statusFilter)) {
        $query->where('status', $this->statusFilter);
    }

    return view('livewire.invoices', [
        'invoices' => $query->latest()->get(), 
        'statuses' => Invoice::select('status')->distinct()->pluck('status'),
    ]);
}




public function downloadPdf($id)
{
    $invoice = Invoice::findOrFail($id);

    $pdf = Pdf::loadView('pdf.invoice', compact('invoice')); // Create a Blade view for PDF
    return response()->streamDownload(fn() => print($pdf->output()), "invoice_{$invoice->invoice_number}.pdf");
}
public function duplicateInvoice($id)
{
    $invoice = Invoice::findOrFail($id);

    // Count how many invoices already have this number (including duplicates)
    $count = Invoice::where('invoice_number', 'LIKE', "{$invoice->invoice_number}%")->count();

    // Generate a new invoice number with a suffix (e.g., INV-100 → INV-100-D1, INV-100-D2)
    $newInvoiceNumber = $invoice->invoice_number . '-D' . $count;

    Invoice::create([
        'invoice_number' => $newInvoiceNumber, // Use unique invoice number
        'customer_email' => $invoice->customer_email,
        'amount' => $invoice->amount,
        'status' => $invoice->status,
    ]);

    session()->flash('message', 'Invoice duplicated successfully!');
}



public function deleteInvoice($id)
{
    Invoice::findOrFail($id)->delete();
    session()->flash('message', 'Invoice deleted successfully!');
}

    public function create()
    {
        $this->resetFields();
        $this->openModal();
    }

    public function store()
{
    $this->validate([
        'invoice_number' => 'required|unique:invoices,invoice_number,' . $this->invoiceId,
        'customer_email' => 'required|email',
        'amount' => 'required|numeric|min:1',
        'status' => 'required|in:draft,unpaid,past due,outstanding',
    ]);

    Invoice::updateOrCreate(['id' => $this->invoiceId], [
        'invoice_number' => $this->invoice_number,
        'customer_email' => $this->customer_email,
        'amount' => $this->amount,
        'status' => $this->status,
    ]);

    session()->flash('message', $this->invoiceId ? 'Invoice updated successfully.' : 'Invoice created successfully.');

    $this->closeModal();
    $this->dispatch('invoiceUpdated');
}


   public function edit($id)
{
    $invoice = Invoice::findOrFail($id);

    $this->invoiceId = $invoice->id;
    $this->invoice_number = $invoice->invoice_number;
    $this->customer_email = $invoice->customer_email;
    $this->amount = $invoice->amount;
    $this->status = $invoice->status;

    $this->isModalOpen = true;

    // Livewire v3 event dispatching
    $this->dispatch('refreshModal');
}

    private function resetFields()
    {
        $this->invoiceId = null;
        $this->invoice_number = '';
        $this->customer_email = '';
        $this->amount = '';
        $this->status = 'unpaid';
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetFields();
    }
}
