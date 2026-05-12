<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
        return view('admin.users');
    }

    public function createUser()
    {
        return view('admin.users-create');
    }

    public function template()
    {
        return view('admin.template');
    }
}