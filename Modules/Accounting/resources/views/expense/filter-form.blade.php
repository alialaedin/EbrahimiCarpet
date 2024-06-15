<div class="card">
  <div class="card-header border-0">
    <p class="card-title">جستجوی پیشرفته</p>
    <x-core::card-options/>
  </div>
  <div class="card-body">
    <div class="row">
      <form action="{{ route("admin.expenses.index") }}" class="col-12">
        <div class="row">

          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="headline_id">سرفصل :</label>
              <select name="headline_id" id="headline_id" class="form-control">
                <option value="" class="text-muted">عنوان سرفصل را انتخاب کنید</option>
                @foreach ($headlines as $headline)
                  <option value="{{ $headline->id }}" @selected(request('headline_id') == $headline->id)>{{ $headline->title }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="title">عنوان :</label>
              <input type="text" id="title" name="title" class="form-control" value="{{ request('title') }}">
            </div>
          </div>

          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="from_payment_date_show">پرداخت از تاریخ :</label>
              <input class="form-control fc-datepicker" id="from_payment_date_show" type="text" autocomplete="off"/>
              <input name="from_payment_date" id="from_payment_date" type="hidden" value="{{ request("from_payment_date") }}"/>
            </div>
          </div>

          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="to_payment_date_show">تا تاریخ :</label>
              <input class="form-control fc-datepicker" id="to_payment_date_show" type="text" autocomplete="off"/>
              <input name="to_payment_date" id="to_payment_date" type="hidden" value="{{ request("to_payment_date") }}"/>
            </div>
          </div>

        </div>
        <x-core::filter-buttons table="expenses"/>
      </form>
    </div>
  </div>
</div>


