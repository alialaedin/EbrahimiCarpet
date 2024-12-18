<?php

namespace Modules\Payment\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PaymentDescriptionButton extends Component
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
        return view('payment::components.payment-description-button');
    }
}
