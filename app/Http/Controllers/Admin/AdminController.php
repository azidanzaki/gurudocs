<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Mapel;
use App\Models\Kelas;

class AdminController extends Controller
{
    // DASHBOARD
    public function dashboard()
    {
        return view('admin.dashboard.index');
    }
}