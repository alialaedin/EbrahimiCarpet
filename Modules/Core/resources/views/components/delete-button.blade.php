<button 
  onclick="confirmDelete('delete-{{ $model->id }}')" 
  class="btn btn-sm btn-icon bg-danger text-white"
  data-toggle="tooltip"
  data-original-title="حذف">
  <i class="fa fa-trash-o" ></i>
</button>
<form 
  action="{{ route($route, $model) }}" 
  method="POST"
  id="delete-{{ $model->id }}" 
  style="display: none">
  @csrf
  @method('DELETE')
</form>