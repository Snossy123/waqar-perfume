<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        User::where('email', $request->input('email'))
            ->where('is_active', false)
            ->delete();  // Clear any existing inactive user with the same email

        $request->validate([
            'email' => 'required|string|email|max:255|unique:users'
        ],[
            'email.required'=>__('validation.required',['attribute'=>'email']),
            'email.unique'=>__('validation.unique',['attribute'=>'email']),
        ]);

        // $otp = random_int(100000, 999999);
        $otp = 123456;
        $email = $request->input('email');
        User::create([
            'email' => $email,
            'otp_hash' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(5)
        ]);

        // send OTP to user via email or SMS here (not implemented in this example)
        // Mail::to($email)->send(new OtpMail($otp));

        return response()->json([
            'message' => __('auth.register_success'),
            'user' => $email,
            'type' => 'register'
        ], 201);
    }

    public function verifyOtp(Request $request)
    {
        // Validate the input
        $request->validate([
            'email' => 'required|string|email|max:255|exists:users,email',
            'otp' => 'required|integer|digits:6'
        ]);

        // Find the user by email
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Check if the OTP matches
        if (!Hash::check($request->input('otp'), $user->otp_hash)) {
            return response()->json(['error' => 'Invalid OTP'], 400);
        }

        // Check if OTP has expired
        if ($user->otp_expires_at < now()) {
            return response()->json(['error' => 'OTP expired'], 400);
        }

        // Mark the user as active and clear OTP data
        $user->is_active = true;
        $user->otp_hash = null;
        $user->otp_expires_at = null;
        $user->save();

        // Generate the JWT token
        $accessToken = JWTAuth::fromUser($user);

        return response()->json([
            'message' => __('auth.otp_success'),
            'token' => $accessToken,
            'refresh_token' => $accessToken,
            'user' => new UserResource($user)

        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255'
        ]);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if (!$user)
            return $this->register($request);

        // $otp = random_int(100000, 999999);
        $otp = 123456;
        $user->otp_hash = Hash::make($otp);
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        // Mail::to($email)->send(new OtpMail($otp));

        return response()->json([
            'message' => __('auth.login_success'),
            'user' => $email,
            'type' => 'login'
        ]);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|exists:users,email'
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !$user->otp_hash) {
            return response()->json(['error' => __('auth.otp_resend_failed')], 400);
        }

        $otp = random_int(100000, 999999);
        $user->otp_hash = Hash::make($otp);
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        Mail::to($user->email)->send(new OtpMail($otp));

        return response()->json(['message' => __('auth.otp_resend_success')]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => __('auth.logout')]);
    }

    public function me()
    {
        return response()->json(new UserResource(auth()->user()));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'phone' => 'nullable|string|regex:/^\+?[0-9]{7,15}$/|unique:users,phone,' . $user->id,
            'name_en' => 'nullable|string|max:119',
            'name_ar' => 'nullable|string|max:119'
        ]);

        $user->phone = $request->input('phone', $user->phone);
        $user->name = [
            'ar' => $request->input('name_ar', $user->name_ar),
            'en' => $request->input('name_en', $user->name_en)
        ];
        $user->save();

        return response()->json(
            [
                'message' => __('auth.profile_update'),
                'user' => new UserResource($user)
            ]);
    }

    public function refershToken()
    {
        try {
            $user = auth()->user();
            $accessToken = JWTAuth::fromUser($user);

            return response()->json([
                'token' => $accessToken,
                'refresh_token' => $accessToken
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not refresh token'], 500);
        }
    }

    public function deleteAccount()
    {
        $user = auth()->user();
        $user->delete();
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Account deleted successfully.']);
    }
}
