<div class="modal fade" id="createPurchaseItemModal" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content modal-content-demo">
      <div class="modal-header">
        <p class="modal-title" style="font-size: 20px;">ثبت آیتم جدید</p><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.purchase-items.store') }}" method="post" class="save">
          @csrf
          <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
          <div class="row">

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">انتخاب محصول :<span class="text-danger">&starf;</span></label>
                <select name="product_id" class="form-control">
                  <option value="" class="text-muted">-- محصول مورد نظر را انتخاب کنید --</option>
                  @foreach ($categories as $category)
                    @if ($category->products()->exists())
                      <optgroup label="{{ $category->title }}" class="text-muted">
                        @foreach ($category->products as $product)
                          <option value="{{ $product->id }}" class="text-dark" @selected(old('product_id') == $product->id)>{{ $product->title }}</option>
                        @endforeach
                      </optgroup>
                    @endif
                  @endforeach
                </select>
              </div>
            </div>
  
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">تعداد:<span class="text-danger">&starf;</span></label>
                <input type="number" class="form-control" name="quantity" placeholder="تعداد محصول خریداری شده را وارد کنید" value="{{ old('quantity') }}">
              </div>
            </div>
  
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">قیمت (تومان):<span class="text-danger">&starf;</span></label>
                <input type="text" class="form-control comma" name="price" placeholder="قیمت محصول را به تومان وارد کنید" value="{{ old('price') }}">
              </div>
            </div>
  
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">تخفیف (تومان): </label>
                <input type="text" class="form-control comma" name="discount" placeholder="تخفیف را به تومان وارد کنید" value="{{ old('discount') }}">
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