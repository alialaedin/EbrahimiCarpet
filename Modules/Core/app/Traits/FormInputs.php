<?php

namespace Modules\Core\Traits;

trait FormInputs
{
	public function generateTextInput($type, $name, $label)
	{
		return [
			'type' => $type,
			'name' => $name,
			'label' => $label,
		];
	}

	public function generateSelectOption($name, $label, $options)
	{
		return [
			'type' => 'select_option',
			'name' => $name,
			'label' => $label,
			'options' => $options,
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
