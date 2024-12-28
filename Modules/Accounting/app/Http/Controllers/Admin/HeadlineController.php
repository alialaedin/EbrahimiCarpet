<?php

namespace Modules\Accounting\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Cache;
use Modules\Accounting\Http\Requests\Admin\Headline\HeadlineStoreRequest;
use Modules\Accounting\Http\Requests\Admin\Headline\HeadlineUpdateRequest;
use Modules\Accounting\Models\Headline;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class HeadlineController extends Controller implements HasMiddleware
{
	public static function middleware(): array
  {
    return [
      new Middleware('can:view headlines', ['index']),
      new Middleware('can:create headlines', ['store']),
      new Middleware('can:edit headlines', ['update']),
      new Middleware('can:delete headlines', ['destroy']),
    ];
  }

  public function index(): View
  {
    $headlines = Cache::rememberForever('all_headlines', function () {
      return Headline::query()
        ->select('id', 'title', 'type', 'status', 'created_at')
        ->latest('id')
        ->paginate()
        ->withQueryString();
    });

    $totalHeadlines = $headlines->total();

    return view('accounting::headline.index', compact(['headlines', 'totalHeadlines']));
  }

  public function store(HeadlineStoreRequest $request): RedirectResponse
  {
    Headline::query()->create($request->validated());
    return redirect()->back();
  }

  public function update(HeadlineUpdateRequest $request, Headline $headline): RedirectResponse
  {
    $headline->update($request->validated());
    return redirect()->back();
  }

  public function destroy(Headline $headline): RedirectResponse
  {
    $headline->delete();
    return redirect()->back();
  }
}
