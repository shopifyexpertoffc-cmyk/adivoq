@extends('layouts.tenant')

@section('title', 'Add Team Member')
@section('page-title', 'Add Team Member')

@section('content')
<div class="max-w-xl">
    <div class="admin-card">
        <h2 class="card-title">New Team Member</h2>

        <form action="{{ route('team.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Name *</label>
                <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                @error('name') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
                @error('email') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-input" value="{{ old('phone') }}">
                @error('phone') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Role *</label>
                <select name="role" class="form-select" required>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
                @error('role') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('team.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Member</button>
            </div>
        </form>
    </div>
</div>
@endsection