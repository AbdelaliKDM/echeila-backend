@extends('layouts/contentNavbarLayout')

@section('title', __('federation.show'))

@section('content')
  <div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">{{ __('federation.profile') }}</h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('federations.index') }}">{{ __('federation.federations') }}</a></li>
            <li class="breadcrumb-item active">{{ $federation->name }}</li>
          </ol>
        </nav>
      </div>
      <a href="{{ route('federations.index') }}" class="btn btn-label-secondary">
        <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
      </a>
    </div>

    <div class="row">
      <!-- Left Column - Federation Card -->
      <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <!-- Federation Image Section -->
            <div class="mb-4">
              <div class="position-relative d-inline-block">
                <img class="img-fluid rounded shadow-sm" 
                     src="{{ $federation->avatar_url }}" 
                     height="120" 
                     width="120" 
                     alt="Federation logo"
                     style="object-fit: cover; border: 4px solid #fff;" />
                @if($federation->user->status === \App\Constants\UserStatus::ACTIVE)
                  <span class="badge bg-success rounded-pill position-absolute" 
                        style="bottom: 5px; right: 5px; width: 20px; height: 20px; padding: 0; border: 3px solid #fff;">
                  </span>
                @endif
              </div>
              <div class="d-flex align-items-center justify-content-center gap-1">
              <h5 class="mb-1 mt-3">{{ $federation->name }}</h5>
              </div>
              <p class="text-muted small mb-0">
                <i class="bx bx-calendar me-1"></i>{{ __('federation.creation_date') }}: {{ $federation->creation_date ? $federation->creation_date->format('Y-m-d') : 'N/A' }}
              </p>
            </div>

            <!-- Quick Stats -->
            <div class="row g-3 mb-4">
              <div class="col-6">
                <div class="border rounded p-3 h-100">
                  <div class="d-flex align-items-center justify-content-center mb-2">
                    <div class="avatar avatar-sm bg-label-primary rounded me-2 d-flex align-items-center justify-content-center">
                      <i class="bx bx-trip"></i>
                    </div>
                    <h4 class="mb-0">{{ $stats['total_trips'] }}</h4>
                  </div>
                  <small class="text-muted">{{ __('federation.total_trips') }}</small>
                </div>
              </div>
              <div class="col-6">
                <div class="border rounded p-3 h-100">
                  <div class="d-flex align-items-center justify-content-center mb-2">
                    <div class="avatar avatar-sm bg-label-warning rounded me-2 d-flex align-items-center justify-content-center">
                      <i class="bx bx-star"></i>
                    </div>
                    <h4 class="mb-0">{{ number_format($stats['avg_rating'], 1) }}</h4>
                  </div>
                  <small class="text-muted">{{ __('federation.avg_rating') }}</small>
                </div>
              </div>
            </div>

            <hr class="my-4">

            <!-- Federation Information -->
            <div class="text-start">
              <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                {{ __('federation.federation_info') }}
              </h6>
              <ul class="list-unstyled mb-0">
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-buildings text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('federation.name') }}</small>
                    <span class="fw-medium">{{ $federation->name }}</span>
                  </div>
                </li>
                @if($federation->description)
                  <li class="mb-3 d-flex align-items-start">
                    <i class="bx bx-info-circle text-muted me-2 mt-1"></i>
                    <div class="flex-grow-1">
                      <small class="text-muted d-block">{{ __('federation.description') }}</small>
                      <span class="fw-medium">{{ $federation->description }}</span>
                    </div>
                  </li>
                @endif
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-check-circle text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('user.status') }}</small>
                    @if($federation->user->status === \App\Constants\UserStatus::ACTIVE)
                      <span class="badge bg-label-success">{{ __('user.statuses.' . \App\Constants\UserStatus::ACTIVE) }}</span>
                    @elseif($federation->user->status === \App\Constants\UserStatus::BANNED)
                      <span class="badge bg-label-danger">{{ __('user.statuses.' . \App\Constants\UserStatus::BANNED) }}</span>
                    @else
                      <span class="badge bg-label-secondary">{{ __('user.statuses.' . $federation->user->status) }}</span>
                    @endif
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-calendar text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('federation.creation_date') }}</small>
                    <span class="fw-medium">{{ $federation->creation_date ? $federation->creation_date->format('Y-m-d') : 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-0 d-flex align-items-center">
                  <i class="bx bx-time-five text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('federation.joined_date') }}</small>
                    <span class="fw-medium">{{ $federation->user->created_at->format('Y-m-d') }}</span>
                  </div>
                </li>
              </ul>
            </div>

            <hr class="my-4">

            <!-- Owner Information -->
            <div class="text-start">
              <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                {{ __('federation.owner_info') }}
              </h6>
              <ul class="list-unstyled mb-0">
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-user text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('federation.owner_name') }}</small>
                    <span class="fw-medium">{{ '@' . $federation->user->username }}</span>
                  </div>
                </li>
                <li class="mb-0 d-flex align-items-center">
                  <i class="bx bx-phone text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('federation.owner_phone') }}</small>
                    <span class="fw-medium">{{ $federation->user->phone }}</span>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column - Drivers List -->
      <div class="col-xl-8 col-lg-7">
        <!-- Statistics Grid -->
        <div class="row g-3 mb-4">
          <div class="col-sm-6 col-xl-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <div class="avatar-initial bg-label-primary rounded">
                      <i class="bx bx-group"></i>
                    </div>
                  </div>
                  <h5 class="mb-0">{{ $stats['total_drivers'] }}</h5>
                </div>
                <small class="text-muted">{{ __('federation.total_drivers') }}</small>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <div class="avatar-initial bg-label-success rounded">
                      <i class="bx bx-check-circle"></i>
                    </div>
                  </div>
                  <h5 class="mb-0">{{ $stats['active_drivers'] }}</h5>
                </div>
                <small class="text-muted">{{ __('federation.active_drivers') }}</small>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <div class="avatar-initial bg-label-danger rounded">
                      <i class="bx bx-x-circle"></i>
                    </div>
                  </div>
                  <h5 class="mb-0">{{ $stats['banned_drivers'] }}</h5>
                </div>
                <small class="text-muted">{{ __('federation.banned_drivers') }}</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Drivers List Card -->
        <div class="card shadow-sm">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">{{ __('federation.drivers_list') }}</h5>
          </div>
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th>{{ __('federation.driver_name') }}</th>
                  <th>{{ __('federation.driver_phone') }}</th>
                  <th>{{ __('federation.driver_status') }}</th>
                  <th>{{ __('federation.joined_date') }}</th>
                  <th>{{ __('app.actions') }}</th>
                </tr>
              </thead>
              <tbody>
                @forelse($drivers as $driver)
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <img class="rounded-circle me-2" 
                             src="{{ $driver->avatar_url }}" 
                             alt="Driver avatar" 
                             width="32" 
                             height="32"
                             style="object-fit: cover;" />
                        <div>
                          <span class="fw-medium">{{ $driver->fullname }}</span>
                          <br>
                          <small class="text-muted">{{ '@' . $driver->user->username }}</small>
                        </div>
                      </div>
                    </td>
                    <td>
                      <i class="bx bx-phone me-1"></i>{{ $driver->user->phone }}
                    </td>
                    <td>
                      @if($driver->user->status === \App\Constants\UserStatus::ACTIVE)
                        <span class="badge bg-label-success">{{ __('user.statuses.' . \App\Constants\UserStatus::ACTIVE) }}</span>
                      @elseif($driver->user->status === \App\Constants\UserStatus::BANNED)
                        <span class="badge bg-label-danger">{{ __('user.statuses.' . \App\Constants\UserStatus::BANNED) }}</span>
                      @else
                        <span class="badge bg-label-secondary">{{ __('user.statuses.' . $driver->user->status) }}</span>
                      @endif
                    </td>
                    <td>
                      <small class="text-muted">{{ $driver->user->created_at->format('Y-m-d') }}</small>
                    </td>
                    <td>
                      <a href="{{ route('drivers.show', $driver->id) }}" class="btn btn-sm btn-icon btn-label-primary">
                        <i class="bx bx-show"></i>
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center py-5">
                      <i class="bx bx-group text-muted mb-2" style="font-size: 2rem;"></i>
                      <p class="text-muted mb-0">{{ __('federation.no_drivers') }}</p>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          @if($drivers->hasPages())
            <div class="card-footer">
              {{ $drivers->links() }}
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
