<div class="modal fade" id="createHeadlineModal" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content modal-content-demo">
      <div class="modal-header">
        <p class="modal-title" style="font-size: 20px;">ثبت سرفصل جدید</p>
        <button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.headlines.store') }}" method="post" class="save">
          @csrf
          <div class="row">

            <div class="co-12">
              <div class="form-group">
                <label for="title" class="control-label">عنوان :<span class="text-danger">&starf;</span></label>
                <input type="text" id="title" class="form-control" name="title" required value="{{ old('title') }}">
                <x-core::show-validation-error name="title"/>
              </div>
            </div>

            <div class="co-12">
              <div class="form-group">
                <label for="type" class="control-label">نوع سرفصل :<span class="text-danger">&starf;</span></label>
                <select name="type" id="type" class="form-control select2" required>
                  <option value="" class="text-muted"> نوع سرفصل را انخاب کنید</option>
                  @foreach (config('accounting.headline_types') as $name => $label)
                    <option value="{{ $name }}" @selected(old("type") === $name)>{{ $label }}</option>
                  @endforeach
                </select>
                <x-core::show-validation-error name="type"/>
              </div>
            </div>

            <div class="co-12">
              <div class="form-group">
                <label for="status" class="control-label">وضعیت :<span class="text-danger">&starf;</span></label>
                <select name="status" id="status" class="form-control select2" required>
                  <option value="" class="text-muted"> وضعیت را انتخاب کنید</option>
                  <option value="1" @selected(old("status", 1) == '1')>فعال</option>
                  <option value="0" @selected(old("status") == '0')>غیر فعال</option>
                </select>
                <x-core::show-validation-error name="status"/>
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
