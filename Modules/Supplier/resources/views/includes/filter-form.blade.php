
<x-core::card>
  <x-slot name="cardTitle">جستجوی پیشرفته</x-slot>
  <x-slot name="cardOptions"><x-core::card-options/></x-slot>
  <x-slot name="cardBody">
    <div class="row">
      <form action="{{ route("admin.suppliers.index") }}" class="col-12">
        <div class="row">

          <div class="col-xl-3 col-lg-6 col-12">
            <div class="form-group">
              <label for="name">نام و نام خانوادگی :</label>
              <input type="text" id="name" name="full_name" class="form-control" value="{{ request('full_name') }}">
            </div>
          </div>

          <div class="col-xl-3 col-lg-6 col-12">
            <div class="form-group">
              <label for="mobile">تلفن همراه :</label>
              <input type="text" id="mobile" name="mobile" class="form-control" value="{{ request('mobile') }}">
            </div>
          </div>

          <div class="col-xl-3 col-lg-6 col-12">
            <div class="form-group">
              <label for="type"> نوع تامین کننده:</label>
              <select id="type" class="form-control">
                <option value="" class="text-muted">همه</option>
                @foreach (config('supplier.types') as $name => $label)
                  <option value="{{ $name }}" @selected(request('type') == $name)>{{ $label }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-xl-3 col-lg-6 col-12">
            <div class="form-group">
              <label for="status">وضعیت :</label>
              <select name="status" id="status" class="form-control">
                <option value="">همه</option>
                <option value="1" @selected(request("status") == "1")>فعال</option>
                <option value="0" @selected(request("status") == "0")>غیر فعال</option>
              </select>
            </div>
          </div>
        </div>
        <x-core::filter-buttons table="suppliers"/>
      </form>
    </div>
  </x-slot>
</x-core::card>
