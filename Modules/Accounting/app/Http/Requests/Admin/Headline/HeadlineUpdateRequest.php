<?php

namespace Modules\Accounting\Http\Requests\Admin\Headline;

use Illuminate\Foundation\Http\FormRequest;

class HeadlineUpdateRequest extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    return [
      'title' => ['required', 'string', 'min:3', 'max:190'],
      'type' => 'required',
      'type.*' => ['required', 'in:' . implode(',', config('core.headline_types'))],
      'status' => 'required',
      'status.*' => ['required', 'boolean']
    ];
  }

  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }
}
