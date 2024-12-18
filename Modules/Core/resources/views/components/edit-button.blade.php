@if (isset($target) && $target != null)
  <button
    data-target="{{ $target }}"
    class="btn btn-sm btn-icon btn-warning text-white"
    data-toggle="modal">
    @isset($title)
      @if ($title != null)
        <span>{{ $title }}</span>
      @endif
    @endisset
    <i class="fa fa-pencil"></i>
  </button>
@else
  <a
    href="{{route($route, $model)}}"
    class="btn btn-sm btn-icon btn-warning text-white"
    data-toggle="tooltip"
    data-original-title="ویرایش">
    @isset($title)
      @if (!is_null($title))
        <span>{{ $title }}</span>
      @endif
    @endisset
    <i class="fa fa-pencil"></i>
  </a>
@endif



