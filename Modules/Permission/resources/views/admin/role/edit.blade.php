@extends('admin.layouts.master')
@section('content')

  <div class="page-header">
    <x-core::breadcrumb :items="[
      ['title' => 'لیست نقش ها', 'route_link' => 'admin.roles.index'],
      ['title' => 'ویرایش نقش']
    ]"/>
  </div>

  <x-core::card>
    <x-slot name="cardTitle">ویرایش نقش</x-slot>
    <x-slot name="cardOptions"></x-slot>
    <x-slot name="cardBody">
      <form action="{{ route('admin.roles.update', $role) }}" method="post" class="save">
        @csrf
        @method('PATCH')
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="name" class="control-label">نام (به انگلیسی) <span class="text-danger">&starf;</span></label>
              <input type="text" class="form-control" name="name" id="name" placeholder="نام را به انگلیسی اینجا وارد کنید" value="{{ old('name', $role->name) }}" required autofocus>
              <x-core::show-validation-error name="name" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="label" class="control-label">نام قابل مشاهده (به فارسی) <span class="text-danger">&starf;</span></label>
              <input type="text" class="form-control" name="label" id="label" placeholder="نام قابل مشاهده را به فارسی اینجا وارد کنید" value="{{ old('label', $role->label) }}" required>
              <x-core::show-validation-error name="label" />
            </div>
          </div>
        </div>
        @if($role->name !== 'super_admin')
          <h4 class="header p-2">مجوزها</h4>
          @foreach($permissions->chunk(4) as $chunk)
            <div class="row">
              @foreach($chunk as $permission)
                <div class="col-12 col-lg-3 col-md-6">
                  <div class="form-group">
                    <label class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" name="permissions[]" value="{{ $permission->name }}" @checked($role->permissions->contains($permission->id))>
                      <span class="custom-control-label">{{ $permission->label }}</span>
                    </label>
                    <x-core::show-validation-error name="permissions" />
                  </div>
                </div>
              @endforeach
            </div>
          @endforeach
        @endif
        <div class="row">
          <div class="col">
            <div class="text-center">
              <button class="btn btn-warning" type="submit">به روزرسانی</button>
            </div>
          </div>
        </div>
      </form>
    </x-slot>
  </x-core::card>

@endsection
