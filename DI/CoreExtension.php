<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace App\CoreModule\DI;

use Venne;
use Venne\Module\CompilerExtension;
use Nette\Application\Routers\Route;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class CoreExtension extends CompilerExtension
{

	/**
	 * Processes configuration data. Intended to be overridden by descendant.
	 * @return void
	 */
	public function loadConfiguration()
	{
		parent::loadConfiguration();
		$container = $this->getContainerBuilder();
		$config = $this->getConfig();

		$container->addDependency($container->parameters["flagsDir"] ."/installed");

		if(file_exists($container->expand($container->parameters["flagsDir"]) ."/installed")){

			// detect prefix
			$prefix = $container->parameters["website"]["routePrefix"];
			$adminPrefix = $container->parameters["administration"]["routePrefix"];
			$languages = $container->parameters["website"]["languages"];
			$prefix = str_replace("<lang>/", "<lang " . implode("|", $languages) . ">/", $prefix);


			// parameters
			$parameters = array();
			$parameters["lang"] = count($languages) > 1 || $container->parameters["website"]["routePrefix"] ? NULL : $container->parameters["website"]["defaultLanguage"];


			// Administration
			$container->addDefinition($this->prefix("adminRoute"))
				->setClass("App\CoreModule\Routes\Admin",
				array(($adminPrefix ? "$adminPrefix/" : "") . '<module>/<presenter>[/<action>[/<id>]]',
					array('module' => "Core", 'presenter' => 'Default', 'action' => 'default',)
				))
				->setInternal(true)
				->setAutowired(false);

			$container->addDefinition($this->prefix("adminRouteList"))
				->setClass("App\CoreModule\Routes\RouteList", array("admin"))
				->addSetup('$service[] = ?', "@core.adminRoute")
				->addTag("route", array("priority"=>9999999))
				->setAutowired(false);


			// CMS route
			$container->addDefinition($this->prefix("pageRoute"))
				->setClass("App\CoreModule\Routes\Page",
					array('@core.cmsManager', '@core.pageRepository', '@core.languageRepository', '@cacheStorage', $prefix, $parameters, $container->parameters["website"]["languages"], $container->parameters["website"]["defaultLanguage"])
					)
				->addTag("route", array("priority"=>100))
				->setAutowired(false);


			// default route
			$container->addDefinition($this->prefix("defaultRoute"))
				->setClass("Nette\Application\Routers\Route", array('', array("presenter" => $container->parameters["website"]["defaultPresenter"], "lang" => NULL), Route::ONE_WAY))
				->addTag("route", array("priority"=>-9999999))
				->setAutowired(false);

		}else{

			$container->addDefinition($this->prefix("adminRoute"))
				->setClass("Nette\Application\Routers\Route",
					array('<action>[/<id>]',
						array("presenter" => "Core:Installation:Default", "action" => "default",)
					))
				->addTag("route")
				->setAutowired(false);

		}

	}

}
