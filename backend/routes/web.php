<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// routes/web.php, api.php or any other central route files you have

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        
        Route::get('/test', function () {
            return "Hello World";
        });

        Route::get('/', function () {
            return view('welcome');
        });

        // Register Routes
        Route::get('/register', function () {
            return view('register');
        })->name('register');

        Route::post('/register', function (Request $request) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:tenants,email',
                'subdomain' => 'required|string|alpha_dash|max:255|unique:tenants,id',
                'password' => 'required|string|min:6',
            ]);

            // Create Tenant centrally
            $tenant = Tenant::create([
                'id' => $request->subdomain,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Map Domain
            $tenant->domains()->create([
                'domain' => $request->subdomain . '.localhost',
            ]);

            // Provision tenant database user & sample posts
            $tenant->run(function () use ($request) {
                // Admin User in Tenant DB
                \App\Models\User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                // Sample Posts in Tenant DB
                \App\Models\Post::create([
                    'title' => 'Bienvenue sur votre espace ' . $request->name . ' !',
                    'content' => 'Cet article a été automatiquement généré pour célébrer la création de votre espace de publication multi-tenant. Vous disposez d\'une base de données totalement isolée !',
                ]);

                \App\Models\Post::create([
                    'title' => 'La sécurité et l\'isolation des données',
                    'content' => 'Grâce à notre architecture multi-base, vos données sont totalement imperméables aux autres locataires du système. Vous pouvez publier en toute sérénité.',
                ]);
            });

            // Generate SSO token and login automatically
            $token = Str::random(40);
            $tenant->update([
                'login_token' => $token,
                'login_token_expires_at' => now()->addMinutes(5),
            ]);

            return redirect('http://' . $tenant->id . '.localhost:8000/login-with-token?token=' . $token);
        });

        // Login Routes
        Route::get('/login', function () {
            return view('login');
        })->name('login');

        Route::post('/login', function (Request $request) {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $tenant = Tenant::where('email', $request->email)->first();

            if (!$tenant || !Hash::check($request->password, $tenant->password)) {
                return back()->withErrors(['email' => 'Adresse email ou mot de passe incorrect.'])->withInput();
            }

            // Generate temporary SSO token
            $token = Str::random(40);
            $tenant->update([
                'login_token' => $token,
                'login_token_expires_at' => now()->addMinutes(5),
            ]);

            return redirect('http://' . $tenant->id . '.localhost:8000/login-with-token?token=' . $token);
        });
    });
}




