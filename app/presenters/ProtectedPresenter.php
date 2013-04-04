<?php

namespace PNAdmin;

/**
 * Presenter for all presenters for logged users.
 */
abstract class ProtectedPresenter extends BasePresenter
{

	public function startup() {
		parent::startup();
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in');
		}
	}

	public function beforeRender() {
		parent::beforeRender();
		$this->template->tableTitles = $this->getService('tables')->getTitles();
	}

}
