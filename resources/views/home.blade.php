<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SciEval — Scientific Temper & Literacy Evaluation Platform</title>
    <meta name="description" content="Evaluate, track and improve your scientific literacy and scientific temper through expert-curated quizzes, analytics and learning modules.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
        .hero-gradient {
            background: linear-gradient(135deg, #0f0c29 0%, #1a1a5c 30%, #0d2137 60%, #0a3d2e 100%);
        }
        .card-glass {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .text-gradient {
            background: linear-gradient(135deg, #60efff 0%, #00ff87 50%, #60efff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .btn-primary {
            background: linear-gradient(135deg, #00d2ff 0%, #3a7bd5 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(0,210,255,0.3); }
        .btn-outline {
            border: 2px solid rgba(255,255,255,0.3);
            transition: all 0.3s ease;
        }
        .btn-outline:hover { border-color: #00d2ff; color: #00d2ff; transform: translateY(-2px); }
        .feature-card { transition: all 0.3s ease; }
        .feature-card:hover { transform: translateY(-8px); }
        .stat-card {
            background: linear-gradient(135deg, rgba(0,210,255,0.1), rgba(0,255,135,0.1));
            border: 1px solid rgba(0,210,255,0.2);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .float-anim { animation: float 6s ease-in-out infinite; }
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 0.7; }
            100% { transform: scale(1.5); opacity: 0; }
        }
        .pulse-ring::before {
            content: '';
            position: absolute;
            inset: -15px;
            border-radius: 50%;
            border: 2px solid #00d2ff;
            animation: pulse-ring 2s ease-out infinite;
        }
        .nav-blur {
            background: rgba(10,15,40,0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .section-dark { background: #080c1a; }
        .section-slightly-dark { background: #0d1224; }
        .category-badge {
            background: rgba(0,210,255,0.15);
            border: 1px solid rgba(0,210,255,0.3);
            color: #00d2ff;
        }
    </style>
</head>
<body class="bg-[#080c1a] text-white antialiased">

    {{-- Navigation --}}
    <nav class="nav-blur fixed top-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-cyan-400 to-emerald-400 flex items-center justify-center font-bold text-gray-900 font-display text-sm">SE</div>
                    <span class="font-display font-bold text-lg text-white">SciEval</span>
                </div>
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-gray-400 hover:text-white transition-colors text-sm font-medium">Features</a>
                    <a href="#categories" class="text-gray-400 hover:text-white transition-colors text-sm font-medium">Categories</a>
                    <a href="#how-it-works" class="text-gray-400 hover:text-white transition-colors text-sm font-medium">How It Works</a>
                    <a href="#leaderboard" class="text-gray-400 hover:text-white transition-colors text-sm font-medium">Leaderboard</a>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary text-white px-5 py-2 rounded-lg text-sm font-semibold">Dashboard →</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-outline text-white px-5 py-2 rounded-lg text-sm font-semibold">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary text-white px-5 py-2 rounded-lg text-sm font-semibold">Get Started</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="hero-gradient min-h-screen flex items-center justify-center relative overflow-hidden pt-16">
        {{-- Background orbs --}}
        <div class="absolute top-20 left-10 w-72 h-72 bg-cyan-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-600/5 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center relative z-10">
            <div class="inline-flex items-center gap-2 category-badge px-4 py-2 rounded-full text-sm font-medium mb-8">
                <span class="w-2 h-2 bg-cyan-400 rounded-full animate-pulse"></span>
                B.Tech Academic Project — MVC Programming
            </div>

            <h1 class="font-display text-5xl md:text-7xl font-bold leading-tight mb-6">
                Measure Your
                <span class="text-gradient block">Scientific Temper</span>
            </h1>

            <p class="text-gray-300 text-xl md:text-2xl max-w-3xl mx-auto mb-10 leading-relaxed">
                A professional evaluation platform to assess, track, and enhance your scientific literacy through expert-curated quizzes, analytics, and personalized learning paths.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16">
                @auth
                    <a href="{{ route('quiz.index') }}" class="btn-primary text-white px-8 py-4 rounded-xl text-lg font-semibold inline-flex items-center gap-2">
                        🧪 Take a Quiz
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn-outline text-white px-8 py-4 rounded-xl text-lg font-semibold">
                        View Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-primary text-white px-8 py-4 rounded-xl text-lg font-semibold inline-flex items-center gap-2">
                        🚀 Start Evaluating Free
                    </a>
                    <a href="{{ route('login') }}" class="btn-outline text-white px-8 py-4 rounded-xl text-lg font-semibold">
                        Already a member?
                    </a>
                @endauth
            </div>

            {{-- Stats bar --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                <div class="stat-card rounded-2xl p-4 text-center">
                    <div class="text-3xl font-display font-bold text-cyan-400">{{ $totalUsers ?? '0' }}+</div>
                    <div class="text-gray-400 text-sm mt-1">Students</div>
                </div>
                <div class="stat-card rounded-2xl p-4 text-center">
                    <div class="text-3xl font-display font-bold text-emerald-400">{{ $totalQuizzes ?? '0' }}+</div>
                    <div class="text-gray-400 text-sm mt-1">Quizzes</div>
                </div>
                <div class="stat-card rounded-2xl p-4 text-center">
                    <div class="text-3xl font-display font-bold text-purple-400">{{ $totalAttempts ?? '0' }}+</div>
                    <div class="text-gray-400 text-sm mt-1">Attempts</div>
                </div>
                <div class="stat-card rounded-2xl p-4 text-center">
                    <div class="text-3xl font-display font-bold text-yellow-400">6</div>
                    <div class="text-gray-400 text-sm mt-1">Categories</div>
                </div>
            </div>
        </div>
    </section>

    {{-- What is Scientific Temper --}}
    <section class="section-dark py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="category-badge inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium mb-6">
                        📖 About Scientific Temper
                    </div>
                    <h2 class="font-display text-4xl font-bold mb-6 text-white">What is <span class="text-gradient">Scientific Temper?</span></h2>
                    <p class="text-gray-300 text-lg mb-6 leading-relaxed">
                        Scientific Temper is a way of thinking that guides an individual to approach life scientifically. It involves <strong class="text-white">rational thinking, open-mindedness, empirical reasoning</strong>, and the ability to question beliefs without scientific basis.
                    </p>
                    <p class="text-gray-400 mb-8 leading-relaxed">
                        Article 51A(h) of the Indian Constitution makes it a fundamental duty of every citizen to develop scientific temper, humanism, and the spirit of inquiry and reform.
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach([['🔬', 'Empirical Thinking', 'Evidence-based reasoning'], ['🧠', 'Critical Analysis', 'Question everything rationally'], ['🌍', 'Open-mindedness', 'Accept facts over beliefs'], ['💡', 'Problem Solving', 'Scientific approach to life']] as $item)
                        <div class="card-glass rounded-xl p-4">
                            <div class="text-2xl mb-2">{{ $item[0] }}</div>
                            <div class="font-semibold text-white text-sm">{{ $item[1] }}</div>
                            <div class="text-gray-400 text-xs mt-1">{{ $item[2] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="relative">
                    <div class="float-anim">
                        <div class="card-glass rounded-3xl p-8 relative">
                            <div class="text-6xl text-center mb-4">🧬</div>
                            <h3 class="font-display font-bold text-xl text-center mb-4 text-white">Your Scientific Literacy Score</h3>
                            <div class="space-y-3">
                                @foreach([['Logical Reasoning', '78', 'text-cyan-400'], ['Evidence Evaluation', '85', 'text-emerald-400'], ['Myth vs Fact', '62', 'text-yellow-400'], ['Scientific Reasoning', '91', 'text-purple-400']] as $skill)
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-300">{{ $skill[0] }}</span>
                                        <span class="{{ $skill[2] }} font-bold">{{ $skill[1] }}%</span>
                                    </div>
                                    <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full bg-gradient-to-r from-cyan-500 to-emerald-500" style="width: {{ $skill[1] }}%"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Categories --}}
    <section id="categories" class="section-slightly-dark py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-4xl font-bold text-white mb-4">Quiz <span class="text-gradient">Categories</span></h2>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto">Six comprehensive categories covering all dimensions of scientific literacy and temper evaluation</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($categories ?? [] as $cat)
                <div class="feature-card card-glass rounded-2xl p-6 text-center group cursor-pointer" onclick="window.location='{{ route('quiz.index') }}'">
                    <div class="text-4xl mb-4 group-hover:scale-110 transition-transform">{{ $cat->icon }}</div>
                    <h3 class="font-display font-bold text-white text-lg mb-2">{{ $cat->name }}</h3>
                    <p class="text-gray-400 text-sm mb-4">{{ $cat->description }}</p>
                    <span class="category-badge px-3 py-1 rounded-full text-xs font-medium">{{ $cat->quizzes->count() }} Quizzes</span>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="section-dark py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-4xl font-bold text-white mb-4">Platform <span class="text-gradient">Features</span></h2>
                <p class="text-gray-400 text-lg">Everything you need for a complete scientific evaluation experience</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['🎯', 'Adaptive Quizzes', 'Multiple difficulty levels — Easy, Medium, Hard — across 6 science categories', 'from-cyan-500/20 to-blue-500/20'],
                    ['⏱️', 'Timed Sessions', 'Real-time countdown timer with session persistence so you never lose progress', 'from-emerald-500/20 to-teal-500/20'],
                    ['📊', 'Deep Analytics', 'Detailed sub-scores for logical thinking, evidence reasoning, myth-vs-fact, and problem solving', 'from-purple-500/20 to-pink-500/20'],
                    ['🏆', 'Leaderboard', 'Compare your performance with peers and climb the scientific temper rankings', 'from-yellow-500/20 to-orange-500/20'],
                    ['📄', 'PDF Reports', 'Download your comprehensive evaluation report as a professional PDF certificate', 'from-red-500/20 to-rose-500/20'],
                    ['🔔', 'Smart Recommendations', 'AI-powered topic recommendations based on your weaknesses and quiz history', 'from-indigo-500/20 to-violet-500/20'],
                ] as $feature)
                <div class="feature-card rounded-2xl p-6 bg-gradient-to-br {{ $feature[3] }} border border-white/10">
                    <div class="text-4xl mb-4">{{ $feature[0] }}</div>
                    <h3 class="font-display font-bold text-white text-lg mb-3">{{ $feature[1] }}</h3>
                    <p class="text-gray-400 leading-relaxed">{{ $feature[2] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section id="how-it-works" class="section-slightly-dark py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-4xl font-bold text-white mb-4">How It <span class="text-gradient">Works</span></h2>
            </div>
            <div class="grid md:grid-cols-4 gap-8">
                @foreach([
                    ['01', '📝', 'Register', 'Create your free account and set up your student profile'],
                    ['02', '🎯', 'Choose Quiz', 'Pick a category and difficulty level that matches your knowledge'],
                    ['03', '🧠', 'Take the Test', 'Answer MCQs one at a time with a live timer tracking your session'],
                    ['04', '📊', 'Get Analysis', 'Receive your scientific temper score with detailed sub-category analytics'],
                ] as $step)
                <div class="text-center">
                    <div class="relative inline-block pulse-ring mb-6">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-cyan-500 to-emerald-500 flex items-center justify-center text-3xl mx-auto">{{ $step[1] }}</div>
                    </div>
                    <div class="text-cyan-400 font-bold text-sm mb-2">STEP {{ $step[0] }}</div>
                    <h3 class="font-display font-bold text-white text-xl mb-3">{{ $step[2] }}</h3>
                    <p class="text-gray-400 text-sm">{{ $step[3] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="hero-gradient py-24">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="font-display text-4xl md:text-5xl font-bold text-white mb-6">
                Ready to Test Your <span class="text-gradient">Scientific Mind?</span>
            </h2>
            <p class="text-gray-300 text-xl mb-10">Join students who are already improving their scientific literacy</p>
            @auth
                <a href="{{ route('quiz.index') }}" class="btn-primary text-white px-10 py-5 rounded-xl text-xl font-semibold inline-block">
                    🧪 Start a Quiz Now
                </a>
            @else
                <a href="{{ route('register') }}" class="btn-primary text-white px-10 py-5 rounded-xl text-xl font-semibold inline-block">
                    🚀 Create Free Account
                </a>
            @endauth
        </div>
    </section>

    {{-- Footer --}}
    <footer class="section-dark border-t border-white/10 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-cyan-400 to-emerald-400 flex items-center justify-center font-bold text-gray-900 font-display text-sm">SE</div>
                        <span class="font-display font-bold text-lg text-white">SciEval</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">Scientific Temper & Literacy Evaluation Platform — A B.Tech MVC Programming Project</p>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Platform</h4>
                    <ul class="space-y-2">
                        @foreach([['Take Quiz', 'quiz.index'], ['Leaderboard', 'leaderboard'], ['Dashboard', 'dashboard']] as $link)
                        <li><a href="{{ route($link[1]) }}" class="text-gray-400 hover:text-cyan-400 transition-colors text-sm">{{ $link[0] }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Account</h4>
                    <ul class="space-y-2">
                        @auth
                        <li><a href="{{ route('profile.edit') }}" class="text-gray-400 hover:text-cyan-400 transition-colors text-sm">Profile</a></li>
                        @else
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-cyan-400 transition-colors text-sm">Login</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-cyan-400 transition-colors text-sm">Register</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/10 pt-8 text-center text-gray-500 text-sm">
                © {{ date('Y') }} SciEval — Scientific Temper Evaluation Platform. Built with Laravel 12 &amp; Tailwind CSS.
            </div>
        </div>
    </footer>

</body>
</html>
