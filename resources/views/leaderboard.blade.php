@extends('layouts.app')

@section('title', 'Leaderboard')
@section('page-title', 'Leaderboard 🏆')
@section('page-subtitle', 'Top scientific minds on the platform')

@section('content')
<div class="space-y-6">

    {{-- Top 3 Podium --}}
    @if($topUsers->count() >= 3)
    <div class="card p-8">
        <div class="flex items-end justify-center gap-4">
            {{-- 2nd Place --}}
            <div class="text-center flex-1">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-white font-bold text-xl mx-auto mb-3">
                    {{ strtoupper(substr($topUsers[1]->name, 0, 1)) }}
                </div>
                <div class="text-white font-semibold text-sm">{{ Str::limit($topUsers[1]->name, 15) }}</div>
                <div class="text-gray-400 text-xs">{{ $topUsers[1]->avg_score }}%</div>
                <div class="h-16 bg-gradient-to-t from-gray-600/30 to-gray-400/30 rounded-t-xl mt-3 flex items-center justify-center">
                    <span class="text-2xl">🥈</span>
                </div>
            </div>
            {{-- 1st Place --}}
            <div class="text-center flex-1">
                <div class="text-3xl mb-2">👑</div>
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-white font-bold text-2xl mx-auto mb-3 ring-4 ring-yellow-400/30">
                    {{ strtoupper(substr($topUsers[0]->name, 0, 1)) }}
                </div>
                <div class="text-white font-bold">{{ Str::limit($topUsers[0]->name, 15) }}</div>
                <div class="text-yellow-400 text-sm font-bold">{{ $topUsers[0]->avg_score }}%</div>
                <div class="h-24 bg-gradient-to-t from-yellow-600/30 to-yellow-400/30 rounded-t-xl mt-3 flex items-center justify-center">
                    <span class="text-2xl">🥇</span>
                </div>
            </div>
            {{-- 3rd Place --}}
            <div class="text-center flex-1">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-amber-600 to-amber-800 flex items-center justify-center text-white font-bold text-xl mx-auto mb-3">
                    {{ strtoupper(substr($topUsers[2]->name, 0, 1)) }}
                </div>
                <div class="text-white font-semibold text-sm">{{ Str::limit($topUsers[2]->name, 15) }}</div>
                <div class="text-gray-400 text-xs">{{ $topUsers[2]->avg_score }}%</div>
                <div class="h-10 bg-gradient-to-t from-amber-700/30 to-amber-600/30 rounded-t-xl mt-3 flex items-center justify-center">
                    <span class="text-2xl">🥉</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Full Leaderboard --}}
    <div class="card overflow-hidden">
        <div class="p-6 border-b border-white/08 flex items-center justify-between">
            <h3 class="font-display font-bold text-white text-lg">🏅 Full Rankings</h3>
            @if($userRank)
            <span class="badge-cyan px-3 py-1 rounded-full text-sm font-medium">Your Rank: #{{ $userRank }}</span>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/08">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Avg Score</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Quizzes</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Level</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Best Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topUsers as $i => $user)
                    <tr class="table-row border-b border-white/05 {{ $user->id == auth()->id() ? 'bg-cyan-500/05' : '' }}">
                        <td class="px-6 py-4">
                            @if($i == 0) <span class="text-2xl">🥇</span>
                            @elseif($i == 1) <span class="text-2xl">🥈</span>
                            @elseif($i == 2) <span class="text-2xl">🥉</span>
                            @else <span class="text-gray-400 font-bold">#{{ $i + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-white text-sm font-medium">
                                        {{ $user->name }}
                                        @if($user->id == auth()->id()) <span class="badge-cyan px-2 py-0.5 rounded text-xs ml-1">You</span> @endif
                                    </div>
                                    <div class="text-gray-500 text-xs">{{ $user->institution ?? 'Student' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-lg {{ $user->avg_score >= 80 ? 'text-emerald-400' : ($user->avg_score >= 60 ? 'text-cyan-400' : 'text-red-400') }}">
                                {{ $user->avg_score }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-300 text-sm">{{ $user->quiz_count }}</td>
                        <td class="px-6 py-4">
                            <span class="badge-purple px-2 py-1 rounded-full text-xs font-medium">
                                {{ $user->avg_score >= 80 ? 'Expert' : ($user->avg_score >= 60 ? 'Proficient' : 'Beginner') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-yellow-400 font-bold">{{ $user->best_score }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
