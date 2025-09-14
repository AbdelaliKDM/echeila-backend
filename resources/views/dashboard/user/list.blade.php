@extends('layouts/contentNavbarLayout')

@section('title', __('app.user'))

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
    <span class="text-muted fw-light">@lang('app.dashboard') /</span> @lang('app.users')
    </div>
    <div class="col-md-auto">
    @permission(\App\Support\Enum\PermissionNames::MANAGE_USERS)
    <a href="{{ route('users.create') }}" class="text-white text-decoration-none">
      <button type="button" class="btn btn-primary" style="float: {{app()->getLocale() == 'ar' ? 'left' : 'right'}}">
      <span class="tf-icons bx bx-plus"></span> @lang('app.add')
      </button>
    </a>
    @endpermission
    </div>
  </h4>

  <!-- Bootstrap Table with Header - Light -->
  <div class="card">
    <h5 class="card-header">@lang('app.users')</h5>
    <div class="table-responsive text-nowrap">
    <div class="table-header row justify-content-between">
      <h5 class="col-md-auto">@lang('app.filter')</h5>
      <div class="col-md-auto">
      <select class="form-select filter-select filter-input" id="user_type_filter" name="user_type_filter">
        <option value="">@lang('app.type') (@lang('app.all'))</option>
        @foreach(\App\Support\Enum\UserTypes::lists() as $key => $value)
      <option value="{{ $key }}">{{ $value }}</option>
      @endforeach
      </select>
      </div>
      <div class="col-md-auto">
      <select class="form-select filter-select filter-input" id="user_role_filter" name="user_role_filter">
        <option value="">@lang('app.role') (@lang('app.all'))</option>
        @foreach(\App\Support\Enum\UserRoles::lists() as $key => $value)
      <option value="{{ $key }}">{{ $value }}</option>
      @endforeach
      </select>
      </div>
      <div class="col-md-auto">
      <select class="form-select filter-select filter-input" id="user_status_filter" name="user_status_filter">
        <option value="">@lang('app.status') (@lang('app.all'))</option>
        @foreach(\App\Constants\Statuses\UserStatus::all2() as $key => $value)
      <option value="{{ $key }}">{{ $value }}</option>
      @endforeach
      </select>
      </div>
    </div>
    <table id="laravel_datatable" class="table">
      <thead class="table-light">
      <tr>
        <th>#</th>
        @foreach ($columns as $column)
      <th>{{ __('app.' . $column) }}</th>
      @endforeach
      </tr>
      </thead>
    </table>
    </div>
  </div>
  <!-- Bootstrap Table with Header - Light -->

  <!-- Delete Form -->
  <form method="POST" action="" id="delete-form" style="display: none;">
    @csrf
    @method('DELETE')
  </form>

@endsection
@section('page-script')
  <script>
    $(document).ready(function () {
    let filters = {
      user_type_filter: $('#user_type_filter').val(),
      user_role_filter: $('#user_role_filter').val(),
      user_status_filter: $('#user_status_filter').val()
    };

    let table = initializeDataTable(
      "{{ route('users.index') }}",
      @json($columns),
      filters
    );

    // Reload DataTable when the filter value changes
    $('.filter-input').on('change', function () {
      let filterName = $(this).attr('id');
      filters[filterName] = $(this).val();
      table.ajax.reload();
    });

    handleActionConfirmation('table_id', "@lang('app.delete')", '#delete-form', '.delete');
    });
  </script>
@endsection