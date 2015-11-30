<?php
namespace app\main\controllers\front
{

    use app\main\models\ModelList;
    use app\main\src\BaseFrontController;
    use core\application\authentication\AuthenticationHandler;
    use core\application\Configuration;
    use core\application\Core;
    use core\application\Go;
    use core\application\Header;

    class l extends BaseFrontController
	{
		public function __construct()
		{
			parent::__construct();
		}

        public function create()
        {
            $m = new ModelList();

            $perma = $m->create(AuthenticationHandler::$data['id_user']);

            Header::location(Configuration::$server_url.'list/'.$perma.'/edit');
        }

        public function view()
        {
            if(!Core::checkRequiredGetVars('permalink_list'))
            {
                Go::to404();
            }

            $m = new ModelList();

            $list = $m->getList($_GET['permalink_list']);


            if(!$list)
                Go::to404();

            if(Core::checkRequiredGetVars('edit') && $_GET["edit"] === true && AuthenticationHandler::$data['id_user'] == $list['id_user'])
            {
                $this->setTemplate('l', 'edit');
            }

            $this->setTitle($list['name_list'].' - Savely.co');

            $this->addContent('data', $list);
        }

	}
}