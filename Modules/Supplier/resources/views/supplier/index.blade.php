@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item active">لیست تامین کنندگان</li>
    </ol>
    @can('create suppliers')
      <x-core::register-button route="admin.suppliers.create" title="ثبت تامین کننده جدید"/>
    @endcan
  </div>
  @include('supplier::includes.filter-form')
  <div class="card">
    <div class="card-header border-0">
      <p class="card-title">لیست تامین کنندگان ({{ $totalSuppliers }})</p>
      <X-core::card-options/>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table table-vcenter table-striped text-nowrap table-bordered border-bottom">
              <thead class="thead-light">
              <tr>
                <th class="text-center">ردیف</th>
                <th class="text-center">نام و نام خانوادگی</th>
                <th class="text-center">شماره موبایل</th>
                <th class="text-center">کد ملی</th>
                <th class="text-center">نوع</th>
                <th class="text-center">کد پستی</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">تاریخ ثبت</th>
                <th class="text-center">عملیات</th>
              </tr>
              </thead>
              <tbody>
              @forelse ($suppliers as $supplier)
                <tr>
                  <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $supplier->name }}</td>
                  <td class="text-center">{{ $supplier->mobile }}</td>
                  <td class="text-center">{{ $supplier->national_code }}</td>
                  <td class="text-center">{{ config('supplier.types.'.$supplier->type) }}</td>
                  <td class="text-center">{{ $supplier->postal_code }}</td>
                  <td class="text-center">
                    <x-core::badge
                      type="{{ $supplier->status ? 'success' : 'danger' }}"
                      text="{{ $supplier->status ? 'فعال' : 'غیر فعال' }}"
                    />
                  <td class="text-center"> @jalaliDate($supplier->created_at) </td>
                  <td class="text-center">
                    @can('create purchases')
                      <button
                        class="btn btn-yellow btn-icon btn-sm"
                        onclick="$('#Form').submit()"
                        data-toggle="tooltip"
                        data-original-title="فاکتور خربد جدید">
                        <i class="fa fa-shopping-cart"></i>
                      </button>
                      <form
                        action="{{ route('admin.purchases.create') }}"
                        id="Form"
                        method="GET"
                        class="d-none">
                        <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                      </form>
                    @endcan
                    @can('create accounts')
                      <button
                        class="btn btn-teal btn-icon btn-sm"
                        onclick="createAccountModal('{{$supplier->name}}', '{{$supplier->id}}')"
                        style="padding: 1px 7px;"
                        data-toggle="tooltip"
                        data-original-title="ایجاد حساب جدید">
                        <i class="fe fe-credit-card" ></i>
                      </button>
                    @endcan
                    @can('view payments')
                      <a
                        href="{{ route('admin.payments.show', $supplier) }}"
                        class="btn btn-green btn-icon btn-sm"
                        data-toggle="tooltip"
                        data-original-title="پرداختی ها">
                        <i class="fa fa-money"></i>
                      </a>
                    @endcan
                    @can('view suppliers')
                        <x-core::show-button route="admin.suppliers.show" :model="$supplier"/>
                      @endcan
                      @can('edit suppliers')
                        <x-core::edit-button route="admin.suppliers.edit" :model="$supplier"/>
                      @endcan
                      @can('delete suppliers')
                        <x-core::delete-button
                          route="admin.suppliers.destroy"
                          :model="$supplier"
                          disabled="{{ !$supplier->isDeletable() }}"
                        />
                      @endcan
                  </td>
                </tr>
              @empty
                <x-core::data-not-found-alert :colspan="9"/>
              @endforelse
              </tbody>
            </table>
            {{ $suppliers->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}
          </div>
        </div>
      </div>
    </div>
  </div>

    <div class="modal fade" id="createAccountModal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document" style="margin-top: 20vh;">
        <div class="modal-content modal-content-demo">
          <div class="modal-header">
            <p class="modal-title" style="font-size: 20px;">ثبت حساب جدید</p><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('admin.accounts.store') }}" method="post" class="save">
              @csrf
              <input type="hidden" id="supplier_id" name="supplier_id">
              <div class="row">
                <div class="col-lg-6 col-12">
                  <div class="form-group">
                    <label for="supplier_name" class="control-label">تامین کننده :<span class="text-danger">&starf;</span></label>
                    <input type="text" id="supplier_name" class="form-control" readonly>
                  </div>
                </div>
                <div class="col-lg-6 col-12">
                  <div class="form-group">
                    <label for="account_number" class="control-label">شماره حساب :<span class="text-danger">&starf;</span></label>
                    <input
                      type="text"
                      id="account_number"
                      class="form-control"
                      name="account_number"
                      placeholder="شماره حساب را وارد کنید"
                      value="{{ old('account_number') }}"
                    />
                    <x-core::show-validation-error name="account_number" />
                  </div>
                </div>
                <div class="col-lg-6 col-12">
                  <div class="form-group">
                    <label for="card_number" class="control-label">شماره کارت :<span class="text-danger">&starf;</span></label>
                    <input
                      type="text"
                      id="card_number"
                      class="form-control"
                      name="card_number"
                      placeholder="شماره کارت را وارد کنید"
                      value="{{ old('card_number') }}"
                    />
                    <x-core::show-validation-error name="card_number" />
                  </div>
                </div>
                <div class="col-lg-6 col-12">
                  <div class="form-group">
                    <label for="bank_name" class="control-label">نام بانک :<span class="text-danger">&starf;</span></label>
                    <input
                      type="text"
                      id="bank_name"
                      class="form-control"
                      name="bank_name"
                      placeholder="نام بانک را وارد کنید"
                      value="{{ old('bank_name') }}"
                    />
                    <x-core::show-validation-error name="bank_name" />
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

@endsection

@section('scripts')
  <script>
    function createAccountModal(name, id) {
      let modal = $('#createAccountModal');
      modal.find('#supplier_id').attr('value', Number(id));
      modal.find('#supplier_name').attr('value', name);
      modal.modal('show');
    }
  </script>
@endsection
