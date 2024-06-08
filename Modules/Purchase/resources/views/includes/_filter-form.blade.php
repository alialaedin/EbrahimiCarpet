<div class="card">

  <div class="card-header border-0">
    <p class="card-title">جستجو پیشرفته</p>
  </div>

  <div class="card-body">
    <div class="row">
      <form action="{{ route("admin.purchases.index") }}" class="col-12">
        <div class="row">

          <div class="col-12 col-md-6 col-xl-3">
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

          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="has_discount">انتخاب تخفیف :</label>
              <select name="has_discount" id="has_discount" class="form-control">
                <option value="">همه</option>
                <option value="1" @selected(request("has_discount") == "1")>تخفیفدار</option>
                <option value="0" @selected(request("has_discount") == "0")>بدون تخفیف</option>
              </select>
            </div>
          </div>

          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="from_purchased_date_show">خرید از تاریخ :</label>
              <input class="form-control fc-datepicker" id="from_purchased_date_show" type="text" autocomplete="off"/>
              <input name="from_purchased_at" id="from_purchased_date" type="hidden" value="{{ request("from_purchased_at") }}"/>
            </div>
          </div>

          <div class="col-12 col-md-6 col-xl-3">
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

  <script>
    $('#from_purchased_date_show').MdPersianDateTimePicker({
      targetDateSelector: '#from_purchased_date',
      targetTextSelector: '#from_purchased_date_show',
      englishNumber: false,
      toDate:true,
      enableTimePicker: false,
      dateFormat: 'yyyy-MM-dd',
      textFormat: 'yyyy-MM-dd',
      groupId: 'rangeSelector1',
    });

    $('#to_purchased_date_show').MdPersianDateTimePicker({
      targetDateSelector: '#to_purchased_date',
      targetTextSelector: '#to_purchased_date_show',
      englishNumber: false,
      toDate:true,
      enableTimePicker: false,
      dateFormat: 'yyyy-MM-dd',
      textFormat: 'yyyy-MM-dd',
      groupId: 'rangeSelector1',
    });
  </script>

@endsection
