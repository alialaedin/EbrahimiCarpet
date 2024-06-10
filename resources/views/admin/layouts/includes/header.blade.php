<div class="app-header header">
  <div class="container-fluid">
    <div class="d-flex">
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
