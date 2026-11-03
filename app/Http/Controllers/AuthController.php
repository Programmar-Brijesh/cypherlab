<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\BlogBuilderPost;

class AuthController extends Controller
{

    public function registerPage()
    {
        return view('auth.register');
    }


    public function register(Request $request)
    {

        $otp = rand(100000, 999999);   // generate 6 digit OTP

        $user = new User();

        $user->name = $request->name;
        // $user->address = $request->address;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->otp = $otp; // SAVE OTP IN DATABASE

        // PROFILE PICTURE UPLOAD
        // if ($request->hasFile('profile_picture')) {
        //     $file = $request->file('profile_picture');

        //     $filename = time() . '_' . $file->getClientOriginalName();

        //     $file->move(public_path('uploads'), $filename);

        //     $user->profile_picture = $filename;
        // }

        // // GALLERY MULTIPLE IMAGE UPLOAD
        // if ($request->hasFile('gallery')) {
        //     $images = [];

        //     foreach ($request->file('gallery') as $file) {
        //         $filename = time() . '_' . $file->getClientOriginalName();

        //         $file->move(public_path('uploads'), $filename);

        //         $images[] = $filename;
        //     }

        //     $user->gallery = json_encode($images);
        // }

        $user->save();

        Mail::raw("Your OTP is: " . $otp, function ($message) use ($user) {

            $message->to($user->email)
                ->subject("Email Verification OTP");
        });

        return redirect()->route('verify.page', ['id' => $user->id]);
    }



    public function verifyPage($id)
    {
        return view('auth.verify', ['id' => $id]);
    }

    public function verifyOtp(Request $request)
    {

        $user = User::find($request->id);

        if ($user->otp == $request->otp) {

            $user->is_verified = 1;
            $user->otp = null;
            $user->save();

            return redirect()->route('login.page');
        } else {

            return "Invalid OTP";
        }
    }

    public function loginPage()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email not found');
        }

        // Google-only account
        if (is_null($user->password)) {
            return back()->with(
                'error',
                'This account uses Google Sign In. Please login with Google.'
            );
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Wrong password');
        }

        if (!$user->is_verified) {
            return back()->with([
                'verify_error' => 'Please verify your email first',
                'email' => $user->email,
            ]);
        }

        Session::put('user', $user);
        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function resendOtp(Request $request)
    {

        $user = User::where('email', $request->email)->first();

        $otp = rand(100000, 999999);

        $user->otp = $otp;
        $user->save();

        Mail::raw("Your new OTP is: " . $otp, function ($message) use ($user) {

            $message->to($user->email)
                ->subject("Resend OTP Verification");
        });

        return redirect()->route('verify.page', ['id' => $user->id]);
    }


    public function checkEmail(Request $request)
    {

        $email = $request->email;

        $exists = User::where('email', $email)->exists();

        if ($exists) {
            return response()->json(['status' => 'taken']);
        } else {
            return response()->json(['status' => 'available']);
        }
    }


    public function dashboard()
    {
        $user = Auth::user() ?? Session::get('user');

        if (!$user) {
            return redirect()->route('login.page');
        }

        $query = BlogBuilderPost::query();
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        } else {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)->orWhereNull('user_id');
            });
        }

        $postCount = (clone $query)->count();
        $publishedCount = (clone $query)->where('status', 'published')->count();
        $draftCount = (clone $query)->where('status', 'draft')->count();
        $totalViews = (clone $query)->sum('views');
        $posts = (clone $query)->latest()->get();

        return view('auth.dashboard', compact('user', 'posts', 'postCount', 'publishedCount', 'draftCount', 'totalViews'));
    }

    public function editProfile()
    {
        $user = Session::get('user');
        return view('auth.edit-profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {

        if (!Session::has('user')) {
            return redirect()->route('login.page');
        }

        $sessionUser = Session::get('user');

        $user = User::find($sessionUser->id);

        $user->name = $request->name;
        // $user->address = $request->address;
        $user->phone = $request->phone;
        $user->email = $request->email;

        // profile picture update
        // if ($request->hasFile('profile_picture')) {
        //     $file = $request->file('profile_picture');

        //     $filename = time() . '_' . $file->getClientOriginalName();

        //     $file->move(public_path('uploads'), $filename);

        //     $user->profile_picture = $filename;
        // }

        // // gallery update
        // if ($request->hasFile('gallery')) {

        //     $images = [];

        //     foreach ($request->file('gallery') as $file) {
        //         $filename = time() . '_' . $file->getClientOriginalName();

        //         $file->move(public_path('uploads'), $filename);

        //         $images[] = $filename;
        //     }

        //     $user->gallery = json_encode($images);
        // }

        $user->save();

        Session::put('user', $user);

        return redirect()->route('dashboard')->with('message', 'Profile updated successfully');
    }

    public function deleteAccount()
    {

        if (!Session::has('user')) {
            return redirect()->route('login.page');
        }

        $sessionUser = Session::get('user');

        $user = User::find($sessionUser->id);

        $user->delete();

        Session::forget('user');

        return redirect()->route('home')->with('message', 'Account deleted successfully');
    }


    public function logout()
    {
        Session::forget('user');
        Auth::logout();

        return redirect()->route('login.page');
    }
}
