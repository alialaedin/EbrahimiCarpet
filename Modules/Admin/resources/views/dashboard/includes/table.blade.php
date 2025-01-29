<div class="col-xl-6">
  <div class="card">
    <div class="card-header border-0 justify-content-between">
      <p class="card-title fs-15 font-weight-bold">{{ $title }}</p>
      <a href="{{ $route }}" class="btn btn-sm btn-outline-info" target="_blank">مشاهده همه</a>
    </div>
    <div class="card-body">
      <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
          <div class="row">
            <table class="table text-center table-vcenter table-striped">
              <thead>
              <tr>
                <th>ردیف</th>
                <th> {{ $table == 'sale-payments' ? 'مشتری' : 'تامین کننده' }}</th>
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
                      <a href="{{ route('admin.customers.show', $data->customer) }}" target="_blank">{{ $data->customer->name }}</a>	
                    @else
                      <a href="{{ route('admin.suppliers.show', $data->supplier) }}" target="_blank">{{ $data->supplier->name }}</a>	
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
      </div>
    </div>
  </div>
</div>