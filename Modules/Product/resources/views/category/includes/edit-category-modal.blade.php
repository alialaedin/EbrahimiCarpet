@foreach ($categories as $category)
  <div class="modal fade" id="editCategoryModal-{{ $category->id }}" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content modal-content-demo">
        <div class="modal-header">
          <p class="modal-title" style="font-size: 20px;">ویرایش دسته بندی - کد {{ $category->id }}</p>
          <button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.categories.update', $category) }}" method="post" class="save">
            @csrf
            @method('PATCH')
            <div class="row">

              <div class="col-md-6">
                <div class="form-group">
                  <label for="title" class="control-label"> عنوان: <span class="text-danger">&starf;</span></label>
                  <input type="text" id="title" class="form-control" name="title" placeholder="عنوان را وارد کنید" value="{{ old('title', $category->title) }}" required autofocus>
                  <x-core::show-validation-error name="title" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="parent_id" class="control-label"> انتخاب دسته بندی والد:</label>
                  <select name="parent_id" id="parent_id" class="form-control">
                    <option value=""> بدون والد </option>
                    @foreach ($parentCategories->whereNotIn('id', $category->id) as $parentCategory)
                      <option
                        value="{{ $parentCategory->id }}"
                        @selected(old('parent_id', $category->parent_id) == $parentCategory->id)>
                        {{ $parentCategory->title }}
                      </option>
                    @endforeach
                  </select>
                  <x-core::show-validation-error name="parent_id" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="unit_type" class="control-label"> نوع واحد:<span class="text-danger">&starf;</span></label>
                  <select name="unit_type" id="unit_type" class="form-control">
                    @foreach (config('core.category_unit_types') as $name => $label)
                      <option value="{{ $name }}" @selected(old('unit_type', $category->unit_type) == $name)> {{ $label }} </option>
                    @endforeach
                  </select>
                  <x-core::show-validation-error name="unit_type" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="status" class="control-label"> نوع واحد:<span class="text-danger">&starf;</span></label>
                  <select name="status" id="status" class="form-control">
                    @foreach (config('core.bool_statuses') as $name => $label)
                      <option value="{{ $name }}" @selected(old('status', $category->status) == $name)> {{ $label }} </option>
                    @endforeach
                  </select>
                  <x-core::show-validation-error name="status" />
                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button class="btn btn-warning" type="submit">بروزرسانی</button>
              <button class="btn btn-danger" data-dismiss="modal">انصراف</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
@endforeach
