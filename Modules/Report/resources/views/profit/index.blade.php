@extends('admin.layouts.master')
@section('content')
  <div class="page-header d-print-none">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">گزارشات</a>
      <li class="breadcrumb-item active">گزارش سود و ضرر</li>
    </ol>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      <x-core::print-button/>
    </div>
  </div>
  <div class="row justify-content-center d-none d-print-flex">
    <p class="fs-22">گزارش سود و ضرر</p>
  </div>
  {{-- <div class="card d-print-none">
    <div class="card-header border-0">
      <p class="card-title">جستجوی پیشرفته</p>
    </div>
    <div class="card-body">
      <div class="row" style="margin-bottom: 25px;">
        <form action="{{ route("admin.reports.profit") }}" class="col-12" method="GET">
          <div class="row">
  
            <div class="col-12 col-lg-6 col-xl-3">
              <div class="form-group">
                <select name="product_id" id="product_id" class="form-control select2">
                  <option value="">محصول را انتخاب کنید</option>
                  @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->title .' - '. $product->sub_title }}</option>
                  @endforeach
                </select>
              </div>
            </div>
  
            <div class="col-12 col-lg-6 col-xl-3">
              <div class="form-group">
                <select name="category_id" id="category_id" class="form-control select2">
                  <option value="">دسته بندی را انتخاب کنید</option>
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                  @endforeach
                </select>
              </div>
            </div>
  
            <div class="col-12 col-lg-6 col-xl-3">
              <div class="form-group">
                <input class="form-control fc-datepicker" id="from_date_show" type="text" autocomplete="off" placeholder="از تاریخ"/>
                <input name="from_date" id="from_date_hidden" type="hidden" value="{{ request("from_date") }}"/>
              </div>
            </div>
  
            <div class="col-12 col-lg-6 col-xl-3">
              <div class="form-group">
                <input class="form-control fc-datepicker" id="to_date_show" type="text" autocomplete="off" placeholder="تا تاریخ"/>
                <input name="to_date" id="to_date_hidden" type="hidden" value="{{ request("to_date") }}"/>
              </div>
            </div>
  
          </div>
  
          <div class="row">
            <div class="col-md-9 col-12">
              <button class="btn btn-primary btn-block" type="submit">جستجو <i class="fa fa-search"></i></button>
            </div>
            <div class="col-md-3 col-12">
              <a href="{{ route("admin.reports.profit") }}" class="btn btn-danger btn-block">حذف همه فیلتر ها <i class="fa fa-close"></i></a>    
            </div>
          </div>
  
        </form>
      </div>
    </div>
  </div> --}}
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
              <span class="btn btn-pink">
                <span class="fs-15">مبلغ خریدادری شده :</span> {{ number_format($sumTotalBuyPrice) }} ریال
              </span> 
              <span class="btn btn-orange mx-3">
                <span class="fs-15">مبلغ فروخته شده :</span> {{ number_format($sumTotalSellPrice) }} ریال
              </span> 
              <span class="btn btn-lime">
                <span class="fs-15">سود شما :</span> {{ number_format($profit) }} ریال
              </span> 
          </div>
          <table class="table table-vcenter text-center table-striped text-nowrap table-bordered border-bottom" style="margin-top: 25px;">
              <thead class="thead-light">
              <tr>
                <th>ردیف</th>
                <th>شناسه فاکتور فروش</th>
                <th>محصول</th>
                <th>شناسه محصول</th>
                <th>ابعاد</th>
                <th>تعداد قلم فروخته شده</th>
                <th>تاریخ فروش</th>
                <th>مبلغ (ریال)</th>
              </tr>
              </thead>
              <tbody>
                @php($counter = 1)
                @forelse ($sales as $sale)
                  @foreach($sale->items as $saleItem)
                    <tr class="table-body">
                      <td class="font-weight-bold">{{ $counter }}</td>
                      <td>{{ $sale->id }}</td>
                      <td>{{ $saleItem->product->title }}</td>
                      <td>{{ $saleItem->product->id }}</td>
                      <td>{{ $saleItem->product->sub_title }}</td>
                      <td>{{ $saleItem->quantity }}</td>
                      <td> @jalaliDate($saleItem->sale->sold_at) </td>
                      <td>{{ number_format($saleItem->getPriceWithDiscount() * $saleItem->quantity) }}</td>
                    </tr>
                    @php($counter++)
                  @endforeach
                @empty
                  <x-core::data-not-found-alert :colspan="7"/>
                @endforelse
              </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')

  <x-core::date-input-script textInputId="from_date_show" dateInputId="from_date_hidden"/>
  <x-core::date-input-script textInputId="to_date_show" dateInputId="to_date_hidden"/>

  <script>
    $('#product_id').select2({
      placeholder: 'انتخاب محصول',
    });
    $('#category_id').select2({
      placeholder: 'انتخاب دسته بندی',
    });
  </script>

{{-- <script>  
  $(document).ready(() => {  
    $('#product_id').on('change', () => {  
      var selectedProductId = $('#product_id').val();   
      $('.table-body').each(function() {  
        var rowProductId = $(this).find('td:eq(3)').text().trim();
        
        if (selectedProductId == '' || rowProductId == selectedProductId) {  
          $(this).show();   
        } else {  
          $(this).hide();   
        }  
      });  
    });  
  });  
</script>  --}}

@endsection