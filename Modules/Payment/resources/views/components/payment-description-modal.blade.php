@foreach ($payments as $payment)
    
<div class="modal fade" id="{{ $idExtention . $payment->id }}" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content modal-content-demo">
      <div class="modal-header">
        <p class="modal-title" style="font-size: 20px;">توضیحات</p><button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <p>{{ $payment->description }}</p>
      </div>
    </div>
  </div>
</div>

@endforeach