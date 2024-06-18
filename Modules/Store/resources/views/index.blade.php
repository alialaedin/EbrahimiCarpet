@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item active">لیست انبار</li>
    </ol>
  </div>
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">لیست انبار</p>
      <x-core::card-options/>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
              <thead class="thead-light">
              <tr>
                <th class="text-center">ردیف</th>
                <th class="text-center">عنوان محصول</th>
                <th class="text-center">تصویر محصول</th>
                <th class="text-center">نوع واحد</th>
                <th class="text-center">موجودی</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">تراکنش ها</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($stores as $store)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">
                    <a href="{{ route('admin.products.show', $store->product) }}">
                      {{ $store->product->title }}
                    </a>
                  </td>
                  <td class="text-center m-0 p-0">
                    @if ($store->product->image)
                      <figure class="figure my-2">
                        <a target="_blank" href="{{ Storage::url($store->product->image) }}">
                          <img
                            src="{{ Storage::url($store->product->image) }}"
                            class="img-thumbnail"
                            alt="image"
                            width="50"
                            style="max-height: 32px;"
                          />
                        </a>
                      </figure>
                    @else
                      <span> - </span>
                    @endif
                  </td>
                  <td class="text-center">{{ $store->product->category->getUnitType() }}</td>
                  <td class="text-center">{{ $store->balance }}</td>
                  <td class="text-center"> @jalaliDate($store->created_at) </td>
                  <td class="text-center">
                    @can('view stores')
                      <x-core::show-button route="admin.stores.show" :model="$store"/>
                    @endcan
                  </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="7"/>
              @endforelse
              </tbody>
            </table>
            {{ $stores->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
