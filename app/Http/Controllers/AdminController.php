<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users()
    {
        $users = User::orderBy('name')->get();

        return view(
            'admin.users',
            compact('users')
        );
    }

    public function updateRole(
        Request $request,
        User $user
    )
    {
        $request->validate([
            'role'=>'required'
        ]);

        $user->update([
            'role'=>$request->role
        ]);

        return back()->with(
            'success',
            'Role updated.'
        );
    }
}