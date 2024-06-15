<?php

namespace Modules\Accounting\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Accounting\Enums\HeadlineType;
use Modules\Accounting\Http\Requests\Admin\Revenue\RevenueStoreRequest;
use Modules\Accounting\Http\Requests\Admin\Revenue\RevenueUpdateRequest;
use Modules\Accounting\Models\Headline;
use Modules\Accounting\Models\Revenue;

class RevenueController extends Controller implements HasMiddleware
{
  public static function middleware(): array
  {
    return [
      new Middleware('can:view revenues', ['index']),
      new Middleware('can:create revenues', ['create', 'store']),
      new Middleware('can:edit revenues', ['edit', 'update']),
      new Middleware('can:delete revenues', ['destroy']),
    ];
  }

  public function index(): View
  {
    $headlineId = request('headline_id');
    $title = request('title');
    $fromPaymentDate = request('from_payment_date');
    $toPaymentDate = request('to_payment_date');

    $revenues = Revenue::query()
      ->select('id', 'title', 'headline_id', 'amount', 'payment_date', 'description', 'created_at')
      ->when($headlineId, fn(Builder $query) => $query->where('headline_id', $headlineId))
      ->when($title, fn(Builder $query) => $query->where('title', 'like', "%$title%"))
      ->when($fromPaymentDate, fn(Builder $query) => $query->whereDate('payment_date', '>=', $fromPaymentDate))
      ->when($toPaymentDate, fn(Builder $query) => $query->whereDate('payment_date', '<=', $toPaymentDate))
      ->with(['headline' => fn($query) => $query->select('id', 'title')])
      ->latest('id')
      ->paginate()
      ->withQueryString();

    $totalRevenues = $revenues->total();
    $headlines = Headline::getHeadlinesByType(HeadlineType::TYPE_REVENUE);

    return view('accounting::revenue.index', compact(['revenues', 'totalRevenues', 'headlines']));
  }

  public function create(): View
  {
    $headlines = Headline::getHeadlinesByType(HeadlineType::TYPE_REVENUE);

    return view('accounting::revenue.create', compact('headlines'));
  }

  public function store(RevenueStoreRequest $request): RedirectResponse
  {
    Revenue::query()->create($request->validated());

    return to_route('admin.revenues.index');
  }

  public function edit(Revenue $revenue): View
  {
    $headlines = Headline::getHeadlinesByType(HeadlineType::TYPE_REVENUE);

    return view('accounting::revenue.edit', compact(['revenue', 'headlines']));
  }

  public function update(RevenueUpdateRequest $request, Revenue $revenue): RedirectResponse
  {
    $revenue->update($request->validated());

    return to_route('admin.revenues.index');
  }

  public function destroy(Revenue $revenue): RedirectResponse
  {
    $revenue->delete();

    return to_route('admin.revenues.index');
  }

}
