<div class="tab-pane" id="{{ $tabId }}">
  <div class="table-responsive recent_jobs py-2 px-0 card-body" style="min-height: 300px; max-height: 300px; overflow-y: auto;">
    <table class="table text-center table-vcenter table-striped">
      <thead>
        <tr>
          <th>ردیف</th>
          <th>{{ $table == 'sale-payments' ? 'مشتری' : 'تامین کننده' }}</th>
          <th>تاریخ سررسید</th>
          <th>مبلغ (ریال)</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($allData as $data)
          <tr>
            <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
            <td>
              @if ($table == 'sale-payments')
                <a href="{{ route('admin.sale-payments.index', parameters: ['customer_id' => $data->customer->id ]) }}" target="_blank">{{ $data->customer->name }}</a>	
              @else
                <a href="{{ route('admin.payments.index', parameters: ['supplier_id' => $data->supplier->id ]) }}" target="_blank">{{ $data->supplier->name }}</a>	
              @endif
            </td>
            <td>{{verta($data->due_date)->format('Y/m/d')}}</td>
            <td>{{ number_format($data->amount) }}</td>
          </tr>
        @empty
          <x-core::data-not-found-alert :colspan="4"/>
        @endforelse
      </tbody>
    </table>
  </div>
</div>