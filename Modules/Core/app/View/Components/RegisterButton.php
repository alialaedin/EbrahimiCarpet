<?php

namespace Modules\Core\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class RegisterButton extends Component
{
	public $route;
	public $title;

	public function __construct(String $route, String $title)
	{
		$this->route = $route;
		$this->title = $title;
	}

	/**
	 * Get the view/contents that represent the component.
	 */
	public function render(): View|string
	{
		return view('core::components.register-button');
	}
}
