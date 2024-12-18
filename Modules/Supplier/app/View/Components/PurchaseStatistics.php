<?php

namespace Modules\Supplier\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Modules\Supplier\Models\Supplier;

class PurchaseStatistics extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Supplier $supplier)
    {
        //
    }

    /**
     * Get the view/contents that represent the component.
     */
    public function render(): View|string
    {
        return view('supplier::components.purchase-statistics');
    }
}
