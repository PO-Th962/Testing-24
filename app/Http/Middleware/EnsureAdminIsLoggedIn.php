<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminIsLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('admin_logged_in') || $request->session()->get('admin_logged_in') !== true) {
            return redirect()->route('admin.login')->with('error', 'กรุณาเข้าสู่ระบบในฐานะผู้ดูแลระบบก่อนดำเนินการต่อ');
        }

        return $next($request);
    }
}
