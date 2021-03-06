<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CoreModule;

use Nette\Config\Compiler;
use Nette\Config\Configurator;
use Nette\DI\Container;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class Module extends \Venne\Module\BaseModule {


	/** @var string */
	protected $version = "2.0";

	/** @var string */
	protected $description = "Core module for Venne:CMS";



	public function compile(Compiler $compiler)
	{
		$compiler->addExtension($this->getName(), new \CoreModule\DI\CoreExtension($this->getPath(), $this->getNamespace()));
	}



	public function install(\Nette\DI\Container $container)
	{
		parent::install($container);

		/* Install default roles */
		$repository = $container->core->roleRepository;
		foreach (array("admin" => NULL, "guest" => NULL, "authenticated" => "guest") as $name => $parent) {
			$role = $repository->createNew();
			$role->name = $name;
			if ($parent) {
				$role->parent = $repository->findOneBy(array("name" => $parent));
			}
			$repository->save($role);
		}
	}

}
