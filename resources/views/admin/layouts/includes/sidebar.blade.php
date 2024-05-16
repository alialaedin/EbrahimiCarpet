<aside class="app-sidebar">
  <div class="app-sidebar__logo">
    <a class="header-brand" href="index.html">
      {{-- <img width="200" height="50" src="{{ Storage::url($logo) }}" class="header-brand-img dark-logo" alt="Dayonelogo"> --}}
    </a>
  </div>
  <div class="app-sidebar3">
    <div class="app-sidebar__user active">
      <div class="dropdown user-pro-body text-center">
        <div class="user-pic">
          {{-- <img src="{{ Storage::url($prof) }}" class="avatar-xxl rounded-circle mb-1"> --}}
        </div>
        <div class="user-info">
        </div>
      </div>
    </div>
    <ul class="side-menu">

       <li class="slide">
        <a class="side-menu__item"  href="{{route("admin.dashboard")}}">
          <i class="feather feather-home sidemenu_icon"></i>
          <span class="side-menu__label">داشبورد</span>
        </a>
      </li>

      <li class="slide">
        <a class="side-menu__item" data-toggle="slide" href="#">
          <i class="fe fe-user sidemenu_icon"></i>
          <span class="side-menu__label">مدیریت اشخاص</span><i class="angle fa fa-angle-left"></i>
        </a>
        <ul class="slide-menu">
          @can('view admins')
            <li><a href="{{route("admin.admins.index")}}" class="slide-item">کاربران سایت</a></li>
          @endcan
        </ul>
      </li>

{{--
      @can('view users')
        <li class="slide">
          <a class="side-menu__item"  href="{{route("admin.users.index")}}">
            <i class="feather feather-user sidemenu_icon"></i>
            <span class="side-menu__label">ادمین ها</span>
          </a>
        </li>
      @endcan

      

      @can('view insurances')
        <li class="slide">
          <a class="side-menu__item"  href="{{route("admin.insurances.index")}}">
            <i class="fe fe-shield sidemenu_icon"></i>
            <span class="side-menu__label">بیمه ها</span>
          </a>
        </li>
      @endcan

      @can('view surgeries')
        <li class="slide">
          <a class="side-menu__item"  href="{{route("admin.surgeries.index")}}">
            <i class="fe fe-activity sidemenu_icon"></i>
            <span class="side-menu__label">جراحی ها</span>
          </a>
        </li>
      @endcan
      
      <li class="slide">
        <a class="side-menu__item" data-toggle="slide" href="#">
          <i class="fe fe-dollar-sign sidemenu_icon"></i>
          <span class="side-menu__label"> مالی</span><i class="angle fa fa-angle-left"></i>
        </a>
        <ul class="slide-menu">
          <li><a href="{{route("admin.payment-doctor.index")}}" class="slide-item">پرداخت به پزشک</a></li>
          @can('view invoices')
            <li><a href="{{route("admin.invoices.index")}}" class="slide-item">مدیریت صورتحساب ها</a></li>
          @endcan
          @can('view payments')
            <li><a href="{{route("admin.payments.index")}}" class="slide-item">مدیریت پرداختی ها</a></li>
          @endcan
        </ul>
      </li>

      <li class="slide">
        <a class="side-menu__item" data-toggle="slide" href="#">
          <i class="fe fe-clipboard sidemenu_icon"></i>
          <span class="side-menu__label">گزارش</span><i class="angle fa fa-angle-left"></i>
        </a>
        <ul class="slide-menu">
          @can('view logs')
            <li><a href="{{route("admin.activity.index")}}" class="slide-item">مدیریت فعالیت ها</a></li>
          @endcan
          @can('view notifications')
            <li><a href="{{route("admin.notifications.index")}}" class="slide-item">مدیریت اعلان ها</a></li>
          @endcan
          <li><a href="{{route("admin.financial-report-of-doctors.form")}}" class="slide-item">گزارش مالی پزشکان</a></li>
          <li><a href="{{route("admin.insurance-report.form")}}" class="slide-item">گزارش بیمه ها</a></li>
        </ul>
      </li>

      @can('view setting groups')
        <li class="slide">
          <a class="side-menu__item"  href="{{route("admin.setting.index")}}">
            <i class="fe fe-settings sidemenu_icon"></i>
            <span class="side-menu__label">تنظیمات</span>
          </a>
        </li>
      @endcan --}}

    </ul>
  </div>
</aside>
