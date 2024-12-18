<div class="form-group mb-0">
  <select id="{{ $selectBoxId }}" name="{{ $paginateRequestName }}" class="form-control">
    @foreach ($values as $paginate)
      <option value="{{ $paginate }}" @selected(request($paginateRequestName) == $paginate)>{{ $paginate }}</option>
    @endforeach
  </select>
</div>