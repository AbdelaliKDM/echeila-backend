@php use Illuminate\Support\Facades\Route; @endphp
<ul class="menu-sub">
  @if (isset($menu))
    @foreach ($menu as $submenu)

    {{-- active menu method --}}
    @php
    $activeClass = null;
    $active = 'active open';
    $currentRouteName = Route::currentRouteName();

    if (!isset($submenu->submenu)) {
    if (gettype($submenu->slug) === 'array') {
    foreach ($submenu->slug as $slug) {
      if (str_contains($currentRouteName, $slug) and str_starts_with($currentRouteName, $slug)) {
      $activeClass = 'active';
      }
    }
    } else {
    if (str_contains($currentRouteName, $submenu->slug) and str_starts_with($currentRouteName, $submenu->slug)) {
      $activeClass = 'active';
    }
    }
    } elseif (isset($submenu->submenu)) {
    if (gettype($submenu->slug) === 'array') {
    foreach ($submenu->slug as $slug) {
      if (str_contains($currentRouteName, $slug) and strpos($currentRouteName, $slug) === 0) {
      $activeClass = $active;
      }
    }
    } else {
    if (str_contains($currentRouteName, $submenu->slug) and strpos($currentRouteName, $submenu->slug) === 0) {
      $activeClass = $active;
    }
    }
    }
    @endphp

    {{-- main menu --}}
    {{-- @php
    $hasPermission = empty($submenu->permission) || (
    is_array($submenu->permission)
    ? collect($submenu->permission)->some(fn($perm) => Auth::user()->hasPermissionTo($perm))
    : Auth::user()->hasPermissionTo($submenu->permission)
    );
    @endphp --}}

    @if ($hasPermission)
    <li class="menu-item {{$activeClass}}">
    <a href="{{ isset($submenu->route) ? route($submenu->route) : 'javascript:void(0)' }}"
      class="{{ isset($submenu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($submenu->target) and !empty($submenu->target)) target="_blank" @endif>
      @if (isset($submenu->icon))
      <i class="{{ $submenu->icon }}"></i>
    @endif
      <div>{{ isset($submenu->name) ? __($submenu->name) : '' }}</div>
    </a>

    {{-- submenu --}}
    @if (isset($submenu->submenu))
    @include('layouts.sections.menu.submenu', ['menu' => $submenu->submenu])
    @endif
    </li>
    @endif
    @endforeach
  @endif
</ul>