<?php
namespace backend\models;

use backend\components\BackendActiveRecord;
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
class Manager extends BackendActiveRecord implements IdentityInterface
{
	/**
	 * @var string the raw password. Used to collect password input and isn't saved in database
	 */
	public $password;
    public $platform;
	const STATUS_DELETED = 0;
	const STATUS_ACTIVE = 10;

	const ROLE_USER = 10;

    const SUPER_ADMIN = 1;
	public static function findIdentityByAccessToken($token,$type=null){}

	public function behaviors()
	{
		return [
			'timestamp' => [
				'class' => 'yii\behaviors\TimestampBehavior',
				'attributes' => [
					BackendActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
					BackendActiveRecord::EVENT_BEFORE_UPDATE => 'update_time',
				],
			],
		];
	}
	public static function tableName(){
		return "manager";
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
     * table manager and manager_platform relationship
     * @return [type] [description]
     */
    public function getManagerPlatforms(){
        return $this->hasMany(ManagerPlatform::className(),['manager_id'=>'id']);
    }
	/**
	 * Finds user by username
	 *
	 * @param string $username
	 * @return null|User
	 */
	public static function findByUsername($username)
	{
		return static::findOne(['username' => $username, 'status' => static::STATUS_ACTIVE]);
	}

	/**
	 * @return int|string|array current user ID
	 */
	public function getId()
	{
		return $this->getPrimaryKey();
	}

	public function getUsername() {
        return $this->user->username;
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

            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username','unique'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'message' => 'This email address has already been taken.', 'on' => 'signup,update'],
            ['email', 'exist', 'message' => 'There is no user with such email.', 'on' => 'requestPasswordResetToken'],


            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function scenarios()
    {
        return [
            'signup' => ['username', 'email', 'password', '!status', '!role'],
            'update'=>['status'],
            'resetPassword' => ['username', 'email', 'password'],
            'resetPassword' => ['password'],
            'default'=>[],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (($this->isNewRecord || $this->getScenario() === 'resetPassword') && !empty($this->password)) {
                $this->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($this->password);
            }
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->getSecurity()->generateRandomKey();
            }
            return true;
        }
        return false;
    }
    /**
     * update manager object attritbutes
     * @param  [type] $attributes [description]
     * @return [type]             [description]
     */
    public function updateAttrs($attributes){
        $attrs = array();
        if (!empty($attributes['username']) && $attributes['username'] != $this->username) {
            $attrs[] = 'username';
            $this->username = $attributes['username'];
        }
         if (!empty($attributes['email']) && $attributes['email'] != $this->email) {
            $attrs[] = 'email';
            $this->email = $attributes['email'];
        }
        if (!empty($attributes['password']) && $attributes['password'] != $this->password) {
            $attrs[] = 'password';
            $this->password = $attributes['password'];
        }
        $this->setScenario('resetPassword');
        $this->status = self::STATUS_ACTIVE;
        if ($this->validate($attrs)) {
            return $this->save(false);
        } else {
            return false;
        }
    }
    /**
     * set user status is delete
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public function deleteUser($uid){
        $this->status = self::STATUS_DELETED;
        if($this->save()){
            return true;
        }
        return false;
    }
    /**
     * get manager object status
     */
    public function getManagerStatus(){
        return function ($model) {
            return $this->getCanUseStatus()[$model->status];
        };
    }
    /**
     * user status list array
     * @return [type] [description]
     */
    public function getCanUseStatus(){
        return [
            self::STATUS_ACTIVE=>'正常',
            self::STATUS_DELETED=>'禁用',
        ];
    }
    /**
     * user status list array
     * @return [type] [description]
     */
    public function getCanUsePlatform(){
        return [
            ManagerPlatform::PLATFORM_CANGCHU=>'仓储',
            ManagerPlatform::PLATFORM_OPERATE=>'运营',
            ManagerPlatform::PLATFORM_BOSS=>'财务',
        ];
    }
    public function getPlatformRelation(){
        return $this->hasMany(ManagerPlatform::className(),['manager_id'=>'id']);
    }
    /**
     * get platforms
     * @return [type] [description]
     */
    public function getPlatforms(){
        return [
            ManagerPlatform::PLATFORM_CANGCHU => '仓储',
            ManagerPlatform::PLATFORM_OPERATE => '运营',
            ManagerPlatform::PLATFORM_BOSS =>'财务',
        ];
    }
    /**
     * [savePlatform description]
     * @param  [type] $platforms [description]
     * @return [type]            [description]
     */
    public function savePlatform($platforms){
        $manager_id = $this->id;
        $model = new ManagerPlatform;
        return $model->updateManagerPlatform($manager_id,$platforms);
    }
    public function fields()
    {
        $fields = parent::fields();
        $fields['platform']='boss';
        return $fields;
    }
    /**
     * 
     * @return [type] [description]
     */
    public function attributeLabels(){
        return [
            'username'=>'用户名',
            'email'=>'邮箱',
            'create_time'=>'创建时间',
            'status'=>'状态',
            'password'=>'密码',
            'platform'=>'平台',
        ];
    }
    /**
     * get user platform
     * @return [type] [description]
     */
    public function getUserPlatform(){
        return function ($model) {
            $str = '';
            if(!empty($model->platformRelation)){
                foreach($model->platformRelation as $value){
                    $str .= $model->getPlatforms()[$value->platform];
                    $str .= ' ';
                }
            }
            return $str;
        };
    }
    
}
