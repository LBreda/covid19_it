<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware(function (Request $req, $next) {
            $user = \Auth::user();
            if ($req->input('hl')) {
                session(['lang' => $req->input('hl')]);
            }
            App::setLocale(session('lang') ?? config('app.locale'));

            return $next($req);
        });

    }
}
