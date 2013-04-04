<?php


use Nette\Localization\ITranslator;
use PNAdmin\Selections\PresentableSelection;

/**
 * Translator.
 */
class Translator extends PresentableSelection implements ITranslator {

	protected $dictionary;

	public function translate($message, $count = NULL) {
		if (!$this->dictionary) {
			$this->dictionary = $this->where('translated', TRUE)->fetchPairs('idf', 'translation');
		}

		if (is_array($message)) {
			$tmp = array_shift($message);
			$args = $message;
			$message = $tmp;
		}

		if (!isset($args)) {
			$args = func_get_args();
			array_shift($args);
		}

		if (isset($this->dictionary[$message])) {
			if (is_array($this->dictionary[$message])) {
				if (count($args) > 0) {
					if (isset($this->dictionary[$message][pluralIndex($args[0])])) {
						$message = $this->dictionary[$message][pluralIndex($args[0])];
					}
				}
			} else {
				$message = $this->dictionary[$message];
			}
		}

		if (count($args) > 0) {
			return vsprintf($message, $args);
		}

		return $message;
	}

}
