<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $field = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        if (! Auth::attempt([$field => $data['login'], 'password' => $data['password'], 'status' => 1], $request->boolean('remember'))) {
            return back()->withInput($request->only('login'))->withErrors(['login' => 'Login details match nahi hue.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('frontend.customer.profile'));
    }

    public function showRegister()
    {
        return view('frontend.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'mobile' => ['required', 'string', 'max:20', Rule::unique('users', 'mobile')->whereNull('deleted_at')],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = $data['email'] ?: 'customer-' . preg_replace('/\D+/', '', $data['mobile']) . '@local.invalid';

        $customer = User::create([
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'email' => $email,
            'password' => $data['password'],
            'status' => 1,
        ]);

        $role = Role::firstOrCreate(['title' => 'Customer']);
        $customer->roles()->syncWithoutDetaching([$role->id]);

        Auth::login($customer);
        $request->session()->regenerate();

        return redirect()->route('frontend.customer.profile')->with('message', 'Customer account ban gaya.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('frontend.home');
    }
}
