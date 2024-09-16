@foreach ($headlines as $headline)
  <div class="modal fade" id="editHeadlineModal-{{ $headline->id }}" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content modal-content-demo">
        <div class="modal-header">
          <p class="modal-title" style="font-size: 20px;">ویرایش سرفصل - کد {{ $headline->id }}</p>
          <button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.headlines.update', $headline) }}" method="post" class="save">
            @csrf
            @method('PATCH')
            <div class="row">

              <div class="col-12">
                <div class="form-group">
                  <label for="title" class="control-label">عنوان :<span class="text-danger">&starf;</span></label>
                  <input type="text" id="title" class="form-control" name="title" required value="{{ old('title', $headline->title) }}">
                  <x-core::show-validation-error name="title"/>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group">
                  <label for="type" class="control-label">نوع سرفصل :<span class="text-danger">&starf;</span></label>
                  <select name="type" id="type" class="form-control select2" required>
                    <option value="" class="text-muted"> نوع سرفصل را انخاب کنید</option>
                    @foreach (config('accounting.headline_types') as $name => $label)
                      <option value="{{ $name }}" @selected(old("type", $headline->type) === $name)>{{ $label }}</option>
                    @endforeach
                  </select>
                  <x-core::show-validation-error name="type"/>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group">
                  <label for="status" class="control-label">وضعیت :<span class="text-danger">&starf;</span></label>
                  <select name="status" id="status" class="form-control select2" required>
                    <option value="1" @selected(old("status", $headline->status) === '1')>فعال</option>
                    <option value="0" @selected(old("status", $headline->status) === '0')>غیر فعال</option>
                  </select>
                  <x-core::show-validation-error name="status"/>
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
