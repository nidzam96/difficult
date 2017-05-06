<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $user = Auth()->user();

        if (!$user->hasRole($role)) {
            # code...
            // dd('Restricted');

            alert()->warning('You have no priviledged to be here.You have been redirected to your products.', 'Restricted Area!')->persistent('Close');

            return redirect()-> route('products.index');
        }

        return $next($request);
    }
}
