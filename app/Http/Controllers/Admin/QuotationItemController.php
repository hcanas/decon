<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateQuotationItemsRequest;
use App\Models\ActivityLog;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Support\Facades\DB;

class QuotationItemController extends Controller
{
    public function __invoke(UpdateQuotationItemsRequest $request)
    {
        DB::beginTransaction();

        $recordsChanged = 0;

        foreach ($request->validated()['items'] AS $item) {
            $quotation_item = QuotationItem::find($item['id']);

            $quotation_item->fill([
                'qty' => $item['qty'],
                'measurement_unit' => $item['measurement_unit'],
                'price' => $item['price'],
                'status' => $item['status'],
            ])->save();

            if ($quotation_item->wasChanged()) $recordsChanged++;
        }

        if ($recordsChanged) {
            Quotation::find($quotation_item->quotation_id)
                ->fill(['status' => 'for approval'])
                ->save();

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'category' => 'quotations',
                'description' => 'Updated quotation items for '
                    .$quotation_item->quotation->customer->email
                    .'.',
            ]);
        }

        DB::commit();

        return redirect()->back();
    }
}
