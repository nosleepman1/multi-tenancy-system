<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - {{ tenant('name') }}</title>
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at top left, rgba(99, 102, 241, 0.1), transparent 40%),
                        radial-gradient(circle at bottom right, rgba(236, 72, 153, 0.08), transparent 40%),
                        #020617;
        }
        .glass-nav {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            border-bottom: 1px rgba(255, 255, 255, 0.06) solid;
        }
        .post-card {
            background: rgba(15, 23, 42, 0.5);
            border: 1px rgba(255, 255, 255, 0.06) solid;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .post-card:hover {
            transform: translateY(-4px);
            border-color: rgba(99, 102, 241, 0.3);
            background: rgba(15, 23, 42, 0.8);
            box-shadow: 0 12px 24px -10px rgba(99, 102, 241, 0.2);
        }
    </style>
</head>
<body class="min-h-full flex flex-col pb-12">

    <!-- Navigation -->
    <nav class="glass-nav sticky top-0 z-50 w-full px-6 py-4">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-pink-500 flex items-center justify-center font-bold text-white text-lg shadow-lg shadow-indigo-500/20">
                    {{ strtoupper(substr(tenant('name'), 0, 1)) }}
                </div>
                <div>
                    <span class="text-xl font-bold tracking-tight text-slate-100">{{ tenant('name') }}</span>
                    <span class="block text-xs font-medium text-indigo-400">{{ tenant('id') }}.localhost</span>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                @if (Auth::check())
                    <div class="text-right hidden sm:block">
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Connecté</span>
                        <span class="text-sm font-medium text-slate-200">{{ Auth::user()->email }}</span>
                    </div>
                    <form action="{{ route('tenant.logout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                style="border: 1px rgba(255, 255, 255, 0.1) solid;"
                                class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-300 hover:text-white hover:bg-slate-900 transition-all">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="http://localhost:8000/login" 
                       style="background: linear-gradient(135deg, #6366f1 0%, #d946ef 100%);"
                       class="px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-lg shadow-indigo-500/10 hover:shadow-indigo-500/20 transition-all">
                        Connexion centrale
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-4xl w-full mx-auto px-6 mt-12 flex-grow">
        
        <!-- Alerts -->
        @if (session('success'))
            <div style="background: rgba(16, 185, 129, 0.1); border: 1px rgba(16, 185, 129, 0.2) solid;" class="p-4 rounded-xl mb-8 flex items-center justify-between text-emerald-400 text-sm">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div style="background: rgba(239, 68, 68, 0.1); border: 1px rgba(239, 68, 68, 0.2) solid;" class="p-4 rounded-xl mb-8 flex items-center justify-between text-red-400 text-sm">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Tenant Header -->
        <div class="mb-12 text-center sm:text-left">
            <h2 class="text-3xl font-extrabold tracking-tight text-slate-100 sm:text-4xl">
                Publications de l'espace
            </h2>
            <p class="mt-3 text-lg text-slate-400">
                Bienvenue sur votre blog privé. Seuls les membres de <strong>{{ tenant('name') }}</strong> peuvent y contribuer.
            </p>
        </div>

        <!-- Posts List -->
        <div class="space-y-6">
            @forelse ($posts as $post)
                <article class="post-card rounded-2xl p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-4 text-xs font-semibold text-slate-500 tracking-wider">
                        <span>ARTICLE</span>
                        <span>{{ $post->created_at->locale('fr')->diffForHumans() }}</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-100 hover:text-indigo-400 transition-colors mb-4">
                        {{ $post->title }}
                    </h3>
                    <p class="text-slate-350 leading-relaxed text-base font-light">
                        {{ $post->content }}
                    </p>
                </article>
            @empty
                <div class="text-center py-16 post-card rounded-2xl">
                    <svg class="mx-auto h-12 w-12 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 00-2-2v10a2 2 0 01-2 2H5m12 0a2 2 0 002-2V8M5 12h5m-5 3h5" />
                    </svg>
                    <h3 class="mt-4 text-sm font-semibold text-slate-300">Aucun article publié</h3>
                    <p class="mt-2 text-xs text-slate-500">Commencez par vous connecter pour publier votre premier article !</p>
                </div>
            @endforelse
        </div>
    </main>

    <!-- Background Grid Effect -->
    <div style="z-index: -1;" class="absolute inset-0 pointer-events-none opacity-10">
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
