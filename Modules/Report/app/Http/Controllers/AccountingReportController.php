<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Modules\Accounting\Models\Expense;
use Modules\Accounting\Models\Headline;
use Modules\Accounting\Models\Revenue;
use Modules\Accounting\Models\Salary;
use Modules\Employee\Models\Employee;

class AccountingReportController extends Controller
{
  public function revenues(): View
  {
    $headlineId = request('headline_id');
    $fromPaymentDate = request('from_payment_date');
    $toPaymentDate = request('to_payment_date') ?? now();

    $revenues = Revenue::query()
      ->select('id', 'headline_id', 'title', 'amount', 'payment_date', 'created_at')
      ->when($headlineId, fn(Builder $query) => $query->where('headline_id', $headlineId))
      ->when($fromPaymentDate, fn(Builder $query) => $query->whereDate('payment_date', '>=', $fromPaymentDate))
      ->whereDate('payment_date', '<=', $toPaymentDate)
      ->with('headline:id,title')
      ->latest('id')
      ->get();

    $totalRevenuesAmount = $revenues->sum('amount');
    $headlines = $this->getHeadlines(Headline::TYPE_REVENUE);

    return view('report::accounting.revenue.index', compact(['revenues', 'headlines', 'totalRevenuesAmount']));
  }

  public function expenses(): View
  {
    $headlineId = request('headline_id');
    $fromPaymentDate = request('from_payment_date');
    $toPaymentDate = request('to_payment_date') ?? now();

    $expenses = Expense::query()
      ->select('id', 'headline_id', 'title', 'amount', 'payment_date', 'created_at')
      ->when($headlineId, fn(Builder $query) => $query->where('headline_id', $headlineId))
      ->when($fromPaymentDate, fn(Builder $query) => $query->whereDate('payment_date', '>=', $fromPaymentDate))
      ->whereDate('payment_date', '<=', $toPaymentDate)
      ->with('headline:id,title')
      ->latest('id')
      ->get();

    $totalExpensesAmount = $expenses->sum('amount');
    $headlines = $this->getHeadlines(Headline::TYPE_EXPENSE);

    return view('report::accounting.expenses.index', compact(['expenses', 'headlines', 'totalExpensesAmount']));
  }

  public function salaries(): View
  {
    $employeeId = request('employee_id');
    $fromPaymentDate = request('from_payment_date');
    $toPaymentDate = request('to_payment_date') ?? now();

    $salaries = Salary::query()
      ->select('id', 'employee_id', 'overtime', 'amount', 'payment_date', 'created_at')
      ->when($employeeId, fn(Builder $query) => $query->where('employee_id', $employeeId))
      ->when($fromPaymentDate, fn(Builder $query) => $query->whereDate('payment_date', '>=', $fromPaymentDate))
      ->whereDate('payment_date', '<=', $toPaymentDate)
      ->with('employee:id,name,mobile,salary')
      ->latest('id')
      ->get();

    $employees = Employee::all('id', 'name', 'mobile');

    return view('report::accounting.salaries.index', compact(['salaries', 'employees']));
  }

  private function getHeadlines(string $type): \Illuminate\Database\Eloquent\Collection|array
  {
    return Headline::query()
      ->select('id', 'title', 'type')
      ->where('type', '=', $type)
      ->latest('id')
      ->get();
  }
}
