<div class="card">
  <div class="card-header border-0">
    <p class="card-title">جستجوی پیشرفته</p>
    <x-core::card-options/>
  </div>
  <div class="card-body">
    <div class="row">
      <form action="{{ route("admin.salaries.index") }}" class="col-12">
        <div class="row">

          <div class="col-12 col-md-6 col-xl-4">
            <div class="form-group">
              <label for="employee_id">پرسنل :</label>
              <select name="employee_id" id="employee_id" class="form-control">
                <option value="" class="text-muted">کارمند را انتخاب کنید</option>
                @foreach ($employees as $employee)
                  <option value="{{ $employee->id }}" @selected(request('employee_id') == $employee->id)>{{ $employee->name . ' - ' . $employee->mobile}}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-12 col-md-6 col-xl-4">
            <div class="form-group">
              <label for="from_payment_date_show">پرداخت از تاریخ :</label>
              <input class="form-control fc-datepicker" id="from_payment_date_show" type="text" autocomplete="off"/>
              <input name="from_payment_date" id="from_payment_date" type="hidden" value="{{ request("from_payment_date") }}"/>
            </div>
          </div>

          <div class="col-12 col-md-6 col-xl-4">
            <div class="form-group">
              <label for="to_payment_date_show">تا تاریخ :</label>
              <input class="form-control fc-datepicker" id="to_payment_date_show" type="text" autocomplete="off"/>
              <input name="to_payment_date" id="to_payment_date" type="hidden" value="{{ request("to_payment_date") }}"/>
            </div>
          </div>

        </div>
        <x-core::filter-buttons table="salaries"/>
      </form>
    </div>
  </div>
</div>

@section('scripts')

  <x-core::date-input-script textInputId="from_payment_date_show" dateInputId="from_payment_date"/>
  <x-core::date-input-script textInputId="to_payment_date_show" dateInputId="to_payment_date"/>

@endsection


