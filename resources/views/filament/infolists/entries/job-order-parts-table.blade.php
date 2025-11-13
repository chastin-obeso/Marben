

@php
    // Get the array of data stored in the 'parts_data' field state
    $parts = $getState() ?? [];
@endphp

<div class="overflow-x-auto rounded-lg border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left font-medium text-gray-700">Name</th>
                <th class="px-4 py-2 text-left font-medium text-gray-700">Unit Price</th>
                <th class="px-4 py-2 text-left font-medium text-gray-700">Quantity</th>
                <th class="px-4 py-2 text-left font-medium text-gray-700">Total Price</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">

                @forelse ($parts as $part)
                <tr>
                    <td class="px-4 py-2">{{ $part['name'] }}</td>
                    <td class="px-4 py-2">₱{{ number_format($part['unit_price'], 2) }}</td>
                    <td class="px-4 py-2">{{ $part['quantity'] }}</td>
                    <td class="px-4 py-2">₱{{ number_format($part['total_price'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-3 text-center text-gray-500">No unbilled job order parts.</td>
                </tr>
            @endforelse

            
        </tbody>
    </table>
</div>
