<?php
namespace app\main\models
{
	use core\application\BaseModel;
	use core\db\Query;

	class ModelLink extends BaseModel
	{
		public function __construct()
		{
			parent::__construct("sil_links", "id_link");
		}

		public function retrieveLinksHome()
		{
			$d = Query::select('*', $this->table)
				->andWhere('last_price_link', Query::LOWER, 'weekly_price_link', false)
				->order('(weekly_price_link-last_price_link)', 'DESC')
				->limit(0, 15)
				->execute($this->handler);
			foreach($d as &$l)
			{
				$this->extractDomainLink($l);
			}
			return $d;
		}

		public function details($pIdLink)
		{
			$l = $this->getTupleById($pIdLink, '*');
			$stats = Query::select('ROUND(MIN(price_state), 2) as min_price, ROUND(MAX(price_state), 2) as max_price, ROUND(AVG(price_state), 2) as average_price, DATEDIFF(NOW(), min(date_state)) as since', 'sil_states')
					->andWhere('id_link', Query::EQUAL, $pIdLink)
					->andWhere('price_state', Query::UPPER, '0')
					->groupBy('id_link')
					->execute();
			$stats = $stats[0];
			$l['max_price'] = $stats['max_price'];
			$l['min_price'] = $stats['min_price'];
			$l['average_price'] = $stats['average_price'];
			$l['since'] = $stats['since'];
			$this->extractDomainLink($l);
			return $l;
		}

		public function getStatesByLink($pIdLink)
		{
			$d = Query::select('DATE_FORMAT( date_state,  \'%d/%c\' ) as xLabel, MAX(price_state) as value', 'sil_states')
				->andWhere('id_link', Query::EQUAL, $pIdLink)
				->andWhere('price_state', Query::UPPER, '0')
				->andWhere('date_state', ' BETWEEN ', 'DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()', false)
				->order('date_state', 'DESC')
				->groupBy('DATE_FORMAT( date_state,  \'%d/%c\' )')
				->limit(0, 7)
				->execute();
			array_shift($d);
			$day = Query::select('DATE_FORMAT( date_state,  \'%d/%c\' ) as xLabel, price_state as value', 'sil_states')
				->andWhere('id_link', Query::EQUAL, $pIdLink)
				->andWhere('price_state', Query::UPPER, '0')
				->order('date_state', 'DESC')
				->limit(0, 1)
				->execute();
			array_unshift($d, $day[0]);
			return $d;
		}

		public function removeUserLink($pIdLink, $pIdUser)
		{
			return Query::delete()->from('sil_user_links')->andWhere('id_link', Query::EQUAL, $pIdLink)->andWhere('id_user', Query::EQUAL, $pIdUser)->execute($this->handler);
		}

		public function retrieveLinksByUser($pIdUser)
		{
			$d = Query::select('*', 'sil_user_links')->join('sil_links')->andWhere('id_user', Query::EQUAL, $pIdUser)->order('date_added_link', 'DESC')->execute($this->handler);
			foreach($d as &$l)
			{
				$this->extractDomainLink($l);
			}
			return $d;
		}

		public function parseLink($pUrl)
		{
			$parsers = array(
				"AmazonFrParser"=>'/^http:\/\/www\.amazon\.fr/',
				"LaRedouteFrParser"=>'/^http:\/\/www\.laredoute\.fr/',
				"AsosFrParser"=>'/^http:\/\/www\.asos\.fr/'
			);

			$class = null;
			foreach($parsers as $className=>$regExp)
			{
				if(!preg_match($regExp, $pUrl, $matches))
					continue;
				$class = "app\\main\\src\\data\\".$className;
			}
			if($class === null)
			{
				//Unknown shop
				return false;
			}

			$user_headers = array(
				'User-Agent: '.$_SERVER['HTTP_USER_AGENT'],
				'Accept: '.$_SERVER['HTTP_ACCEPT'],
				'Accept-Encoding: '.$_SERVER['HTTP_ACCEPT_ENCODING'],
				'Accept-Language: '.$_SERVER['HTTP_ACCEPT_LANGUAGE']
			);

			$context = stream_context_create(array(
				'http' => array(
					'ignore_errors'=>true,
					'method'=>'GET',
					'header'=>implode('\r\n', $user_headers)
				)
			));
			$content = file_get_contents($pUrl, false, $context);

			if(empty($content))
			{
				trace("nop empty response");
				return false;
			}

			$parsedData = $class::parse($content);
			if(empty($parsedData['canonical']))
				$parsedData['canonical'] = $pUrl;
			if (strpos($parsedData['canonical'], 'http://') !== 0)
			{
				if(!preg_match('/^\//', $parsedData['canonical'], $matches))
					$parsedData['canonical'] = '/'.$parsedData['canonical'];

				$domain = explode('/', $pUrl);
				$parsedData['canonical'] = 'http://'.$domain[2].$parsedData['canonical'];

			}
			return $parsedData;
		}

		public function updateLink($pUrl)
		{
			$content = $this->parseLink($pUrl);

			if($content===false)
				return;
			$existing_link = $this->one(Query::condition()->andWhere('url_link', Query::EQUAL, $pUrl)->orWhere('canonical_link', Query::EQUAL, $content['canonical']));
			if(!$existing_link)
				return;

			$this->updateById($existing_link['id_link'], array(
				'canonical_link'=>$content['canonical'],
				'url_link'=>$pUrl,
				'title_link'=>$content['title'],
				'image_link'=>$content['image']
			));

		}

		public function addState($pUrl, $pIdUser = null)
		{
			$content = $this->parseLink($pUrl);

			if($content===false || (is_array($content)&&empty($content['title'])&&empty($content['price'])))
			{
				return false;
			}

			$existing_link = $this->one(Query::condition()->andWhere('url_link', Query::EQUAL, $pUrl)->orWhere('canonical_link', Query::EQUAL, $content['canonical']));

			if(!$existing_link)
				Query::insert(array('url_link'=>$pUrl, 'canonical_link'=>$content['canonical'], 'title_link'=>$content['title'], 'image_link'=>$content['image']))->into($this->table)->execute($this->handler);
			else
				Query::update('sil_links')->values(array('url_link'=>$pUrl, 'canonical_link'=>$content['canonical'], 'title_link'=>$content['title'], 'image_link'=>$content['image']))->andWhere('id_link', Query::EQUAL, $existing_link['id_link'])->execute();
			$existing_link = $this->one(Query::condition()->andWhere('url_link', Query::EQUAL, $pUrl)->orWhere('canonical_link', Query::EQUAL, $content['canonical']));

			if(isset($pIdUser))
			{
				if(!Query::count('sil_user_links', Query::condition()->andWhere('id_user', Query::EQUAL, $pIdUser)->andWhere('id_link', Query::EQUAL, $existing_link['id_link'])))
					Query::insert(array('id_user'=>$pIdUser, 'id_link'=>$existing_link['id_link']), 'sil_user_links')->into('sil_user_links')->execute();
			}


			Query::insert(array('id_link'=>$existing_link['id_link'], 'price_state'=>$content['price']['price'], 'devise_state'=>$content['price']['devise']))->into('sil_states')->execute($this->handler);
			Query::update($this->table)->values(array('last_price_link'=>$content['price']['price'], 'devise_link'=>$content['price']['devise']))
				->andWhere('id_link', Query::EQUAL, $existing_link['id_link'])
				->execute();
			return true;
		}

		private function decorateLink(&$pData)
		{

			$s = Query::select('*', 'sil_states')->andWhere('id_link', Query::EQUAL, $pData['id_link'])->andWhere('price_state', Query::UPPER, '0')->order('date_state','DESC')->limit('0', '1')->execute($this->handler);
			$pData['last_state'] = $s[0];
			$s = Query::execute("SELECT MIN(ROUND(price_state, 2)) as previous_price FROM sil_states WHERE id_link = '".$pData['id_link']."' AND ROUND(price_state, 2) > '".$pData['last_state']['price_state']."' AND price_state > 0 AND date_state BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW() ORDER BY date_state;", $this->handler);
			$pData['previous_price'] = null;
			if(!empty($s))
			{
				$pData['previous_price'] = round($s[0]['previous_price'], 2);
				if ($pData['previous_price']==$pData['last_state']['price_state'])
					$pData['previous_price'] = null;
			}
		}

		private function extractDomainLink(&$pLink)
		{
			$url = explode('/', $pLink['canonical_link']);
			$pLink['domain_link'] = preg_replace('/www\./', '', $url[2]);
		}

		public function updatePriceLinks()
		{
			$links = $this->all();

			foreach($links as &$l)
			{
				$this->decorateLink($l);

				Query::update($this->table)
					->values(array('weekly_price_link'=>$l['previous_price']))
					->andWhere('id_link', Query::EQUAL, $l['id_link'])
					->execute($this->handler);
			}
		}
	}
}