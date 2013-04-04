<?php

namespace PNAdmin;


use Nette\Application\UI\Presenter;
use Nette\InvalidStateException;
use Nette\Localization\ITranslator;
use PNAdmin\Selections\PresentableSelectionContainer;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Presenter
{
	/** @var \PNAdmin\Selections\PresentableSelectionContainer */
	protected $selectionContainer;

	protected $translator;

	public function startup() {
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in');
		}

		parent::startup();
	}

	public function injectSelectionContainer(PresentableSelectionContainer $container)
	{
		if ($this->selectionContainer) {
			throw new InvalidStateException('Selection container was already set.');
		}

		$this->selectionContainer = $container;
	}

	public function injectTranslator(ITranslator $translator)
	{
		if ($this->translator) {
			throw new InvalidStateException('Translator was already set.');
		}

		$this->translator = $translator;
	}

	public function beforeRender()
	{
		$this->template->tableTitles = $this->selectionContainer->getTitles();
		$this->template->setTranslator($this->translator);
	}

}
