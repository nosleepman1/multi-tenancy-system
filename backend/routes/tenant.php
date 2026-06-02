<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Models\Post;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,

])->group(function () {

    Route::get('/', function () {
        $posts = Post::orderBy('created_at', 'desc')->get();
        return view('tenant_home', compact('posts'));
    })->name('tenant.home');

    Route::get('/login-with-token', function (Request $request) {
        $token = $request->query('token');
        
        if (!$token) {
            return redirect('/')->with('error', 'Jeton d\'accès absent.');
        }

        // Verify the token centrally
        $tenant = tenancy()->central(function () use ($token) {
            return \App\Models\Tenant::where('login_token', $token)
                ->where('login_token_expires_at', '>', now())
                ->first();
        });

        if ($tenant) {
            // Find the administrator user inside this tenant DB
            $user = User::where('email', $tenant->email)->first();
            
            if ($user) {
                Auth::login($user);
            }

            // Consume/clear the token centrally
            tenancy()->central(function () use ($tenant) {
                $tenant->update([
                    'login_token' => null,
                    'login_token_expires_at' => null,
                ]);
            });

            return redirect('/')->with('success', 'Connexion automatique réussie !');
        }

        return redirect('/')->with('error', 'Le jeton de connexion est invalide ou a expiré.');
    });

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Déconnexion réussie.');
    })->name('tenant.logout');
});
