<?php

namespace PNAdmin\Selections;


use Nette\Database\Table\Selection;

/**
 * Adaptable selection.
 */
class AdaptableSelection extends Selection
{
	private $namespace;

	public function __construct($table, \Nette\Database\Connection $connection, $namespace)
	{
		parent::__construct($table, $connection);
		$this->namespace = $namespace;
	}

	public function formatRowClass()
	{
		$name = implode('', array_map('ucfirst', explode('_', $this->name)));
		return $this->namespace . '\\' . $name . 'Row';
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
		return new static($table ?: $this->name, $this->connection, $this->namespace);
	}

}
