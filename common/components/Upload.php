<?php
namespace common\components;

use Yii;
use yii\web\UploadedFile;

class Upload{

    /**
     * upload images
     * @param  [type]  $file    [description]
     * @param  boolean $ifthumb [description]
     * @return [type]           [description]
     */
    public function uploadImage($file,$ifthumb=false){
        $attachDir = Yii::$app->params['attachDir'];
        $dir = '/public_upload/operate/'.'day_'.date('ymd').'/';
        $save_path = $attachDir . $dir;
        if(!is_dir($save_path)){
            @mkdir($save_path,0777,true);
        }
        $filetype = $this->getImageBufferExt($file['upload']['tmp_name']);
        $error = $this->showError($file,$filetype);
        if($error){
            return $error;
        }
        $md5 = md5_file($file['upload']['tmp_name']);
        $new_file_name = date("Ymd") . '_' . substr($md5,0,7) . '.' . $filetype;
        $file_path = $save_path . $new_file_name;
        $url = Yii::$app->params['staticDomain']. $dir.$new_file_name;
        $model = new UploadedFile;
        $model->tempName=$file['upload']['tmp_name'];
        if($model->saveAs($file_path)){
            $realpath = $dir.$new_file_name;
            if($ifthumb){
                // $resize = new ResizeImage;
                // $thumb1 = $resize->getThumb($file_path,$realpath,$filetype,400,400);
                // $thumb2 = $resize->getThumb($file_path,$realpath,$filetype,200,200,true);
                // $url = Yii::$app->params['targetDomain'].$thumb1;
                // return [true,$url,$thumb1,$thumb2];
            }
            return [true,$url,$realpath];
        }else{
            return [false,"文件上传失败，请检查上传目录设置和目录读写权限"];
        }
    }

    public function showError($file,$filetype){
        $type = ['gif', 'jpg', 'jpeg', 'png', 'bmp'];
        if(!in_array($filetype,$type)){
            return [false,"错误的文件类型！"];
        }
        if($file['upload']['size']>5*1024*1024){
            return [false,"上传的文件不能超过5M"];
        }
        return false;
    }

    public function getImageBufferExt($buffer) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
        $mime = finfo_file($finfo, $buffer) ;
        switch ($mime) {
            case 'image/png':
            case 'image/x-png':
                return "png";
            case 'image/pjpeg':
            case 'image/jpeg':
                return "jpg";
            case 'image/gif':
                return "gif";
            default:
                return "";
        }
    }
}