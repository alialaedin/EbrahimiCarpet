@foreach ($stores as $store)
<div class="modal fade" id="decreaseStoreFormModal-{{ $store->id }}" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content modal-content-demo">
      <div class="modal-header">
        <p class="modal-title" style="font-size: 20px;">کاهش موجودی انبار</p><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.stores.increase-decrease') }}" method="post" class="save">
          @csrf
          @method('PUT')
          <div class="row">

            <input type="hidden" value="decrement">

            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="product_id" class="control-label">عنوان محصول :</label>
                <input type="text" id="product_id" class="form-control" name="product_id" value="{{ $store->product->title }}" readonly>
              </div>
            </div>

            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="unit_type" class="control-label">نوع واحد :</label>
                <input type="text" id="unit_type" class="form-control" name="unit_type" value="{{ $store->product->category->getUnitType() }}" readonly>
              </div>
            </div>

            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="balance" class="control-label">موجودی فعلی :</label>
                <input type="number" id="balance" class="form-control" name="balance" value="{{ $store->balance }}" readonly>
              </div>
            </div>

            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="quantity" class="control-label">تعداد کاهش :<span class="text-danger">&starf;</span></label>
                <input type="number" id="quantity" class="form-control" name="quantity" value="{{ old('quantity') }}">
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
                <label for="description" class="control-label">توضیحات :</label>
                <textarea id="description" class="form-control" name="description" rows="4">{{ old('description') }}</textarea>
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
@endforeach
