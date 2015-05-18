<?php
namespace operate\models;
use Yii;
use backend\models\Logistic as Lg;

class Logistic extends lg
{
	const PER_PAGE = 26;
	/**
	 * [getLogisticData description]
	 * @return [type] [description]
	 */
	public static function getLogisticData($params){
		$query = static::getLogisticQuery($params);
		$count = $query->select('logistic_no')
				    	->distinct()
				        ->count();
	    $pages = new \yii\data\Pagination(['totalCount' => $count]);
	    $ret = [];
	    $query->select('logistic_no')
				    	->distinct()
				    	->offset($pages->offset)
				        ->limit(10)
				        ->orderby(['create_time'=>SORT_DESC]);

		$data = $query->all();
		$logistic_no_ids = array_map(function($a){return $a['logistic_no'];}, $data);
		$ret = [];
		$results = Logistic::find()
	    			->with(['orders','tracking','orderSend'])
	    			->where(['logistic_no'=>$logistic_no_ids])
	    			->all();
	    foreach($results as $result){
	    	$ret[$result->logistic_no]['orderInfo'][] = $result; 
	    }
	    return [$ret,$pages,$count];
	}
	/**
	 * get need print data
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public static function getPrintData($params){
		$count = static::getNeedPrintCount($params);
		if(!isset($params['logistic_provider']) || empty($params['logistic_provider'])){
			return [];
		}
		if($count != 0){
			$page = round($count / self::PER_PAGE);
			$query = static::getLogisticQuery($params);
			$rets = [];
			for($i=1;$i<=$page;$i++){
				$query->select('logistic_no')
				    	->distinct()
				    	->offset(($i - 1) * self::PER_PAGE)
				        ->limit(self::PER_PAGE)
				        ->orderby(['create_time'=>SORT_DESC]);
				$data = $query->all();
				$logistic_no_ids = array_map(function($a){return $a['logistic_no'];}, $data);
				$results = Logistic::find()
			    			->with(['orders','tracking','orderSend'])
			    			->where(['logistic_no'=>$logistic_no_ids])
			    			->all();
			    foreach($results as $result){
			    	$rets[$i][$result->logistic_no]['orderInfo'][] = $result; 
			    }
			}
			return $rets;
		}
		return [];
	}
	/**
	 * [getNeedPrintCount description]
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public static function getNeedPrintCount($params){
		$count = 0;
		if(isset($params['begin_time']) && !empty($params['begin_time'])){
			if(isset($params['end_time']) && !empty($params['end_time'])){
				$query = static::getLogisticQuery($params);
				$count = $query->select('logistic_no')
				    	->distinct()
				        ->count();
			}
		}
		return $count;
	}
	/**
	 * get logistic list basic query 
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public static function getLogisticQuery($params){
		$query = Logistic::find();
		
		if(isset($params['order_id']) && !empty($params['order_id'])){
			$result = Logistic::find()->where(['order_id'=>trim($params['order_id'])])->one();
			if(!empty($result)){
				$logistic_no = $result->logistic_no;
				$query->andWhere(['logistic_no'=>$logistic_no]);
			}else{
				$query->andWhere(['order_id'=>trim($params['order_id'])]);
			}
		}
		if(isset($params['logistic_no']) && !empty($params['logistic_no'])){
			$query->andWhere(['logistic_no'=>trim($params['logistic_no'])]);
		}
		if(isset($params['logistic_provider']) && !empty($params['logistic_provider'])){
			$query->andWhere(['logistic_provider'=>$params['logistic_provider']]);
		}
		if(isset($params['begin_time']) && !empty($params['begin_time'])){
			if(isset($params['end_time']) && !empty($params['end_time'])){
				$query->andWhere('create_time >=:begin_time AND create_time <= :end_time',[':begin_time'=>strtotime($params['begin_time']),":end_time"=>strtotime($params['end_time'])]);
			}
			
		}
		return $query;
	}
	/**
	 * two date is the same day
	 * @param  [type]  $begin_time [description]
	 * @param  [type]  $end_time   [description]
	 * @return boolean             [description]
	 */
	public static function isDiffDays($begin_time,$end_time){
		$last_date = getdate(strtotime($begin_time));
		$this_date = getdate(strtotime($end_time));
	    if(($last_date['year']===$this_date['year'])&&($this_date['yday']===$last_date['yday'])){
	        return false;
	    }else{
	        return true;
	    }
	}
}