<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderReportController extends Controller
{
    public function __invoke(Request $request)
    {
        $purchase_orders = DB::table('purchase_orders', 'PO')
            ->leftJoin('quotations AS Q', 'Q.id', 'PO.quotation_id')
            ->leftJoin('quotation_items AS QI', 'QI.quotation_id', 'Q.id')
            ->select(DB::raw(
                ($request->get('month')
                ? 'DAY(CONVERT_TZ(PO.created_at, "GMT", "+08:00")) AS day,'
                : 'MONTH(CONVERT_TZ(PO.created_at, "GMT", "+08:00")) AS month,'
                ). 'COUNT(DISTINCT(CASE
                    WHEN PO.status = "paid" OR PO.status = "for delivery"
                    THEN PO.id
                    ELSE NULL
                END)) AS pending_delivery_count,
                SUM(CASE
                    WHEN (PO.status = "paid" OR PO.status = "for delivery") AND QI.status = "available"
                    THEN (QI.qty * QI.price)
                    ELSE 0
                END) AS pending_delivery_amount,
                COUNT(DISTINCT(CASE
                    WHEN PO.status = "delivered"
                    THEN PO.id
                    ELSE NULL
                END)) AS delivered_count,
                SUM(CASE
                    WHEN PO.status = "delivered" AND QI.status = "available"
                    THEN (QI.qty * QI.price)
                    ELSE 0
                END) AS delivered_amount,
                COUNT(DISTINCT(CASE
                    WHEN PO.status = "cancelled"
                    THEN PO.id
                    ELSE NULL
                END)) AS cancelled_count,
                SUM(CASE
                    WHEN PO.status = "cancelled" AND QI.status = "available"
                    THEN (QI.qty * QI.price)
                    ELSE 0
                END) AS cancelled_amount'
            ));

        if ($request->get('month')) {
            $purchase_orders->groupBy('day')
                ->orderBy('day')
                ->where(
                    DB::raw('MONTH(CONVERT_TZ(PO.created_at, "GMT", "+08:00"))'),
                    $request->get('month')
                );
        } else {
            $purchase_orders->groupBy('month')
                ->orderBy('month');
        }

        if ($request->get('year')) {
            $purchase_orders->where(
                DB::raw('YEAR(CONVERT_TZ(PO.created_at, "GMT", "+08:00"))'),
                $request->get('year')
            );
        }

        return response()->json($purchase_orders->get());
    }
}
