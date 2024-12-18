<?php

namespace Modules\Sale\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class SalePaymentDescriptionButton extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $target)
    {
        //
    }

    /**
     * Get the view/contents that represent the component.
     */
    public function render(): View|string
    {
        return view('sale::components.sale-payment-description-button');
    }
}
