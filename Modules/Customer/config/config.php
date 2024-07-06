<?php

use Modules\Customer\Models\Customer;

$genderMale = Customer::GENDER_MALE;
$genderFemale = Customer::GENDER_FEMALE;

return [
  'name' => 'Customer',

  'genders' => [
    $genderMale => 'مرد',
    $genderFemale => 'زن',
  ],

  'gender_prefix_to_print' => [
    $genderMale => 'جناب آقای',
    $genderFemale => 'سرکار خانم',
  ],

  'statuses' => [
    1 => 'فعال',
    0 => 'غیر فعال',
  ]
];
