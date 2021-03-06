<?php

/**
 * My Application
 *
 * @copyright  Copyright (c) 2010 John Doe
 * @package	MyApplication
 */

namespace CoreModule\AdminModule;

use Nette\Application\UI, Nette\Security;

/**
 * Sign in/out presenters.
 *
 * @author	 John Doe
 * @package	MyApplication
 */
class LoginPresenter extends \CoreModule\Presenters\AdminPresenter {


	/** @persistent */
	public $backlink;



	public function startup()
	{
		parent::startup();
		if (!$this->context->createCheckConnection()) {
			$this->flashMessage("Only administrator can be logged", "warning");
		}
	}



	/**
	 * Sign in form component factory.
	 *
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm($name)
	{
		$form = new \CoreModule\Forms\LoginForm;
		return $form;
	}



	public function beforeRender()
	{
		parent::beforeRender();
		$this->template->hideMenuItems = true;
	}

}
