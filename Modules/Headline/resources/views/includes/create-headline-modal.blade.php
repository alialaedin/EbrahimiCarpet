<div class="modal fade" id="createHeadlineModal" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content modal-content-demo">
      <div class="modal-header">
        <p class="modal-title" style="font-size: 20px;">ثبت سرفصل جدید</p><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.headlines.store') }}" method="post" class="save">
          @csrf
          <div class="row">

            <div class="col-md-12">
              <div class="form-group">
                <label for="title" class="control-label">عنوان :<span class="text-danger">&starf;</span></label>
                <input type="text" id="title" class="form-control" name="title" placeholder="عنوان سرفصل را وارد کنید" value="{{ old('title') }}">
                <x-core::show-validation-error name="title" />
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="type" class="control-label">نوع :<span class="text-danger">&starf;</span></label>
                <select name="type" id="type" class="form-control">
                  <option value="" class="text-muted">نوع سرفصل را انتخاب کنید</option>
                  @foreach (config('core.headline_types') as $typeName => $typeLabel)
                    <option value="{{ $typeName }}" @selected(old('type') == $typeName)>{{ $typeLabel }}</option>
                  @endforeach
                </select>
                <x-core::show-validation-error name="type" />
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="status" class="control-label">وضعیت :<span class="text-danger">&starf;</span></label>
                <select name="status" id="status" class="form-control">
                  <option value="" class="text-muted">وضعیت سرفصل را انتخاب کنید</option>
                  <option value="1">فعال</option>
                  <option value="0">غیر فعال</option>
                </select>
                <x-core::show-validation-error name="status" />
              </div>
            </div>

          </div>

          <div class="modal-footer">
            <button class="btn btn-success" type="submit">ثبت و ذخیره</button>
            <button class="btn btn-danger" data-dismiss="modal">انصراف</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>