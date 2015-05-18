<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\extensions\Grid\backend;;

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
                return Html::a('<span class="glyphicon glyphicon-cog"></span>', "/auth/assignment?user_id=$model->id", [
                    'title' => Yii::t('yii', 'assignment roles to user'),
                ]);
            };
        }
        if (!isset($this->buttons['assign'])) {
            $this->buttons['assign'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-cog"></span>', $url, [
                    'title' => Yii::t('yii', 'assign user and roles permission'),
                ]);
            };
        }
        parent::initDefaultButtons();
        
    }
}
