<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class AdminController extends Controller
{
    public function updateRole(Request $request, $id)
{
    $user = User::findOrFail($id);
    $user->role = $request->role;
    $user->save();

    return back()->with('success', 'User role updated successfully!');
}
}
