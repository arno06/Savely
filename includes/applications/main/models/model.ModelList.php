<?php

namespace app\main\models
{

    use core\application\BaseModel;
    use core\db\Query;
    use core\utils\SimpleRandom;

    class ModelList extends BaseModel
    {
        const DEFAULT_NAME = "List";

        public function __construct()
        {
            parent::__construct('sil_lists', 'id_list');
        }

        public function create($pIdUser)
        {
            $perma = SimpleRandom::string(8);

            while($this->count(Query::condition()->andWhere('permalink_list', Query::EQUAL, $perma)))
            {
                $perma = SimpleRandom::string(8);
            }

            $name = self::DEFAULT_NAME;

            $current_count = $this->count(Query::condition()->andWhere('name_list', Query::LIKE, self::DEFAULT_NAME.'%')->andWhere('id_user', Query::EQUAL, $pIdUser));

            if($current_count > 0)
            {
                $name .= " (".($current_count+1).")";
            }

            $this->insert(array('name_list'=>$name, 'permalink_list'=>$perma, 'public_list'=>0, 'share_list'=>1, 'id_user'=>$pIdUser, 'creation_date_list'=>'NOW()'));

            return $perma;
        }

        public function getList($pPermalink)
        {
            $data = $this->one(Query::condition()->andWhere('permalink_list', Query::EQUAL, $pPermalink));

            if(!$data)
                return null;

            return $data;
        }


        public function retrieveByUser($pIdUser, $pCount = 10)
        {
            return $this->all(Query::condition()->andWhere('id_user', Query::EQUAL, $pIdUser)->order('name_list')->limit(0, $pCount));
        }
    }
}