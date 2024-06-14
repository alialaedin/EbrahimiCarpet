<?php

namespace Modules\Headline\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Cache;
use Modules\Headline\Http\Requests\Admin\HeadlineStoreRequest;
use Modules\Headline\Http\Requests\Admin\HeadlineUpdateRequest;
use Modules\Headline\Models\Headline;

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

  public function index()
  {
    $headlines = Cache::rememberForever('all_headlines', function () {
      return Headline::query()
        ->select('id', 'title', 'type', 'status', 'created_at')
        ->latest('id')
        ->paginate()
        ->withQueryString();
    });

    $totalHeadlines = $headlines->total();

    return view('headline::index', compact(['headlines', 'totalHeadlines']));
  }
  
  public function store(HeadlineStoreRequest $request)
  {
    Headline::query()->create($request->validated());

    return to_route('admin.headlines.index');
  }

  public function update(HeadlineUpdateRequest $request, Headline $headline)
  {
    $headline->update($request->validated());

    return to_route('admin.headlines.index');
  }

  public function destroy(Headline $headline)
  {
    $headline->delete();

    return to_route('admin.headlines.index');
  }
}
