<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use backend\models\ManagerPlatform;
/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = false;
    public $verifyCode;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            ['username','validatePlatform']
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        $user = $this->getUser();
        if (!$user || !$user->validatePassword($this->password)) {
            $this->addError('password', 'Incorrect username or password.');
        }
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePlatform()
    {
        $user = $this->getUser();
        if($user->id != Manager::SUPER_ADMIN){
            $platform = ManagerPlatform::find()->where(['manager_id'=>$user->id,'platform'=>Yii::$app->id])->one();
            if(empty($platform)){
                $this->addError('username', 'This username can not login this platform.');
            }
        }
        
    }
    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    private function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Manager::findByUsername($this->username);
        }
        return $this->_user;
    }
    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名：',
            'password' => '密码：',
        ];
    }
}
