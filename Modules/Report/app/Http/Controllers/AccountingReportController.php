<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Modules\Accounting\Models\Expense;
use Modules\Accounting\Models\Revenue;
use Modules\Accounting\Models\Salary;

class AccountingReportController extends Controller
{
  public function revenues(): View
  {
    $revenues = Revenue::query()
      ->select('id', 'headline_id', 'title', 'amount', 'payment_date', 'created_at')
      ->with('headline:id,title')
      ->latest('id')
      ->get();

    return view('report::accounting.revenue.index', compact('revenues'));
  }

  public function expenses(): View
  {
    $expenses = Expense::query()
      ->select('id', 'headline_id', 'title', 'amount', 'payment_date', 'created_at')
      ->with('headline:id,title')
      ->latest('id')
      ->get();

    return view('report::accounting.expenses.index', compact('expenses'));
  }

  public function salaries(): View
  {
    $salaries = Salary::query()
      ->select('id', 'employee_id', 'overtime', 'amount', 'payment_date', 'created_at')
      ->with('employee:id,name,mobile,salary')
      ->latest('id')
      ->get();

    return view('report::accounting.salaries.index', compact('salaries'));
  }
}
