<script>
  $('#' + "{{ $textInputId }}").MdPersianDateTimePicker({
    targetDateSelector: '#' + "{{ $dateInputId }}",
    targetTextSelector: '#' + "{{ $textInputId }}",
    englishNumber: false,
    toDate:true,
    enableTimePicker: true,
    dateFormat: 'yyyy-MM-dd',
    textFormat: 'yyyy-MM-dd',
    groupId: 'rangeSelector1',
  });
</script>
