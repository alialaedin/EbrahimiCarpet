@foreach ($sale->items as $item)
  <div class="modal fade" id="editSaleItemModal{{$item->id}}" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content modal-content-demo">
        <div class="modal-header">
          <p class="modal-title" style="font-size: 20px;">ویرایش آیتم فروش - کد {{ $sale->id }}</p><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.sale-items.update', $item) }}" method="post" class="save">

            @csrf
            @method('PATCH')

            <div class="row">

              <div class="col-md-12">
                <div class="form-group">
                  <label for="title" class="control-label">نام محصول :</label>
                  <input type="text" id="title" value="{{ $item->product->title }}" class="form-control" readonly>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="balance" class="control-label">موجودی : </label>
                  <input type="number" id="balance" class="form-control" name="balance" value="{{ number_format($item->product->store->balance) }}" readonly>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="quantity" class="control-label">تعداد :<span class="text-danger">&starf;</span></label>
                  <input type="number" id="quantity" class="form-control" name="quantity" placeholder="تعداد محصول خریداری شده را وارد کنید" value="{{ old('quantity', $item->quantity) }}">
                  <x-core::show-validation-error name="quantity" />
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
