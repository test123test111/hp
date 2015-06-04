<?php
namespace console\controllers;
use Yii;
use yii\console\Controller;
use common\models\HpCity;
class ImportHpCityController extends Controller{

    /**
     * action for import alipay info to table
     * @return [type] [description]
     */
    public function actionCity(){
        $file_name = '/tmp/city.csv';
        $results = $this->readfile($file_name);
        foreach($results as $result){
            $province = trim($result[0]);
            $city = trim($result[1]);
            $subcity = trim($result[2]);
            $level = trim($result[3]);
            $model = new HpCity;
            $cityData = HpCity::find()->where(['name'=>$city])->andWhere('pid != 0')->one();
            if(empty($cityData)){
            	$provinceData = HpCity::find()->where(['name'=>$province])->one();
            	$model->pid = $provinceData->id;
            	$model->name = $city;
            	if($model->save()){
            		echo $city." 已经导入数据库";echo "\r\n";
            	}
            }
            
        }
    }
    /**
     * action for import alipay info to table
     * @return [type] [description]
     */
    public function actionProvince(){
        $file_name = '/tmp/city.csv';
        $results = $this->readfile($file_name);
        foreach($results as $result){
            $province = trim($result[0]);
            $city = trim($result[1]);
            $subcity = trim($result[2]);
            $level = trim($result[3]);
            $model = new HpCity;
            $provinceData = HpCity::find()->where(['name'=>$province])->one();
            if(empty($provinceData)){
            	$model->name = $province;
            	if($model->save()){
            		echo $province." 已经导入数据库";echo "\r\n";
            	}
            }
            
        }
    }
    /**
     * action for import alipay info to table
     * @return [type] [description]
     */
    public function actionDistrict(){
        $file_name = '/tmp/city.csv';
        $results = $this->readfile($file_name);
        foreach($results as $result){
            $province = trim($result[0]);
            $city = trim($result[1]);
            $subcity = trim($result[2]);
            $level = trim($result[3]);
            $model = new HpCity;
            $districtData = HpCity::find()->where(['name'=>$subcity])->andWhere('pid !=0')->one();
            if(empty($districtData)){
            	$cityData = HpCity::find()->where(['name'=>$city])->andWhere('pid !=0')->one();
            	$model->pid = $cityData->id;
            	if(!$subcity){
            		$subcity = "";
            	}
            	$model->name = $subcity;
            	if(!$level){
            		$level = 0;
            	}
            	$model->level = $level;
            	if($model->save()){
            		echo $subcity." 已经导入数据库";echo "\r\n";
            	}
            }
            
        }
     }
     public function actionPjson(){
        $provinces = HpCity::find()->where(['pid'=>0])->all();
        $ret = [];
        foreach($provinces as $province){
            $data['ProID'] = $province->id;
            $data['name'] = $province->name;
            $data['ProSort'] = $province->id;
            array_push($ret, $data);
        }
        file_put_contents('/tmp/province.js', json_encode($ret));
     }
     public function actionCjson(){
        $provinces = HpCity::find()->where(['pid'=>0])->all();
        $ret = [];
        $ret1 = [];
        foreach($provinces as $province){
            $citys = HpCity::find()->where(['pid'=>$province->id])->all();
            foreach($citys as $city){
                $data['CityID'] = $city->id;
                $data['name'] = $city->name;
                $data['ProID'] = $province->id;
                $data['CitySort'] = $city->id;
                array_push($ret, $data);

                $districts = HpCity::find()->where(['pid'=>$city->id])->all();
                foreach($districts as $district){
                    $data1['Id'] = $district->id;
                    $data1['DisName'] = $district->name;
                    $data1['CityID'] = $city->id;
                    $data1['DisSort'] = 'null';
                    array_push($ret1, $data1);
                }
            }
        }
        file_put_contents('/tmp/city.js', json_encode($ret));
        file_put_contents('/tmp/district.js', json_encode($ret1));
     }
     public function actionDjson(){
        $citys = HpCity::find()->where(['pid'=>0])->all();
        $ret = [];
        foreach($provinces as $province){
            $citys = HpCity::find()->where(['pid'=>$province->id])->all();
            foreach($citys as $city){
                $data['CityID'] = $city->id;
                $data['name'] = $city->name;
                $data['ProID'] = $province->id;
                $data['CitySort'] = $city->id;
                array_push($ret, $data);
            }
        }
        file_put_contents('/tmp/city.js', json_encode($ret));
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