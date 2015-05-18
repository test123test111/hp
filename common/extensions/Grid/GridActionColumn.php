<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\extensions\Grid;

use Yii;
use yii\helpers\Html;
use yii\grid\ActionColumn;

/**
 * ActionColumn is a column for the [[GridView]] widget that displays buttons for viewing and manipulating the items.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GridActionColumn extends ActionColumn
{
    /**
     * Initializes the default button rendering callbacks
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['assignment'])) {
            $this->buttons['assignment'] = function ($url, $model) {
                return Html::a('<i class="icon-cog"></i>', "/auth/assignment?user_id=$model->id", [
                    'title' => Yii::t('yii', '分配权限'),
                ]);
            };
        }

        if (!isset($this->buttons['assign'])) {
            $this->buttons['assign'] = function ($url, $model) {
                return Html::a('<span class="icon-cog"></span>', $url, [
                    'title' => Yii::t('yii', 'assign user and roles permission'),
                ]);
            };
        }

        if (!isset($this->buttons['updatepw'])) {
            $this->buttons['updatepw'] = function ($url, $model) {
                return Html::a('<i class="icon-lock"></i>', "/manager/updatepw/$model->id", [
                    'title' => Yii::t('yii', 'update user password'),
                ]);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                return Html::a('<span class="icon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ]);
            };
        }
        parent::initDefaultButtons();
        
    }
}
