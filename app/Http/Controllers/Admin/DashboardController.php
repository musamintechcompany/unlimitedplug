<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('management.portal.admin.dashboard');
    }

    public function profile()
    {
        return view('management.portal.admin.profile');
    }
}
