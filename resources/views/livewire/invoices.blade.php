<div class="p-6 bg-white rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-4">
        <select wire:model.live="statusFilter" class="p-2 border border-gray-300 rounded-md shadow-sm">
              <option value="">All</option>
                <option value="draft">Draft</option>
                <option value="unpaid">Unpaid</option>
                <option value="past due">Past Due</option>
                <option value="outstanding">Outstanding</option>
        </select>

        <button wire:click="create" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600">
            Create Invoice
        </button>
    </div>

    @if(session()->has('message'))
        <div class="mt-2 p-3 bg-green-100 text-green-800 rounded-md shadow-md">
            {{ session('message') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 rounded-lg shadow-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-300 px-4 py-2 text-left">Invoice No</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Amount</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                    <tr wire:key="invoice-{{ $invoice->id }}" class="border border-gray-300">
                        <td class="border border-gray-300 px-4 py-2">{{ $invoice->invoice_number }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $invoice->customer_email }}</td>
                        <td class="border border-gray-300 px-4 py-2">${{ number_format($invoice->amount, 2) }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <span class="px-2 py-1 text-sm font-semibold 
                                {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} 
                                rounded">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td class="border border-gray-300 px-4 py-2 space-x-2">
                            <button wire:click="edit({{ $invoice->id }})" 
                                class="px-3 py-1 bg-yellow-500 text-dark rounded-lg hover:bg-yellow-600">
                                Edit
                            </button>
                            <button wire:click="downloadPdf({{ $invoice->id }})" 
                                class="px-3 py-1 bg-green-500 text-dark rounded-lg hover:bg-green-600">
                                Download PDF
                            </button>
                            <button wire:click="duplicateInvoice({{ $invoice->id }})" 
                                class="px-3 py-1 bg-blue-500 text-dark rounded-lg hover:bg-blue-600">
                                Duplicate
                            </button>
                            <button wire:click="deleteInvoice({{ $invoice->id }})" 
                                class="px-3 py-1 bg-red-500 text-dark rounded-lg hover:bg-red-600">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($isModalOpen)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-xl font-semibold mb-4">
                    {{ $invoiceId ? 'Edit Invoice' : 'Create Invoice' }}
                </h2>

                <input type="text" wire:model="invoice_number" placeholder="Invoice Number" 
                        class="w-full p-2 border border-gray-300 rounded-md mb-2 shadow-sm">
                    @error('invoice_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    <input type="email" wire:model="customer_email" placeholder="Email" 
                        class="w-full p-2 border border-gray-300 rounded-md mb-2 shadow-sm">
                    @error('customer_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    <input type="number" wire:model="amount" placeholder="Amount" 
                        class="w-full p-2 border border-gray-300 rounded-md mb-2 shadow-sm">
                    @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    <select wire:model="status" class="w-full p-2 border border-gray-300 rounded-md mb-2 shadow-sm">
                        <option value="draft">Draft</option>
                        <option value="unpaid">Unpaid</option>
                        <option value="past due">Past Due</option>
                        <option value="outstanding">Outstanding</option>
                    </select>

                <div class="flex justify-end space-x-2">
                    <button wire:click="store" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600">
                        Save
                    </button>
                    <button wire:click="closeModal" class="px-4 py-2 bg-gray-500 text-white rounded-lg shadow-md hover:bg-gray-600">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

    @endif
</div>


<script src="https://unpkg.com/@tailwindcss/browser@4"></script>




