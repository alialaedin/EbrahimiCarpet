<?php

namespace Modules\Core\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class CreateButton extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(  
        public string $route,  
        public string $title,  
        public string $id = '',       
        public string $class = '',      
        public $param = null,           
        public string $type = 'link',   
        public $target = null,
    ) {} 

    /**
     * Get the view/contents that represent the component.
     */
    public function render(): View|string
    {
        return view('core::components.create-button');
    }
}
