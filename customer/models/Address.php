<?php
namespace customer\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use customer\components\CustomerActiveRecord;

class Address extends CustomerActiveRecord {
	const USER_ADDRESS_DATA_NULL = 123123213;
    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'address';
    }


    public function rules()
    {
        return [
            [['name', 'address','company'], 'required'],
            [['phone', 'province', 'city', 'area', 'address', 'zip', 'tel_area_code', 'tel', 'tel_ext','info'], 'safe'],
        ];
    }
    public function behaviors()
    {
        return BaseArrayHelper::merge(
            parent::behaviors(),
            [
                'timestamp' => [
                    'class' => 'yii\behaviors\TimestampBehavior',
                    'attributes' => [
                        CustomerActiveRecord::EVENT_BEFORE_INSERT => ['created'],
                    ],
                    'value' => function (){ return date("Y-m-d H:i:s");}
                ],
           ]
        );
    }

    /**
     * get user all address
     * @param $uid
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getUserAddress($uid)
    {
        return static::find()->where(['uid' => $uid])->orderBy(['status' => SORT_DESC])->all();
    }
    /**
     * get user company json for autocomplete
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getUserCompany($uid){
        $results = static::find()->where(['uid' => $uid])->orderBy(['status' => SORT_DESC])->all();
        $ret = [];
        foreach($results as $result){
            $ret[] = ['label'=>$result->company,'vsa'=>'/cart/addressinfo/'.$result->id];
        }
        return json_encode($ret);
    }
    /**
     * table owner and table address relationship
     * @return [type] [description]
     */
    public function getUser(){
        return $this->hasOne(Owner::className(),['id'=>'uid']);
    }
    /**
     * 获取用户一个地址
     * @param $id int 地址ID
     * @param $uid int 用户ID
     * @return array|null|\yii\db\ActiveRecord 返回地址模型
     */
    public static function findOneUserAddressModel($id, $uid)
    {
        return static::find()->where(['id' => $id, 'uid' => $uid])->one();
    }
    /**
     * [getAddressByUid description]
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getAddressByUid($uid){
        $owner = Owner::findOne($uid);
        if ($owner->big_owner == 1) {
            $query = static::find()->joinWith(['user'=>function($query) use ($owner){
                    return $query->where(['department'=>$owner->department]);
                }])->orderBy(['address.id'=>SORT_DESC]);
        } else {
            $query = static::find()->with(['user'])->where(['uid'=>$uid])->orderBy(['address.id'=>SORT_DESC]);
        }
        $count = $query->count();
        $pages = new \yii\data\Pagination(['totalCount' => $count]);
        $ret = [];
        $query->offset($pages->offset)->limit(20);

        $data = $query->all();
        return [$data,$pages,$count];
    }
    /**
     * get customer all address
     * @param  [type] $begin_time [description]
     * @param  [type] $end_time   [description]
     * @return [type]             [description]
     */
    public static function getDatas($owner_id){
        $str = "序号,创建人,收货单位名称,收货联系人,收货人联系电话,收货人地址,所在城市,创建人,创建日期,备注\n";
        $offset = 0;
        $limit = 100;
        $data = [];
        $rs = [];
        $i = 1;
        $owner = Owner::findOne($uid);
        if ($owner->big_owner == 1) {
            $query = static::find()->joinWith(['user'=>function($query) use ($owner){
                    return $query->where(['department'=>$owner->department]);
                }])->orderBy(['address.id'=>SORT_DESC]);
        } else {
            $query = static::find()->with(['user'])->where(['uid'=>$uid])->orderBy(['address.id'=>SORT_DESC]);
        }
        $num = $query->count();

        while(true){
            $results = $query->limit($limit)->offset($offset)->all();
            if(empty($results)){
                break;
            }
            foreach($results as $key =>$result){
                $data[$i]['id'] = $i;
                $data[$i]['uid'] = $result->user->english_name;
                $data[$i]['company'] = $result->company;
                $data[$i]['name'] = $result->name;
                $data[$i]['phone'] = $result->phone;
                $data[$i]['address'] = $result->province.$result->city.$result->area.$result->address;
                $data[$i]['city'] = $result->city;
                $data[$i]['created_user'] = isset($result->user) ? $result->user->english_name : '';
                $data[$i]['created'] = $result->created;
                $data[$i]['info'] = $result->info;
                $str .= $data[$i]['id'].",".$data[$i]['uid'].",".$data[$i]['company'].",".$data[$i]['name'].",".$data[$i]['phone'].",".$data[$i]['address'].",".$data[$i]['city'].",". $data[$i]['created_user'] .',' . $data[$i]['created'].",".$data[$i]['info']."\r\n"; //用引文逗号分开
                $i++;
            }
           
            $offset += $limit;
            if ($offset > $num) {
                break;
            }
        }
        return $str;
    }
}