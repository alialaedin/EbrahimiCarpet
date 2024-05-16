<?php

namespace Modules\Core\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class BreadCrumb extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $items)
    {
        $this->items = $items;
    }

    /**
     * Get the view/contents that represent the component.
     */
    public function render(): View|string
    {
        return view('core::components.breadcrumb');
    }
}
