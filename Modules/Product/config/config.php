<?php

use Modules\Product\Models\Category;

$typeMeter = Category::UNIT_TYPE_METER;
$typeNumber = Category::UNIT_TYPE_NUMBER;

return [
  'name' => 'Product',

  'unit_types' => [
    $typeMeter => 'متر',
    $typeNumber => 'عدد',
  ]
];
