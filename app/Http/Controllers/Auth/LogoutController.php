<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;

class LogoutController extends Controller
{
  public function action()
  {

    Session::flush();
    Auth::logout();
    return redirect()->route('login');

  }
}
