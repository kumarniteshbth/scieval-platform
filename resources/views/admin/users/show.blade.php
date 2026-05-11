@extends('layouts.app')

@section('title', 'User Profile')
@section('page-title', 'Student Profile 👤')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="card p-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-2xl">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="font-display font-bold text-white text-xl">{{ $user->name }}</h2>
                <div class="text-gray-400 text-sm">{{ $user->email }}</div>
                <div class="mt-1">
                    <span class="{{ $user->role === 'admin' ? 'badge-red' : 'badge-cyan' }} px-2 py-0.5 rounded text-xs font-medium">{{ ucfirst($user->role) }}</span>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div class="card p-4 text-center">
                <div class="font-display font-bold text-2xl text-cyan-400">{{ $user->quiz_attempts_count }}</div>
                <div class="text-gray-400 text-xs mt-1">Quizzes Taken</div>
            </div>
            <div class="card p-4 text-center">
                @php $avg = $user->quiz_attempts->filter(fn($a) => $a->result)->avg(fn($a) => $a->result->score_percentage); @endphp
                <div class="font-display font-bold text-2xl text-emerald-400">{{ $avg ? round($avg) . '%' : '—' }}</div>
                <div class="text-gray-400 text-xs mt-1">Avg Score</div>
            </div>
            <div class="card p-4 text-center">
                <div class="font-display font-bold text-2xl text-yellow-400">{{ $user->created_at->format('d M Y') }}</div>
                <div class="text-gray-400 text-xs mt-1">Joined</div>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-semibold">Edit User</a>
        <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-white/10 text-gray-300 hover:border-white/20 transition-colors text-sm">← Back</a>
    </div>
</div>
@endsection
