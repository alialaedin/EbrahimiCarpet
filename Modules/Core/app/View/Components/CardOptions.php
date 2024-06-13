<?php

namespace Modules\Core\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class CardOptions extends Component
{
    public function render(): View|string
    {
        return view('core::components.card-options');
    }
}
