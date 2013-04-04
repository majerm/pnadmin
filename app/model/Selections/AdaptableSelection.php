<?php

namespace PNAdmin;


/**
 * Adaptable selection.
 */
class AdaptableSelection extends \Nette\Database\Table\Selection
{

	public function formatRowClass()
	{
		$name = implode('', array_map('ucfirst', explode('_', $this->name)));
		return '\\' . __NAMESPACE__ . '\\' . $name . 'Row';
	}

	public function createRow(array $row) {
		$class = $this->formatRowClass();
		if (is_subclass_of($class, '\Nette\Database\Table\ActiveRow')) {
			return new $class($row, $this);
		} else {
			return parent::createRow($row);
		}
	}
	
	public function createSelectionInstance($table = NULL) {
		return new static($table ?: $this->name, $this->connection);
	}

}
