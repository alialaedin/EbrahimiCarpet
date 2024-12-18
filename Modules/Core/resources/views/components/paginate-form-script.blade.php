<script>

  let filterForm = $('#' + @json($formId));
  let selectBox = $('#' + @json($selectBoxId));
  let paginateInputName = @json($paginateRequestName);
  let input = $(`input[name=${paginateInputName}]`);

  $(document).ready(() => {  
    selectBox.on('change', (event) => {  
      input.val(event.target.value);
      filterForm.submit();  
    });  
  }); 

</script>