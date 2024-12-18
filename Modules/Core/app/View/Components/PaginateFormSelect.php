<?php

namespace Modules\Core\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PaginateFormSelect extends Component
{
    public function __construct(
        public string $selectBoxId, 
        public string $paginateRequestName,
        public array $values
    ){}

    public function render(): View|string
    {
        return view('core::components.paginate-form-select');
    }
}
