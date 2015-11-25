<?php
namespace app\main\src
{

    use core\application\authentication\AuthenticationHandler;
    use core\application\DefaultController;
	use core\application\Go;
	use core\tools\form\Form;

	class BaseFrontController extends DefaultController
	{
		function __construct()
		{
            AuthenticationHandler::getInstance();
			if(!AuthenticationHandler::$data)
			{
                Go::to();
			}

			$this->addScript('ShopLater');
			$f = new Form('search');
			$this->addForm('search', $f);

			$f = new Form('addEntry');
			$this->addForm('addUrl', $f);
		}

        public function not_found()
        {
            $this->setTemplate(null, null, 'template.404.tpl');
        }


    }
}