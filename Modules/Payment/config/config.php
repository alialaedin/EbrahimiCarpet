<?php

use Modules\Payment\Models\Payment;

$typeCash = Payment::TYPE_CASH;
$typeInstallment = Payment::TYPE_INSTALLMENT;
$typeCheque = Payment::TYPE_CHEQUE;

return [
  'name' => 'Payment',

  'types' => [
    $typeCash => 'نقد',
    $typeInstallment => 'قسط',
    $typeCheque => 'چک',
  ],

  'statuses' => [
    $typeCash => [
      0 => 'پرداخت نشده',
      1 => 'پرداخت شده'
    ],
    $typeInstallment => [
      0 => 'پرداخت نشده',
      1 => 'پرداخت شده'
    ],
    $typeCheque => [
      0 => 'پاس نشده',
      1 => 'پاس شده'
    ],
  ]

];
