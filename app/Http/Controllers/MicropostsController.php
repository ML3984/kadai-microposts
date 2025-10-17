<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Micropost;
use App\Models\User;

class MicropostsController extends Controller
{
    public function index()
    {
        $data = [];
        if (Auth::check()) { // 認証済みの場合
            // 認証済みユーザーを取得
            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            // ユーザーとフォロー中ユーザーの投稿の一覧を作成日時の降順で取得
            $microposts = $user->feed_microposts()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'microposts' => $microposts,
            ];
        }

        // welcomeビューでそれらを表示
        return view('welcome', $data);
    }

    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'content' => 'required|max:255',
        ]);

        // 認証済みユーザー（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->microposts()->create([
            'content' => $request->content,
        ]);

        // 前のURLへリダイレクトさせる
        return back();
    }

    public function destroy(string $id)
    {
        // idの値で投稿を検索して取得
        $micropost = Micropost::findOrFail($id);

        // 認証済みユーザー（閲覧者）がその投稿の所有者である場合は投稿を削除
        if (Auth::id() === $micropost->user_id) {
            $micropost->delete();
            return back()
                ->with('success','Delete Successful');
        }

        // 前のURLへリダイレクトさせる
        return back()
            ->with('Delete Failed');
    }

    public function edit($id)
    {
        $micropost = Micropost::findOrFail($id);

        // 他人の投稿は編集できないようにする
        if (Auth::id() !== $micropost->user_id) {
            return redirect('/')->with('error', 'Unauthorized');
        }

        return view('microposts.edit', [
            'micropost' => $micropost,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|max:255',
        ]);

        $micropost = Micropost::findOrFail($id);

        if (Auth::id() === $micropost->user_id) {
            $micropost->content = $request->content;
            $micropost->save();
            return redirect('/')->with('success', 'Updated Successfully');
        }

        return redirect('/')->with('error', 'Unauthorized');
    }
}