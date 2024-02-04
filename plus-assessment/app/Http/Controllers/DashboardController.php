<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\NewsController;

class DashboardController extends Controller
{
    public function index(Request $request, NewsController $newsController)
    {
        $user = $request->user();

        if ($user->isAdmin() || $user->isContentManager()) {
            $search = $request->input('search');
            $query = User::query();

            if ($search) {
                $query->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            }

            $users = $query->paginate(10);

            if ($request->ajax()) {
                return View::make('users_table', compact('users'))->render();
            }

            $users = $query->paginate(10);
            return view('users', compact('users'));
        } else {

            // Fetch news data using the NewsController
            $data = $newsController->getNews();
            $news = $data['data']['news'];

            return view('news', ['news' => $news]);
        }
    }
}
