<?php
namespace app\main\controllers\front
{
    use core\application\FrontController;
    use app\main\models\ModelLink;
    use core\application\authentification\AuthentificationHandler;
    use core\application\Go;
    use core\application\Core;
    use core\application\Dictionary;
    use core\tools\form\Form;
    use core\tools\PsartekMailer;
    use core\data\SimpleJSON;

    class index extends FrontController
    {
        private $model_link;

        public function __construct()
        {

            $this->model_link = new ModelLink();

            $this->addScript('ShopLater');
            $f = new Form('search');
            $this->addForm('search', $f);

            $f = new Form('addEntry');
            $this->addForm('addUrl', $f);
        }

        public function parser()
        {
            $f = new Form('addURL');

            if($f->isValid())
            {
                $v = $f->getValues();
                $url = $v['url'];

                $m = new ModelLink();

                $result = $m->parseLink($url);

                $this->addContent('parsing', print_r($result, true));
            }

            $this->addForm('add', $f);
        }

        public function signout()
        {
            AuthentificationHandler::unsetUserSession();
            Go::toFront();
        }

        public function index()
        {
            if(!empty(AuthentificationHandler::$data))
                Go::toFront('a');

            $f = new Form('search');
            $this->addForm('search', $f);

            $this->setTitle('Savely.co');

            $this->addScript('Dabox');
            $this->addScript('Request');
            $this->addScript('StageChart');
            $form = new Form('login');
            if($form->isValid())
            {
                $values = $form->getValues();
                if(AuthentificationHandler::setUserSession($values['login'], $values['password']))
                {
                    Go::toFront('a');
                }
                else
                    $this->addContent('login_error', Dictionary::term('login.error.unknown_user'));
            }

            $m = new ModelLink();

            $all = $m->retrieveLinksHome();

            $this->addContent('products', $all);

            $this->addForm('login', $form);
        }

        public function details()
        {
            if (!Core::checkRequiredGetVars('id', 'tab'))
                Go::to404();

            if(!Core::checkRequiredGetVars('no-async'))
                Core::deactivateDebug();
            $details = $this->model_link->details($_GET['id']);

            $s = array_reverse($this->model_link->getStatesByLink($_GET['id']));
            $this->addContent('states', str_replace('"', "'", SimpleJSON::encode($s)));
            $this->addContent('details', $details);
            $this->addContent('tab', $_GET['tab']);
        }


        public function states()
        {
            $m = new ModelLink();
            $all = $m->all();
            foreach($all as $l)
            {
                $m->addState($l['canonical_link']);
            }

            $m->updatePriceLinks();


            $p = new PsartekMailer();
            $p->setFrom('Savely.co', 'no-reply@savely.co');
            $p->addAdress('Arnaud NICOLAS', 'arno06@gmail.com');
            $p->setSubject('Savely.co - recup\' des prix ;-)');
            $p->setTemplate('mails', 'states');
			Core::performResponse('ok');
        }

        public function plz_update()
        {
            $m = new ModelLink();
            $m->updatePriceLinks();
            Core::performResponse('bouboup');
        }
    }
}