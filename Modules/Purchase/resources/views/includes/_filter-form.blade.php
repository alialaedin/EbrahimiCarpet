<div class="card">

  <div class="card-header border-0">
    <p class="card-title">جستجو پیشرفته</p>
    <x-core::card-options/>
  </div>

  <div class="card-body">
    <div class="row">
      <form action="{{ route("admin.purchases.index") }}" class="col-12">
        <div class="row">

          <div class="col-12 col-md-6 col-xl-4">
            <div class="form-group">
              <label for="supplier_id">انتخاب تامین کننده :</label>
              <select name="supplier_id" id="supplier_id" class="form-control">
                <option value="">همه</option>
                @foreach ($suppliers as $supplier)
                  <option value="{{ $supplier->id }}" @selected(request("supplier_id") == $supplier->id)>{{ $supplier->name .' - '. $supplier->mobile }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-12 col-md-6 col-xl-4">
            <div class="form-group">
              <label for="from_purchased_date_show">خرید از تاریخ :</label>
              <input class="form-control fc-datepicker" id="from_purchased_date_show" type="text" autocomplete="off"/>
              <input name="from_purchased_at" id="from_purchased_date" type="hidden" value="{{ request("from_purchased_at") }}"/>
            </div>
          </div>

          <div class="col-12 col-md-6 col-xl-4">
            <div class="form-group">
              <label for="to_purchased_date_show">تا تاریخ :</label>
              <input class="form-control fc-datepicker" id="to_purchased_date_show" type="text" autocomplete="off"/>
              <input name="to_purchased_at" id="to_purchased_date" type="hidden" value="{{ request("to_purchased_at") }}"/>
            </div>
          </div>

        </div>

        <x-core::filter-buttons table="purchases"/>

      </form>
    </div>
  </div>
</div>

@section('scripts')
  <x-core::date-input-script textInputId="from_purchased_date_show" dateInputId="from_purchased_date"/>
  <x-core::date-input-script textInputId="to_purchased_date_show" dateInputId="to_purchased_date"/>
@endsection
