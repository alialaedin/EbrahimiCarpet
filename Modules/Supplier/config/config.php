<?php

use Modules\Supplier\Models\Supplier;

$typeLegal = Supplier::TYPE_LEGAL;
$typeReal = Supplier::TYPE_REAL;

return [
    'name' => 'Supplier',
    'types' => [
      $typeLegal => 'حقوقی',
      $typeReal => 'حقیقی'
    ]
];
