<div class="card">

  <div class="card-header border-0 justify-content-between ">
    <div class="d-flex">
      <p class="card-title ml-2" style="font-weight: bolder;"> {{ $table['card_header'] }} </p>
      <span class="fs-15 ">({{ $table['total_resault'] }})</span>
    </div>
  </div>
  
  <div class="card-body">
    <div class="table-responsive">
      <div id="hr-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
        <div class="row">
          <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
            <thead class="thead-light">
              <tr>
                @foreach ($table['table_headers'] as $name => $value)
                  <th class="text-center border-top">{{ $value['label'] }}</th>
                @endforeach
                <th class="text-center border-top">عملیات</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($table['data'] as $data)
                <tr>
                  @foreach ($table['table_headers'] as $name => $value)
                    <td class="text-center">
                      @if ($value['type'] == 'text')
                        {{ $data->{$name} }}
                      @elseif ($value['type'] == 'date')
                        {{ verta($data->{$name})->format('Y/m/d') }}
                      @elseif ($value['type'] == 'number')
                        {{ number_format($data->{$name}) }}
                      @elseif ($value['type'] == 'boolean')
                        <x-core::badge 
                          type="{{ $data->{$name} ? 'success' : 'danger' }}" 
                          text="{{ $data->{$name} ? 'فعال' : 'غیر فعال' }}" 
                        />
                      @endif
                    </td>
                  @endforeach
                  <td class="text-center">
                    <div class="d-flex justify-content-center">
                      @foreach ($table['operation_buttons'] as $operation => $route)
                        @if ($operation == 'show')
                          @can('view customers')
                            <x-core::show-button :route="$route" :model="$data"/>
                          @endcan
                        @elseif ($operation == 'edit')
                          @can('edit customers')
                            <x-core::edit-button :route="$route" :model="$data"/>
                          @endcan
                        @elseif ($operation == 'delete')
                          @can('delete customers')
                            <x-core::delete-button :route="$route" :model="$data"/>
                          @endcan
                        @endif
                      @endforeach
                    </div>
                  </td>
                </tr>
                @empty
                  <x-core::data-not-found-alert :colspan="7"/>
              @endforelse
            </tbody>
          </table>
          {{ $table['data']->onEachSide(1)->links("vendor.pagination.bootstrap-4") }}
        </div>
      </div>
    </div>
  </div>
</div>