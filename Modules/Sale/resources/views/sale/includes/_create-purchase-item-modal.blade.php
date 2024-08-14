<div class="modal fade" id="createSaleItemModal" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content modal-content-demo">
      <div class="modal-header">
        <p class="modal-title" style="font-size: 20px;">ثبت آیتم جدید</p><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.sale-items.store') }}" method="post" class="save">
          @csrf
          <input type="hidden" name="sale_id" value="{{ $sale->id }}">
          <div class="row">

            <div class="col-md-12">
              <div class="form-group">
                <label for="product_id" class="control-label">انتخاب محصول :<span class="text-danger">&starf;</span></label>
                <select name="product_id" id="product_id" class="form-control" onchange="getProductStore('#product_id')">
                  <option value="" class="text-muted">-- محصول مورد نظر را انتخاب کنید --</option>
                  @foreach ($categories as $category)
                    @if ($category->products()->exists())
                      <optgroup label="{{ $category->title }}" class="text-muted">
                        @foreach ($category->products as $product)
                          <option value="{{ $product->id }}" class="text-dark" @selected(old('product_id') == $product->id)>{{ $product->title .' '. $product->sub_title }}</option>
                        @endforeach
                      </optgroup>
                    @endif
                  @endforeach
                </select>
                <x-core::show-validation-error name="product_id" />
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="balance" class="control-label">موجودی:</label>
                <input type="number" id="balance" class="form-control" name="balance" placeholder="ابتدا محصول را انتخاب کنید" value="{{ old('balance') }}" readonly>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="quantity" class="control-label">تعداد:<span class="text-danger">&starf;</span></label>
                <input type="number" id="quantity" class="form-control" name="quantity" placeholder="تعداد محصول خریداری شده را وارد کنید" value="{{ old('quantity') }}">
                <x-core::show-validation-error name="quantity" />
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="price" class="control-label">قیمت (ریال):</label>
                <input type="text" id="price" class="form-control" name="price" placeholder="ابتدا محصول را انتخاب کنید" value="{{ old('price') }}" readonly>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="discount" class="control-label">تخفیف (ریال): </label>
                <input type="text" id="discount" class="form-control" name="discount" placeholder="ابتدا محصول را انتخاب کنید" value="{{ old('discount') }}" readonly>
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

@section('scripts')
  <script>
    function getProductStore(id) {

      let productId = $(id).val();
      let token = $('meta[name="csrf-token"]').attr('content');

      $.ajax({
        url: '{{ route("admin.sales.get-product-store") }}',
        type: 'POST',
        data: {product_id: productId},
        headers: {'X-CSRF-TOKEN': token},
        success: function(response) {
          $('#balance').val(response.balance);
          $('#price').val(response.price);
          $('#discount').val(response.discount);
        }
      });
    }
  </script>
@endsection
