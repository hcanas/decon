<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateQuotationRequest;
use App\Mail\QuotationSent;
use App\Models\ActivityLog;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'nullable|numeric',
            'keyword' => 'nullable|max:255',
            'only' => [
                'nullable',
                Rule::in(['pending', 'sent', 'confirmed', 'cancelled']),
            ],
        ]);

        if ($validator->fails()) abort(404);

        $data = Quotation::search($request->get('keyword'), function ($meilisearch, $query, $options) use ($validator) {
            if (!empty($validator->validated()['only'])) {
                $options['filter'] = 'status = '.$validator->validated()['only'];
            }

            $options['sort'] = ['created_at:desc'];

            return $meilisearch->search($query, $options);
        });

        return Inertia::render('Quotations/Index', [
            'filters' => $request->query(),
            'data' => $data->query(function ($query) {
                    $query->with('items.product.brand')
                        ->with('purchaseOrder');
                })
                ->paginate($request->get('per_page' ?? 30))
                ->withQueryString()
                ->through(fn ($quotation) => [
                    'id' => $quotation->id,
                    'customer' => $quotation->customer,
                    'status' => $quotation->status,
                    'items' => $quotation->items,
                    'total' => $quotation->items()->where('status', 'available')->sum(DB::raw('qty * price')),
                    'purchase_order' => $quotation->purchaseOrder,
                    'created_at' => $quotation->created_at,
                    'updated_at' => $quotation->updated_at,
                ]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuotationRequest $request, Quotation $quotation)
    {
        $quotation->load('customer', 'items.product.brand');

        DB::beginTransaction();

        if ($request->validated('status') === 'sent') {
            $quotation->fill([
                'status' => 'sent',
            ])->save();

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'category' => 'quotations',
                'description' => 'Sent quotation to '.$quotation->customer->email.'.',
            ]);

            Mail::to($quotation->customer->email)->send(new QuotationSent($quotation));
        } elseif ($request->validated('status') === 'for approval') {
            $quotation->fill([
                'status' => 'for approval',
            ])->save();

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'category' => 'quotations',
                'description' => 'Requested quotation approval for '.$quotation->customer->email.'.',
            ]);
        } elseif ($request->validated('status') === 'cancelled') {
            $quotation->fill([
                'status' => 'cancelled',
            ])->save();

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'category' => 'quotations',
                'description' => 'Cancelled quotation request of '.$quotation->customer->email.'.',
            ]);
        } elseif ($request->validated()['status'] === 'confirmed') {
            if (empty($quotation->purchaseOrder()->first())) {
                $quotation->purchaseOrder()->create([
                    'reference_number' => date('ymd', strtotime('now')).'-'.bin2hex(random_bytes(3)),
                    'status' => 'unpaid',
                ]);

                $quotation->fill([
                    'status' => 'confirmed',
                ])->save();

                ActivityLog::create([
                    'user_id' => $request->user()->id,
                    'category' => 'quotations',
                    'description' => 'Confirmed quotation of '.$quotation->customer->email.'.',
                ]);
            }
        }

        DB::commit();

        return redirect()->back();
    }
}
