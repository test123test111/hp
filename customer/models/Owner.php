<?php
namespace customer\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
/**
 * Class User
 * @package common\models
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 */
class Owner extends ActiveRecord implements IdentityInterface
{
    /**
     * @var string the raw password. Used to collect password input and isn't saved in database
     */
    public $password;

    const STATUS_DELETED = 1;
    const STATUS_ACTIVE = 0;

    const ROLE_USER = 10;

    public static function findIdentityByAccessToken($token,$type = null){}
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created', 'modified'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'modified',
                ],
                'value' => function (){ return date("Y-m-d H:i:s");}
            ],
        ];
    }
    public static function tableName(){
        return "owner";
    }
    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return null|User
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => static::STATUS_ACTIVE]);
    }

    /**
     * @return int|string|array current user ID
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    public function getUsername() {
        return $this->user->english_name;
    }
    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE,'on'=>'update'],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => [self::ROLE_USER]],

            ['english_name', 'filter', 'filter' => 'trim'],
            ['english_name', 'required'],
            ['english_name', 'unique'],
            ['english_name', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'message' => 'This email address has already been taken.', 'on' => 'signup,update'],
            ['email', 'exist', 'message' => 'There is no user with such email.', 'on' => 'requestPasswordResetToken'],

            // ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function scenarios()
    {
        return [
            'signup' => ['english_name', 'email', 'password','phone','tell', '!status', '!role'],
            'update'=>['status'],
            'resetPassword' => ['username', 'email', 'password'],
            'resetPassword' => ['password'],
            'requestPasswordResetToken' => ['email'],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (($this->isNewRecord || $this->getScenario() === 'resetPassword') && !empty($this->password)) {
                $this->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($this->password);
            }
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->getSecurity()->generateRandomString();
            }
            return true;
        }
        return false;
    }

    public function getAllData(){
        $provider = new ActiveDataProvider([
              'query' => static::find()
                         // ->where(['status'=>10])
                         ->orderby('id desc'),
            'sort' => [
                'attributes' => ['id',],
            ],
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
        return $provider;
    }

    public function updateAttrs($attributes){
        $attrs = array();
        if (!empty($attributes['english_name']) && $attributes['english_name'] != $this->english_name) {
            $attrs[] = 'english_name';
            $this->english_name = $attributes['english_name'];
        }
        if (!empty($attributes['email']) && $attributes['email'] != $this->email) {
            $attrs[] = 'email';
            $this->email = $attributes['email'];
        }
        if (!empty($attributes['phone']) && $attributes['phone'] != $this->phone) {
            $attrs[] = 'phone';
            $this->phone = $attributes['phone'];
        }
        if (!empty($attributes['tell']) && $attributes['tell'] != $this->tell) {
            $attrs[] = 'tell';
            $this->tell = $attributes['tell'];
        }
        if (!empty($attributes['password']) && $attributes['password'] != $this->password) {
            $attrs[] = 'password';
            $this->password = $attributes['password'];
        }
        $this->setScenario('resetPassword');
        if ($this->validate($attrs)) {
            return $this->save(false);
        } else {
            return false;
        }
    }
    public function getDepartments(){
        return $this->hasOne(\common\models\Department::className(),['id'=>'department']);
    }
    public function getCategorys(){
        return $this->hasOne(\common\models\Category::className(),['id'=>'category']);
    }
    public function getProductlines(){
        return $this->hasOne(\common\models\ProductLine::className(),['id'=>'product_line']);
    }
    public function getProducttwolines(){
        return $this->hasOne(\common\models\ProductTwoLine::className(),['id'=>'product_two_line']);
    }
    // public function 
    public function deleteUser($uid){
        $model = Owner::find($uid);
        $model->setScenario('update');
        $model->status = self::STATUS_DELETED;
        $model->update();
        if($model->update()){
            return true;
        }
        return false;
    }

    public function attributeLabels(){
        return [
            'english_name'=>'名称',
            'email'=>'邮箱',
            'password'=>'密码',
            'phone'=>'移动电话',
            'tell'=>'固定电话',
        ];
    }
    public static function getBudgetUsers($uid,$except = false){
        $owner = static::findOne($uid);
        $results = static::find()
                ->where(['department'=>$owner->department,'storeroom_id'=>$owner->storeroom_id])
                ->all();
        $ret = [];
        if ($except) {
            foreach ($results as $result) {
                if($result->id != $uid){
                    $ret[] = ['label'=>$result->english_name,'vsa'=>$result->id];
                } else {
                    continue;
                }
                
            }
        } else {
            foreach ($results as $result) {
                $ret[] = ['label'=>$result->english_name,'vsa'=>$result->id];
            }
            
        }
        // foreach($results as $result){
        //     if($except){
        //         if($result->id != $uid){
        //             $ret[] = ['label'=>$result->english_name,'vsa'=>$result->id];
        //         }
        //     }else{
        //         $ret[] = ['label'=>$result->english_name,'vsa'=>$result->id];
        //     }
        // }
        return json_encode($ret);
    }
}
