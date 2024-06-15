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
        <li class="breadcrumb-item">
          <a href="{{ route('admin.revenues.index') }}">لیست درامد ها</a>
        </li>
        <li class="breadcrumb-item">ثبت درامد جدید</li>
      </ol>
    </div>

    <div class="card">
      <div class="card-header border-0">
        <p class="card-title">ثبت درامد جدید</p>
        <x-core::card-options/>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.revenues.store') }}" method="post" class="save">
          @csrf

          <div class="row">

            <div class="col-md-6 col-12">
              <div class="form-group">
                <label for="headline_id" class="control-label">انتخاب سرفصل :<span class="text-danger">&starf;</span></label>
                <select name="headline_id" id="headline_id" class="form-control">
                  <option value="" class="text-muted">عنوان سرفصل را انتخاب کنید</option>
                  @foreach ($headlines as $headline)
                    <option value="{{ $headline->id }}" @selected(old('headline_id') == $headline->id)>{{ $headline->title }}</option>
                  @endforeach
                </select>
                <x-core::show-validation-error name="headline_id" />
              </div>
            </div>

            <div class="col-md-6 col-12">
              <div class="form-group">
                <label for="title" class="control-label">عنوان :<span class="text-danger">&starf;</span></label>
                <input type="text" name="title" id="title" placeholder="عنوان را وارد کنید" class="form-control" value="{{ old('title') }}">
                <x-core::show-validation-error name="title" />
              </div>
            </div>

            <div class="col-md-6 col-12">
              <div class="form-group">
                <label for="amount" class="control-label">مبلغ پرداخت شده (نومان) :<span class="text-danger">&starf;</span></label>
                <input type="text" name="amount" id="amount" placeholder="مبلغ درامد را به تومان  وارد کنید" class="form-control comma" value="{{ old('amount') }}">
                <x-core::show-validation-error name="amount" />
              </div>
            </div>

            <div class="col-md-6 col-12">
              <div class="form-group">
                <label for="payment_date_show" class="control-label">تاریخ پرداخت :<span class="text-danger">&starf;</span></label>
                <input class="form-control fc-datepicker" id="payment_date_show" type="text" autocomplete="off" placeholder="تاریخ پرداخت را انتخاب کنید" />
                <input name="payment_date" id="payment_date" type="hidden" value="{{ old("payment_date") }}" required/>
                <x-core::show-validation-error name="payment_date" />
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
                <label for="description" class="control-label">توضیحات :</label>
                <textarea name="description" id="description" placeholder="توضیحات لازم را وارد کنید" class="form-control" rows="5"> {{ old('description') }} </textarea>
                <x-core::show-validation-error name="description" />
              </div>
            </div>

          </div>

          <x-core::store-button/>

        </form>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <x-core::date-input-script textInputId="payment_date_show" dateInputId="payment_date"/>
@endsection
