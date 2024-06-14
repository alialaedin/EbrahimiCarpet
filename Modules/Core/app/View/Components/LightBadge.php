<?php

namespace Modules\Core\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class LightBadge extends Component
{
	public function __construct(
		public string  $type,
		public string  $text
	) {
		//
	}

	/**
	 * Get the view/contents that represent the component.
	 */
	public function render(): View|string
	{
		return view('core::components.light-badge');
	}
}
