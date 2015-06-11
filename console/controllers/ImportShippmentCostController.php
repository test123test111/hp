<?php
namespace console\controllers;
use Yii;
use yii\console\Controller;
use common\models\ShippmentCost;
class ImportShippmentCostController extends Controller{

	public function actionRun(){
		$file_name = '/tmp/cost_templete.csv';
        $results = $this->readfile($file_name);
        foreach($results as $result){
            $storeroom_id = trim($result[0]);
            $transport_type = trim($result[1]);
            $to_city = trim($result[3]);
            $city_level = trim($result[4]);
            $fob_weight = 10;
            $fob_price = trim($result[6]);
            if($fob_price == null){
            	$fob_price = '0.00';
            }
            $increase_weight = 1;
            $increase_price = trim($result[8]);
            if($increase_price == null){
            	$increase_price = '0.00';
            }
            if($to_city != null){
            	$model = new ShippmentCost;
	            $model->storeroom_id = $storeroom_id;
	            $model->transport_type = $transport_type;
	            $model->to_city = $to_city;
	            $model->city_level = $city_level;
	            $model->fob_weight = $fob_weight;
	            $model->fob_price = $fob_price;
	            $model->increase_weight = $increase_weight;
	            $model->increase_price = $increase_price;
	            if($model->save()){
	            	echo $storeroom_id." to ".$result[2]." ".$to_city." 导入成功";echo "\r\n";
	            } 
            }
            
            
        }
	}
    public function actionRunplatform(){
        $file_name = '/tmp/toplatform.csv';
        $results = $this->readfile($file_name);
        foreach($results as $result){
            $storeroom_id = trim($result[0]);
            $transport_type = trim($result[1]);
            $to_city = trim($result[3]);
            $city_level = trim($result[4]);
            $fob_weight = 10;
            $fob_price = trim($result[6]);
            if($fob_price == null){
                $fob_price = '0.00';
            }
            $increase_weight = 1;
            $increase_price = trim($result[8]);
            $model = new ShippmentCost;
            $model->storeroom_id = $storeroom_id;
            $model->transport_type = $transport_type;
            $model->to_city = $to_city;
            $model->city_level = $city_level;
            $model->fob_weight = $fob_weight;
            $model->fob_price = $fob_price;
            $model->increase_weight = $increase_weight;
            $model->increase_price = $increase_price;
            $model->to_type = 1;
            if($model->save()){
                echo $storeroom_id." to ".$result[2]." ".$to_city." 导入成功";echo "\r\n";
            } 
            
            
        }
    }
	/**
     * read csv file
     * @param  [type] $file_name [description]
     * @param  [type] $line      [description]
     * @return [type]            [description]
     */
    public function readfile($file_name){
        $file = fopen($file_name,'r'); 
        while ($data = fgetcsv($file)) { 
            $results[] = $data;
        }
        fclose($file);
        return $results;
    }
}