<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('user.show')->with([
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        abort_unless(Gate::allows('update', $user), 403);

        return view('user.edit')->with([
            'user' => $user,
            'message_success' => Session::get('message_success'),
            'message_warning' => Session::get('message_warning'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     * @return UserController|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, User $user)
    {
        abort_unless(Gate::allows('update', $user), 403);

        $request->validate([
            'name' => 'required|min:3',
            'motto' => 'required|min:5',
            'about_me' => 'required|min:10',
            'image' => 'mimes:jpeg,jpg,bmp,png,gif'
        ]);

        if ($request->image) {
            $this->saveImages($request->image, $user->id);

        }

        $user->update([
            'name' => $request['name'],
            'motto' => $request['motto'],
            'about_me' => $request['about_me'],
        ]);
        return  redirect('home')->with([
                'message_success' => 'The user <b>' . $user->name . '</b> details were updated.'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        abort_unless(Gate::allows('delete', $user), 403);

    }

    public function saveImages($imageInput, $user_id)
    {
        $image = Image::make($imageInput);
        if ($image->width() > $image->height()) {//Landscape
            $image->widen(500)
                ->save(public_path() . '/img/users/' . $user_id . '_large.jpg')
                ->widen(300)->pixelate(12)
                ->save(public_path() . '/img/users/' . $user_id . '_pixelated.jpg');
            $image = Image::make($imageInput)
                ->widen(60)->save(public_path() . '/img/users/' . $user_id . '_thumb.jpg');
        } else {//Portrait
            $image->heighten(500)
                ->save(public_path() . '/img/users/' . $user_id . '_large.jpg')
                ->heighten(300)->pixelate(12)
                ->save(public_path() . '/img/users/' . $user_id . '_pixelated.jpg');
            $image = Image::make($imageInput)
                ->heighten(60)->save(public_path() . '/img/users/' . $user_id . '_thumb.jpg');
        }
    }

    public function deleteImages($user_id)
    {
        if (file_exists(public_path() . '/img/users/' . $user_id . '_large.jpg')) {
            unlink(public_path() . '/img/users/' . $user_id . '_large.jpg');
        }
        if (file_exists(public_path() . '/img/users/' . $user_id . '_pixelated.jpg')) {
            unlink(public_path() . '/img/users/' . $user_id . '_pixelated.jpg');
        }
        if (file_exists(public_path() . '/img/users/' . $user_id . '_thumb.jpg')) {
            unlink(public_path() . '/img/users/' . $user_id . '_thumb.jpg');
        }
        return back()->with([
            'message_success' => 'The user image was deleted.'
        ]);
    }
}
