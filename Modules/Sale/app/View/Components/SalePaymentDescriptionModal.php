<?php

namespace Modules\Sale\View\Components;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;
use Illuminate\View\View;

class SalePaymentDescriptionModal extends Component
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
        return view('sale::components.sale-payment-description-modal');
    }
}
