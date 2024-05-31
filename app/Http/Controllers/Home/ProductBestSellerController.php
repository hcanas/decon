<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductBestSellerController extends Controller
{
    public function __invoke()
    {
        return DB::table('purchase_orders', 'PO')
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
            ->whereRaw(DB::raw('PO.created_at BETWEEN (NOW() - INTERVAL 30 DAY) AND NOW()'))
            ->groupBy('P.name', 'B.value')
            ->orderBy('qty', 'desc')
            ->limit(8)
            ->get();
    }
}
