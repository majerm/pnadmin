<?php

/**
 * This file is part of the PNAdmin
 *
 * Copyright (c) 2013 Milan Majer
 */

namespace PNAdmin;



/** Presenter factory for current namespace.
 *
 * @author Milan Majer <majerm@gmail.com>
 */
class PresenterFactory extends \Nette\Application\PresenterFactory
{

	/** Return presenter class name with actual namespace name.
	 * 
	 * @param type $presenter
	 * @return type presenter class name with namespace
	 */
	public function formatPresenterClass($presenter) {
		return __NAMESPACE__ . '\\' . parent::formatPresenterClass($presenter);
	}


	/** Return unformated presenter class name.
	 * 
	 * @param type $presenter
	 * @return type unformated presenter class name
	 */
	public function unformatPresenterClass($presenter) {
		$tmp = preg_replace('~^'.preg_quote(__NAMESPACE__).'\\\\~', '', $presenter);
		return parent::unformatPresenterClass($tmp);
	}

}
