<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Name</label>
                <input type="text" name="name" value="{{ old('name', $admin->name ?? '') }}" class="form-control" required>
                @error('name')<span class="text-danger small">{{ $message }}</span>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email', $admin->email ?? '') }}" class="form-control" required>
                @error('email')<span class="text-danger small">{{ $message }}</span>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Password {{ isset($admin) ? '(leave blank to keep current)' : '' }}</label>
                <input type="password" name="password" class="form-control" {{ isset($admin) ? '' : 'required' }}>
                @error('password')<span class="text-danger small">{{ $message }}</span>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" {{ isset($admin) ? '' : 'required' }}>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Assign Roles</label>
                <div class="row">
                    @foreach($roles as $role)
                        <div class="col-md-3 col-sm-4 col-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}"
                                    id="role-{{ $role->id }}"
                                    {{ in_array($role->id, old('roles', isset($admin) ? $admin->roles->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role-{{ $role->id }}">
                                    {{ $role->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('roles')<span class="text-danger small d-block">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="{{ panel_route('admins.index') }}" class="btn btn-light">Cancel</a>
        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
    </div>
</div>

