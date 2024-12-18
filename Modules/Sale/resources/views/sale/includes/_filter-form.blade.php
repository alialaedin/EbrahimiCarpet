<x-core::card>
  <x-slot name="cardTitle">جستجو پیشرفته</x-slot>
  <x-slot name="cardOptions"><x-core::card-options/></x-slot>
  <x-slot name="cardBody">
    <form action="{{ route("admin.sales.index") }}" class="col-12">
      <div class="row">

        <div class="col-12 col-md-6 col-xl-3">
          <div class="form-group">
            <label for="customer_id">شناسه فاکتور فروش :</label>
            <input type="text" class="form-control" name="id" value="{{ request('id') }}">
          </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
          <div class="form-group">
            <label for="customer_id">انتخاب مشتری :</label>
            <select name="customer_id" id="customer_id" class="form-control">
              <option value="">همه</option>
              @foreach ($customers as $customer)
                <option value="{{ $customer->id }}" @selected(request("customer_id") == $customer->id)>{{ $customer->name .' - '. $customer->mobile }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
          <div class="form-group">
            <label for="from_sold_at_show">فروش از تاریخ :</label>
            <input class="form-control fc-datepicker" id="from_sold_at_show" type="text" autocomplete="off"/>
            <input name="from_sold_at" id="from_sold_at" type="hidden" value="{{ request("from_sold_at") }}"/>
          </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
          <div class="form-group">
            <label for="to_sold_at_show">تا تاریخ :</label>
            <input class="form-control fc-datepicker" id="to_sold_at_show" type="text" autocomplete="off"/>
            <input name="to_sold_at" id="to_sold_at" type="hidden" value="{{ request("to_sold_at") }}"/>
          </div>
        </div>

      </div>

      <x-core::filter-buttons table="sales"/>

    </form>
  </x-slot>
</x-core::card>

@section('scripts')

  <x-core::date-input-script textInputId="from_sold_at_show" dateInputId="from_sold_at"/>
  <x-core::date-input-script textInputId="to_sold_at_show" dateInputId="to_sold_at"/>

  <script>
    new CustomSelect('#customer_id', 'انتخاب مشتری');
  </script>

@endsection
