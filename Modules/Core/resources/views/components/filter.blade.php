<div class="card">

  <div class="card-header border-0">
		<p class="card-title" style="font-weight: bolder;">جستجو پیشرفته</p>
	</div>

  <div class="card-body">
    <div class="row">
			<form action="{{ route($route) }}" class="col-12">
				<div class="row">
					@foreach($inputs as $inputName => $inputDetails)
						<div class="col-12 col-md-6 col-xl-3 col-xxl-2">
							<div class="form-group">
								<label class="font-weight-bold"> {{	$inputDetails['label'] }} :</label>
								@if(in_array($inputDetails['type'], ['text', 'number', 'email']))
									<input 
										type="{{ $inputDetails['type'] }}" 
										name="{{ $inputName }}" 
										class="form-control" 
										value="{{ request($inputName) }}"
									/>
								@elseif($inputDetails['type'] == 'select_option')
									<select class="form-control" name="{{ $inputName }}">
										@foreach($inputDetails['options'] as $value => $label)
											<option value="{{ $value }}" @selected(request($inputName) == "{$value}")>{{ $label }}</option>
										@endforeach
									</select>
								@elseif($value['type'] == 'date')
									<input class="form-control fc-datepicker" id="{{ $inputName }}_show" type="text" autocomplete="off"/>
									<input name="{{ $inputName }}" id="{{ $inputName }}_hidden" type="hidden" value="{{ request($inputName) }}"/>  
								@endif
							</div>
						</div>
					@endforeach
				</div>
				<x-core::filter-buttons table="customers"/>
			</form>
		</div>
  </div>
</div>