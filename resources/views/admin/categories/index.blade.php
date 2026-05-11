@extends('layouts.app')

@section('title', 'Manage Categories')
@section('page-title', 'Manage Categories 🏷️')

@section('content')
<div class="space-y-6">
    <div class="flex justify-end">
        <a href="{{ route('admin.categories.create') }}" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-semibold">+ Add Category</a>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($categories as $category)
        <div class="card p-5">
            <div class="text-4xl mb-3">{{ $category->icon }}</div>
            <h3 class="font-display font-bold text-white text-lg mb-1">{{ $category->name }}</h3>
            <p class="text-gray-400 text-sm mb-3">{{ $category->description }}</p>
            <div class="flex gap-2 text-xs text-gray-500 mb-4">
                <span>📋 {{ $category->quizzes->count() }} Quizzes</span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.categories.edit', $category->id) }}" class="flex-1 text-center badge-yellow py-1.5 rounded-lg text-xs font-medium hover:opacity-80">Edit</a>
                <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" onsubmit="return confirm('Delete?')" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full badge-red py-1.5 rounded-lg text-xs font-medium hover:opacity-80">Delete</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
