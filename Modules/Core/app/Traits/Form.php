<?php

namespace Modules\Core\Traits;

trait Form
{
	public function generateInput($type, $label, $isRequired, $value = null)
	{
		return [
			'type' => $type,
			'label' => $label,
			'is_required' => $isRequired,
			'value' => $value,
		];
	}

	public function generateSelectInput($placeholder, $options, $isRequired, $value, $class, $multiple)
	{
		return [
			'type' => 'select_option',
			'placeholder' => $placeholder,
			'options' => $options,
			'value' => $value,
			'class' => $class,
			'multiple' => $multiple,
			'is_required' => $isRequired,
		];
	}

	public function generateCheckBoxInput($name, $options, $isRequired)
	{
		/* 
			$options = [
				'label' => 'is_checked'
			]
		*/

		return [
			'type' => 'checkbox',
			'name' => $name,
			'options' => $options,
			'is_required' => $isRequired,
		];
	}
}
