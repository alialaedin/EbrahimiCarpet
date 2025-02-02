@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[['title' => 'لیست کارمندان', 'route_link' => 'admin.employees.index'], ['title' => 'ویرایش کارمند']]"/>
  </div>
  
  <x-core::card>
    <x-slot name="cardTitle">ویرایش کارمند</x-slot>
    <x-slot name="cardOptions"><x-core::card-options/></x-slot>
    <x-slot name="cardBody">
      <form action="{{ route('admin.employees.update', $employee) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
          <div class="col-lg-4 col-md-6 col-12">
            <div class="form-group">
              <label for="name" class="control-label"> نام و نام خانوادگی: <span class="text-danger">&starf;</span></label>
              <input
                type="text"
                id="name"
                class="form-control"
                name="name"
                placeholder="نام و نام خانوادگی را وارد کنید"
                value="{{ old('name', $employee->name)}}"
                required
                autofocus
              />

              <x-core::show-validation-error name="name" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6 col-12">
            <div class="form-group">
              <label for="mobile" class="control-label"> شماره موبایل: <span class="text-danger">&starf;</span></label>
              <input
                type="text"
                id="mobile"
                class="form-control"
                name="mobile"
                placeholder="شماره موبایل را وارد کنید"
                value="{{ old('mobile', $employee->mobile) }}"
                required
              />
              <x-core::show-validation-error name="mobile" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6 col-12">
            <div class="form-group">
              <label for="telephone" class="control-label"> تلفن ثابت:</label>
              <input
                type="text"
                id="telephone"
                class="form-control"
                name="telephone"
                placeholder="تلفن ثابت را وارد کنید"
                value="{{ old('telephone', $employee->telephone) }}"
              />
              <x-core::show-validation-error name="telephone" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6 col-12">
            <div class="form-group">
              <label for="national_code" class="control-label"> کد ملی:</label>
              <input
                type="text"
                id="national_code"
                class="form-control"
                name="national_code"
                placeholder="کد ملی را وارد کنید"
                value="{{ old('national_code', $employee->national_code) }}"
              />
              <x-core::show-validation-error name="national_code" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6 col-12">
            <div class="form-group">
              <label for="employmented_date_show" class="control-label">تاریخ استخدام : <span class="text-danger">&starf;</span></label>
              <input
                class="form-control fc-datepicker"
                id="employmented_date_show"
                type="text"
                placeholder="تاریخ استخدام را انتخاب کنید"
                autocomplete="off"
              />
              <input
                name="employmented_at"
                id="employmented_date"
                type="hidden"
                required
                value="{{	old('employmented_at', $employee->employmented_at) }}"
              />
              <x-core::show-validation-error name="employmented_at" />
            </div>

          </div>
          <div class="col-lg-4 col-md-6 col-12">
            <div class="form-group">
              <label for="salary" class="control-label"> میزان حقوق (ریال): <span class="text-danger">&starf;</span></label>
              <input
                type="text"
                id="salary"
                class="form-control comma"
                name="salary"
                placeholder="میزان حقوق را وارد کنید"
                value="{{ old('salary', number_format($employee->salary)) }}"
                required
              />
              <x-core::show-validation-error name="salary" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6 col-12">
            <div class="form-group">
              <label for="card_number" class="control-label"> شماره کارت: <span class="text-danger">&starf;</span></label>
              <input
                type="text"
                id="card_number"
                class="form-control"
                name="card_number"
                placeholder="شماره کارت را وارد کنید"
                value="{{ old('card_number', $employee->card_number) }}"
                required
              />
              <x-core::show-validation-error name="card_number" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6 col-12">
            <div class="form-group">
              <label for="sheba_number" class="control-label"> شماره شبا:</label>
              <input
                type="text"
                id="sheba_number"
                class="form-control"
                name="sheba_number"
                placeholder="شماره شبا را وارد کنید"
                value="{{ old('sheba_number', $employee->sheba_number) }}"
              />
              <x-core::show-validation-error name="sheba_number" />
            </div>
          </div>
          <div class="col-lg-4 col-md-6 col-12">
            <div class="form-group">
              <label for="bank_name" class="control-label"> نام بانک: <span class="text-danger">&starf;</span></label>
              <input
                type="text"
                id="bank_name"
                class="form-control"
                name="bank_name"
                placeholder="نام بانک را وارد کنید"
                value="{{ old('bank_name', $employee->bank_name) }}"
                required
              />
              <x-core::show-validation-error name="bank_name" />
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="address" class="control-label">محل سکونت:<span class="text-danger">&starf;</span></label>
              <textarea
                name="address"
                id="address"
                class="form-control"
                rows="3"
                placeholder="محل سکونت را وارد کنید"
                required>
                {{ old('address', $employee->address) }}
              </textarea>
              <x-core::show-validation-error name="address" />
            </div>
          </div>
        </div>
        <x-core::update-button/>
      </form>
    </x-slot>
  </x-core::card>

@endsection

@section('scripts')
  <x-core::date-input-script textInputId="employmented_date_show" dateInputId="employmented_date"/>
@endsection
