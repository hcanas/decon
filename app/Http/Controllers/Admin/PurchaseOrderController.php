<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePurchaseOrder;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'nullable|numeric',
            'keyword' => 'nullable|max:255',
            'only' => 'nullable|max:255',
        ]);

        if ($validator->fails()) abort(404);

        $data = PurchaseOrder::search($request->get('keyword'), function ($meilisearch, $query, $options) use ($validator) {
            switch ($validator->validated()['only'] ?? '') {
                case 'unpaid':
                case 'paid':
                case 'delivered':
                case 'cancelled':
                    $options['filter'] = 'status = '.$validator->validated()['only'];
                    $options['sort'] = ['updated_at:desc'];
                    break;

                case 'upcoming_deliveries':
                    $options['filter'] = 'status = "for delivery"';
                    $options['sort'] = ['delivery_date:asc'];
                    break;

                default:
                    $options['sort'] = ['created_at:desc'];
                    break;
            }

            return $meilisearch->search($query, $options);
        });

        return Inertia::render('PurchaseOrders/Index', [
            'filters' => $request->query(),
            'data' => $data->query(function ($query) {
                    $query->with('quotation.items.product.brand')
                        ->with('quotation.customer');
                })
                ->paginate($request->get('per_page', 20))
                ->withQueryString()
                ->through(fn ($purchase_order) => [
                    'id' => $purchase_order->id,
                    'reference_number' => $purchase_order->reference_number,
                    'quotation' => $purchase_order->quotation,
                    'payment_details' => $purchase_order->payment_details,
                    'delivery_date' => $purchase_order->delivery_date,
                    'amount' => $purchase_order->quotation()->first()
                        ->items()->where('status', 'available')->sum(DB::raw('qty * price')),
                    'status' => $purchase_order->status,
                    'updated_at' => $purchase_order->updated_at,
                ]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseOrder $request, PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->fill($request->validated());

        if ($purchaseOrder->isDirty('payment_details') AND empty($purchaseOrder->delivery_date)) {
            if (empty($purchaseOrder->payment_details)) {
                $purchaseOrder->fill(['status' => 'unpaid']);
            } else {
                $purchaseOrder->fill(['status' => 'paid']);
            }

            $purchaseOrder->save();
        } elseif ($purchaseOrder->isDirty('delivery_date') AND $purchaseOrder->payment_details) {
            if (empty($purchaseOrder->delivery_date)) {
                $purchaseOrder->fill(['status' => 'paid']);
            } else {
                $purchaseOrder->fill(['status' => 'for delivery']);
            }

            $purchaseOrder->save();
        } elseif ($purchaseOrder->isDirty('status')) {
            if ($purchaseOrder->status === 'delivered' AND $purchaseOrder->getOriginal('status') === 'for delivery') {
                $purchaseOrder->save();
            } elseif ($purchaseOrder->status === 'cancelled' AND $purchaseOrder->getOriginal('status') !== 'delivered') {
                $purchaseOrder->save();
            }
        }

        return redirect()->back();
    }
}
