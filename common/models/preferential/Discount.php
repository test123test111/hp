<?php
namespace common\models\preferential;

use Yii;
use yii\base\Component;
use common\models\Order;

/**
 * use this function
 * ```php
 *      \Yii::createObject(
 *          [
 *              'class' => 'mall\models\preferential\Discount',
 *              'memberId' => intval($uid),
 *              'beforePrice' => round($price,2),
 *          ]
 *      )
 * 如果在订单创建的时候调用此方法 需用在绑定组件中添加属性 $this->create = false 并把 $this->create 的值修改为true
 *
 * 如果调用此类发生错误会抛出2个异常需要接受
 * \UnexpectedValueException 类方法返回值跟预期值不一样会抛出此异常
 * \InvalidArgumentException 类设置属性值不合法会抛出此异常
 * \RuntimeException 类没有设置相关属性抛出此异常
 * ```
 * Class MemberGrade
 * @package mall\models\preferences
 */
class Discount extends Component
{
    public $pluginMap = array(
        'coupon'=>'Coupon',
    );
    /**
     * @var float 订单前价格
     */
    protected $beforePrice;
    /**
     * @var float 订单优惠后的价格
     */
    protected $afterPrice;
    /**
     * @var int 会员ID
     */
    protected $memberId;
    /**
     * @var int 优惠类型
     */
    protected $preferentialType;
    /**
     * @var string 优惠提示
     */
    protected $preferentialAnnotation;
    /**
     * @var float 优惠折扣
     */
    protected $discount;

    /**
     * function_description
     *
     * @param $classname:
     *
     * @return
     */
    protected function getDiscountClassname($class_name) {
        if (!isset($this->pluginMap[$class_name])) {
             return null;
        } else {
            return "common\models\preferential\\".$this->pluginMap[$class_name];
        }
    }


    /**
     * create a widget and initializes it.
     *
     * @param $widget_name:
     * @param $properties:
     *
     * @return
     */
    protected function createClass($class_name, $properties=array()) {
        $className = $this->getDiscountClassname($class_name);
        if (empty($className)) {
            throw new \Exception("Can not find class name for class: ".$class_name);
        }
        $widget= new $className();
        foreach ($properties as $name=>$value) {
            if (isset($widget->$name)) {
                $widget->$name = $value;
            }
        }
        return $widget;
    }
    /**
     * 设置价格
     * @param $value float 订单价格
     */
    public function setBeforePrice($value)
    {
        $this->beforePrice = round($value, 2);
    }

    /**
     * 返回优惠说明
     * @return string
     */
    public function getPreferentialAnnotation()
    {
        return $this->preferentialAnnotation;
    }


    /**
     * 返回优惠类型
     * @return int
     */
    public function getPreferentialType()
    {
        return $this->preferentialType;
    }
    /**
     * @param  object order
     * @param  array perferential_array example:['coupon'=>'xxxxxxxx','??'=>'?????']
     * @return pay price
     */
    public function getOrderPayPrice($order,$perferential_array){
        foreach($perferential_array as $key=>$value){
            $plugin = $this->createClass($key, $value);
            $plugin->setOrder($order);
            $plugin->getPriceAfterDisount();
        }
        $order = Order::findOne($order->id);
        return $order;
    }
}