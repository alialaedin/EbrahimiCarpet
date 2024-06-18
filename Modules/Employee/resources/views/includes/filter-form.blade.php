<div class="card">
  <div class="card-header border-0">
    <p class="card-title">جستجوی پیشرفته</p>
    <x-core::card-options/>
  </div>
  <div class="card-body">
    <div class="row">
      <form action="{{ route("admin.employees.index") }}" class="col-12">
        <div class="row">
          <div class="col-12 col-md-6 col-xl-3 ">
            <div class="form-group">
              <label for="full_name">نام و نام خانوادگی :</label>
              <input type="text" id="full_name" name="full_name" class="form-control" value="{{ request('full_name') }}">
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3 ">
            <div class="form-group">
              <label for="mobile">تلفن همراه :</label>
              <input type="text" id="mobile" name="mobile" class="form-control" value="{{ request('mobile') }}">
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="from_employmented_date_show">استخدام از تاریخ :</label>
              <input class="form-control fc-datepicker" id="from_employmented_date_show" type="text" autocomplete="off"/>
              <input name="from_employmented_at" id="from_employmented_date" type="hidden" value="{{ request("from_employmented_at") }}"/>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="to_employmented_date_show">تا تاریخ :</label>
              <input class="form-control fc-datepicker" id="to_employmented_date_show" type="text" autocomplete="off"/>
              <input name="to_employmented_at" id="to_employmented_date" type="hidden" value="{{ request("to_employmented_at") }}"/>
            </div>
          </div>
        </div>
        <x-core::filter-buttons table="employees"/>
      </form>
    </div>
  </div>
</div>

@section('scripts')
  <x-core::date-input-script textInputId="from_employmented_date_show" dateInputId="from_employmented_date"/>
  <x-core::date-input-script textInputId="to_employmented_date_show" dateInputId="to_employmented_date"/>
@endsection
