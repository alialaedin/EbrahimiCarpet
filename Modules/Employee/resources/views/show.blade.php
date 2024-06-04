@extends('admin.layouts.master')
@section('content')
  <div class="col-12">
    <div class="col-xl-12 col-md-12 col-lg-12">

      <div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
    	</div>

      <div class="row">
        <div class="card">

          <div class="card-header border-0">
            <p class="card-title">مشخصات کارمند</p>
          </div>

          <div class="card-body">
            <div class="row">

              <div class="col-xl-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">شناسه کارمند :</span>
                  <span class="fs-14 mr-1"> {{ $employee->id }} </span>
                </div>
              </div>

              <div class="col-xl-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">نام و نام خانوادگی :</span>
                  <span class="fs-14 mr-1"> {{ $employee->name }} </span>
                </div>
              </div>

              <div class="col-xl-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">میزان حقوق :</span>
                  <span class="fs-14 mr-1"> {{ number_format($employee->salary) }} تومان </span>
                </div>
              </div>

              <div class="col-xl-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">کد ملی :</span>
                  <span class="fs-14 mr-1"> {{ $employee->national_code }} </span>
                </div>
              </div>

              <div class="col-xl-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">تلفن همراه :</span>
                  <span class="fs-14 mr-1"> {{ $employee->mobile }} </span>
                </div>
              </div>

              <div class="col-xl-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">تلفن ثابت :</span>
                  <span class="fs-14 mr-1"> {{ $employee->telephone }} </span>
                </div>
              </div>

              <div class="col-xl-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">شماره کارت :</span>
                  <span class="fs-14 mr-1"> {{ $employee->card_number }} </span>
                </div>
              </div>

              <div class="col-xl-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">شماره شبا :</span>
                  <span class="fs-14 mr-1"> {{ $employee->sheba_number }} </span>
                </div>
              </div>

              <div class="col-xl-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">نام بانک :</span>
                  <span class="fs-14 mr-1"> {{ $employee->bank_name }} </span>
                </div>
              </div>

              <div class="col-xl-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">تاریخ استخدام :</span>
                  <span class="fs-14 mr-1"> {{ verta($employee->employmented_at)->formatDate()}} </span>
                </div>
              </div>

              <div class="col-xl-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">تاریخ ثبت :</span>
                  <span class="fs-14 mr-1"> {{ verta($employee->created_at)->formatDate() }} </span>
                </div>
              </div>

              <div class="col-xl-4 col-md-6 col-12">
                <div class="d-flex align-items-center my-1">
                  <span class="fs-16 font-weight-bold ml-1">تاریخ آخرین ویرایش :</span>
                  <span class="fs-14 mr-1"> {{ verta($employee->updated_at)->formatDate() }} </span>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>

      <div class="row">


        <div class="col-lg-4 col-md-6 col-12">
          <div class="card">

            <div class="card-header border-0">
              <p class="card-title">اطلاعات پایه</p>
            </div>

            <div class="card-body">
              <div class="row">

                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">شناسه :</span>
                    <span class="fs-14 mr-1"> {{ $employee->id }} </span>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">تاریخ ثبت :</span>
                    <span class="fs-14 mr-1"> {{ verta($employee->created_at)->format('Y/m/d') }} </span>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">تاریخ استخدام :</span>
                    <span class="fs-14 mr-1"> {{ verta($employee->employmented_at)->format('Y/m/d') }} </span>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">تاریخ ویرایش :</span>
                    <span class="fs-14 mr-1"> {{ verta($employee->updated_at)->format('Y/m/d') }} </span>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>

        <div class="col-lg-4 col-md-6 col-12">
          <div class="card">

            <div class="card-header border-0">
              <p class="card-title">اطلاعات شخص</p>
            </div>

            <div class="card-body">
              <div class="row">

                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">نام و نام خانوادگی :</span>
                    <span class="fs-14 mr-1"> {{ $employee->name }} </span>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">کد ملی :</span>
                    <span class="fs-14 mr-1"> {{ $employee->natioal_code ?? '-' }} </span>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">تلفن همراه :</span>
                    <span class="fs-14 mr-1"> {{ $employee->mobile }} </span>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">تلفن ثابت :</span>
                    <span class="fs-14 mr-1"> {{ $employee->telephone }} </span>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>

        <div class="col-lg-4 col-md-6 col-12">
          <div class="card">

            <div class="card-header border-0">
              <p class="card-title ml-2">اطلاعات بانکی</p>
            </div>

            <div class="card-body">
              <div class="row">

                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">میزان حقوق (تومان) :</span>
                    <span class="fs-14 mr-1"> {{ number_format($employee->salary) }} </span>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">شماره کارت :</span>
                    <span class="fs-14 mr-1"> {{ $employee->card_number }} </span>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">شماره شبا :</span>
                    <span class="fs-14 mr-1"> {{ $employee->sheba_number ?? '-' }} </span>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">نام بانک :</span>
                    <span class="fs-14 mr-1"> {{ $employee->bank_name }} </span>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
