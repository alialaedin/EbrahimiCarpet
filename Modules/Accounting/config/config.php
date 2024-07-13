<?php

use Modules\Accounting\Models\Headline;

$typeRevenue = Headline::TYPE_REVENUE;
$typeExpense = Headline::TYPE_EXPENSE;

return [
  'name' => 'Accounting',

  'headline_types' => [
    $typeRevenue => 'درآمد',
    $typeExpense => 'هزینه'
  ]

];
