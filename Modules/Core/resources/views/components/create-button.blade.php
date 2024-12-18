
@php
	$id = isset($id) ? $id : null;	
	$class = isset($class) ? $class : null;	
	$param = isset($param) ? $param : null;	
@endphp

@if (isset($type) && $type == 'modal')
  <button 
		class="btn btn-sm btn-indigo {{ $class }}" 
		data-target="{{ '#' . $target }}"
    data-toggle="modal" 
		{{ $id }}>
    {{ $title }}
    <i class="fa fa-plus mr-1"></i>
  </button>
@else
  <a 
		href="{{ route($route, $param) }}" 
		class="btn btn-sm btn-indigo {{ $class }}"
		{{ $id }}>
    {{ $title }}
    <i class="fa fa-plus"></i>
  </a>
@endif
