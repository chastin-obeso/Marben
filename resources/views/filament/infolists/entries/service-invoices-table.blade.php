


<div class="overflow-x-auto rounded-lg border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left font-medium text-gray-700">Invoice #</th>
                <th class="px-4 py-2 text-left font-medium text-gray-700">Payment Date</th>
                <th class="px-4 py-2 text-left font-medium text-gray-700">Amount Paid</th>
                <th class="px-4 py-2 text-left font-medium text-gray-700">Payment Type</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($getRecord()->serviceInvoices as $invoice)
                <tr>
                    <td class="px-4 py-2">{{ $invoice->service_invoice_number }}</td>
                    <td class="px-4 py-2">{{ $invoice->payment_date}}</td>
                    <td class="px-4 py-2">₱{{ number_format($invoice->amount_paid, 2) }}</td>
                    <td class="px-4 py-2">{{ $invoice->payment_type }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-3 text-center text-gray-500">No service invoices found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
