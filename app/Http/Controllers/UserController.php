<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Cache;





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
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:user,admin',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => null,
        ]);

       
        Mail::to($user->email)->send(new VerifyEmail($user));

        return response()->json(['message' => 'Registration successful! Check your email for verification.']);
    }

    // public function verifyEmail($id)
    // {
    //     $user = User::find($id);
    //     if (!$user) {
    //         return response()->json(['message' => 'User not found'], 404);
    //     }

    //     if ($user->email_verified_at) {
    //         return response()->json(['message' => 'Email already verified']);
    //     }

    //     $user->email_verified_at = now();
    //     $user->save();

    //     return response()->json(['message' => 'Email verified successfully!']);
    // }
    public function verifyEmail(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Email verified successfully!']);
        }

        return redirect()->route('login')->with('success', 'Email verified successfully!');
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

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    if (!$user->email_verified_at) {
        return response()->json(['message' => 'Please verify your email first'], 403);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    //  Redirect only non-admin users to dashboard
    if ($user->role !== 'admin') {
        return redirect()->route('dashboard')->with('message', 'Login successful!');
    }

    return response()->json([
        'message' => 'Login successful',
        'token' => $token,
        'user' => $user,
    ]);
}

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);
    
    //     $user = User::where('email', $request->email)->first();
    
    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json(['message' => 'Invalid credentials'], 401);
    //     }
    
    //     if (!$user->email_verified_at) {
    //         return response()->json(['message' => 'Please verify your email first'], 403);
    //     }
    
        // $token = $user->createToken('auth_token')->plainTextToken;
    
        // if ($request->wantsJson()) {
        //     return response()->json([
        //         'message' => 'Login successful',
        //         'token' => $token,
        //         'user' => $user,
        //         'redirect_url' => $user->role === 'admin' ? url('/admin/dashboard') : url('/user/dashboard')
        //     ]);
        // }
    
        // return redirect()->route($user->role === 'admin' ? 'admin.dashboard' : 'dashboard')
        //     ->with('success', 'Login successful!');
    // }
    
    
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json(['message' => 'Invalid credentials'], 401);
    //     }

    //     if (!$user->email_verified_at) {
    //         return response()->json(['message' => 'Please verify your email first'], 403);
    //     }

    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'message' => 'Login successful',
    //         'token' => $token,
    //         'user' => $user,
    //     ]);
    // }

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

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Email not found!'], 404);
        }

        $otp = rand(100000, 999999); 

        
        Cache::put('otp_' . $user->email, $otp, now()->addMinutes(2));

        
        Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
            $message->to($user->email)->subject('Password Reset OTP');
        });

        return response()->json(['message' => 'OTP sent successfully!']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);

        $cachedOtp = Cache::get('otp_' . $request->email);
        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return response()->json(['message' => 'Invalid OTP!'], 400);
        }

        return response()->json(['message' => 'OTP verified successfully!']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => 'required|min:6|confirmed'
        ]);

        $cachedOtp = Cache::get('otp_' . $request->email);
        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return response()->json(['message' => 'Invalid OTP!'], 400);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found!'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        
        Cache::forget('otp_' . $request->email);

        return response()->json(['message' => 'Password reset successfully!']);
    }

}
