@extends('layouts/contentNavbarLayout')

@section('title', __('app.user'))

@section('content')
  <h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">{{ __($edit ? 'app.edit' : 'app.add') }}/</span> @lang('app.user')
    @permission(\App\Support\Enum\PermissionNames::MANAGE_USERS)
    <a href="{{ route('users.index') }}" class="text-white text-decoration-none">
      <button type="button" class="btn btn-primary" style="float: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
        <span class="tf-icons bx bx-arrow-back"></span> @lang('app.back')
      </button>
    </a>
    @endpermission
  </h4>

  @if ($edit)
 <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
   @csrf
   @method('PATCH')
   @else
     <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
       @csrf
       @endif

       <div class="row">
         <div class="col-xl">
           <div class="card mb-4">
             <div class="card-body">
               <div class="mb-3">
                 <label for="firstname" class="form-label">{{ __('app.firstname') }}</label>
                 <input type="text" name="firstname" class="form-control" id="firstname" placeholder="{{ __('app.fullname_ar') }}" value="{{ old('fullname_ar', $user->fullname_ar ?? '') }}" required>
               </div>
               <div class="mb-3">
                 <label for="lastname" class="form-label">{{ __('app.lastname') }}</label>
                 <input type="text" name="lastname" class="form-control" id="lastname" placeholder="{{ __('app.fullname_fr') }}" value="{{ old('fullname_fr', $user->fullname_fr ?? '') }}" required>
               </div>
               {{-- <div class="mb-3">
                 <label for="username" class="form-label">{{ __('app.username') }}</label>
                 <input type="text" name="username" class="form-control" id="username" placeholder="{{ __('app.username') }}" value="{{ old('username', $user->username ?? '') }}" required>
               </div> --}}
               <div class="mb-3">
                 <label for="email" class="form-label">{{ __('app.email') }}</label>
                 <input type="text" name="email" class="form-control" id="email" placeholder="{{ __('app.email') }}" value="{{ old('email', $user->email ?? '') }}" required>
               </div>
               <div class="mb-3">
                 <label for="phone" class="form-label">{{ __('app.phone') }}</label>
                 <input type="text" name="phone" class="form-control phone-mask" id="phone" placeholder="{{ __('app.phone') }}" value="{{ old('phone', $user->phone ?? '') }}" required>
               </div>

               @if(!$edit)
                 <div class="mb-3">
                   <div class="form-password-toggle">
                     <label for="password" class="form-label">{{ __('app.password') }}</label>
                     <div class="input-group input-group-merge">
                       <input type="text" name="password" class="form-control" id="password" placeholder="············" value="{{ old('password') }}" required>
                       <span class="input-group-text cursor-pointer" id="basic-default-password"><i class="bx bx-show"></i></span>
                     </div>
                   </div>
                 </div>
               @endif

               <div class="mb-3">
                 <label for="gender" class="form-label">{{ __('app.gender') }}</label>
                 <select name="gender" class="form-select" id="gender" required>
                   <option value="">{{ __('app.select_option') }}</option>
                   @foreach(\App\Constants\Gender::all2() as $key => $value)
                     <option value="{{ $key }}" {{ old('gender', $user->gender ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                   @endforeach
                 </select>
               </div>

               <div class="mb-3">
                 <label for="type" class="form-label">{{ __('app.type') }}</label>
                 <select name="type" class="form-select" id="type" required>
                   <option value="">{{ __('app.select_option') }}</option>
                   @foreach(\App\Support\Enum\UserTypes::lists() as $key => $value)
                     <option value="{{ $key }}" {{ old('type', $user->type ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                   @endforeach
                 </select>
               </div>

               <div class="mb-3">
                 <label for="role" class="form-label">{{ __('app.role') }}</label>
                 <select name="role" class="form-select" id="role" required disabled>
                   <option value="">{{ __('app.select_option') }}</option>
                 </select>
               </div>

               <div class="mb-3">
                 <label for="avatar" class="form-label">{{ __('app.avatar') }}</label>
                 <div class="input-group">
                   <input type="file" name="avatar" class="form-control" id="avatar" placeholder="{{ __('app.avatar') }}">
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

     @section('scripts')
       <script>
         @php
           $adminOptions = array_merge(
               ['' => __('app.select_option')],
               \App\Support\Enum\UserRoles::lists()
           );
         @endphp

         const secondSelectOptions = {
           'admin': @json($adminOptions),
         };

         const typeSelect = document.getElementById('type');
         const roleSelect = document.getElementById('role');

         function populateRoleOptions(selectedValue) {
           roleSelect.innerHTML = '';

           if (selectedValue && secondSelectOptions[selectedValue]) {
             roleSelect.removeAttribute('disabled');
             for (let key in secondSelectOptions[selectedValue]) {
               let option = document.createElement('option');
               option.value = key;
               option.textContent = secondSelectOptions[selectedValue][key];
               roleSelect.appendChild(option);
             }
           } else {
             roleSelect.setAttribute('disabled', 'disabled');
             let defaultOption = document.createElement('option');
             defaultOption.value = '';
             defaultOption.textContent = '{{ __('app.select_option') }}';
             roleSelect.appendChild(defaultOption);
           }
         }

         typeSelect.addEventListener('change', function() {
           populateRoleOptions(this.value);
         });

         document.addEventListener('DOMContentLoaded', function() {
           @if ($edit)
           const initialType = typeSelect.value;
           const initialRole = '{{ old('role', $user->getRoleNames()->first() ?? '') }}';
           populateRoleOptions(initialType);
           if (initialRole) {
             roleSelect.value = initialRole;
           }
           @endif
         });
       </script>
 @endsection
