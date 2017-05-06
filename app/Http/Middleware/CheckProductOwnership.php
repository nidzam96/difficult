<?php

namespace App\Http\Middleware;

use Closure;
use App\Product;

class CheckProductOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $product_id = $request->product;

        $product = Product::find($product_id);

        if ($product) {
            # code...
            $product_owner = $product->user_id;

            $current_user_id = Auth()->id();

            if ($current_user_id!=$product_owner) {
                # code...
                // dd("Kau ingat kau pandai!!!");
                alert()->warning('We do not support scam or any type of internet crime!!', 'Breach Alert!!!')->persistent('Go Back');

                return redirect()-> route('my_products');
            }
        }
        return $next($request);
    }
}
