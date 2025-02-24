<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\TwoFactorCodeMail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

class AuthController extends Controller
{
    /**
     * Handle user registration.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function register(Request $request)
{
    Log::info('Register process initiated.');

    // Validate form fields, including password confirmation and reCAPTCHA
    $validate = Validator::make($request->all(), [
        'name' => 'required|string|max:200|alpha_spaces',
        'email' => 'required|string|email|max:200|unique:users',
        'password' => [
            'required',
            'string',
            'min:8',
            'max:50',
            'confirmed',
            'regex:/[A-Z]/',     // At least one uppercase letter
            'regex:/[a-z]/',     // At least one lowercase letter
            'regex:/[0-9]/',     // At least one number
            'regex:/[\W_]/',     // At least one special character
        ],
        'g-recaptcha-response' => 'required|captcha',
    ]);

    // If validation fails, return back with errors
    if ($validate->fails()) {
        return redirect()->route('register')
            ->withErrors($validate)
            ->withInput();
    }

    // Attempt to register the user
    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    } catch (\Exception $e) {
        Log::error("E_04: Error registering user - " . $e->getMessage());
        return redirect()->route('register')->with('error', 'There was an issue registering the user.');
    }

    return redirect()->route('login')->with('success', 'User successfully registered.');
}


    /**
     * Handle user login.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        Log::info('Login process initiated.');

        $validate = Validator::make($request->all(), [
            'g-recaptcha-response' => 'required|captcha'
        ]);
    
        if ($validate->fails()) {
            return redirect()->route('login')
                ->withErrors($validate) 
                ->withInput();
        }

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            Log::error('E_05: Invalid credentials provided.', ['email' => $request->email]);
            return redirect()->route('login')
            ->withErrors('Incorrect credentials.')
            ->withInput();
        }

        $this->generateTwoFactorCode($user);

        session(['user_id' => $user->id]);

        return redirect()->route('verify-2fa')
        ->with('success', 'A verification code has been sent.');
    }
    /**
     * Verify the two-factor authentication code.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyTwoFactorCode(Request $request)
    {
        Log::info('2FA verification process initiated.');

        $request->validate([
            'two_factor_code' => 'required|digits:6',
            'g-recaptcha-response' => 'required|captcha',
        ]);

            $user_id = session('user_id');

            if (!$user_id) {
                Log::error('E_08: No user_id found in session.');
                return redirect()->route('login')->withErrors('Session expired or invalid.');
            }

            $user = User::findOrFail($user_id);

            if (!$user->two_factor_code) {
                Log::error('E_06: No 2FA code found.', ['user_id' => $user->id]);
                return redirect()->route('verify-2fa')
                    ->withErrors('No verification code found.')
                    ->withInput();
            }
        
            if (Carbon::parse($user->two_factor_expires_at)->lt(now())) {
                Log::error('E_07: 2FA code expired.', ['user_id' => $user->id]);
                return redirect()->route('verify-2fa')
                    ->withErrors('The code has expired.')
                    ->withInput();
            }
        
            if (!Hash::check($request->two_factor_code, $user->two_factor_code)) {
                Log::error('E_06: Incorrect 2FA code.', ['user_id' => $user->id]);
                return redirect()->route('verify-2fa')
                    ->withErrors('Incorrect verification code.')
                    ->withInput();
            }
        // Clear the code and grant access
        $user->update([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ]);

        // Create session only after successful verification and reCAPTCHA validation
        Auth::login($user);

        session()->forget('user_id');

        return redirect()->route('home')->with('success', 'Code successfully verified.');
    }

    /**
     * Generate a two-factor authentication code and send it to the user's email.
     *
     * @param \App\Models\User $user
     * @return void|\Illuminate\Http\JsonResponse
     */
    public function generateTwoFactorCode(User $user)
    {
        if (!$user) {
            Log::warning('User not found while generating 2FA code.');
            return response()->json(['message' => 'Invalid user.'], 404);
        }

        $plainCode = rand(100000, 999999); // Generate a 6-digit code
        $hashedCode = Hash::make($plainCode); // Hash the code before saving

        $user->update([
            'two_factor_code' => $hashedCode,
            'two_factor_expires_at' => now()->addMinutes(10), // Expires in 10 minutes
        ]);
    
        if (!$user->email) {
            Log::warning('User has no email.', ['user_id' => $user->id]);
            return response()->json(['message' => 'User does not have a valid email.'], 400);
        }

        Log::info('Sending 2FA code.', ['email' => $user->email]);
        Mail::to($user->email)->send(new TwoFactorCodeMail($plainCode));
    }
}
