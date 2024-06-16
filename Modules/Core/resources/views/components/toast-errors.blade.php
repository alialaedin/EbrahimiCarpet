@if($errors->any())
  @php
    toastr()->error('خطای اعتبار سنجی! مجددا امتحان کنید.');
  @endphp
@endif
