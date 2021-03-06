<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CoreModule\Entities;

use Nette\Object;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class BasePageEntity extends \Venne\Doctrine\ORM\BaseEntity {


	const LINK = "";

	/**
	 * @var \CoreModule\Entities\PageEntity
	 * @OneToOne(targetEntity="\CoreModule\Entities\PageEntity", cascade={"persist", "remove", "detach"})
	 * @JoinColumn(name="page_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $page;

	/**
	 * @Form(type="manyToMany", targetEntity="\CoreModule\Entities\LanguageEntity")
	 */
	protected $languages;

	/**
	 * @Form(type="manyToOne", targetEntity="\CoreModule\Entities\PageEntity")
	 */
	protected $translationFor;

	/**
	 * @Form(type="manyToOne", targetEntity="\CoreModule\Entities\PageEntity")
	 */
	protected $parent;

	/**
	 * @Form(type="manyToOne", targetEntity="\CoreModule\Entities\LayoutEntity")
	 */
	protected $layout;



	public function __construct()
	{
		$this->page = new \CoreModule\Entities\PageEntity(static::LINK);
		parent::__construct();
	}



	public function __toString()
	{
		return $this->title;
	}



	public function getPage()
	{
		if (!$this->page) {
			$this->page = new \CoreModule\Entities\PageEntity(static::LINK);
		}
		return $this->page;
	}



	public function getUrl()
	{
		return $this->getPage()->url;
	}



	public function getLocalUrl()
	{
		return $this->getPage()->localUrl;
	}



	public function setLocalUrl($url)
	{
		$this->getPage()->localUrl = $url;
	}



	public function getParams()
	{
		return $this->getPage()->params;
	}



	public function setParams($params)
	{
		$this->getPage()->params = $params;
	}



	public function getTitle()
	{
		return $this->getPage()->title;
	}



	public function setTitle($title)
	{
		$this->getPage()->title = $title;
	}



	public function getDescription()
	{
		return $this->getPage()->description;
	}



	public function setDescription($description)
	{
		$this->getPage()->description = $description;
	}



	public function getKeywords()
	{
		return $this->getPage()->keywords;
	}



	public function setKeywords($keywords)
	{
		$this->getPage()->keywords = $keywords;
	}



	public function getType()
	{
		return $this->getPage()->type;
	}



	public function setType($type)
	{
		$this->getPage()->type = $type;
	}



	public function getRobots()
	{
		return $this->getPage()->robots;
	}



	public function setRobots($robots)
	{
		$this->getPage()->robots = $robots;
	}



	public function getParent()
	{
		return $this->getPage()->parent;
	}



	public function setParent($parent)
	{
		$this->getPage()->parent = $parent;
	}



	public function getLanguages()
	{
		return $this->getPage()->languages;
	}



	public function setLanguages($languages)
	{
		$this->getPage()->languages = $languages;
	}



	public function getTranslationFor()
	{
		return $this->getPage()->translationFor;
	}



	public function setTranslationFor($translationFor)
	{
		$this->getPage()->translationFor = $translationFor;
	}



	public function getTranslations()
	{
		return $this->getPage()->translations;
	}



	public function setTranslations($translations)
	{
		$this->getPage()->translations = $translations;
	}



	public function getLayout()
	{
		return $this->getPage()->layout;
	}



	public function setLayout($layout)
	{
		$this->getPage()->layout = $layout;
	}



	/**
	 * @param $layoutFile
	 */
	public function setLayoutFile($layoutFile)
	{
		$this->getPage()->layoutFile = $layoutFile;
	}



	/**
	 * @return mixed
	 */
	public function getLayoutFile()
	{
		return $this->getPage()->layoutFile;
	}

}

