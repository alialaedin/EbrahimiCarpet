<button
  class="btn btn-purple mx-1 text-white my-md-1"
  style="padding: 4px 12px;"
  onclick="window.print()"
>
  {{ $title ?? null }}
  <i @class([
    'fe',
    'fe-printer',
    'mr-1' => !is_null($title)
  ])></i>
</button>

