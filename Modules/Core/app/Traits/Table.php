<?php

namespace Modules\Core\Traits;

trait Table
{
	public function table($cardHeader, $total, $data, $operationButtons, $tableHeaders)
	{
		return [
			'card_header' => $cardHeader,
			'total_resault' => $total,
			'table_headers' => $tableHeaders,
			'data' => $data,
			'operation_buttons' => $operationButtons
		];
	}
}
