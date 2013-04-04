<?php

namespace PNAdmin\Selections;



/**
 * Presentable
 */
interface IPresentableSelection
{
	/**
	 * Returns title of selection
	 * @return string
	 */
	public function getTitle();



	/**
	 * Returns metadata of table
	 * @return array[]
	 */
	public function getColumns();
}
