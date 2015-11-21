<?php
namespace app\main\controllers\front
{
	use app\main\src\BaseFrontController;
	use app\main\models\ModelLink;
    use core\application\authentication\AuthenticationHandler;
    use core\tools\form\Form;
	use core\application\Core;
	use core\application\Go;
	use core\data\SimpleJSON;

	class a extends BaseFrontController
	{

		private $model_link;

		public function __construct()
		{
			parent::__construct();
			$this->model_link = new ModelLink();
		}

		public function index()
		{
			$this->addScript('Dabox');
			$this->addScript('Request');
			$this->addScript('M4Tween');
			$this->addScript('StageChart');
			$this->setTitle('feed - Savely.co');

			$f = new Form('addEntry');
			if($f->isValid())
			{

				$values = $f->getValues();
				$url = $values['url'];
				$this->model_link->addState($url, AuthenticationHandler::$data['id_user']);
			}
			$this->addContent('user_links', $this->model_link->retrieveLinksByUser(AuthenticationHandler::$data['id_user']));
		}

		public function remove_link()
		{
			if(!Core::checkRequiredGetVars('id'))
				Go::to404();
			$this->model_link->removeUserLink($_GET['id'], AuthenticationHandler::$data['id_user']);
			Core::performResponse('ok');
		}

		public function retrieve_states()
		{
			if(!Core::checkRequiredGetVars('id_link'))
				Core::performResponse('Missing argument');

			$m = new ModelLink();

			$s = $m->getStatesByLink($_GET['id_link']);

			Core::performResponse(SimpleJSON::encode($s), 'json');
		}

		public function update()
		{
			$links = $this->model_link->retrieveLinksByUser(AuthenticationHandler::$data['id_user']);
			foreach($links as $l)
			{
				$this->model_link->updateLink($l['url_link']);
			}
			Go::to('a');
		}
	}
}