<?php

namespace Modules\Core\View\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Illuminate\View\View;

class ShowButton extends Component
{
	public function __construct(public String $route, public Model $model)
	{
		$this->route = $route;
		$this->model = $model;
	}
	public function render(): View|string
	{
		return view('core::components.show-button');
	}
}
