<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerReportController extends Controller
{
    public function __invoke(Request $request)
    {
        $customers = DB::table('purchase_orders', 'PO')
            ->leftJoin('quotations AS Q', 'Q.id', 'PO.quotation_id')
            ->leftJoin('customers AS C', 'C.id', 'Q.customer_id')
            ->leftJoin('quotation_items AS QI', 'QI.quotation_id', 'Q.id')
            ->select(DB::raw(
                'C.email,
                MAX(C.code) AS code,
                MAX(C.contact_number) AS contact_number,
                MAX(C.viber_id) AS viber_id,
                SUM(QI.qty * QI.price) AS amount'
            ))
            ->where('QI.status', 'available')
            ->groupBy('C.email')
            ->orderBy('amount', 'desc')
            ->limit(10);

        if ($request->get('month')) {
            $customers->where(
                DB::raw('MONTH(CONVERT_TZ(PO.created_at, "GMT", "+08:00"))'),
                $request->get('month')
            );
        }

        if ($request->get('year')) {
            $customers->where(
                DB::raw('YEAR(CONVERT_TZ(PO.created_at, "GMT", "+08:00"))'),
                $request->get('year')
            );
        }

        return response()->json($customers->get());
    }
}
