<?php
namespace operate\models;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use operate\models\Order;
use operate\models\User;
class UserVip extends ActiveRecord {

    const WEIXIN_GROUP_IN=1;
    const WEIXIN_GROUP_NO=0;

    public static function tableName(){
        return "user_vip";
    }

    public function rules()
    {
        return [
            [['user_id'],'unique'],
            [['user_id','qq','user_create_time'], 'integer'],
            [['user_id','name','phone'],'required'],
            [['phone_back'],'integer'],
            [['phone_back'],'string','min'=>11,'max'=>11],
            [['weixin'],'string','max'=>32],
            [['remark',],'string','max'=>100],
            [['in_weixin'],'default','value'=>0],
        ];
    }

    /**
     * 取是否在微信群
     */
    public function getWeixinGroupStatus(){
        return function ($model) {
            return $this->getInWeixinStatus()[$model->in_weixin];
        };
    }
    /**
     * uservip status list array
     * @return [type] [description]
     */
    public function getInWeixinStatus(){
        return [
            self::WEIXIN_GROUP_IN=>'是',
            self::WEIXIN_GROUP_NO=>'否',
        ];
    }

    /*
     * 统计已支付的订单数量
     */
    public function getOrderCount(){
        return $this->hasMany(Order::className(),['user_id'=>'user_id'])->where(['status'=>['success','prepayed','packed','to_demostic','demostic','to_user','refund','wait_refund']])->count(1);
    }

    /*
     * 统计已支付的订单总金额
     */
    public function getOrderSum(){
        return $this->hasMany(Order::className(),['user_id'=>'user_id'])->where(['status'=>['success','prepayed','packed','to_demostic','demostic','to_user','refund','wait_refund']])->sum('sum_price');
    }

    /*
     * 取最后一单
     */
    public function getLastOrder(){
        return $this->hasOne(Order::className(),['user_id'=>'user_id'])->orderBy(['create_time'=>SORT_DESC])->limit(1);
    }


    public function attributeLabels(){
        return [
            'user_id' => '用户ID',
            'name'=>'用户名',
            'phone'=>'手机号',
            'qq'=>'QQ',
            'in_weixin'=>'是否在微信群',
            'weixin'=>'微信',
            'last_order_time'=>'最后一单时间',
            'total_order_num' => '订单总量',
            'total_order_money' => '订单总额',
            'phone_back' => '备用手机号',
            'remark' => '备注',
        ];
    }

    /*
     * 是否在微信群
     */
    public function getInWeixin(){
        return [
            self::WEIXIN_GROUP_IN =>'是',
        ];
    }

    /*
     * 取VIP用户信息，名称＼常用收货地址等
     */
    public static function getVipUserInfo($user_id){
        $query = UserVip::find();
        $query->andWhere(['user_id'=>$user_id]);
        $vipInfo = $query->all();
        $user = new User();
        $userInfo = $user->findOne($user_id);
        $orderPhone = $userInfo->userOrderPhone;
        $orderAddr = $userInfo->userOrderAddr;
        return ['userinfo'=>$userInfo,'vipinfo'=>$vipInfo,'orderPhone'=>$orderPhone,'orderAddr'=>$orderAddr,];
    }

    /*
     * 取所有VIP用户ID
     */
    public static function getAllUserIds(){
        $query = Static::find();
        $query->select('user_id');
        $data = $query->all();
        $return = [];
        if(!empty($data)){
            foreach($data as $v){
                $return[] = $v['user_id'];
            }
        }
        return $return;
    }


    public function beforeSave($insert){
        if(parent::beforeSave($insert)) {
            if (is_array($this->in_weixin)) {
                $this->in_weixin = $this->in_weixin[0];
            }
            if (!isset($this->in_weixin) || !$this->in_weixin) {
                $this->in_weixin = 0;
            }

            if ($this->isNewRecord) {
                $this->create_time = time();
                $this->last_update_time =  time();
            } else {

                $this->last_update_time =  time();
            }
            return true;
        }

    }


}