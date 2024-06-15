<button
  onclick="confirmDelete('delete-{{ $model->id }}')"
  class="btn btn-sm btn-icon btn-danger text-"
  data-toggle="tooltip"
  data-original-title="حذف"
  @disabled($disabled)>
  <i class="fa fa-trash-o"></i>
</button>
<form
  action="{{ route($route, $model) }}"
  method="POST"
  id="delete-{{ $model->id }}"
  style="display: none">
  @csrf
  @method('DELETE')
</form>
