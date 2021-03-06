<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CoreModule\Managers;

use Venne;
use Nette\Object;
use Nette\DI\Container;
use Venne\Forms\PageForm;
use \Closure;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class CmsManager extends Object
{


	/** @var Container */
	protected $context;

	/** @var array */
	protected $contentTypes = array();



	public function __construct(Container $context)
	{
		$this->context = $context;
	}



	/* --------------------- Configuration ------------------------- */


	public function addContentType($name, $label, array $pageParams, \Venne\Doctrine\ORM\BaseRepository $repository, Closure $formFactory)
	{
		$this->contentTypes[$name] = array("label" => $label, "form" => $formFactory, "repository" => $repository, "params" => $pageParams);
	}



	/* ----------------------- get --------------------------- */


	/**
	 * Get Content Types as array
	 *
	 * @return array
	 */
	public function getContentTypes()
	{
		$ret = array();
		foreach ($this->contentTypes as $key => $item) {
			$ret[$key] = $item["label"];
		}
		return $ret;
	}



	public function hasContentType($type)
	{
		return isset($this->contentTypes[$type]);
	}



	public function getContentForm($type, $entity)
	{
		$closure = $this->contentTypes[$type]["form"];
		$form = $closure();
		$form->setEntity($entity);
		return $form;
	}



	public function getContentRepository($type)
	{
		return $this->contentTypes[$type]["repository"];
	}



	public function getContentEntity($type)
	{
		return $this->getContentRepository($type)->createNew();
	}



	public function getContentParams($type)
	{
		return $this->contentTypes[$type]["params"];
	}

}

