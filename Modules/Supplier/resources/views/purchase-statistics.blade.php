<div class="row">
  <div class="col-xl-4 col-lg-6 col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-9">
            <div class="mt-0 text-right">
              <span class="fs-16 font-weight-semibold"> مبلغ کل خرید (تومان) : </span>
              <h3 class="mb-0 mt-1 text-info fs-20"> {{ number_format($supplier->calcTotalPurchaseAmount()) }} </h3>
            </div>
          </div>
          <div class="col-3">
            <div class="icon1 bg-info-transparent my-auto float-left">
              <i class="fa fa-money"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-4 col-lg-6 col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-9">
            <div class="mt-0 text-right">
              <span class="fs-16 font-weight-semibold"> جمع پرداخت شده ها (تومان) : </span>
              <h3 class="mb-0 mt-1 text-danger fs-20"> {{ number_format($supplier->calcTotalPaymentAmount()) }} </h3>
            </div>
          </div>
          <div class="col-3">
            <div class="icon1 bg-danger-transparent my-auto float-left">
              <i class="fa fa-money"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-4 col-lg-6 col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-9">
            <div class="mt-0 text-right">
              <span class="fs-16 font-weight-semibold"> مبلغ باقی مانده (تومان) : </span>
              <h3 class="mb-0 mt-1 text-success fs-20"> {{ number_format($supplier->getRemainingAmount()) }}  </h3>
            </div>
          </div>
          <div class="col-3">
            <div class="icon1 bg-success-transparent my-auto float-left">
              <i class="fa fa-money"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
