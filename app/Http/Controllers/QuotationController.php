<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuotationRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Quotation;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function __invoke(CreateQuotationRequest $request)
    {
        DB::beginTransaction();

        $customer = Customer::where('code', $request->validated('code', ''))
            ->orWhere('email', $request->validated('email', ''))
            ->first();

        if (empty($customer)) {
            $customer = Customer::create([
                'code' => bin2hex(random_bytes(4)),
                'email' => $request->validated('email'),
                'contact_number' => $request->validated('contact_number'),
                'viber_id' => $request->validated('viber_id'),
            ]);
        }

        $quotation = Quotation::create([
            'customer_id' => $customer->id,
            'status' => 'pending',
        ]);

        foreach ($request->validated('items') AS $item) {
            $product = Product::with('measurementUnit')->find($item['id']);

            $quotation->items()->create([
                'product_id' => $product->id,
                'price' => $product->price,
                'qty' => $item['qty'],
                'measurement_unit' => $product->measurementUnit->value,
                'status' => $product->status,
            ]);
        }

        DB::commit();
    }
}
