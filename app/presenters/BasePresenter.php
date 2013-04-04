<?php

namespace PNAdmin;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends \Nette\Application\UI\Presenter
{
	public function beforeRender() {
		parent::beforeRender();
		$translator = $this->getService('translator');
		if ($translator) {
			$this->template->setTranslator($translator);
		}
	}
}
