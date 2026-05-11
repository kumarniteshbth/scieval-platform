@extends('layouts.app')

@section('title', 'All Results')
@section('page-title', 'All Quiz Results 📊')
@section('page-subtitle', 'Platform-wide student performance data')

@section('content')
<div class="space-y-6">

    <div class="card p-4">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search student..."
                class="input-field px-4 py-2 text-sm flex-1 min-w-48">
            <select name="category" class="input-field px-4 py-2 text-sm">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary px-6 py-2 rounded-lg text-sm font-semibold">Filter</button>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/08">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Quiz</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Score</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Level</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $result)
                    <tr class="table-row border-b border-white/05">
                        <td class="px-6 py-4">
                            <div class="text-white text-sm font-medium">{{ $result->attempt->user->name }}</div>
                            <div class="text-gray-500 text-xs">{{ $result->attempt->user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-white text-sm">{{ $result->attempt->quiz->title }}</div>
                            <span class="badge-cyan px-2 py-0.5 rounded text-xs">{{ $result->attempt->quiz->category->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-lg {{ $result->score_percentage >= 60 ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $result->score_percentage }}%
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="{{ $result->passed ? 'badge-green' : 'badge-red' }} px-2 py-1 rounded-full text-xs font-medium">
                                {{ $result->passed ? 'Passed' : 'Failed' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge-purple px-2 py-1 rounded-full text-xs font-medium">{{ $result->literacy_level }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-sm">{{ $result->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6">{{ $results->links() }}</div>
    </div>

</div>
@endsection
