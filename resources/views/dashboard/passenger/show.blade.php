@extends('layouts/contentNavbarLayout')

@section('title', __('passenger.show'))

@section('content')
  <div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">{{ __('passenger.profile') }}</h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('passengers.index') }}">{{ __('app.passengers') }}</a></li>
            <li class="breadcrumb-item active">{{ $passenger->fullname }}</li>
          </ol>
        </nav>
      </div>
      <a href="{{ route('passengers.index') }}" class="btn btn-label-secondary">
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
                     src="{{ $passenger->avatar_url }}" 
                     height="120" 
                     width="120" 
                     alt="User avatar"
                     style="object-fit: cover; border: 4px solid #fff;" />
                @if($passenger->user->status === \App\Constants\UserStatus::ACTIVE)
                  <span class="badge bg-success rounded-pill position-absolute" 
                        style="bottom: 5px; right: 5px; width: 20px; height: 20px; padding: 0; border: 3px solid #fff;">
                  </span>
                @endif
              </div>
              <div class="d-flex align-items-center justify-content-center gap-1">
                <h5 class="mb-1 mt-3">{{ $passenger->fullname }}</h5>
              </div>
              <p class="text-primary mb-1">{{ '@' . $passenger->user->username }}</p>
              <p class="text-muted small mb-0">
                <i class="bx bx-phone me-1"></i>{{ $passenger->user->phone }}
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
                  <small class="text-muted">{{ __('passenger.trips') }}</small>
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
                  <small class="text-muted">{{ __('passenger.avg_rating') }}</small>
                </div>
              </div>
            </div>

            <hr class="my-4">

            <!-- Personal Information -->
            <div class="text-start">
              <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                {{ __('passenger.personal_info') }}
              </h6>
              <ul class="list-unstyled mb-0">
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-user text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('passenger.first_name') }}</small>
                    <span class="fw-medium">{{ $passenger->first_name ?? 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-user text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('passenger.last_name') }}</small>
                    <span class="fw-medium">{{ $passenger->last_name ?? 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-check-circle text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('user.status') }}</small>
                    @if($passenger->user->status === \App\Constants\UserStatus::ACTIVE)
                      <span class="badge bg-label-success">{{ __('user.statuses.' . \App\Constants\UserStatus::ACTIVE) }}</span>
                    @elseif($passenger->user->status === \App\Constants\UserStatus::BANNED)
                      <span class="badge bg-label-danger">{{ __('user.statuses.' . \App\Constants\UserStatus::BANNED) }}</span>
                    @else
                      <span class="badge bg-label-secondary">{{ __('user.statuses.' . $passenger->user->status) }}</span>
                    @endif
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-phone text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('passenger.phone') }}</small>
                    <span class="fw-medium">{{ $passenger->phone }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-calendar text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('passenger.birth_date') }}</small>
                    <span class="fw-medium">{{ $passenger->birth_date ? $passenger->birth_date->format('Y-m-d') : 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-0 d-flex align-items-center">
                  <i class="bx bx-time-five text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('passenger.joined_date') }}</small>
                    <span class="fw-medium">{{ $passenger->user->created_at->format('Y-m-d') }}</span>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column - Statistics & Data -->
      <div class="col-xl-8 col-lg-7">
        <!-- Wallet Card -->
        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="d-flex align-items-center mb-2">
                  <i class="bx bx-wallet text-success me-2" style="font-size: 1.5rem;"></i>
                  <h6 class="mb-0">{{ __('passenger.wallet') }}</h6>
                </div>
                <h3 class="mb-0 text-success fw-bold">{{ number_format($passenger->user->wallet->balance ?? 0, 2) }} {{ __('app.DZD') }}</h3>
                <small class="text-muted">{{ __('passenger.wallet_balance') }}</small>
              </div>
              <div class="avatar avatar-lg bg-label-success rounded">
                <div class="avatar-initial bg-label-success rounded">
                  <i class="bx bx-wallet" style="font-size: 2rem;"></i>
                </div>
              </div>
            </div>
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
                <small class="text-muted">{{ __('passenger.total_reviews') }}</small>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <div class="avatar-initial bg-label-warning rounded">
                      <i class="bx bx-package"></i>
                    </div>
                  </div>
                  <h5 class="mb-0">{{ $stats['cargos_count'] }}</h5>
                </div>
                <small class="text-muted">{{ __('passenger.total_cargos') }}</small>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <div class="avatar-initial bg-label-danger rounded">
                      <i class="bx bx-search"></i>
                    </div>
                  </div>
                  <h5 class="mb-0">{{ $stats['lost_and_founds_count'] }}</h5>
                </div>
                <small class="text-muted">{{ __('passenger.total_lost_and_founds') }}</small>
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
                  <h5 class="mb-0 text-truncate" title="{{ number_format($stats['total_spent'], 2) }} {{ __('app.DZD') }}">
                    {{ number_format($stats['total_spent'], 0) }} {{ __('app.DZD') }}
                  </h5>
                </div>
                <small class="text-muted">{{ __('passenger.total_amount_spent') }}</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabs for organized content -->
        <ul class="nav nav-pills mb-3" role="tablist">
          <li class="nav-item">
            <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#transactions" role="tab">
              <i class="bx bx-transfer me-1"></i>{{ __('passenger.recent_transactions') }}
            </button>
          </li>
          <li class="nav-item">
            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#trips" role="tab">
              <i class="bx bx-trip me-1"></i>{{ __('passenger.recent_trips') }}
            </button>
          </li>
          <li class="nav-item">
            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews" role="tab">
              <i class="bx bx-star me-1"></i>{{ __('passenger.reviews') }}
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
                      <th>{{ __('trip.driver') }}</th>
                      <th>{{ __('trip.type') }}</th>
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
                          @if($trip->driver)
                            <a href="{{ route('drivers.show', $trip->driver->user->id) }}" class="text-decoration-none">
                              <div class="d-flex align-items-center">
                                <i class="bx bx-user-circle me-1"></i>
                                <span>{{ $trip->driver->fullname }}</span>
                              </div>
                            </a>
                          @else
                            <span class="text-muted">N/A</span>
                          @endif
                        </td>
                        <td>
                          <span class="{{ 'badge bg-label-' . \App\Constants\TripType::get_color($trip->type)}}">
                              {{ \App\Constants\TripType::get_name($trip->type) }}
                            </span>
                        </td>
                        <td>
                          <small class="text-muted">{{ $trip->created_at->format('Y-m-d H:i') }}</small>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="3" class="text-center py-5">
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
                        <p class="text-muted fst-italic mb-0">No review text provided</p>
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