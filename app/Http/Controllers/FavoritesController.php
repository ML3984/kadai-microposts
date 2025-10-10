<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Micropost;
use Illuminate\Support\Facades\Auth;

class FavoritesController extends Controller
{
    // お気に入りに追加
    public function store($id)
    {
        $user = Auth::user();

        if ($user->favorite($id)) {
            return back();
        } else {
            return back();
        }
    }

    // お気に入りから削除
    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->unfavorite($id)) {
            return back();
        } else {
            return back();
        }
    }
}
