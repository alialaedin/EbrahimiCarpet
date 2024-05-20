<?php

namespace Modules\Core\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Filter extends Component
{
	public function __construct(public string $route, public array $inputs)
	{
		$this->route = $route;
		$this->inputs = $inputs;
	}

	public function render(): View|string
	{
		return view('core::components.filter');
	}
}
