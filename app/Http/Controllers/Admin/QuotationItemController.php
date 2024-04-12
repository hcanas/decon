<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateQuotationItemsRequest;
use App\Models\QuotationItem;
use Illuminate\Support\Facades\DB;

class QuotationItemController extends Controller
{
    public function __invoke(UpdateQuotationItemsRequest $request)
    {
        DB::beginTransaction();

        foreach ($request->validated()['items'] AS $item) {
            QuotationItem::find($item['id'])->fill([
                'qty' => $item['qty'],
                'measurement_unit' => $item['measurement_unit'],
                'price' => $item['price'],
                'status' => $item['status'],
            ])->save();
        }

        DB::commit();

        return redirect()->back();
    }
}
