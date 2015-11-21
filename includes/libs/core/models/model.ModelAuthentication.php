<?php
namespace core\models
{
    use core\application\BaseModel;
    use core\application\Configuration;
    use core\application\Core;
    use core\db\Query;

    /**
     * Model de gestion des authentifications
     *
     * @author Arnaud NICOLAS <arno06@gmail.com>
     * @version .1
     * @package models
     */
    class ModelAuthentication extends BaseModel
    {
        static private $instance;

        static public $data;

        public function __construct()
        {
            parent::__construct(sprintf(Configuration::$authentication_tableName, Core::$application), Configuration::$authentication_tableId);
        }

        static public function isUser($pLogin, $pMdp, $pHash = false)
        {
            if(empty($pLogin)||empty($pMdp))
                return false;

            /** @var BaseModel $instance */
            $instance = self::getInstance();

            if($result = $instance->one(Query::condition()->andWhere(Configuration::$authentication_fieldLogin, Query::EQUAL, $pLogin)))
            {
                $salt = $result[Configuration::$authentication_fieldSalt];
                $value = $pMdp;
                if(!$pHash)
                    $value = self::computePasswordHash($pMdp, $salt);
                if($result[configuration::$authentication_fieldPassword] == $value)
                {
                    self::$data = $result;
                    return true;
                }
            }
            return false;
        }

        static private function computeSalt()
        {
            return openssl_random_pseudo_bytes(16);
        }

        static private function computePasswordHash($pPassword, $pSalt)
        {
            return sha1(crypt($pPassword, $pSalt));
        }

        public function changePassword($pCurrentPassword, $pNewPassword)
        {
            if($this->getPasswordHash() !== self::computePasswordHash($pCurrentPassword, self::$data[Configuration::$authentication_fieldSalt]))
                return false;

            $newSalt = self::computeSalt();

            $newPasswordHash = self::computePasswordHash($pNewPassword, $newSalt);

            $this->updateById(self::$data[$this->id], array(
                Configuration::$authentication_fieldSalt=>$newSalt,
                Configuration::$authentication_fieldPassword=>$newPasswordHash
            ));

            return true;
        }

        public function getId()
        {
            return self::$data[$this->id];
        }

        public function getPasswordHash()
        {
            return self::$data[Configuration::$authentication_fieldPassword];
        }

        public function getLogin()
        {
            return self::$data[Configuration::$authentication_fieldLogin];
        }

        /**
         * @return ModelAuthentication
         */
        static public function getInstance()
        {
            if(!self::$instance)
                self::$instance = new ModelAuthentication();
            return self::$instance;
        }
    }
}