@extends('admin.layouts.master')
@section('content')
  <div class="col-12">

		<div class="page-header">

      <ol class="breadcrumb align-items-center">
        <li class="breadcrumb-item">
          <a href="{{ route('admin.dashboard') }}">
            <i class="fe fe-home ml-1"></i> داشبورد
          </a>
        </li>
        <li class="breadcrumb-item active">لیست مشتری ها</li>
      </ol>

      @can('create customers')
        <x-core::register-button route="admin.customers.create" title="ثبت مشتری جدید"/>
      @endcan

    </div>

    <div class="card">

      <div class="card-header border-0">
        <p class="card-title">جستجوی پیشرفته</p>
        <x-core::card-options/>
      </div>

      <div class="card-body">

        <div class="row">

          <form action="{{ route("admin.customers.index") }}" class="col-12">

            <div class="row">

              <div class="col-12 col-md-6 col-xl-3 col-xxl-2">
                <div class="form-group">
                  <label for="name">نام و نام خانوادگی :</label>
                  <input type="text" id="name" name="full_name" class="form-control" value="{{ request('full_name') }}">
                </div>
              </div>

              <div class="col-12 col-md-6 col-xl-3 col-xxl-2">
                <div class="form-group">
                  <label for="telephone">تلفن ثابت :</label>
                  <input type="text" id="telephone" name="telephone" class="form-control" value="{{ request('telephone') }}">
                </div>
              </div>

              <div class="col-12 col-md-6 col-xl-3 col-xxl-2">
                <div class="form-group">
                  <label for="mobile">تلفن همراه :</label>
                  <input type="text" id="mobile" name="mobile" class="form-control" value="{{ request('mobile') }}">
                </div>
              </div>

              <div class="col-12 col-md-6 col-xl-3 col-xxl-2">
                <div class="form-group">
                  <label for="status">وضعیت :</label>
                  <select name="status" id="status" class="form-control">
                    <option value="">همه</option>
                    <option value="1" @selected(request("status") == "1")>فعال</option>
                    <option value="0" @selected(request("status") == "0")>غیر فعال</option>
                  </select>
                </div>
              </div>

            </div>

            <x-core::filter-buttons table="customers"/>

          </form>

        </div>

      </div>

    </div>

    <div class="card">

      <div class="card-header border-0">
        <p class="card-title">لیست مشتری ها  <span class="fs-15">({{ $customersCount }})</span></p>
        <x-core::card-options/>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-vcenter text-nowrap table-bordered border-bottom">
                <thead class="thead-light">
                  <tr>
                    <th class="text-center border-top">ردیف</th>
                    <th class="text-center border-top">نام و نام خانوادگی</th>
                    <th class="text-center border-top">شماره موبایل</th>
                    <th class="text-center border-top">تلفن ثابت</th>
                    <th class="text-center border-top">وضعیت</th>
                    <th class="text-center border-top">تاریخ ثبت</th>
                    <th class="text-center border-top">عملیات</th>
                  </tr>
                </thead>

                <tbody>
                  @forelse ($customers as $customer)
                    <tr>
                      <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                      <td class="text-center">{{ $customer->name }}</td>
                      <td class="text-center">{{ $customer->mobile }}</td>
                      <td class="text-center">{{ $customer->telephone }}</td>
                      <td class="text-center">
                        <x-core::badge
                          type="{{ $customer->status ? 'success' : 'danger' }}"
                          text="{{ $customer->status ? 'فعال' : 'غیر فعال' }}"
                        />
                      </td>
                      <td class="text-center">{{ verta($customer->created_at)->formatDate() }}</td>
                      <td class="text-center">

                        @can('view sale_payments')
                          <a
                            href="{{ route('admin.sale-payments.index', $customer) }}"
                            class="btn btn-success btn-icon btn-sm"
                            data-toggle="tooltip"
                            data-original-title="پرداختی ها">
                            <i class="fa fa-money"></i>
                          </a>
                        @endcan

                        @can('view customers')
                          <x-core::show-button route="admin.customers.show" :model="$customer"/>
                        @endcan

                        @can('edit customers')
                          <x-core::edit-button route="admin.customers.edit" :model="$customer"/>
                        @endcan

                        @can('delete customers')

                            <button
                              onclick="confirmDelete('delete-{{ $customer->id }}')"
                              class="btn btn-sm btn-icon btn-danger"
                              data-toggle="tooltip"
                              data-original-title="حذف"
                              @disabled($customer->sales->isNotEmpty())>
                              <i class="fa fa-trash-o"></i>
                            </button>

                            <form
                              action="{{ route('admin.customers.destroy', $customer) }}"
                              method="POST"
                              id="delete-{{ $customer->id }}"
                              style="display: none">
                              @csrf
                              @method('DELETE')
                            </form>

                        @endcan

                      </td>
                    </tr>
                    @empty
											<x-core::data-not-found-alert :colspan="7"/>
                  @endforelse
                </tbody>

              </table>

              {{ $customers->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
