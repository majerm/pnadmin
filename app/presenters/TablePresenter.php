<?php

namespace PNAdmin;


/**
 * Table presenter.
 */
class TablePresenter extends ProtectedPresenter
{

	public function renderDefault($table, $listingPage=1)
	{
		$service = $this->getService('tables')->get($table);

		$translator = $this->getService('translator');
		$grid = new \Grido\Grid($this, 'grido');
		$model = $this->getService('tables')->get($table);
		$grid->setModel($model);
		$grid->setTranslator($translator);
		$grid->setDefaultPerPage(30);
		$grid->addAction('edit', 'Edit', \Grido\Components\Actions\Action::TYPE_HREF, 'Table:edit', array('table' => $table))
		     ->setIcon('pencil');
		$grid->setOperations(array('delete' => 'Delete'), function($operation, $id) {} );
		
		if ($model instanceof IGridoDataSource) {
			$model->gridoConfigure($grid);
		}
		$grid->setExporting();
		
	}
	
	public function renderEdit($table, $id)
	{
	}

	/**
     * Handler for operations.
     * @param string $operation
     * @param array $id
     */
    public function gridOperationsHandler($operation, $id)
    {
        if ($id) {
            $row = implode(', ', $id);
            $this->flashMessage("Process operation '$operation' for row with id: $row...", 'info');
        } else {
            $this->flashMessage('No rows selected.', 'error');
        }

        $this->redirect($operation, array('id' => $id));
    }
}
