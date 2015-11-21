<?php
namespace app\main\src
{
	use core\application\FrontController;
	use core\application\authentification\AuthentificationHandler;
	use core\application\Go;
	use core\tools\form\Form;

	class BaseFrontController extends FrontController
	{
		function __construct()
		{

			if(!AuthentificationHandler::$data)
			{
				Go::toFront();
			}

			$this->addScript('ShopLater');
			$f = new Form('search');
			$this->addForm('search', $f);

			$f = new Form('addEntry');
			$this->addForm('addUrl', $f);

		}

	}
}