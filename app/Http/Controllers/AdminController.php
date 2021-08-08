<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\UserType;
use App\Models\User;
use App\Models\Website;
use Illuminate\Support\Facades\Cookie;


class AdminController extends Controller
{
  public function index()
  {
    return view('admin/index');
  }
}
