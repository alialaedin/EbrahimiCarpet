<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Modules\Accounting\Models\Expense;
use Modules\Accounting\Models\Revenue;

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

    return view('report::accounting.revenue.index', compact('expenses'));
  }
}
