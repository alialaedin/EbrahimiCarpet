<button onclick="confirmDelete('delete-{{ $model->id }}')" class="action-btns1 bg-danger mx-1">
  <i class="fe fe-trash-2 text-white"></i>
</button>
<form 
  action="{{ route($route, $model) }}" 
  method="POST"
  id="delete-{{ $model->id }}" 
  style="display: none">
  @csrf
  @method('DELETE')
</form>