<?php

// namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller;
// use App\Models\User;
// use App\Models\Shop;  // Import Shop model
// use Illuminate\Auth\Events\Registered;
// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Validation\Rules;
// use Illuminate\View\View;

// class RegisteredUserController extends Controller
// {
//     /**
//      * Display the registration view.
//      */
//     public function create(): View
//     {
//         return view('auth.register');
//     }

//     /**
//      * Handle an incoming registration request.
//      */
//     public function store(Request $request): RedirectResponse
// {
//     $request->validate([
//         'name' => 'required|string|max:255',
//         'email' => 'required|string|email|max:255|unique:users',
//         'password' => 'required|string|confirmed|min:8',
//         'shop_name' => 'required|string|max:255',  // new field for shop name
//     ]);

//     // Create the Shop first
//     $shop = \App\Models\Shop::create([
//         'name' => $request->shop_name,
//     ]);

//     // Now create the User linked to this shop
//     $user = \App\Models\User::create([
//         'name' => $request->name,
//         'email' => $request->email,
//         'password' => \Illuminate\Support\Facades\Hash::make($request->password),
//         'shop_id' => $shop->id,  // link user to created shop
//     ]);

//     event(new Registered($user));

//     Auth::login($user);

//     return redirect(route('dashboard'));
// }

// }

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Create the Shop first
        $shop = Shop::create([
            'name' => $request->shop_name,
        ]);

        // Create the User linked to that shop
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'shop_id' => $shop->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
