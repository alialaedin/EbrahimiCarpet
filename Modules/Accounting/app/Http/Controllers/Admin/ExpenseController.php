<?php

namespace Modules\Accounting\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Accounting\Enums\HeadlineType;
use Modules\Accounting\Http\Requests\Admin\Expense\ExpenseStoreRequest;
use Modules\Accounting\Http\Requests\Admin\Expense\ExpenseUpdateRequest;
use Modules\Accounting\Models\Expense;
use Modules\Accounting\Models\Headline;

class ExpenseController extends Controller implements HasMiddleware
{
  public static function middleware(): array
  {
    return [
      new Middleware('can:view expenses', ['index']),
      new Middleware('can:create expenses', ['create', 'store']),
      new Middleware('can:edit expenses', ['edit', 'update']),
      new Middleware('can:delete expenses', ['destroy']),
    ];
  }

  public function index(): View
  {
    $headlineId = request('headline_id');
    $title = request('title');
    $fromPaymentDate = request('from_payment_date');
    $toPaymentDate = request('to_payment_date');

    $expenses = Expense::query()
      ->select('id', 'title', 'headline_id', 'amount', 'payment_date', 'description', 'created_at')
      ->filters()
      ->with(['headline' => fn($query) => $query->select('id', 'title')])
      ->latest('id')
      ->paginate()
      ->withQueryString();

    $totalExpenses = $expenses->total();
    $headlines = Headline::getHeadlinesByType(HeadlineType::TYPE_EXPENSE);

    return view('accounting::expense.index', compact(['expenses', 'totalExpenses', 'headlines']));
  }

  public function create(): View
  {
    $headlines = Headline::getHeadlinesByType(HeadlineType::TYPE_EXPENSE);
    return view('accounting::expense.create', compact('headlines'));
  }

  public function store(ExpenseStoreRequest $request): RedirectResponse
  {
    Expense::query()->create($request->validated());
    return to_route('admin.expenses.index');
  }

  public function edit(Expense $expense): View
  {
    $headlines = Headline::getHeadlinesByType(HeadlineType::TYPE_EXPENSE);
    return view('accounting::expense.edit', compact(['expense', 'headlines']));
  }

  public function update(ExpenseUpdateRequest $request, Expense $expense): RedirectResponse
  {
    $expense->update($request->validated());
    return to_route('admin.expenses.index');
  }

  public function destroy(Expense $expense): RedirectResponse
  {
    $expense->delete();
    return to_route('admin.expenses.index');
  }

}
