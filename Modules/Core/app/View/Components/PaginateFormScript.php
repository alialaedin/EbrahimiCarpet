<?php

namespace Modules\Core\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PaginateFormScript extends Component
{
    public function __construct(
        public string $formId, 
        public string $selectBoxId, 
        public string $paginateRequestName
    ){}

    public function render(): View|string
    {
        return view('core::components.paginate-form-script');
    }
}
