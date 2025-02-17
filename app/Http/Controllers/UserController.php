<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function show(){
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|integer|in:0,1',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),  
            'role' => $request->role ?? 0,  
      ]);

        return redirect()->route('login');
    }

    public function showlogin(){
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // if (Auth::attempt([
        //     'email' => $request->email,
        //     'password' => $request->password
        // ])) {
        //     if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        //         if (Auth::user()->role === 'admin') {
        //             return redirect()->route('admin.dashboard');
        //         }
        //     return redirect()->intended('/dashboard'); 
        // }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
    
            if ($user->role == 1) { // Admin
                return redirect('/admin');
            } else { // Normal user
                return redirect()->intended('/dashboard'); 
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function dashboard(){
        return view('dashboard');
    }
    public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
}


    public function index()
{
    $users = User::paginate(10);

    return view('userlist', compact('users'));
}


public function edit($id)
{
    $user = User::find($id);
    
    if (!$user) {
        return redirect()->route('index')->with('error', 'User not found.');
    }

    return view('edituser', compact('user'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $id, 
    ]);

    $user = User::find($id);
    
    if (!$user) {
        return redirect()->route('index')->with('error', 'User not found.');
    }

    $user->name = $request->name;
    $user->email = $request->email;
    $user->save();

    return redirect()->route('users.index')->with('success', 'User updated successfully');
}
public function destroy($id)
{
    $user = User::find($id);

    if (!$user) {
        return redirect()->route('users.index')->with('error', 'User not found.');
    }

    $user->delete();

    return redirect()->route('users.index')->with('success', 'User deleted successfully');
}


    

}
