<button
  class="btn btn-sm btn-purple mx-1 text-white my-md-1"
  style="padding: 4px 12px;"
  onclick="window.print()"
>
  @isset($title)
    @if ($title !== null)
      <b>{{ $title }}</b>
    @endif
  @endisset
  <i @class([
    'fa',
    'fa-print',
    'mr-1' => !is_null($title)
  ])></i>
</button>

