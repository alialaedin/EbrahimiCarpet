@extends('admin.layouts.master')
@section('content')
  <div class="col-12">
    <div class="col-xl-12 col-md-12 col-lg-12">

      <div class="page-header">
        <x-core::breadcrumb :items="$breadcrumbItems" />
    	</div>

      <div class="row">

        {{-- <div class="col-xl-9 col-md-12 col-lg-12">
          <div class="tab-menu-heading hremp-tabs p-0 ">
            <div class="tabs-menu1">
              <!-- Tabs -->
              <ul class="nav panel-tabs">
                <li class="mr-4"><a href="#tab5" class="active" data-toggle="tab">اطلاعات پایه</a></li>
                <li><a href="#tab6" data-toggle="tab" class="">اطلاعات شخص</a></li>
                <li><a href="#tab7" data-toggle="tab">اطلاعات بانکی</a></li>
              </ul>
            </div>
          </div>
          <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
            <div class="tab-content">
              <div class="tab-pane active" id="tab5">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-2">
                      <span class="fs-16 font-weight-bold mb-0 mt-2">شناسه :</span>
                    </div>
                    <div class="col-md-10">
                      <span class="fs-14"> {{ $personnel->id }} </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> --}}

        <div class="col-lg-4 col-md-6 col-12">
          <div class="card">
    
            <div class="card-header border-0 justify-content-between ">
              <div class="d-flex">
                <p class="card-title ml-2" style="font-weight: bolder;">اطلاعات پایه</p>
              </div>
            </div>
    
            <div class="card-body">
              <div class="row">
    
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">شناسه :</span>
                    <span class="fs-14 mr-1"> {{ $personnel->id }} </span>
                  </div>
                </div>
    
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">تاریخ ثبت :</span>
                    <span class="fs-14 mr-1"> {{ verta($personnel->created_at)->format('Y/m/d') }} </span>
                  </div>
                </div>
    
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">تاریخ استخدام :</span>
                    <span class="fs-14 mr-1"> {{ verta($personnel->employmented_at)->format('Y/m/d') }} </span>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">تاریخ ویرایش :</span>
                    <span class="fs-14 mr-1"> {{ verta($personnel->updated_at)->format('Y/m/d') }} </span>
                  </div>
                </div>
    
              </div>
            </div>
    
          </div>
        </div>

        <div class="col-lg-4 col-md-6 col-12">
          <div class="card">
    
            <div class="card-header border-0 justify-content-between ">
              <div class="d-flex">
                <p class="card-title ml-2" style="font-weight: bolder;">اطلاعات شخص</p>
              </div>
            </div>
    
            <div class="card-body">
              <div class="row">
    
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">نام و نام خانوادگی :</span>
                    <span class="fs-14 mr-1"> {{ $personnel->name }} </span>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">کد ملی :</span>
                    <span class="fs-14 mr-1"> {{ $personnel->natioal_code ?? '-' }} </span>
                  </div>
                </div>
    
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">تلفن همراه :</span>
                    <span class="fs-14 mr-1"> {{ $personnel->mobile }} </span>
                  </div>
                </div>
    
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">تلفن ثابت :</span>
                    <span class="fs-14 mr-1"> {{ $personnel->telephone }} </span>
                  </div>
                </div>
    
              </div>
            </div>
    
          </div>
        </div>

        <div class="col-lg-4 col-md-6 col-12">
          <div class="card">
    
            <div class="card-header border-0 justify-content-between ">
              <div class="d-flex">
                <p class="card-title ml-2" style="font-weight: bolder;">اطلاعات بانکی</p>
              </div>
            </div>
    
            <div class="card-body">
              <div class="row">
    
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">میزان حقوق (تومان) :</span>
                    <span class="fs-14 mr-1"> {{ number_format($personnel->salary) }} </span>
                  </div>
                </div>
    
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">شماره کارت :</span>
                    <span class="fs-14 mr-1"> {{ $personnel->card_number }} </span>
                  </div>
                </div>
    
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">شماره شبا :</span>
                    <span class="fs-14 mr-1"> {{ $personnel->sheba_number ?? '-' }} </span>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center my-1">
                    <span class="fs-16 font-weight-bold">نام بانک :</span>
                    <span class="fs-14 mr-1"> {{ $personnel->bank_name }} </span>
                  </div>
                </div>
    
              </div>
            </div>
    
          </div>
        </div>

        

        {{-- <div class="card">
  
          <div class="card-header border-0 justify-content-between ">
            <div class="d-flex">
              <p class="card-title ml-2" style="font-weight: bolder;">مشخصات فرد</p>
            </div>
          </div>
  
          <div class="card-body">

            <div class="row">

              <div class="col-xl-4 col-lg-4 col-md-6 col-12 my-2">
                <div class="align-items-center">
                  <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-6 col-12">
                      <span class="fs-16 font-weight-bold">شناسه :</span>
                    </div>
                    <div class="col-xl-8 col-lg-7 col-md-6 col-12">
                      <span class="fs-14 mr-1"> {{ number_format($personnel->salary) }} </span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-4 col-lg-4 col-md-6 col-12 my-2">
                <div class="align-items-center">
                  <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-6 col-12">
                      <span class="fs-16 font-weight-bold">میزان حقوق (تومان) :</span>
                    </div>
                    <div class="col-xl-8 col-lg-7 col-md-6 col-12">
                      <span class="fs-14 mr-1"> {{ number_format($personnel->salary) }} </span>
                    </div>
                  </div>
                </div>
              </div>
  
              <div class="col-xl-4 col-lg-4 col-md-6 col-12 my-2">
                <div class="align-items-center">
                  <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-6 col-12">
                      <span class="fs-16 font-weight-bold">شماره کارت :</span>
                    </div>
                    <div class="col-xl-8 col-lg-7 col-md-6 col-12">
                      <span class="fs-14 mr-1"> {{ $personnel->card_number }} </span>
                    </div>
                  </div>
                </div>
              </div>
  
              <div class="col-xl-4 col-lg-4 col-md-6 col-12 my-2">
                <div class="align-items-center">
                  <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-6 col-12">
                      <span class="fs-16 font-weight-bold">شماره شبا :</span>
                    </div>
                    <div class="col-xl-8 col-lg-7 col-md-6 col-12">
                      <span class="fs-14 mr-1"> {{ $personnel->sheba_number ?? '-' }} </span>
                    </div>
                  </div>
                </div>
              </div>
  
              <div class="col-xl-4 col-lg-4 col-md-6 col-12 my-2">
                <div class="align-items-center">
                  <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-6 col-12">
                      <span class="fs-16 font-weight-bold">نام بانک :</span>
                    </div>
                    <div class="col-xl-8 col-lg-7 col-md-6 col-12">
                      <span class="fs-14 mr-1"> {{ $personnel->bank_name }} </span>
                    </div>
                  </div>
                </div>
              </div>

            </div>
  
          </div>
  
        </div> --}}

      </div>

    </div>
  </div>
@endsection