<?php

namespace PNAdmin\Selections;

use \Grido\Components\Columns\Column;
use \Grido\Components\Columns\Date;
use \Grido\Components\Filters\Filter;
use Nette\DI\Statement;


/**
 * Adaptable selection.
 */
class PresentableSelection extends AdaptableSelection implements IPresentableSelection
{
	private $title;

	public function getTitle() {
		// TODO: cache it!
		if (!$this->title) {
			$row = $this->getConnection()->query('SHOW TABLE STATUS WHERE `Name` = \''.$this->name.'\'')->fetch(\PDO::FETCH_ASSOC);
			$this->title = $row['Comment'];
		}
		return $this->title;
	}

	public function getColumns()
	{
		$driver = $this->getConnection()->getSupplementalDriver();
		$columns = $driver->getColumns($this->getName());
		return $columns;
	}

}
