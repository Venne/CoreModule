<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace App\CoreModule\Helpers;

use Venne;
use Nette;
use Nette\Object;
use Venne\Templating\BaseHelper;
use Doctrine\Common\EventManager;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class ContentHelper extends BaseHelper
{


	/** @var EventManager */
	protected $eventManager;



	/**
	 * @param EventManager $eventManager 
	 */
	public function __construct(EventManager $eventManager)
	{
		parent::__construct();
		$this->eventManager = $eventManager;
	}



	/**
	 * @param $text
	 * @return string
	 */
	public function run($text)
	{
		$args = new \App\CoreModule\Events\ContentHelperArgs();
		$args->setText($text);

		$this->eventManager->dispatchEvent(\App\CoreModule\Events\ContentHelperEvents::onContentRender, $args);

		return $args->getText();
	}

}

