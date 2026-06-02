<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion centralisée - Multi-Tenancy SaaS</title>
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.15), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(236, 72, 153, 0.1), transparent 40%),
                        #020617;
        }
        .glass-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px rgba(255, 255, 255, 0.08) solid;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
        }
        .input-glow:focus {
            outline: none;
            border-color: rgba(99, 102, 241, 0.6);
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.2);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #d946ef 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
        }
    </style>
</head>
<body class="flex min-h-full items-center justify-center p-6">

    <div class="w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400">
                Multi-Tenancy Platform
            </h1>
            <p class="mt-3 text-sm text-slate-400">Accédez à votre espace en vous connectant à votre compte centralisé.</p>
        </div>

        <!-- Card -->
        <div class="glass-card rounded-2xl p-8">
            <h2 class="text-xl font-semibold text-slate-200 mb-6">Connexion</h2>

            <!-- Errors -->
            @if ($errors->any())
                <div style="background: rgba(239, 68, 68, 0.1); border: 1px rgba(239, 68, 68, 0.2) solid;" class="p-4 rounded-xl mb-6">
                    <ul class="list-disc list-inside text-sm text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Adresse Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="admin@votre-espace.com" 
                           style="background: rgba(15, 23, 42, 0.8); border: 1px rgba(255, 255, 255, 0.1) solid;"
                           class="w-full px-4 py-3 rounded-xl text-slate-100 placeholder-slate-500 input-glow transition-all">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" 
                           style="background: rgba(15, 23, 42, 0.8); border: 1px rgba(255, 255, 255, 0.1) solid;"
                           class="w-full px-4 py-3 rounded-xl text-slate-100 placeholder-slate-500 input-glow transition-all">
                </div>

                <!-- Button -->
                <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-white font-semibold btn-gradient mt-2">
                    Se connecter et accéder
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-slate-500">
                Pas encore d'espace ? <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-medium transition-all">Créez-en un</a>
            </div>
        </div>
    </div>

    <!-- Background Grid Effect -->
    <div style="z-index: -1;" class="absolute inset-0 pointer-events-none opacity-20">
        <svg class="h-full w-full" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
            <defs>
                <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255, 255, 255, 0.07)" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)" />
        </svg>
    </div>

</body>
</html>
