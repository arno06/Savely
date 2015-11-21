<?php
namespace core\models
{
    use core\application\BaseModel;
    use core\application\Configuration;
    use core\db\Query;

    /**
     * Model de gestion des authentifications
     *
     * @author Arnaud NICOLAS <arno06@gmail.com>
     * @version .1
     * @package models
     */
    class ModelAuthentification extends BaseModel
    {
        static private $instance;

        static public $data;

        public function __construct()
        {
            $this->table = sprintf(Configuration::$authentification_tableName,Configuration::$site_application);
            $this->id = Configuration::$authentification_tableId;
        }

        static public function isUser($pLogin, $pMdp, $pHash = false)
        {
            if(empty($pLogin)||empty($pMdp))
                return false;

            $instance = self::getInstance();

            if($result = $instance->one(Query::condition()->andWhere(Configuration::$authentification_fieldLogin, Query::EQUAL, $pLogin)))
            {
                $salt = $result[Configuration::$authentification_fieldSalt];
                $value = $pMdp;
                if(!$pHash)
                    $value = self::computePasswordHash($pMdp, $salt);
                if($result[configuration::$authentification_fieldPassword] == $value)
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
            if($this->getPasswordHash() !== self::computePasswordHash($pCurrentPassword, self::$data[Configuration::$authentification_fieldSalt]))
                return false;

            $newSalt = self::computeSalt();

            $newPasswordHash = self::computePasswordHash($pNewPassword, $newSalt);

            $this->updateById(self::$data[$this->id], array(
                Configuration::$authentification_fieldSalt=>$newSalt,
                Configuration::$authentification_fieldPassword=>$newPasswordHash
            ));

            return true;
        }

        public function getId()
        {
            return self::$data[$this->id];
        }

        public function getPasswordHash()
        {
            return self::$data[Configuration::$authentification_fieldPassword];
        }

        public function getLogin()
        {
            return self::$data[Configuration::$authentification_fieldLogin];
        }

        /**
         * @return ModelAuthentification
         */
        static public function getInstance()
        {
            if(!self::$instance)
                self::$instance = new ModelAuthentification();
            return self::$instance;
        }
    }
}