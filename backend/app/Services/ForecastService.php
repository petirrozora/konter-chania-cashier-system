<?php
namespace App\Services;

use App\Models\Product;
use App\Models\SaleItem;
use App\Models\SalesForecast;
use App\Models\RestockRecommendation;
use Illuminate\Support\Facades\DB;

class ForecastService
{
    /**
     * Baseline modular forecast.
     * This is intentionally method-agnostic at the UI level and can later
     * be replaced by the approved thesis method/service.
     */
    public function run(int $horizonDays = 7): array
    {
        $products = Product::with('stock')->where('status','active')->get();
        $results = [];
        foreach ($products as $product) {
            $daily = SaleItem::where('product_id',$product->id)
                ->where('created_at','>=',now()->subDays(30))
                ->sum('quantity');
            $avgDaily = $daily / 30;
            $predicted = round(max(0, $avgDaily * $horizonDays), 2);
            SalesForecast::create([
                'product_id'=>$product->id,
                'forecast_period'=>now()->addDays($horizonDays)->toDateString(),
                'predicted_quantity'=>$predicted,
                'method'=>'Baseline Moving Average 30 Hari',
            ]);
            $safety = (float)($product->stock?->minimum_stock ?? 0);
            $current = (int)($product->stock?->quantity ?? 0);
            $recommended = max(0, (int)ceil($predicted + $safety - $current));
            $status = $recommended > 0 ? ($current <= $safety ? 'Perlu Restock' : 'Pantau') : 'Aman';
            RestockRecommendation::create([
                'product_id'=>$product->id,'current_stock'=>$current,
                'predicted_demand'=>$predicted,'safety_stock'=>$safety,
                'recommended_quantity'=>$recommended,'status'=>$status
            ]);
            $results[] = compact('product','predicted','recommended','status');
        }
        return $results;
    }
}
