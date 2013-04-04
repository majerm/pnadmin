<?php

namespace PNAdmin;


use PNAdmin\Presenters\Presenter;

/**
 * Table presenter.
 */
class TablePresenter extends Presenter
{

	public function startup() {
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in');
		}

		parent::startup();
	}

	public function beforeRender() {
		parent::beforeRender();
		$this->template->tableTitles = $this->selectionContainer->getTitles();
		$this->template->setTranslator($this->translator);
	}
}
