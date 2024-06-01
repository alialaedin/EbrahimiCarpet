@foreach ($purchase->items as $item)
  <div class="modal fade" id="editPurchaseItemModal{{$item->id}}" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content modal-content-demo">
        <div class="modal-header">
          <p class="modal-title" style="font-size: 20px;">ویرایش آیتم</p><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.purchase-items.update', $item) }}" method="post" class="save">

            @csrf
            @method('PATCH')

            <div class="row">

              <div class="col-md-6">
                <div class="form-group">
                  <label for="title" class="control-label">نام محصول :<span class="text-danger">&starf;</span></label>
                  <input type="text" id="title" value="{{ $item->product->title }}" class="form-control" disabled>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="quantity" class="control-label">تعداد:<span class="text-danger">&starf;</span></label>
                  <input type="number" id="quantity" class="form-control" name="quantity" placeholder="تعداد محصول خریداری شده را وارد کنید" value="{{ old('quantity', $item->quantity) }}">
                  <x-core::show-validation-error name="quantity" />
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="price" class="control-label">قیمت (تومان):<span class="text-danger">&starf;</span></label>
                  <input type="text" id="price" class="form-control comma" name="price" placeholder="قیمت محصول را به تومان وارد کنید" value="{{ old('price', number_format($item->price)) }}">
                  <x-core::show-validation-error name="price" />
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="discount" class="control-label">تخفیف (تومان): </label>
                  <input type="text" id="discount" class="form-control comma" name="discount" placeholder="تخفیف را به تومان وارد کنید" value="{{ old('discount', $item->discount ? number_format($item->discount) : null) }}">
                  <x-core::show-validation-error name="discount" />
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
