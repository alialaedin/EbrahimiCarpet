<div class="table-responsive">
  <table @isset($id) id="{{ $id }}" @endisset class="table table-striped text-nowrap text-center">
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