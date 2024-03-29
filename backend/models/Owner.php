<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use common\models\Budget;
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

    const IS_BIG_OWNER = 1;
    const IS_NOT_BIG_OWNER = 0;

    const IS_BUDGET = 1;
    public static function findIdentityByAccessToken($token,$type=null){}
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
            'attributeStamp' => [
                      'class' => 'yii\behaviors\AttributeBehavior',
                      'attributes' => [
                          ActiveRecord::EVENT_BEFORE_INSERT => ['created_uid','modified_uid'],
                          ActiveRecord::EVENT_BEFORE_UPDATE => 'modified_uid',
                      ],
                      'value' => function () {
                          return Yii::$app->user->id;
                      },
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
    public static function findByUsername($username)
    {
        return static::findOne(['english_name' => $username, 'status' => static::STATUS_ACTIVE]);
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
            [['storeroom_id','department','category','product_line','product_two_line'], 'required'],
            ['email', 'unique'],
            ['email', 'email'],
            [['department','category','phone','tell','product_line','product_two_line','big_owner','is_budget','budget'],'safe'],
            ['email', 'unique', 'message' => 'This email address has already been taken.', 'on' => 'signup,update'],
            ['email', 'exist', 'message' => 'There is no user with such email.', 'on' => 'requestPasswordResetToken'],
            ['big_owner','checkBigOwnerOnly'],

            // ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function checkBigOwnerOnly()
    {
        if($this->big_owner == self::IS_BIG_OWNER){
            $result = static::find()->where(['big_owner'=>self::IS_BIG_OWNER,'category'=>$this->category])->one();
            if($this->id){
                if(!empty($result) && $result->id != $this->id){
                    $this->addError('big_owner', '一个部门只能有一个大owner');
                }
            }else{
                if(!empty($result)){
                    $this->addError('big_owner', '一个部门只能有一个大owner');
                }
            }
            
        }
    }
    // public function scenarios()
    // {
    //     return [
    //         'signup' => ['english_name','big_owner', 'is_budget','budget','department','category','product_line','product_two_line','email', 'password','phone','tell', '!status', '!role'],
    //         // 'signup' => ['english_name','big_owner', 'is_budget','budget','department','category','product_line','product_two_line','email', 'password','phone','tell', '!status', '!role'],
    //         'resetPassword' => ['english_name', 'email', 'password','big_owner'],
    //         // 'resetPassword' => ['password'],
    //         'requestPasswordResetToken' => ['email'],
    //     ];
    // }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!empty($this->password)) {
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
        if (!empty($attributes['department']) && $attributes['department'] != $this->department) {
            $attrs[] = 'department';
            $this->department = $attributes['department'];
        }
        if (!empty($attributes['category']) && $attributes['category'] != $this->category) {
            $attrs[] = 'category';
            $this->category = $attributes['category'];
        }
        if (!empty($attributes['product_line']) && $attributes['product_line'] != $this->product_line) {
            $attrs[] = 'product_line';
            $this->product_line = $attributes['product_line'];
        }
        if (!empty($attributes['product_two_line']) && $attributes['product_two_line'] != $this->product_two_line) {
            $attrs[] = 'product_two_line';
            $this->product_two_line = $attributes['product_two_line'];
        }
        if (!empty($attributes['big_owner']) && $attributes['big_owner'] != $this->big_owner) {
            $attrs[] = 'big_owner';
            $this->big_owner = $attributes['big_owner'];
        }
        if (!empty($attributes['is_budget']) && $attributes['is_budget'] != $this->is_budget) {
            $attrs[] = 'is_budget';
            $this->is_budget = $attributes['is_budget'];
        }
        if (!empty($attributes['budget']) && $attributes['budget'] != $this->budget) {
            $attrs[] = 'budget';
            $this->budget = $attributes['budget'];
        }
        // $this->setScenario('resetPassword');
        if ($this->validate($attrs)) {
            
            return $this->update(false);
        } else {
            return false;
        }
    }
    public function getDepartments(){
        return $this->hasOne(Department::className(),['id'=>'department']);
    }
    public function getStoreroom(){
        return $this->hasOne(Storeroom::className(),['id'=>'storeroom_id']);
    }
    public function getCategorys(){
        return $this->hasOne(Category::className(),['id'=>'category']);
    }
    public function getProductlines(){
        return $this->hasOne(ProductLine::className(),['id'=>'product_line']);
    }
    public function getProducttwolines(){
        return $this->hasOne(ProductTwoLine::className(),['id'=>'product_two_line']);
    }
    public function getBudgets(){
        return $this->hasOne(Budget::className(),['owner_id'=>'id'])->orderBy(['id'=>SORT_DESC]);
    }
    // public function 
    public function deleteUser(){
        $this->status = self::STATUS_DELETED;
        $this->save(false);
        return true;
    }

    public function attributeLabels(){
        return [
            'english_name'=>'名称',
            'email'=>'邮箱',
            'password'=>'密码',
            'phone'=>'移动电话',
            'tell'=>'固定电话',
            'department'=>'部门',
            'category'=>'组别',
            'product_line'=>'一级产品线',
            'product_two_line'=>'二级产品线',
            'created'=>'创建时间',
            'big_owner'=>'是否管理员',
            'is_budget'=>'有无预算权',
            'budget'=>'预算金额',
            'storeroom_id'=>'所在库房'
        ];
    }
    /**
     * [getCanChoseProvince description]
     * @return [type] [description]
     */
    public function getCanChoseDepartment(){
        $province = Department::find()->all();
        $ret = [''=>'请选择...'];
        foreach($province as $pro){
            $ret[$pro->id] = $pro->name;
        }
        return $ret;
    }
    public function getDefaultCategory(){
        return \yii\helpers\ArrayHelper::map(Category::find()->where(['department_id' => $this->department])->all(),'id','name');
    }
    public function getDefaultProductLine(){
        return \yii\helpers\ArrayHelper::map(ProductLine::find()->where(['category_id' => $this->category])->all(),'id','name');
    }
    public function getDefaultProductTwoLine(){
        return \yii\helpers\ArrayHelper::map(ProductTwoLine::find()->where(['product_line_id' => $this->product_line])->all(),'id','name');
    }
    public function getDepartmentName(){
        return Department::findOne($this->department)->name;
    }
    public function getCategoryName(){
        return Category::findOne($this->category)->name;
    }
    public function getProductLineName(){
        return ProductLine::findOne($this->product_line)->name;
    }
    public function getProductTwoLineName(){
        return ProductTwoLine::findOne($this->product_two_line)->name;
    }
    /**
     * get big owner by uid
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getBigOwnerByUid($uid){
        $owner = static::findOne($uid);
        $category = $owner->category;

        $user = static::find()->where(['category'=>$category,'big_owner'=>self::IS_BIG_OWNER])->one();
        if(!empty($user)){
            return $user;
        }
        return false;
    }
    /**
     * [getBudgetUsers description]
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getBudgetUsers($uid){
        $owner = static::findOne($uid);
        $category = $owner->category;
        if($owner->big_owner == self::IS_BIG_OWNER){
            return static::find()->where(['category'=>$category])->all();
        }else{
            return static::find()->where(['id'=>$uid])->all();
        }
    }
    /**
     * [getCanUseStorerooms description]
     * @return [type] [description]
     */
    public function getCanUseStorerooms(){
        $rs = Storeroom::find()->all();
        $arr = [];
        if($rs){
            foreach($rs as $key=>$v){
                $arr[$v['id']]=$v['name'];
            }

        }
        return $arr;
    }
    public static function getOwnerByMid($material_id){
        $material = Material::findOne($material_id);
        if($material){
            $owner = Owner::find()->where(['id'=>$material->owner_id])->all();
            $ret = [];
            return array_map(function($a) use ($ret){
                $ret = ['id'=>$a['id'],'name'=>$a['english_name']];
                return $ret;
            },$owner);
        }
        
    }

    /**
     * [getAllCategorysByOwnerId description]
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getAllCategorysByOwnerId($uid)
    {
        $owner = static::findOne($uid);
        if ($owner->big_owner == Owner::IS_BIG_OWNER) {
            return Category::find()->where(['department_id' => $owner->department])->all();
        }
        return Category::find()->where(['id' => $owner->category])->all();
    }

    public static function getAllDepartmentOwners($uid)
    {
        $owner = static::findOne($uid);
        if ($owner->big_owner == Owner::IS_BIG_OWNER) {
            return static::find()->where(['department' => $owner->department])->all();
        }
        return static::find()->where(['id'=>$uid])->all();
    }
}
