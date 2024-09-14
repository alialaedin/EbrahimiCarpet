<div class="col-xl-6">
    <div class="card">
      <div class="card-header border-0 justify-content-between">
        <p class="card-title fs-15 font-weight-bold">{{ $title }}</p>
        <button onclick="$('#{{ $showAllDataBtnId }}').submit()" class="btn btn-outline-primary btn-sm">مشاهده همه</button>
        <form
          action="{{ route('admin.'. $table .'.index') }}"
          id="{{ $showAllDataBtnId }}"
          class="d-none">
          <input type="hidden" name="type" value="cheque">
        </form>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table text-center table-vcenter table-striped">
                <thead>
                <tr>
                  <th>ردیف</th>
                  <th>
										@if ($table == 'sale-payments')
											مشتری
										@else
											تامین کننده	
										@endif
									</th>
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
												<a href="{{ route('admin.customers.show', $data->customers) }}">{{ $data->customers->name }}</a>	
											@else
												<a href="{{ route('admin.suppliers.show', $data->suppliers) }}">{{ $data->suppliers->name }}</a>	
											@endif
                    </td>
                    <td> {{verta($data->due_date)->format('Y/m/d')}} </td>
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