<?php

namespace PNAdmin;



/**
 * Presentable
 */
interface IPresentableTable
{

	public function getTitle();

	public function getRows($where);

	public function get($id);

	public function getForm();
	
	public function getColumns();

}
