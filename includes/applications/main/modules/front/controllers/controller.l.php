<?php
namespace app\main\controllers\front
{

    use app\main\models\ModelList;
    use app\main\src\BaseFrontController;
    use core\application\authentication\AuthenticationHandler;
    use core\application\Autoload;
    use core\application\Configuration;
    use core\application\Core;
    use core\application\Go;
    use core\application\Header;
    use core\data\SimpleJSON;
    use core\db\Query;

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
                Autoload::addScript('Savely.ListEdit');
                $this->setTemplate('l', 'edit');
            }

            $this->setTitle($list['name_list'].' - Savely.co');

            $this->addContent('data', $list);
        }

        public function update()
        {
            if(!Core::checkRequiredGetVars('permalink_list', 'prop_list') || !isset($_POST) || empty($_POST) || !isset($_POST['value']) || empty($_POST['value']))
            {
                Go::to404();
            }

            $m = new ModelList();

            $list = $m->one(Query::condition()->andWhere('permalink_list', Query::EQUAL, $_GET['permalink_list']));

            if(!$list)
                Go::to404();

            $name = $_GET['prop_list'];

            if($m->updateById($list['id_list'], array($name=>$_POST['value'])))
            {
                $response = array("message"=>"ok");
            }
            else
            {
                $response = array("error", "Unable to perform an update on field '".$name."'");
            }

            $response = SimpleJSON::encode($response);

            Core::performResponse($response, 'json');
        }

	}
}