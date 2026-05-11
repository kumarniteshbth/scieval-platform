@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User ✏️')

@section('content')
<div class="max-w-lg mx-auto">
    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-6">
        @csrf @method('PUT')
        <div class="card p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="input-field w-full px-4 py-3">
                @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="input-field w-full px-4 py-3">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Role</label>
                <select name="role" required class="input-field w-full px-4 py-3">
                    <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Institution</label>
                <input type="text" name="institution" value="{{ old('institution', $user->institution) }}" class="input-field w-full px-4 py-3">
            </div>
        </div>
        <div class="flex gap-4">
            <button type="submit" class="btn-primary flex-1 py-3 rounded-xl font-display font-bold">💾 Update User</button>
            <a href="{{ route('admin.users.index') }}" class="px-6 py-3 rounded-xl border border-white/10 text-gray-300 hover:border-white/20 transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection
