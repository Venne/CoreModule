<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CoreModule\AdminModule\WebsiteModule;

use \Nette\Application\UI\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 *
 * @secured
 */
class LanguagePresenter extends BasePresenter
{


	/** @persistent */
	public $id;

	/** @inject websiteForm */
	public $websiteForm;



	public function startup()
	{
		parent::startup();
		$this->addPath("Language", $this->link(":Core:Admin:Website:Language:"));
	}



	public function createComponentForm($name)
	{
		$repository = $this->context->core->languageRepository;
		$entity = $repository->createNew();
		$em = $this->context->entityManager;
		$config = $this->context->configManager;

		$form = $this->context->core->createLanguageForm();
		$form->setEntity($entity);
		$form->addSubmit("_submit", "Save");
		$form->onSuccess[] = function($form) use ($repository, $config)
		{
			try {
				$repository->save($form->entity);
				$form->getPresenter()->flashMessage("Language has been created", "success");
			} catch (\Venne\Doctrine\ORM\SqlException $e) {
				if ($e->getCode() == 23000) {
					$form->presenter->flashMessage("Language is not unique", "warning");
					return;
				} else {
					throw $e;
				}
			}

			$languages = array();
			foreach ($repository->findAll() as $entity) {
				$languages[] = $entity->alias;
			}
			$config["parameters"]["website"]["languages"] = $languages;
			$config->save();
			$form->getPresenter()->redirect("default");
		};
		return $form;
	}



	public function createComponentFormEdit($name)
	{
		$repository = $this->context->core->languageRepository;
		$entity = $repository->find($this->id);
		$em = $this->context->entityManager;
		$config = $this->context->configManager;

		$form = $this->context->core->createLanguageForm();
		$form->setEntity($entity);
		$form->addSubmit("_submit", "Save");
		$form->onSuccess[] = function($form) use ($repository, $config)
		{
			try {
				$repository->save($form->entity);
				$form->getPresenter()->flashMessage("Language has been updated", "success");
			} catch (\Venne\Doctrine\ORM\SqlException $e) {
				if ($e->getCode() == 23000) {
					$form->presenter->flashMessage("Language is not unique", "warning");
					return;
				} else {
					throw $e;
				}
			}

			$languages = array();
			foreach ($repository->findAll() as $entity) {
				$languages[] = $entity->alias;
			}
			$config["parameters"]["website"]["languages"] = $languages;
			if ($form->entity->id == 1) {
				$config["parameters"]["website"]["defaultLanguage"] = $form->entity->alias;
			}
			$config->save();
			$form->getPresenter()->redirect("this");
		};
		return $form;
	}



	public function handleDelete($id)
	{
		$repository = $this->context->core->languageRepository;
		$repository->delete($repository->find($this->id));

		$config = $this->context->configManager;
		$languages = array();
		foreach ($repository->findAll() as $entity) {
			$languages[] = $entity->alias;
		}
		$config["parameters"]["website"]["languages"] = $languages;
		$config->save();

		$this->flashMessage("Language has been deleted", "success");
		$this->redirect("this", array("id" => NULL));
	}



	public function renderDefault()
	{
		$this->template->table = $this->context->core->languageRepository->findAll();
	}

}
