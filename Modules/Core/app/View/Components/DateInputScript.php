<?php

namespace Modules\Core\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class DateInputScript extends Component
{
  /**
   * Create a new component instance.
   */
  public function __construct(
    public string $textInputId,
    public string $dateInputId
  )
  {
    //
  }

  /**
   * Get the view/contents that represent the component.
   */
  public function render(): View|string
  {
    return view('core::components.date-input-script');
  }
}
