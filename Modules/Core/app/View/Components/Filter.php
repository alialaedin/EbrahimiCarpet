<?php

namespace Modules\Core\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Filter extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $action,
        public array $inputs,
    ) {}

    /**
     * Get the view/contents that represent the component.
     */
    public function render(): View|string
    {
        return view('core::components.filter');
    }
}
