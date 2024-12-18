<?php

namespace Modules\Payment\View\Components;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;
use Illuminate\View\View;

class PaymentDescriptionModal extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Collection|array|LengthAwarePaginator $payments,
        public string $idExtention
    ){}

    /**
     * Get the view/contents that represent the component.
     */
    public function render(): View|string
    {
        return view('payment::components.payment-description-modal');
    }
}
