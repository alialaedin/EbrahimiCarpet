<div class="card">
  <div class="card-header border-0">
    <p class="card-title">جستجوی پیشرفته</p>
    <X-core::card-options/>
  </div>
  <div class="card-body">
    <div class="row">
      <form action="{{ route("admin.suppliers.index") }}" class="col-12">
        <div class="row">

          <div class="col-xl-4 col-md-6 col-12">
            <div class="form-group">
              <label for="name">نام و نام خانوادگی :</label>
              <input type="text" id="name" name="full_name" class="form-control" value="{{ request('full_name') }}">
            </div>
          </div>

          <div class="col-xl-4 col-md-6 col-12">
            <div class="form-group">
              <label for="mobile">تلفن همراه :</label>
              <input type="text" id="mobile" name="mobile" class="form-control" value="{{ request('mobile') }}">
            </div>
          </div>

          <div class="col-xl-4 col-md-6 col-12">
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
  </div>
</div>
