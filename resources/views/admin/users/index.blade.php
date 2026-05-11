@extends('layouts.app')

@section('title', 'Manage Users')
@section('page-title', 'Manage Users 👥')
@section('page-subtitle', 'View and manage all registered students')

@section('content')
<div class="space-y-6">

    {{-- Search + Filter --}}
    <div class="card p-4">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..."
                class="input-field px-4 py-2 text-sm flex-1 min-w-48">
            <select name="role" class="input-field px-4 py-2 text-sm">
                <option value="">All Roles</option>
                <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <button type="submit" class="btn-primary px-6 py-2 rounded-lg text-sm font-semibold">Search</button>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-lg text-sm text-gray-400 hover:text-white border border-white/10 hover:border-white/20 transition-colors">Reset</a>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/08">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Quizzes</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Avg Score</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="table-row border-b border-white/05">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-white text-sm font-medium">{{ $user->name }}</div>
                                    <div class="text-gray-500 text-xs">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="{{ $user->role === 'admin' ? 'badge-red' : 'badge-cyan' }} px-2 py-1 rounded-full text-xs font-medium">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-300 text-sm">{{ $user->quiz_attempts_count }}</td>
                        <td class="px-6 py-4">
                            @php $avg = $user->quiz_attempts->filter(fn($a) => $a->result)->avg(fn($a) => $a->result->score_percentage); @endphp
                            <span class="font-bold text-sm {{ $avg >= 60 ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $avg ? round($avg) . '%' : '—' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-sm">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="badge-cyan px-3 py-1 rounded-lg text-xs font-medium hover:opacity-80">View</a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="badge-yellow px-3 py-1 rounded-lg text-xs font-medium hover:opacity-80">Edit</a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Delete this user?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="badge-red px-3 py-1 rounded-lg text-xs font-medium hover:opacity-80">Delete</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6">{{ $users->links() }}</div>
    </div>

</div>
@endsection
