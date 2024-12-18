<div class="table-responsive">
  <table class="table table-striped text-nowrap text-center">
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