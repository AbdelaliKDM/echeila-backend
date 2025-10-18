@extends('layouts/contentNavbarLayout')

@section('title', __('app.create-federation'))

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('federation.add-new') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('federations.index') }}">{{ __('app.federations') }}</a></li>
          <li class="breadcrumb-item active">{{ __('app.add') }}</li>
        </ol>
      </nav>
    </div>
    <a href="{{ route('federations.index') }}" class="btn btn-label-secondary">
      <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
    </a>
  </div>

  <form action="{{ route('federations.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="row card-body">
                        <div class="mb-3 col-md-6">
                            <label for="user_id" class="form-label">{{ __('federation.passenger') }}</label>
                            <select name="user_id" class="form-select" id="user_id" required>
                                <option value="">{{ __('app.select_option') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->passenger->fullname }} ({{ $user->phone }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">{{ __('federation.name') }}</label>
                            <input type="text" name="name" class="form-control" id="name"
                                         placeholder="{{ __('federation.name') }}"
                                         value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="description" class="form-label">{{ __('federation.description') }}</label>
                            <textarea name="description" class="form-control" id="description"
                                                placeholder="{{ __('federation.description') }}">{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="creation_date" class="form-label">{{ __('federation.creation_date') }}</label>
                            <input type="date" name="creation_date" class="form-control" id="creation_date"
                                         value="{{ old('creation_date') }}" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="image" class="form-label">{{ __('federation.image') }}</label>
                            <div class="input-group">
                                <input type="file" name="image" class="form-control" id="image"
                                             placeholder="{{ __('federation.image') }}" accept="image/*">
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
