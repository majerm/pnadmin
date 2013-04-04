<?php

namespace PNAdmin;

use \Grido\Components\Columns\Column;
use \Grido\Components\Columns\Date;
use \Grido\Components\Filters\Filter;


/**
 * Adaptable selection.
 */
class PresentableSelection extends AdaptableSelection implements IGridoDataSource
{

	protected $translator;
	
	public function gridoSetTranslator($translator)
	{
		$this->translator = $translator;
	}
	
	public function gridoTranslate($message)
	{
		if ($this->translator) {
			call_user_func_array(array($this->translator, 'translate'), func_get_args());
		} else {
			return $message;
		}
	}

	public function gridoConfigure(&$grido) {
		$columns = $this->getColumns();

		$filtered = array_filter($columns, array($this, 'filerColumns'));
		foreach ($filtered as $column) {
			$this->gridoAddColumn($grido, $column);
		}
	}
	
	public function gridoAddColumn(&$grido, $column)
	{
		$vendorType = $column['vendor']['Type'];
		$gridoType = Column::TYPE_TEXT;
		if ($vendorType == 'datetime' || $vendorType == 'date') {
			$gridoType = Column::TYPE_DATE;
		} elseif ($column['name'] == 'email') {
			$gridoType = Column::TYPE_MAIL;
		}
		$label = $this->gridoTranslate($column['vendor']['Comment']);
		$gridoColumn = $grido->addColumn($column['name'], $label, $gridoType);
		$gridoColumn->setSortable();
		//dump($values);
		if ($gridoType == Column::TYPE_DATE) {
			$gridoColumn
					->setDateFormat(Date::FORMAT_TEXT)
					->setFilter(Filter::TYPE_DATE);
		} elseif (substr($vendorType, 0, 5) == 'enum(') {
			$values = explode(',', substr($vendorType, 5, -1));
			$values = array_map(function ($value) { return substr($value, 1, -1); }, $values);
			$options = array('' => '');
			foreach ($values as $value) {
				$options[$value] = $this->gridoTranslate($value);
			}
			$grido->addFilter($column['name'], $label, Filter::TYPE_SELECT, $options);
		} else {
			$gridoColumn->setFilter();
		}
	}

	public function getForm() {
		return NULL;
	}

	public function getRows($where=NULL) {
		if ($where) {
			$this->where($where);
		}
		return $this;
	}

	public function getTitle() {
		return ucfirst(str_replace('_', ' ', $this->name));
	}

	public function getColumns()
	{
		$driver = $this->getConnection()->getSupplementalDriver();
		$columns = $driver->getColumns($this->getName());
		return $columns;
	}

	public function filerColumns($column)
	{
		return !preg_match('~_html(_[a-z]{2})?$~', $column['name']);
	}


}
