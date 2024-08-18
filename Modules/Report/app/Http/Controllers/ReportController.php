<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReportController extends Controller
{
  public function index(): View
  {
    $reports = $this->reports();
    return view('report::index', compact('reports'));
  }

  private function reports(): array
  {
    return [
      [
        'title' => 'گزارش سود و ضرر',
        'route' => 'admin.reports.profit'
      ],
      [
        'title' => 'گزارش خرید ها',
        'route' => 'admin.reports.purchases-filter'
      ],
      [
        'title' => 'گزارش فروش ها',
        'route' => 'admin.reports.sales-filter'
      ],
      [
        'title' => 'گزارش مالی تامین کننده (کلی)',
        'route' => 'admin.reports.all-suppliers-finance'
      ],
      [
        'title' => 'گزارش مالی تامین کننده (جزئی)',
        'route' => 'admin.reports.suppliers-finance-filter'
      ],
      [
        'title' => 'گزارش پرداختی به تامین کننده',
        'route' => 'admin.reports.supplier-payments-filter'
      ],
      [
        'title' => 'گزارش مالی مشتریان (کلی)',
        'route' => 'admin.reports.all-customers-finance'
      ],
      [
        'title' => 'گزارش مالی مشتریان (جزئی)',
        'route' => 'admin.reports.customers-finance-filter'
      ],
      [
        'title' => 'گزارش دریافتی از مشتری',
        'route' => 'admin.reports.customer-payments-filter'
      ],
      [
        'title' => 'گزارش مالی هزینه ها',
        'route' => 'admin.reports.expenses'
      ],
      [
        'title' => 'گزارش مالی درامد ها',
        'route' => 'admin.reports.revenues'
      ],
      [
        'title' => 'گزارش مالی حقوق ها',
        'route' => 'admin.reports.salaries'
      ]
    ];
  }
}
