@foreach ($product->children as $childProduct)
  <div class="modal fade" id="edit-product-form-{{ $childProduct->id }}" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content modal-content-demo">
        <div class="modal-header">
          <p class="modal-title" style="font-size: 20px;">ویرایش محصول - کد {{ $childProduct->id }}</p>
          <button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.products.update', $childProduct) }}" method="POST">

            @csrf
            @method('PATCH')

            <input type="hidden" name="title" value="{{ $product->title }}">
            <input type="hidden" name="print_title" value="{{ $product->print_title }}">
            <input type="hidden" name="category_id" value="{{ $product->category_id }}">  
            <input type="hidden" name="description" value="{{ $product->description }}">  

            <div class="row">

              <div class="col-12">
                <div class="form-group">
                  <label> قیمت (ریال): <span class="text-danger">&starf;</span></label>
                  <input type="text" class="form-control comma" name="price" value="{{ old('price', number_format($childProduct->price)) }}">
                  <x-core::show-validation-error name="price" />
                </div>
              </div>

              <div class="col-12">
                <div class="form-group">
                  <label> تخفیف (ریال): <span class="text-danger">&starf;</span></label>
                  <input type="text" class="form-control comma" name="discount" value="{{ old('discount', number_format($childProduct->discount)) }}">
                  <x-core::show-validation-error name="discount" />
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label> انتخاب وضعیت:<span class="text-danger">&starf;</span></label>
                  <div class="custom-controls-stacked">
                    <label class="custom-control custom-radio">
                      <input type="radio" class="custom-control-input" name="status" value="1" @checked(old('status', $childProduct->status) == '1')>
                      <span class="custom-control-label">فعال</span>
                    </label>
                    <label class="custom-control custom-radio">
                      <input type="radio" class="custom-control-input" name="status" value="0" @checked(old('status', $childProduct->status) == '0')>
                      <span class="custom-control-label">غیر فعال</span>
                    </label>
                  </div>
                  <x-core::show-validation-error name="status" />
                </div>
              </div>

            </div>

            <div class="justify-content-center d-flex">
              <button class="btn btn-warning mx-1" type="submit">بروزرسانی</button>
              <button class="btn btn-outline-danger mx-1" data-dismiss="modal">انصراف</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
@endforeach