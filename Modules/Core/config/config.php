<?php

return [
  'name' => 'Core',

  'super_admin_role' => [
    'name' => 'super_admin',
    'label' => 'مدیر ارشد',
  ],

  'events' => [
    'created' => 'ایجاد کرد',
    'updated' => 'ویرایش کرد',
    'deleted' => 'حذف کرد'
  ],

  'payment_types' => [
    'cash' => 'نقد',
    'installment' => 'قسط',
    'cheque' => 'چک',
  ],

  'headline_types' => [
    'revenue' => 'درامد',
    'expense' => 'هزینه'
  ],

  'accept_image_mimes' => [
    'png',
    'jpg'
  ],

  'category_unit_types' => [
    'meter' => 'متر',
    'number' => 'عدد'
  ],

  'bool_statuses' => [
    '1' => 'فعال',
    '0' => 'غیر فعال'
  ]
];
