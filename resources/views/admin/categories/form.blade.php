@extends('layouts.app')

@section('title', isset($category) ? 'Edit Category' : 'Add Category')
@section('page-title', isset($category) ? 'Edit Category ✏️' : 'Add Category ➕')

@section('content')
<div class="max-w-lg mx-auto">
    <form method="POST" action="{{ isset($category) ? route('admin.categories.update', $category->id) : route('admin.categories.store') }}" class="space-y-6">
        @csrf
        @if(isset($category)) @method('PUT') @endif

        <div class="card p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Category Name *</label>
                <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
                    class="input-field w-full px-4 py-3" placeholder="e.g. Physics">
                @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                <textarea name="description" rows="3" class="input-field w-full px-4 py-3 resize-none"
                    placeholder="Brief description">{{ old('description', $category->description ?? '') }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Icon (Emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon', $category->icon ?? '🔬') }}"
                        class="input-field w-full px-4 py-3" placeholder="🔬">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Color (Hex)</label>
                    <input type="color" name="color" value="{{ old('color', $category->color ?? '#00d2ff') }}"
                        class="input-field w-full h-12 px-2 py-2 cursor-pointer">
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="btn-primary flex-1 py-3 rounded-xl font-display font-bold">
                {{ isset($category) ? '💾 Update' : '➕ Add Category' }}
            </button>
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 rounded-xl border border-white/10 text-gray-300 hover:border-white/20 transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection
