<div class="card">

  <div class="card-header border-0">
    <p class="card-title" style="font-weight: bolder;">جستجو پیشرفته</p>
    <div class="card-options">
      <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
      <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
      <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
    </div>
  </div>

  <div class="card-body">
    <div class="row">
      <form action="{{ route("admin.products.index") }}" class="col-12">
        <div class="row">

          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="title">عنوان :</label>
              <input type="text" id="title" name="title" class="form-control" value="{{ request('title') }}">
            </div>
          </div>

          <div class="col-12 col-md-6 col-xl-3">
            <div class="form-group">
              <label for="category_id">دسته بندی :</label>
              <select name="category_id" id="category_id" class="form-control">
                <option value="">همه</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}" @selected(request("category_id") == $category->id)>{{ $category->title }}</option>
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
