@extends('layouts/contentNavbarLayout')

@section('title', __('driver.show'))

@section('content')
  <div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">{{ __('driver.profile') }}</h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('drivers.index') }}">{{ __('app.drivers') }}</a></li>
            <li class="breadcrumb-item active">{{ $driver->fullname }}</li>
          </ol>
        </nav>
      </div>
      <a href="{{ route('drivers.index') }}" class="btn btn-label-secondary">
        <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
      </a>
    </div>

    <div class="row">
      <!-- Left Column - Profile Card -->
      <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <!-- Avatar Section -->
            <div class="mb-4">
              <div class="position-relative d-inline-block">
                <img class="img-fluid rounded-circle shadow-sm" 
                     src="{{ $driver->avatar_url }}" 
                     height="120" 
                     width="120" 
                     alt="User avatar"
                     style="object-fit: cover; border: 4px solid #fff;" />
                @if($driver->user->status === \App\Constants\UserStatus::ACTIVE)
                  <span class="badge bg-success rounded-pill position-absolute" 
                        style="bottom: 5px; right: 5px; width: 20px; height: 20px; padding: 0; border: 3px solid #fff;">
                  </span>
                @endif
              </div>
              <div class="d-flex align-items-center justify-content-center gap-1">
                <h5 class="mb-1 mt-3">{{ $driver->fullname }}</h5>
              </div>
              <p class="text-primary mb-1">{{ '@' . $driver->user->username }}</p>
              <p class="text-muted small mb-0">
                <i class="bx bx-phone me-1"></i>{{ $driver->user->phone }}
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
                    <h4 class="mb-0">{{ $stats['trips_count'] }}</h4>
                  </div>
                  <small class="text-muted">{{ __('driver.trips') }}</small>
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
                  <small class="text-muted">{{ __('driver.avg_rating') }}</small>
                </div>
              </div>
            </div>

            <hr class="my-4">

            <!-- Personal Information -->
            <div class="text-start">
              <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                {{ __('driver.personal_info') }}
              </h6>
              <ul class="list-unstyled mb-0">
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-user text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('driver.first_name') }}</small>
                    <span class="fw-medium">{{ $driver->first_name ?? 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-user text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('driver.last_name') }}</small>
                    <span class="fw-medium">{{ $driver->last_name ?? 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-envelope text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('driver.email') }}</small>
                    <span class="fw-medium">{{ $driver->email ?? 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-check-circle text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('user.status') }}</small>
                    @if($driver->user->status === \App\Constants\UserStatus::ACTIVE)
                      <span class="badge bg-label-success">{{ __('user.statuses.' . \App\Constants\UserStatus::ACTIVE) }}</span>
                    @elseif($driver->user->status === \App\Constants\UserStatus::BANNED)
                      <span class="badge bg-label-danger">{{ __('user.statuses.' . \App\Constants\UserStatus::BANNED) }}</span>
                    @else
                      <span class="badge bg-label-secondary">{{ __('user.statuses.' . $driver->user->status) }}</span>
                    @endif
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-briefcase text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('driver.driver_status') }}</small>
                    @if($driver->status === \App\Constants\DriverStatus::APPROVED)
                      <span class="badge bg-label-success">{{ __('constants.approved') }}</span>
                    @elseif($driver->status === \App\Constants\DriverStatus::DENIED)
                      <span class="badge bg-label-danger">{{ __('constants.denied') }}</span>
                    @else
                      <span class="badge bg-label-warning">{{ __('constants.pending') }}</span>
                    @endif
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-phone text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('driver.phone') }}</small>
                    <span class="fw-medium">{{ $driver->phone }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-calendar text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('driver.birth_date') }}</small>
                    <span class="fw-medium">{{ $driver->birth_date ? $driver->birth_date->format('Y-m-d') : 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-building text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('driver.federation') }}</small>
                    <span class="fw-medium">{{ $driver->federation->name ?? 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-0 d-flex align-items-center">
                  <i class="bx bx-time-five text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('driver.joined_date') }}</small>
                    <span class="fw-medium">{{ $driver->user->created_at->format('Y-m-d') }}</span>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column - Statistics & Data -->
      <div class="col-xl-8 col-lg-7">
        <!-- Wallet & Subscription Row -->
        <div class="row g-3 mb-4">
          <!-- Wallet Card -->
          <div class="col-md-6">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                  <div>
                    <div class="d-flex align-items-center mb-2">
                      <i class="bx bx-wallet text-success me-2" style="font-size: 1.5rem;"></i>
                      <h6 class="mb-0">{{ __('driver.wallet') }}</h6>
                    </div>
                    <h3 class="mb-0 text-success fw-bold">{{ number_format($driver->user->wallet->balance ?? 0, 2) }} {{ __('app.DZD') }}</h3>
                    <small class="text-muted">{{ __('driver.wallet_balance') }}</small>
                  </div>
                  <div class="avatar avatar-lg bg-label-success rounded">
                    <div class="avatar-initial bg-label-success rounded">
                      <i class="bx bx-wallet" style="font-size: 2rem;"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Subscription Card -->
          <div class="col-md-6">
            @if($driver->subscription)
              <div class="card shadow-sm h-100">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between">
                    <div>
                      <div class="d-flex align-items-center mb-2">
                        <i class="bx bx-calendar-check text-primary me-2" style="font-size: 1.5rem;"></i>
                        <h6 class="mb-0">{{ __('driver.subscription') }}</h6>
                      </div>
                      <p class="mb-1">
                        <strong>{{ __('driver.subscription_status') }}:</strong>
                        @if($driver->subscription->end_date >= now())
                          <span class="badge bg-label-success ms-2">{{ __('driver.subscription_active') }}</span>
                        @else
                          <span class="badge bg-label-danger ms-2">{{ __('driver.subscription_expired') }}</span>
                        @endif
                      </p>
                      <p class="mb-0 text-muted small">
                        <strong>{{ __('driver.subscription_end_date') }}:</strong> {{ $driver->subscription->end_date->format('Y-m-d') }}
                      </p>
                    </div>
                    <div class="avatar avatar-lg bg-label-primary rounded">
                      <div class="avatar-initial bg-label-primary rounded">
                        <i class="bx bx-calendar" style="font-size: 2rem;"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @else
              <div class="card shadow-sm h-100">
                <div class="card-body text-center py-4">
                  <i class="bx bx-calendar-x text-muted mb-2" style="font-size: 3rem;"></i>
                  <p class="text-muted mb-0">{{ __('driver.no_active_subscription') }}</p>
                </div>
              </div>
            @endif
          </div>
        </div>

        <!-- Statistics Grid -->
        <div class="row g-3 mb-4">
          <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <div class="avatar-initial bg-label-primary rounded">
                      <i class="bx bx-message-square-detail"></i>
                    </div>
                  </div>
                  <h5 class="mb-0">{{ $stats['reviews_count'] }}</h5>
                </div>
                <small class="text-muted">{{ __('driver.total_reviews') }}</small>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <div class="avatar-initial bg-label-info rounded">
                      <i class="bx bx-transfer-alt"></i>
                    </div>
                  </div>
                  <h5 class="mb-0">{{ $stats['transactions_count'] }}</h5>
                </div>
                <small class="text-muted">{{ __('driver.total_transactions') }}</small>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <div class="avatar-initial bg-label-warning rounded">
                      <i class="bx bx-briefcase"></i>
                    </div>
                  </div>
                  <h5 class="mb-0">{{ $stats['services_count'] }}</h5>
                </div>
                <small class="text-muted">{{ __('driver.total_services') }}</small>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <div class="avatar-initial bg-label-success rounded">
                      <i class="bx bx-money"></i>
                    </div>
                  </div>
                  <h5 class="mb-0 text-truncate" title="{{ number_format($stats['total_earned'], 2) }} {{ __('app.DZD') }}">
                    {{ number_format($stats['total_earned'], 0) }} {{ __('app.DZD') }}
                  </h5>
                </div>
                <small class="text-muted">{{ __('driver.total_amount_earned') }}</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Vehicle Information Card -->
        @if($driver->vehicle)
          <div class="card shadow-sm mb-4">
            <div class="card-header pb-3">
              <h6 class="m-0"><i class="bx bx-car me-2"></i>{{ __('driver.vehicle_info') }}</h6>
            </div>
            <div class="card-body">
              <div class="row">
                <!-- Vehicle Details -->
                <div class="col-md-6 mb-3">
                  <div class="d-flex align-items-center">
                    <i class="bx bx-shape-circle text-muted me-2"></i>
                    <div>
                      <small class="text-muted d-block">{{ __('vehicle.model') }}</small>
                      <span class="fw-medium">{{ $driver->vehicle->model->brand->name ?? 'N/A' }} {{ $driver->vehicle->model->name ?? '' }}</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="d-flex align-items-center">
                    <i class="bx bx-palette text-muted me-2"></i>
                    <div>
                      <small class="text-muted d-block">{{ __('vehicle.color') }}</small>
                      <div class="d-flex align-items-center gap-2">
                        @if($driver->vehicle->color)
                          <span class="d-inline-block rounded" 
                                style="width: 24px; height: 24px; background-color: {{ $driver->vehicle->color->code }}; border: 2px solid #ddd;"
                                title="{{ $driver->vehicle->color->code }}"></span>
                        @endif
                        <span class="fw-medium">{{ $driver->vehicle->color->name ?? 'N/A' }}</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="d-flex align-items-center">
                    <i class="bx bx-calendar text-muted me-2"></i>
                    <div>
                      <small class="text-muted d-block">{{ __('vehicle.production_year') }}</small>
                      <span class="fw-medium">{{ $driver->vehicle->production_year ?? 'N/A' }}</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="d-flex align-items-center">
                    <i class="bx bx-hash text-muted me-2"></i>
                    <div>
                      <small class="text-muted d-block">{{ __('vehicle.plate_number') }}</small>
                      <span class="fw-medium">{{ $driver->vehicle->plate_number ?? 'N/A' }}</span>
                    </div>
                  </div>
                </div>

                <!-- Vehicle Images -->
                @php
                  $vehicleImage = $driver->vehicle->getFirstMediaUrl('image');
                  $vehiclePermit = $driver->vehicle->getFirstMediaUrl('permit');
                @endphp

                @if($vehicleImage || $vehiclePermit)
                  <div class="col-12 mt-3">
                    <hr class="my-3">
                    <div id="vehicleImagesGallery" class="d-none">
                      @if($vehicleImage)
                        <img src="{{ $vehicleImage }}" alt="{{ __('vehicle.image') }}" data-title="{{ __('vehicle.image') }}">
                      @endif
                      @if($vehiclePermit)
                        <img src="{{ $vehiclePermit }}" alt="{{ __('vehicle.permit') }}" data-title="{{ __('vehicle.permit') }}">
                      @endif
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                      @if($vehicleImage)
                        <button type="button" class="btn btn-outline-primary btn-sm view-vehicle-image" data-index="0">
                          <i class="bx bx-image me-1"></i>{{ __('vehicle.image') }}
                        </button>
                      @endif
                      @if($vehiclePermit)
                        <button type="button" class="btn btn-outline-primary btn-sm view-vehicle-image" data-index="{{ $vehicleImage ? '1' : '0' }}">
                          <i class="bx bx-file me-1"></i>{{ __('vehicle.permit') }}
                        </button>
                      @endif
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </div>
        @endif

        <!-- Cards Information -->
        @if($driver->cards->isNotEmpty())
          <div class="card shadow-sm mb-4">
            <div class="card-header pb-3">
              <h6 class="m-0"><i class="bx bx-id-card me-2"></i>{{ __('driver.cards') }}</h6>
            </div>
            <div class="card-body">
              <div class="row">
                @foreach($driver->cards as $card)
                  <div class="col-md-6 mb-4">
                    <div class="border rounded p-3 h-100">
                      <div class="d-flex align-items-start mb-3">
                        <i class="bx bx-credit-card text-primary me-2" style="font-size: 1.5rem;"></i>
                        <div class="flex-grow-1">
                          <h6 class="mb-1">{{ \App\Constants\CardType::get_name($card->type) }}</h6>
                          <p class="mb-1 small text-muted">
                            <strong>{{ __('card.number') }}:</strong> {{ $card->number }}
                          </p>
                          <p class="mb-0 small text-muted">
                            <strong>{{ __('card.expiration_date') }}:</strong> {{ $card->expiration_date ? $card->expiration_date->format('Y-m-d') : 'N/A' }}
                          </p>
                        </div>
                      </div>

                      @php
                        $frontImage = $card->getFirstMediaUrl('front_image');
                        $backImage = $card->getFirstMediaUrl('back_image');
                      @endphp

                      @if($frontImage || $backImage)
                        <div id="cardImagesGallery{{ $card->id }}" class="d-none">
                          @if($frontImage)
                            <img src="{{ $frontImage }}" alt="{{ __('card.front_image') }}" data-title="{{ \App\Constants\CardType::get_name($card->type) }} - {{ __('card.front_image') }}">
                          @endif
                          @if($backImage)
                            <img src="{{ $backImage }}" alt="{{ __('card.back_image') }}" data-title="{{ \App\Constants\CardType::get_name($card->type) }} - {{ __('card.back_image') }}">
                          @endif
                        </div>
                        <div class="d-flex gap-2 flex-wrap mt-2">
                          @if($frontImage)
                            <button type="button" class="btn btn-outline-primary btn-sm view-card-image" data-card-id="{{ $card->id }}" data-index="0">
                              <i class="bx bx-image me-1"></i>{{ __('card.front_image') }}
                            </button>
                          @endif
                          @if($backImage)
                            <button type="button" class="btn btn-outline-primary btn-sm view-card-image" data-card-id="{{ $card->id }}" data-index="{{ $frontImage ? '1' : '0' }}">
                              <i class="bx bx-image me-1"></i>{{ __('card.back_image') }}
                            </button>
                          @endif
                        </div>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif

        <!-- Services Information -->
        @if($driver->services->isNotEmpty())
          <div class="card shadow-sm mb-4">
            <div class="card-header pb-3">
              <h6 class="m-0"><i class="bx bx-briefcase me-2"></i>{{ __('driver.services') }}</h6>
            </div>
            <div class="card-body">
              <div class="d-flex flex-wrap gap-2">
                @foreach($driver->services as $service)
                  <span class="{{ 'badge bg-label-' .  \App\Constants\TripType::get_color($service->trip_type)}}" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                    {{ \App\Constants\TripType::get_name($service->trip_type) }}
                  </span>
                @endforeach
              </div>
            </div>
          </div>
        @endif

        <!-- Tabs for organized content -->
        <ul class="nav nav-pills mb-3" role="tablist">
          <li class="nav-item">
            <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#transactions" role="tab">
              <i class="bx bx-transfer me-1"></i>{{ __('driver.recent_transactions') }}
            </button>
          </li>
          <li class="nav-item">
            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#trips" role="tab">
              <i class="bx bx-trip me-1"></i>{{ __('driver.recent_trips') }}
            </button>
          </li>
          <li class="nav-item">
            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews" role="tab">
              <i class="bx bx-star me-1"></i>{{ __('driver.reviews') }}
            </button>
          </li>
        </ul>

        <div class="tab-content">
          <!-- Transactions Tab -->
          <div class="tab-pane fade show active" id="transactions" role="tabpanel">
            <div class="card shadow-sm">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>{{ __('transaction.amount') }}</th>
                      <th>{{ __('transaction.type') }}</th>
                      <th>{{ __('transaction.date') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($transactions as $transaction)
                      <tr>
                        <td>
                          <span class="fw-bold {{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($transaction->amount, 2) }} @lang('app.DZD')
                          </span>
                        </td>
                        <td>
                          <span class="{{ 'badge bg-label-' . \App\Constants\TransactionType::get_color($transaction->type)}}">
                            {{ \App\Constants\TransactionType::get_name($transaction->type) }}
                          </span>
                        </td>
                        <td>
                          <small class="text-muted">{{ $transaction->created_at->format('Y-m-d H:i') }}</small>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="3" class="text-center py-5">
                          <i class="bx bx-info-circle text-muted mb-2" style="font-size: 2rem;"></i>
                          <p class="text-muted mb-0">{{ __('app.no_data_available') }}</p>
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              @if($transactions->hasPages())
                <div class="card-footer">
                  {{ $transactions->links() }}
                </div>
              @endif
            </div>
          </div>

          <!-- Trips Tab -->
          <div class="tab-pane fade" id="trips" role="tabpanel">
            <div class="card shadow-sm">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>{{ __('trip.id') }}</th>
                      <th>{{ __('trip.type') }}</th>
                      <th>{{ __('trip.status') }}</th>
                      <th>{{ __('trip.date') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($recentTrips as $trip)
                      <tr>
                        <td>
                          <a href="{{ route('trips.show', $trip->id) }}" class="text-decoration-none">
                              <span class="fw-medium">#{{ $trip->identifier }}</span>
                          </a>
                        </td>
                        <td>
                          <span class="{{ 'badge bg-label-' . \App\Constants\TripType::get_color($trip->type)}}">
                            {{ \App\Constants\TripType::get_name($trip->type) }}
                          </span>
                        </td>
                        <td>
                          <span class="{{ 'badge bg-label-' . \App\Constants\TripStatus::get_color($trip->status)}}">
                            {{ \App\Constants\TripStatus::get_name($trip->status) }}
                          </span>
                        </td>
                        <td>
                          <small class="text-muted">{{ $trip->created_at->format('Y-m-d H:i') }}</small>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="4" class="text-center py-5">
                          <i class="bx bx-trip text-muted mb-2" style="font-size: 2rem;"></i>
                          <p class="text-muted mb-0">{{ __('app.no_data_available') }}</p>
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Reviews Tab -->
          <div class="tab-pane fade" id="reviews" role="tabpanel">
            <div class="card shadow-sm">
              <div class="card-body">
                @forelse($reviews as $review)
                  <div class="d-flex mb-4 pb-4 border-bottom">
                    <div class="flex-shrink-0">
                      <div class="avatar">
                        <div class="avatar-initial bg-label-primary rounded">
                          <i class="bx bx-star"></i>
                        </div>
                      </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                          <h6 class="mb-1">
                            <a href="{{ route('trips.show', $review->trip->id) }}" class="text-decoration-none">
                              <span class="fw-medium">#{{ $review->trip->identifier }}</span>
                          </a>
                          </h6>
                          <div class="mb-2">
                            @for($i = 0; $i < 5; $i++)
                              @if($i < $review->rating)
                                <i class="bx bxs-star text-warning"></i>
                              @else
                                <i class="bx bx-star text-muted"></i>
                              @endif
                            @endfor
                            <span class="ms-2 fw-bold text-warning">{{ $review->rating }}.0</span>
                          </div>
                        </div>
                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                      </div>
                      @if($review->comment)
                        <p class="text-muted mb-0">{{ $review->comment }}</p>
                      @else
                        <p class="text-muted fst-italic mb-0">{{ __('driver.no_review_text') }}</p>
                      @endif
                    </div>
                  </div>
                @empty
                  <div class="text-center py-5">
                    <i class="bx bx-message-square-detail text-muted mb-2" style="font-size: 3rem;"></i>
                    <p class="text-muted mb-0">{{ __('app.no_data_available') }}</p>
                  </div>
                @endforelse
              </div>
              @if($reviews->hasPages())
                <div class="card-footer">
                  {{ $reviews->links() }}
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('page-style')
  <!-- ViewerJS CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/viewerjs@1.11.6/dist/viewer.min.css">
@endsection

@section('page-script')
  <!-- ViewerJS -->
  <script src="https://cdn.jsdelivr.net/npm/viewerjs@1.11.6/dist/viewer.min.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize Vehicle Images Gallery
      const vehicleGalleryElement = document.getElementById('vehicleImagesGallery');
      if (vehicleGalleryElement) {
        const vehicleGallery = new Viewer(vehicleGalleryElement, {
          inline: false,
          title: function(image) {
            return image.alt + ' (' + (this.index + 1) + '/' + this.length + ')';
          },
          toolbar: {
            zoomIn: 1,
            zoomOut: 1,
            oneToOne: 1,
            reset: 1,
            prev: 1,
            play: 0,
            next: 1,
            rotateLeft: 1,
            rotateRight: 1,
            flipHorizontal: 1,
            flipVertical: 1,
          },
          navbar: true,
          tooltip: true,
          movable: true,
          zoomable: true,
          rotatable: true,
          scalable: true,
          transition: true,
          fullscreen: true,
          keyboard: true,
        });

        // Vehicle image buttons
        document.querySelectorAll('.view-vehicle-image').forEach(button => {
          button.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            vehicleGallery.view(index);
          });
        });
      }

      // Initialize Card Images Galleries
      @if($driver->cards->isNotEmpty())
        @foreach($driver->cards as $card)
          const cardGalleryElement{{ $card->id }} = document.getElementById('cardImagesGallery{{ $card->id }}');
          if (cardGalleryElement{{ $card->id }}) {
            const cardGallery{{ $card->id }} = new Viewer(cardGalleryElement{{ $card->id }}, {
              inline: false,
              title: function(image) {
                return image.alt + ' (' + (this.index + 1) + '/' + this.length + ')';
              },
              toolbar: {
                zoomIn: 1,
                zoomOut: 1,
                oneToOne: 1,
                reset: 1,
                prev: 1,
                play: 0,
                next: 1,
                rotateLeft: 1,
                rotateRight: 1,
                flipHorizontal: 1,
                flipVertical: 1,
              },
              navbar: true,
              tooltip: true,
              movable: true,
              zoomable: true,
              rotatable: true,
              scalable: true,
              transition: true,
              fullscreen: true,
              keyboard: true,
            });

            // Card image buttons for card {{ $card->id }}
            document.querySelectorAll('.view-card-image[data-card-id="{{ $card->id }}"]').forEach(button => {
              button.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                cardGallery{{ $card->id }}.view(index);
              });
            });
          }
        @endforeach
      @endif
    });
  </script>
@endsection
