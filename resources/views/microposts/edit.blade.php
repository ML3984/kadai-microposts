@extends('layouts.app')

@section('content')

    <div class="flex justify-center">
        <form method="POST" action="{{ route('microposts.update', $micropost->id) }}" class="w-1/2">
            @csrf
            @method('PUT')

            <div class="my-4">
                <label for="content" class="label">
                    <span class="label-text">ポスト編集:</span>
                </label>
                <input type="text" name="content" value="{{ $micropost->content }}" class="input input-bordered w-full">
            </div>

            <button type="submit" class="btn btn-neutral btn-outline">更新</button>
        </form>
    </div>

@endsection
