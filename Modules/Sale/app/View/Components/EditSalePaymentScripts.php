<?php

namespace Modules\Sale\View\Components;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;
use Illuminate\View\View;

class EditSalePaymentScripts extends Component
{
    public function __construct(  
        private Collection|array|LengthAwarePaginator $cashes,  
        private Collection|array|LengthAwarePaginator $cheques,
        private Collection|array|LengthAwarePaginator $installments,
    ){}  

    /**
     * Get the view/contents that represent the component.
     */
    public function render(): View|string
    {
        return view('sale::components.edit-sale-payment-scripts', [  
            'cashPayments' => $this->cashes,  
            'chequePayments' => $this->cheques,  
            'installmentPayments' => $this->installments,  
        ]);  
    }
}
