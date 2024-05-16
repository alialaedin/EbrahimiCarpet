<div class="app-header header">
  <div class="container-fluid">
    <div class="d-flex">
      <a class="header-brand" href="index.html">
        <img src="{{asset('assets/images/brand/logo.png')}}" class="header-brand-img desktop-lgo" alt="Dayonelogo">
        <img src="{{asset('assets/images/brand/logo-white.png')}}" class="header-brand-img dark-logo" alt="Dayonelogo">
        <img src="{{asset('assets/images/brand/favicon.png')}}" class="header-brand-img mobile-logo" alt="Dayonelogo">
        <img src="{{asset('assets/images/brand/favicon1.png')}}" class="header-brand-img darkmobile-logo" alt="Dayonelogo">
      </a>
      <div class="app-sidebar__toggle" data-toggle="sidebar">
        <a class="open-toggle" href="#">
          <i class="feather feather-menu"></i>
        </a>
        <a class="close-toggle" href="#">
          <i class="feather feather-x"></i>
        </a>
      </div>
      <div class="d-flex order-lg-2 my-auto mr-auto">
        <div class="dropdown header-fullscreen">
          <a class="nav-link icon full-screen-link">
            <i class="feather feather-maximize fullscreen-button fullscreen header-icons"></i>
            <i class="feather feather-minimize fullscreen-button exit-fullscreen header-icons"></i>
          </a>
        </div>
        <div class="dropdown header-notify">
          <a class="nav-link icon" data-toggle="sidebar-right" data-target=".sidebar-right">
            <i class="feather feather-bell header-icon"></i>
          </a>
        </div>
        <div class="dropdown profile-dropdown">
          <a href="#" class="nav-link pr-1 pl-0 leading-none" data-toggle="dropdown">
            <span>
              <img src="{{asset('assets/images/users/16.jpg')}}" alt="img" class="avatar avatar-md bradius">
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow animated">
            <a class="dropdown-item d-flex">
              <i class="feather feather-user ml-3 fs-16 my-auto"></i>
              <div class="mt-1">پروفایل</div>
            </a>
            <button type="button" class="dropdown-item d-flex" data-toggle="modal" data-target="#changePassswordForm">
              <i class="feather feather-edit-2 ml-3 fs-16 my-auto"></i>
              <div class="mt-1">تغییر کلمه عبور</div>
            </button>
            <a class="dropdown-item d-flex">
              <i class="feather feather-power ml-3 fs-16 my-auto"></i>
              <div class="mt-1">خروج</div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- <div class="sidebar sidebar-right sidebar-animate ps ps--active-y">
  <div class="card-header border-bottom pb-5">
    <h4 class="card-title">اعلان ها </h4>
    <div class="card-options">
      <a href="#" class="btn btn-sm btn-icon btn-light text-primary" data-toggle="sidebar-right" data-target=".sidebar-right"><i class="feather feather-x"></i> </a>
    </div>
  </div>
  <div class="">
    <div class="list-group-item  align-items-center border-0">
      <div class="d-flex">
        @forelse ($notifications as $notification)
          <div class="mt-1">
            <a href="{{ route('admin.notifications.show', $notification) }}" class="font-weight-semibold fs-16">
              {{ $notification->body }}
            </a>
            <span class="clearfix"></span>
            <span class="text-muted fs-13 ml-auto">
              <i class="mdi mdi-clock text-muted mr-1"></i>
              {{ $notification->created_at->diffForHumans() }}
            </span>
          </div>
        @empty
          <span class="text-danger mt-1">اعلانی ندارید</span>
        @endforelse
      </div>
    </div>
  </div>
  <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
    <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
  </div>
  <div class="ps__rail-y" style="top: 0px; height: 703px; right: 347px;">
    <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 488px;"></div>
  </div>
</div> --}}