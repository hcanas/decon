<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductReportController extends Controller
{
    public function __invoke(Request $request)
    {
        $products = DB::table('purchase_orders', 'PO')
            ->leftJoin('quotations AS Q', 'Q.id', 'PO.quotation_id')
            ->leftJoin('quotation_items AS QI', 'QI.quotation_id', 'Q.id')
            ->leftJoin('products AS P', 'P.id', 'QI.product_id')
            ->leftJoin('brands AS B', 'B.id', 'P.brand_id')
            ->select(DB::raw(
                'MAX(P.image) AS image,
                P.name,
                MAX(P.description) AS description,
                B.value AS brand,
                SUM(QI.qty) AS qty'
            ))
            ->where('QI.status', 'available')
            ->groupBy('P.name', 'B.value')
            ->orderBy('qty', 'desc')
            ->limit(8);

        if ($request->get('month')) {
            $products->where(
                DB::raw('MONTH(CONVERT_TZ(PO.created_at, "GMT", "+08:00"))'),
                $request->get('month')
            );
        }

        if ($request->get('year')) {
            $products->where(
                DB::raw('YEAR(CONVERT_TZ(PO.created_at, "GMT", "+08:00"))'),
                $request->get('year')
            );
        }

        return response()->json($products->get());
    }
}
