<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CoreModule\Presenters;

use Venne;
use CoreModule\Routes\Page as PageRoute;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class PagePresenter extends FrontPresenter
{


	/** @persistent */
	public $url = "";

	/** @var \CoreModule\Entities\BasePageEntity */
	public $page;



	/**
	 * @return void
	 */
	public function startup()
	{
		parent::startup();
		$entity = $this->getParameter("page");

		// generate path
		$this->generatePath($entity);

		// load page module entity
		$this->page = $this->loadPage();
		if (!$this->page) {
			throw new \Nette\Application\BadRequestException;
		}
		$this->url = $this->page->url;
	}

	
	
	/**
	 * Generate path.
	 * 
	 * @param $page 
	 */
	protected function generatePath($page)
	{
		if($page->parent){
			$this->generatePath($page->parent);
		}
		$this->addPath($page->title, $this->link(":" . $page->type, array("url" => $page->url)));
	}



	/**
	 * Load page.
	 *
	 * @return \CoreModule\Entities\BasePageEntity
	 */
	protected function loadPage()
	{
		$entity = $this->getParameter("page");
		$repository = $this->context->core->cmsManager->getContentRepository($entity->type);

		$page = $repository->findOneBy(array("page" => $entity->id));
		if (!$page && !$this->url) {
			$page = $this->context->blog->blogRepository->findOneBy(array("mainPage" => true));
		}
		return $page;
	}



	/**
	 * Redirect to other language.
	 *
	 * @param string $alias
	 */
	public function handleChangeLanguage($alias)
	{
		$page = $this->page->page->getPageWithLanguageAlias($alias);
		$this->redirect("this", array("lang" => $alias, "url" => ($page ? $page->url : "")));
	}



	/**
	 * Get layout path.
	 *
	 * @return null|string
	 */
	protected function getLayoutPath()
	{
		if (!$this->page->layoutFile) {
			return NULL;
		}

		$pos = strpos($this->page->layoutFile, "/");
		$module = lcfirst(substr($this->page->layoutFile, 1, $pos - 1));

		if (!$this->context->hasService($module)) {
			return NULL;
		}

		return $this->context->$module->getPath() . "/layouts/" . substr($this->page->layoutFile, $pos + 1);
	}



	/**
	 * Formats layout template file names.
	 *
	 * @return array
	 */
	public function formatLayoutTemplateFiles()
	{
		$path = $this->getLayoutPath();
		return ($path ? array($path . "/@layout.latte") : parent::formatLayoutTemplateFiles());
	}



	/**
	 * Formats view template file names.
	 *
	 * @return array
	 */
	public function formatTemplateFiles()
	{
		$ret = parent::formatTemplateFiles();
		$name = $this->getName();
		$presenter = str_replace(":", "/", $this->name);

		$path = $this->getLayoutPath();
		if ($path) {
			$ret = array_merge(array(
				"$path/$presenter/$this->view.latte",
				"$path/$presenter.$this->view.latte",
			), $ret);
		}
		return $ret;
	}



	/**
	 * Component factory. Delegates the creation of components to a createComponent<Name> method.
	 *
	 * @param  string	  component name
	 * @return IComponent  the created component (optionally)
	 */
	public function createComponent($name)
	{
		if (substr($name, 0, 17) == "contentExtension_") {
			$this->context->eventManager->dispatchEvent(\Venne\ContentExtension\Events::onContentExtensionRender);
		} else {
			return parent::createComponent($name);
		}
	}



	/**
	 * Common render method.
	 *
	 * @return void
	 */
	public function beforeRender()
	{
		parent::beforeRender();
		$this->template->entity = $this->page;
		$this["head"]->setTitle($this->page->title);
		$this["head"]->setRobots($this->page->robots);
	}

}

