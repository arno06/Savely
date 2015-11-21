<?php
namespace app\main\controllers\front
{
	use app\main\src\BaseFrontController;
	use core\tools\form\Form;
	use core\models\ModelAuthentification;
	use core\application\authentification\AuthentificationHandler;

	class s extends BaseFrontController
	{
		public function __construct()
		{
			parent::__construct();
			$this->setTitle('Settings - Savely.co');
		}

		public function index()
		{

		}

		public function account()
		{
			$this->setTitle('Edit your account - Savely.co');
			$f = new Form('account');

			if($f->isValid())
			{
				$v = $f->getValues();

				$m = ModelAuthentification::getInstance();

				$m->updateById($m->getId(), $v);
			}

			$f->injectValues(AuthentificationHandler::$data);

			$this->addForm('account',$f);
		}

		public function password()
		{
			$this->setTitle('Change your password - Savely.co');

			$f = new Form('password');

			if($f->isValid())
			{
				$v = $f->getValues();
				if(ModelAuthentification::getInstance()->changePassword($v['currentPassword'], $v['newPassword']))
				{
					AuthentificationHandler::unsetUserSession();
					AuthentificationHandler::setUserSession(ModelAuthentification::getInstance()->getLogin(), $v['newPassword']);
					$this->addContent('confirmation', 'New Password Saved');
				}
				else
					$this->addContent('error', 'You current password does not match');
			}
			else
				$this->addContent('error', $f->getError());

			$this->addForm('password', $f);
		}

		public function notifications()
		{

		}

		public function connected_apps()
		{

		}

		public function privacy()
		{

		}
	}
}