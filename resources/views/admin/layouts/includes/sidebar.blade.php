<aside class="app-sidebar">
  <div class="app-sidebar__user">
    <div class="dropdown user-pro-body text-center">
      <div class="user-info">
        <span class="text-light fs-18">مدیریت فرش ابراهیمی</span>
      </div>
    </div>
  </div>
  <div class="app-sidebar3 mt-0">

    <ul class="side-menu">

      @can('view dashboard stats')
        <li class="slide">
          <a class="side-menu__item" href="{{route("admin.dashboard")}}">
            <i class="feather feather-home sidemenu_icon"></i>
            <span class="side-menu__label">داشبورد</span>
          </a>
        </li>
      @endcan

      @role('super_admin')
      <li class="slide">
        <a class="side-menu__item" data-toggle="slide" href="#">
          <i class="fe fe-edit sidemenu_icon"></i>
          <span class="side-menu__label">اطلاعات پایه</span><i class="angle fa fa-angle-left"></i>
        </a>
        <ul class="slide-menu">
          <li><a href="{{route("admin.roles.index")}}" class="slide-item">مدیریت نقش ها</a></li>
          <li><a href="{{route("admin.accounts.index")}}" class="slide-item">مدیریت حساب ها</a></li>
        </ul>
      </li>
      @endrole

      @canany(['view customers', 'view employees', 'view suppliers'])
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
            @can('view employees')
              <li><a href="{{route("admin.employees.index")}}" class="slide-item">پرسنل</a></li>
            @endcan
            @can('view suppliers')
              <li><a href="{{route("admin.suppliers.index")}}" class="slide-item">تامین کنندگان</a></li>
            @endcan
          </ul>
        </li>
      @endcanany

      @canany(['view categories', 'view products'])
        <li class="slide">
          <a class="side-menu__item" data-toggle="slide" href="#">
            <i class="fe fe-package sidemenu_icon"></i>
            <span class="side-menu__label">مدیریت محصولات</span><i class="angle fa fa-angle-left"></i>
          </a>
          <ul class="slide-menu">
            @can('view categories')
              <li><a href="{{route("admin.categories.index")}}" class="slide-item">دسته بندی ها</a></li>
            @endcan
            @can('view products')
              <li><a href="{{route("admin.products.index")}}" class="slide-item">محصولات</a></li>
              {{-- <li><a href="{{route("admin.pricing.create")}}" class="slide-item">قیمت گذاری</a></li> --}}
            @endcan
          </ul>
        </li>
      @endcanany

      @canany(['view purchases', 'view sales'])
        <li class="slide">
          <a class="side-menu__item" data-toggle="slide" href="#">
            <i class="fe fe-shopping-cart sidemenu_icon"></i>
            <span class="side-menu__label">مدیریت سفارشات</span><i class="angle fa fa-angle-left"></i>
          </a>
          <ul class="slide-menu">
            @can('view purchases')
              <li><a href="{{route("admin.purchases.index")}}" class="slide-item">خرید ها</a></li>
            @endcan
            @can('view sales')
              <li><a href="{{route("admin.sales.index")}}" class="slide-item">فروش ها</a></li>
            @endcan
          </ul>
        </li>
      @endcanany

      @can('view payments')
        <li class="slide">
          <a class="side-menu__item" data-toggle="slide" href="#">
            <i class="fa fa-money sidemenu_icon"></i>
            <span class="side-menu__label">مدیریت پرداختی ها</span><i class="angle fa fa-angle-left"></i>
          </a>
          <ul class="slide-menu">
            <li><a href="{{route("admin.payments.index")}}" class="slide-item">تمامی پرداختی ها</a></li>
            <li><a href="{{route("admin.payments.installments")}}" class="slide-item">اقساط</a></li>
            <li><a href="{{route("admin.payments.cheques")}}" class="slide-item">چک ها</a></li>
            <li><a href="{{route("admin.payments.cashes")}}" class="slide-item">نقدی ها</a></li>
          </ul>
        </li>
      @endcan
      @can('view sale_payments')
        <li class="slide">
          <a class="side-menu__item" data-toggle="slide" href="#">
            <i class="fa fa-money sidemenu_icon"></i>
            <span class="side-menu__label">مدیریت دریافتی ها</span><i class="angle fa fa-angle-left"></i>
          </a>
          <ul class="slide-menu">
            <li><a href="{{route("admin.sale-payments.index")}}" class="slide-item">تمامی دریافتی ها</a></li>
            <li><a href="{{route("admin.sale-payments.installments")}}" class="slide-item">اقساط</a></li>
            <li><a href="{{route("admin.sale-payments.cheques")}}" class="slide-item">چک ها</a></li>
            <li><a href="{{route("admin.sale-payments.cashes")}}" class="slide-item">نقدی ها</a></li>
          </ul>
        </li>
      @endcan

      @canany(['view headlines', 'view revenues', 'view salaries', 'view costs'])
        <li class="slide">
          <a class="side-menu__item" data-toggle="slide" href="#">
            <i class="fe fe-dollar-sign sidemenu_icon"></i>
            <span class="side-menu__label">مدیریت هزینه / درامد </span><i class="angle fa fa-angle-left"></i>
          </a>
          <ul class="slide-menu">
            @can('view headlines')
              <li><a href="{{route("admin.headlines.index")}}" class="slide-item">سرفصل ها</a></li>
            @endcan
            @can('view expenses')
              <li><a href="{{route("admin.expenses.index")}}" class="slide-item">هزینه ها</a></li>
            @endcan
            @can('view revenues')
              <li><a href="{{route("admin.revenues.index")}}" class="slide-item">درامد ها</a></li>
            @endcan
            @can('view salaries')
              <li><a href="{{route("admin.salaries.index")}}" class="slide-item">حقوق ها</a></li>
            @endcan
          </ul>
        </li>
      @endcanany

      @role('super_admin')
      <li class="slide">
        <a class="side-menu__item" href="{{route("admin.stores.index")}}">
          <i class="fe fe-database sidemenu_icon"></i>
          <span class="side-menu__label">مدیریت انبار</span>
        </a>
      </li>

      <li class="slide">
        <a class="side-menu__item" href="{{route("admin.reports.index")}}">
          <i class="fe fe-clipboard sidemenu_icon"></i>
          <span class="side-menu__label">گزارشات</span>
        </a>
      </li>
      @endrole
    </ul>
  </div>
</aside>
