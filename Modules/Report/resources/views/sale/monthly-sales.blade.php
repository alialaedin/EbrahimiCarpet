@extends('admin.layouts.master')
@section('content')
  <div class="page-header">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fe fe-home ml-1"></i> داشبورد
        </a>
      </li>
      <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">گزارشات</a>
      <li class="breadcrumb-item">گزارش فروش ماهانه</li>
    </ol>
  </div>

  <div class="card">
    <div class="card-header border-bottom-0">
      <h3 class="card-title">گزارش فروش ماهانه سال {{ verta()->year }}</h3>
    </div>
    <div class="card-body">
			<div class="panel panel-primary">
				<div class=" tab-menu-heading p-0 bg-light">
					<div class="tabs-menu1 ">
						<ul class="nav panel-tabs">
							@foreach ($sales as $month => $items)

								@php
									$newMonth = $month < 10 ? '0' . $month : $month;
									$date = verta()->format('Y') .'/'. $newMonth . '/01'
								@endphp

								<li>
									<a 
										href="#tab-{{ $month }}" 
										data-toggle="tab" 
										style="cursor: pointer;">
										{{ verta()->parse($date)->format('%B') }}
									</a>
								</li>

							@endforeach
						</ul>
					</div>
				</div>
				<div class="panel-body tabs-menu-body">
					<div class="tab-content">
						@foreach ($sales as $month => $items)
							<div class="tab-pane" id="tab-{{ $month }}">
								<div class="table-responsive">
									<div class="dataTables_wrapper dt-bootstrap4 no-footer">
										<table class="table table-striped text-nowrap text-center">
											<thead>
											<tr>
												<th>ردیف</th>
												<th>خریدار</th>
												<th>شناسه سفارش</th>
												<th>تاریخ فروش</th>
												<th>تعداد اقلام</th>
												<th>تخفیف کل (ریال)</th>
												<th>هزینه دوخت / نصب (ریال)</th>
												<th>قیمت نهایی (ریال)</th>
											</tr>
											</thead>
											<tbody>
												@foreach ($items->sortByDesc('sold_at') as $sale)
													<tr>
														<td class="font-weight-bold">{{ $loop->iteration }}</td>
														<td>{{ $sale->customer->name }}</td>
														<td>{{ $sale->id }}</td>
														<td>@jalaliDateFormat($sale->sold_at)</td>
														<td>{{ $sale->items_count }}</td>
														<td>{{ number_format($sale->total_discount) }}</td>
														<td>{{ number_format($sale->cost_of_sewing) }}</td>
														<td>{{ number_format($sale->total_amount) }}</td>
													</tr>
												@endforeach
												<tr class="bg-dark text-light font-weight-bold fs-18">
													<td colspan="7">جمع کل</td>
													<td colspan="1">{{ number_format($items->sum('total_amount')) }}</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						@endforeach
					</div>
				</div>
			</div>
    </div>
	</div>

@endsection