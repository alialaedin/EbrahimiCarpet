<div class="table-responsive">
  <table @isset($id) id="{{ $id }}" @endisset class="table table-striped text-nowrap text-center {{ isset($class) ? $class : '' }}">
    <thead>
      {{$tableTh}}
    </thead>
    <tbody>
    {{ $tableTd }}
    </tbody>
  </table>
  @isset($extraData)
    {{ $extraData }}
  @endisset
</div>