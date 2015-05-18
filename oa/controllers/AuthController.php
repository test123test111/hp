<?php
namespace oa\controllers;

use Yii;
use backend\components\BackendController;
use backend\models\AuthItem;
use backend\models\Assign;
use backend\models\AssignMent;

class AuthController extends BackendController{

    public $layout = false;
    public $enableCsrfValidation=false;

    public function actionPermlist(){
        $model = new AuthItem;
        return $this->render("permlist",['model'=>$model]);

    }
    //show all roles
    public function actionRolelist(){
        $model = new AuthItem;
        $msg = '';
        if(!empty($_POST)){
            $model->load(Yii::$app->request->post());
            if($model->validate()){
                list($status,$msg) = $model->createItemInfo($_POST['AuthItem']);
                if($status){
                    return $this->redirect(Yii::$app->request->getReferrer());
                }
            }
        }      
        return $this->render("rolelist",['model'=>$model,'isNew'=>true,'show_error'=>$msg]);
    }

    // get permissions.php's perm value and insert into db
    public function actionFlushperms(){
        $model = new AuthItem;
        $model->flushPerms();
        Yii::$app->session->setFlash('success', '权限导入成功,您现在可以进行权限分配');
        return $this->render('flush');
    }

    //assign permissions to roles
    public function actionAssign(){
        $model = new AuthItem;
        $itemName = @$_GET['id']?:"";
        $status = '';
        if($itemName && Yii::$app->request->isPost){
            if(empty($_POST['child'])){
                $auth_item = AuthItem::find(['name'=>$itemName,'description'=>Yii::$app->id])->one();
                if(!empty($auth_item)){
                    $status = Assign::deleteAll(['parent'=>$itemName]);
                }
            }else{
                $status = $model->addPermToRole($itemName,$_POST['child']);
            }
            if($status){
                return $this->redirect(Yii::$app->request->getReferrer());
            }
        }
        $roles = $model->getAllRoles($itemName);
        $perms = $model->getPermData();
        $items = $model->assignedRoles($itemName);
        return $this->render("assign",['model'=>$model,'roles'=>$roles,'perms'=>$perms,'items'=>$items,'status'=>$status]);
    }

    //assign roles to users 
    public function actionAssignment(){
        $model = new AuthItem;
        $userId = @$_GET['user_id']?:"";
        if(Yii::$app->request->isPost){
            if($userId){
                AuthItem::clearUserPermissionByPlatform($userId,Yii::$app->id);
                if(!empty($_POST['child']['role'])){
                    $model->assignRoleToUser($userId,$_POST['child']['role']);
                }
            }
            return $this->redirect(Yii::$app->request->getReferrer());
            
        }
        
        $roles = $model->getUserRoles($userId);
        $allRoles = $model->getRoles();
        return $this->render("assignment",['userId'=>$userId,'roles'=>$roles,'allRoles'=>$allRoles]);
    }


    public function actionUpdate(){
        $itemName = $_GET['id'];
        $item = AuthItem::findOne($itemName);
        if (!$item) {
            throw new NotFoundHttpException();
        }
        $msg = '';
        if(!empty($_POST)){
            list($status,$msg) = $item->updateItemInfo($itemName,$item->type,$_POST['AuthItem']);
            if($status){
                return $this->redirect("/auth/rolelist");
            }
        }
        return $this->render("rolelist",['model'=>$item,'isNew'=>false,'show_error'=>$msg]);
    }


    public function actionDelete(){
        $itemName = $_GET['id'];
        $model = AuthItem::findOne($itemName);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        $msg = '';
        if(!empty($_POST)){
            list($status,$msg) = $model->removeItemInfo($itemName,$model->type);
            if($status){
                return $this->redirect("/auth/rolelist");
            }
        }
        return $this->redirect("/auth/rolelist");
    }
    
    public function actionTest() {
     echo 1;exit;
    }
}
