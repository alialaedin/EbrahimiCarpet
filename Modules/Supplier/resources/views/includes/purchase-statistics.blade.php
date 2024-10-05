<div class="row">

  @include('admin::dashboard.includes.info-box', [
    'title' => 'مبلغ کل خرید (ریال)',
    'amount' => number_format($supplier->total_purchases_amount),
    'color' => 'primary',
  ])
  @include('admin::dashboard.includes.info-box', [
    'title' => 'جمع کل پرداختی ها (ریال)',
    'amount' => number_format($supplier->total_payments_amount),
    'color' => 'pink',
  ])
  @include('admin::dashboard.includes.info-box', [
    'title' => 'پرداختی های پرداخت شده (ریال)',
    'amount' => number_format($supplier->paid_payments_amount),
    'color' => 'success',
  ])
  @include('admin::dashboard.includes.info-box', [
    'title' => 'پرداختی های پرداخت نشده (ریال)',
    'amount' => number_format($supplier->unpaid_payments_amount),
    'color' => 'warning',
  ])
  @include('admin::dashboard.includes.info-box', [
    'title' => 'پرداختی های نقدی (ریال)',
    'amount' => number_format($supplier->all_payments_amount['cash']),
    'color' => 'secondary',
  ])
  @include('admin::dashboard.includes.info-box', [
    'title' => 'پرداختی های چکی (ریال)',
    'amount' => number_format($supplier->all_payments_amount['cheque']),
    'color' => 'danger',
  ])
  @include('admin::dashboard.includes.info-box', [
    'title' => 'پرداختی های قسطی (ریال)',
    'amount' => number_format($supplier->all_payments_amount['installment']),
    'color' => 'purple',
  ])
  @include('admin::dashboard.includes.info-box', [
    'title' => 'مبلغ باقی مانده (ریال)',
    'amount' => number_format($supplier->remaining_amount),
    'color' => 'info',
  ])
</div>