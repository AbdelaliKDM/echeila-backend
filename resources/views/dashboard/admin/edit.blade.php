@extends('layouts/contentNavbarLayout')

@section('title', __('app.admin'))

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span class="text-muted fw-light">{{ __('app.edit') }}/</span> @lang('app.admin')
    </div>
    <div class="col-md-auto">
      @permission(\App\Support\Enum\Permissions::MANAGE_ADMINS)
      <a href="{{ route('admins.index') }}" class="text-white text-decoration-none">
        <button type="button" class="btn btn-primary" style="float: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
          <span class="tf-icons bx bx-arrow-back"></span> @lang('app.back')
        </button>
      </a>
      @endpermission
    </div>
  </h4>
  <form action="{{ route('admins.update', $admin->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <div class="row">
      <div class="col-xl">
        <div class="card mb-4">
          <div class="row card-body">
            <div class="mb-3 col-md-6">
              <label for="firstname" class="form-label">{{ __('admin.firstname') }}</label>
              <input type="text" name="firstname" class="form-control" id="firstname"
                     placeholder="{{ __('admin.firstname') }}"
                     value="{{ old('firstname', $admin->firstname ?? '') }}" required>
            </div>
            <div class="mb-3 col-md-6">
              <label for="lastname" class="form-label">{{ __('admin.lastname') }}</label>
              <input type="text" name="lastname" class="form-control" id="lastname"
                     placeholder="{{ __('admin.lastname') }}" value="{{ old('lastname', $admin->lastname ?? '') }}"
                     required>
            </div>
            <div class="mb-3 col-md-6">
              <label for="email" class="form-label">{{ __('admin.email') }}</label>
              <input type="text" name="email" class="form-control" id="email" placeholder="{{ __('admin.email') }}"
                     value="{{ old('email', $admin->email ?? '') }}" required>
            </div>
            <div class="mb-3 col-md-6">
              <label for="phone" class="form-label">{{ __('admin.phone') }}</label>
              <input type="text" name="phone" class="form-control phone-mask" id="phone"
                     placeholder="{{ __('admin.phone') }}" value="{{ old('phone', $admin->phone ?? '') }}" required>
            </div>
            <div class="mb-3 col-md-6">
              <label for="role" class="form-label">{{ __('admin.role') }}</label>
              <select name="role" class="form-select" id="role" required>
                <option value="">{{ __('app.select_option') }}</option>
                @foreach(\App\Support\Enum\Roles::all(true) as $key => $value)
                  <option value="{{ $key }}" {{ old('role', $admin->getRoleNames()->first() ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3 col-md-6">
              <label for="avatar" class="form-label">{{ __('admin.avatar') }}</label>
              <div class="input-group">
                <input type="file" name="avatar" class="form-control" id="avatar"
                       placeholder="{{ __('admin.avatar') }}">
              </div>
            </div>
            <div class="form-group" style="text-align: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
              <button type="submit" class="btn btn-primary">{{ __('app.send') }}</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection
