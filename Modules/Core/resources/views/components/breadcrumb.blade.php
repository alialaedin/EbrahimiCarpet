<ol class="breadcrumb">
  <li class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}">
        <i class="fe fe-life-buoy ml-1"></i> داشبورد
    </a>
  </li>
  @foreach ($items as $item)
    @if (is_null($item['link']))
      <li class="breadcrumb-item active">
        {{ $item['title'] }}
      </li>
    @else
      <li class="breadcrumb-item">
        <a href="{{ $item['link'] }}">{{ $item['title'] }}</a>
      </li>
    @endif
  @endforeach
</ol>
