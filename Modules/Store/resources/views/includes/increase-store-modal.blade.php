@foreach ($products as $product)
<div class="modal fade" id="increaseStoreFormModal-{{ $product->id }}" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content modal-content-demo">
      <div class="modal-header">
        <p class="modal-title" style="font-size: 20px;">افزایش موجودی انبار</p><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.stores.increment-balance') }}" method="post" class="save">
          @csrf
          @method('PUT')
          <div class="row">

            <input type="hidden" value="{{ $product->id }}" name="product_id">

            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label class="control-label">عنوان محصول :</label>
                <input type="text" class="form-control" value="{{ $product->title .' '. $product->sub_title }}" readonly>
              </div>
            </div>

            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label class="control-label">موجودی فعلی :</label>
                <input type="number" class="form-control" value="{{ $product->stores->sum('balance') }}" readonly>
              </div>
            </div>

            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="quantity" class="control-label">تعداد افزایش :<span class="text-danger">&starf;</span></label>
                <input type="number" id="quantity" class="form-control" name="quantity" value="{{ old('quantity') }}">
              </div>
            </div>

            <div class="col-lg-6 col-12">
              <div class="form-group">
                <label for="purchased_price" class="control-label">قیمت خرید :<span class="text-danger">&starf;</span></label>
                <input type="text" id="purchased_price" class="form-control comma" name="purchased_price" value="{{ old('purchased_price') }}">
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
