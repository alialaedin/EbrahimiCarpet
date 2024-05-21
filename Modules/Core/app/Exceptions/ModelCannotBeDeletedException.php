<?php

namespace Modules\Core\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModelCannotBeDeletedException extends Exception
{
	/**
	 * Report the exception.
	 *
	 * @return void
	 */
	public function report()
	{
		//
	}

	public function render(Request $request)
	{
		$code = $this->getCode();
		if ($code == 0) {
			$code = Response::HTTP_CONFLICT;
		}

		if ($request->wantsJson()) {
			return response()->error($this->getMessage(), $code);
		}

		toastr()->error($this->getMessage());

		return redirect()->back();
	}
}
