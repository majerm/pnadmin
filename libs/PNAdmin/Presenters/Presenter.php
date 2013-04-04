<?php
/**
 * Created by Pavel Rak (rak.pavel.89@gmail.com)
 * Copyright(c) 2013. All rights reserved.
 */

namespace PNAdmin\Presenters;


use Grido\Components\Columns\Column;
use Grido\Components\Filters\Filter;
use Grido\Grid;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Nette\NotImplementedException;
use PNAdmin\Selections\PresentableSelection;
use PNAdmin\Selections\PresentableSelectionContainer;

abstract class Presenter extends \Nette\Application\UI\Presenter
{
	/** @var \Nette\Database\Table\ActiveRow */
	private $record;

	/** @var \PNAdmin\Selections\PresentableSelectionContainer */
	protected $selectionContainer;

	/** @var \Nette\Localization\ITranslator */
	protected $translator;

	/** @var \Grido\Grid */
	protected $gridComponent;

	/** @var \PNAdmin\Selections\PresentableSelection */
	protected $selection;

	/** @var int */
	protected $itemsPerPage = 30;



	/**
	 * @param PresentableSelectionContainer $container
	 * @throws Nette\InvalidStateException
	 */
	public function injectSelectionContainer(PresentableSelectionContainer $container)
	{
		if ($this->selectionContainer) {
			throw new Nette\InvalidStateException('Selection container has already been set');
		}
		$this->selectionContainer = $container;
	}



	/**
	 * @param ITranslator $translator
	 * @throws Nette\InvalidStateException
	 */
	public function injectTranslator(ITranslator $translator)
	{
		if ($this->translator) {
			throw new Nette\InvalidStateException('Translator has already been set');
		}
		$this->translator = $translator;
	}

	/**
	 * Inits presenter
	 */
	public function startup()
	{
		parent::startup();

		$this->gridComponent = new Grid($this, 'grido');
		$this->selection = $this->selectionContainer->get($this->getParameter('table'));
	}



	/**
	 * @throws \Exception
	 */
	public function actionDefault($table)
	{
		$this->gridComponent->setModel($this->selection);
		$this->gridComponent->setTranslator($this->getContext()->translator);
		$this->gridComponent->setDefaultPerPage($this->itemsPerPage);
		$this->gridComponent->addAction('edit', 'Edit', \Grido\Components\Actions\Action::TYPE_HREF, 'edit', array('table' => $this->getParameter('table')))
			->setIcon('pencil');

		$presenter = $this;
		$this->gridComponent->setOperations(array('delete' => 'Delete'), function($operation, $id) use ($presenter) {
			$method = 'handle' . ucfirst($operation);
			if (!$presenter->getReflection()->hasMethod($method)) {
				throw new \Exception("Method $method does not exists in presenter " . get_class($presenter));
			}

			$presenter->{$method}($id);
		});

		$this->configureColumns();
	}




	public function actionEdit($table, $id)
	{

	}



	/**
	 * Deletes record from DB
	 */
	public function handleDelete()
	{
		$record = $this->getRecord();
		$record->delete();
	}



	/**
	 * @param string $name
	 */
	public function createComponentEditForm($name)
	{
		$form = new Form($this, $name);

		// TODO: implement form creation
	}



	/**
	 * Adds column to grid
	 * @param $column
	 */
	protected function addColumn($column)
	{
		// determine type of column
		$vendorType = $column['vendor']['Type'];
		$gridoType = Column::TYPE_TEXT;
		if ($vendorType == 'datetime' || $vendorType == 'date') {
			$gridoType = Column::TYPE_DATE;
		} elseif ($column['name'] == 'email') {
			$gridoType = Column::TYPE_MAIL;
		}

		// create column
		$label = $this->translator->translate($column['vendor']['Comment']);
		$gridoColumn = $this->gridComponent->addColumn($column['name'], $label, $gridoType);
		$gridoColumn->setSortable();

		// filters
		if ($gridoType == Column::TYPE_DATE) {
			$gridoColumn
				->setDateFormat(Date::FORMAT_TEXT)
				->setFilter(Filter::TYPE_DATE);
		} elseif (substr($vendorType, 0, 5) == 'enum(') {
			$values = explode(',', substr($vendorType, 5, -1));
			$values = array_map(function ($value) { return substr($value, 1, -1); }, $values);
			$options = array('' => '');
			foreach ($values as $value) {
				$options[$value] = $this->translator->translate($value);
			}
			$this->gridComponent->addFilter($column['name'], $label, Filter::TYPE_SELECT, $options);
		} else {
			$gridoColumn->setFilter();
		}
	}



	/**
	 * Configures grid columns
	 */
	protected function configureColumns()
	{
		$columns = $this->selection->getColumns();

		$filtered = array_filter($columns, array($this, 'filterColumn'));
		foreach ($filtered as $column) {
			$this->addColumn($column);
		}
	}



	/**
	 * Helper method for filtering grid columns
	 * @param array $column
	 * @return bool
	 */
	public function filterColumn($column)
	{
		return TRUE;
	}



	/**
	 * @return \Nette\Database\Table\ActiveRow
	 * @throws \Nette\Application\BadRequestException
	 */
	protected function getRecord()
	{
		$id = $this->getParameter('id');
		if (!$this->record) {
			$record = $this->selection->get($id);
			if (!$record) {
				throw new BadRequestException('Row of table ' . $this->selection->getName() . ' does not exist.');
			}
			$this->record = $record;
		}

		return $this->record;
	}
}