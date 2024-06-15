<?php

namespace Modules\Core\View\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Illuminate\View\View;

class DeleteButton extends Component
{
  public function __construct(
    public string $route,
    public Model  $model,
    public bool   $disabled = false
  )
  {
  }

  public function render(): View|string
  {
    return view('core::components.delete-button');
  }
}
