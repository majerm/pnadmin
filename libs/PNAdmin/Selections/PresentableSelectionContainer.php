<?php
/**
 * Created by Pavel Rak (rak.pavel.89@gmail.com)
 * Copyright(c) 2013. All rights reserved.
 */

namespace PNAdmin\Selections;


use Nette\Object;

class PresentableSelectionContainer extends Object
{
	/** @var IPresentableSelection[] */
	protected $selections = array();



	/**
	 * Adds selection to container
	 * @param PresentableSelection $selection
	 */
	public function addSelection(PresentableSelection $selection)
	{
		$this->selections[$selection->getName()] = $selection;
	}



	/**
	 * Returns array of
	 * @return IPresentableSelection[]
	 */
	public function getSelections()
	{
		return $this->selections;
	}


	/**
	 * Returns array of selection titles
	 * @return array
	 */
	public function getTitles()
	{
		$titles = array();
		foreach ($this->selections as $selection) {
			$titles[$selection->getName()] = $selection->getTitle();
		}
		return $titles;
	}



	/**
	 * Returns
	 * @param $name
	 * @return mixed
	 */
	public function get($name)
	{
		return $this->selections[$name];
	}
}