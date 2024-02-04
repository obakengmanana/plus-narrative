<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all users
        $search = $request->input('search');

        $users = User::when($search, function ($query) use ($search) {
            $query->where('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        })->paginate(10);

        if ($request->ajax()) {
            return View::make('users_table', compact('users'))->render();
        }

        return view('users', compact('users'));
    }
}