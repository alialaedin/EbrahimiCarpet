<?php

namespace Modules\Core\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class FilterButtons extends Component
{
	
	public function __construct(public string $table)
	{
		$this->table = $table;
	}

	/**
	 * Get the view/contents that represent the component.
	 */
	public function render(): View|string
	{
		return view('core::components.filter-buttons');
	}
}
