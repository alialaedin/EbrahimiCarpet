<?php

namespace Modules\Core\Traits;

trait BreadCrumb
{
	public function breadcrumbItems($page, $table, $model)
	{
		$commonItem = [
			[
				'title' => "لیست $model ها",
				'link' => $page != 'index' ? route("admin.$table.index") : null
			]
		];

		$items = [
			'index' => $commonItem,
			'create' => array_merge($commonItem, [
				[
					'title' => "ثبت $model جدید",
					'link' => null
				]
			]),
			'edit' => array_merge($commonItem, [
				[
					'title' => "ویرایش $model",
					'link' => null
				]
			]),
			'show' => array_merge($commonItem, [
				[
					'title' => "نمایش $model",
					'link' => null
				]
			])
		];

		return $items[$page] ?? [];
	}
}
