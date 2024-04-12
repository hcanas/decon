<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css'])
</head>
<body>
    <div class="flex flex-col space-y-12">
        <div class="flex items-start justify-between">
            <div class="flex flex-col space-y-1">
                <span>Logo</span>
                <span class="text-sm">Code: {{ $customer->code }}</span>
                <span class="text-sm">Email: {{ $customer->email }}</span>
                <span class="text-sm">Contact #: {{ $customer->contact_number }}</span>
                <span class="text-sm">Viber ID: {{ $customer->viber_id }}</span>
            </div>
            <span>{{ $created_at }}</span>
        </div>

        @php
            $total = 0;
        @endphp
        <table class="table-auto border-collapse">
            <tr>
                <th class="text-left p-2 border">Product</th>
                <th class="text-right p-2 border">Qty</th>
                <th class="text-right p-2 border">Price</th>
                <th class="text-right p-2 border">Total</th>
            </tr>
            @foreach($items AS $item)
                @php
                    if ($item->status === 'available') {
                        $total += ($item->qty * $item->price);
                    }
                @endphp
                <tr>
                    <td class="p-2 border @php echo $item->status === 'unavailable' ? 'text-gray-400 line-through' : '' @endphp">
                        <div class="flex flex-col">
                            <span>{{ $item->product->name }} ({{ $item->product->brand->value }})</span>
                            <span class="text-sm w-96 truncate">{{ $item->product->description }}</span>
                        </div>
                    </td>
                    <td class="text-right p-2 border @php echo $item->status === 'unavailable' ? 'text-gray-400 line-through' : '' @endphp">{{ number_format($item->qty)  }}</td>
                    <td class="text-right p-2 border @php echo $item->status === 'unavailable' ? 'text-gray-400 line-through' : '' @endphp">&#8369;{{ number_format($item->price, 2) }}</td>
                    <td class="text-right p-2 border @php echo $item->status === 'unavailable' ? 'text-gray-400 line-through' : '' @endphp">&#8369;{{ number_format($item->qty * $item->price, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-right p-2 border">
                    <span class="font-bold">&#8369;{{ number_format($total, 2) }}</span>
                </td>
            </tr>
        </table>

        <p class="text-right font-bold">Reply to this email to confirm your order.</p>
    </div>
</body>
</html>
