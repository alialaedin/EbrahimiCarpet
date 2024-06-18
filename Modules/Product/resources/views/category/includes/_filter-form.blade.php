<div class="card">
  <div class="card-header border-0">
    <p class="card-title">جستجو پیشرفته</p>
    <x-core::card-options/>
  </div>
  <div class="card-body">
    <div class="row">
      <form action="{{ route("admin.categories.index") }}" class="col-12">
        <div class="row">
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="title">عنوان :</label>
              <input type="text" id="title" name="title" class="form-control" value="{{ request('title') }}">
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="parent_id">انتخاب والد :</label>
              <select name="parent_id" id="parent_id" class="form-control">
                <option value="">همه</option>
                <option value="none" @selected(request("parent_id") == 'none')>بدون والد</option>
                @foreach ($parentCategories as $category)
                  <option value="{{ $category->id }}" @selected(request("parent_id") == $category->id)>{{ $category->title }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="unit_type">نوع واحد :</label>
              <select name="unit_type" id="unit_type" class="form-control">
                <option value="">همه</option>
                <option value="meter" @selected(request("unit_type") == "meter")>متر</option>
                <option value="number" @selected(request("unit_type") == "number")>عدد</option>
              </select>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
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
        <x-core::filter-buttons table="categories"/>
      </form>
    </div>
  </div>
</div>
