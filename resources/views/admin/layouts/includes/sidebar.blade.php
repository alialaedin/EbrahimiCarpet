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
          <i class="fe fe-edit sidemenu_icon"></i>
          <span class="side-menu__label">اطلاعات پایه</span><i class="angle fa fa-angle-left"></i>
        </a>
        <ul class="slide-menu">
          @role('super_admin')
            <li><a href="{{route("admin.roles.index")}}" class="slide-item">مدیریت نقش ها</a></li>
          @endrole
        </ul>
      </li>

      <li class="slide">
        <a class="side-menu__item" data-toggle="slide" href="#">
          <i class="fe fe-user sidemenu_icon"></i>
          <span class="side-menu__label">مدیریت کاربران</span><i class="angle fa fa-angle-left"></i>
        </a>
        <ul class="slide-menu">
          @role('super_admin')
            <li><a href="{{route("admin.admins.index")}}" class="slide-item">ادمین ها</a></li>
          @endrole
          @can('view customers')
            <li><a href="{{route("admin.customers.index")}}" class="slide-item">مشتری ها</a></li>
          @endcan
          @can('view personnels')
            <li><a href="{{route("admin.personnels.index")}}" class="slide-item">پرسنل</a></li>
          @endcan
          @can('view suppliers')
            <li><a href="{{route("admin.suppliers.index")}}" class="slide-item">تامین کنندگان</a></li>
          @endcan
        </ul>
      </li>

      <li class="slide">
        <a class="side-menu__item" data-toggle="slide" href="#">
          <i class="fa fa-shopping-basket sidemenu_icon"></i>
          <span class="side-menu__label">مدیریت محصولات</span><i class="angle fa fa-angle-left"></i>
        </a>
        <ul class="slide-menu">
          @can('view categories')
            <li><a href="{{route("admin.categories.index")}}" class="slide-item">دسته بندی ها</a></li>
          @endcan
          @can('view products')
            <li><a href="{{route("admin.products.index")}}" class="slide-item">محصولات</a></li>
          @endcan
        </ul>
      </li>

      <li class="slide">
        <a class="side-menu__item" data-toggle="slide" href="#">
          <i class="fe fe-shopping-cart sidemenu_icon"></i>
          <span class="side-menu__label">مدیریت سفارشات</span><i class="angle fa fa-angle-left"></i>
        </a>
        <ul class="slide-menu">
          @can('view purchases')
            <li><a href="{{route("admin.purchases.index")}}" class="slide-item">خرید ها</a></li>
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
