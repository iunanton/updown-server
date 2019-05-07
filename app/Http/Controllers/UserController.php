<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user;

    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    
    public function space() {
        return response([
            'space_size' => $this->user->space_size,
            'space_used' => $this->user->space_used
        ]);
    }
}
