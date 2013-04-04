<?php

namespace PNAdmin;


/**
 * TablePresentation.
 */
class TablePresentation extends \Nette\Object
{

	protected $presentableTables = array();

	public function __construct() {
		$services = func_get_args();
		foreach ($services as $service) {
			if (!($service instanceof IGridoDataSource)) {
				throw new \Exception(get_class($service) . " not implemented IPresentableTable");
			}
			$this->presentableTables[$service->getName()] = $service;
		}
	}

	public function getTitles()
	{
		$titles = array();
		foreach ($this->presentableTables as $table) {
			$titles[$table->getName()] = $table->getTitle();
		}
		return $titles;
	}

	public function get($name)
	{
		return $this->presentableTables[$name];
	}

}
