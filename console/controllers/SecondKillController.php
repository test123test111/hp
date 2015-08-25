<?php
namespace console\controllers;
use Yii;
use yii\console\Controller;
class SecondKillController extends Controller
{
	public function actionRun()
	{

		while (true) {
			$date = date('Y-m-d H:i:s');
			if ($date >= '2015-08-24 10:59:58') {
				$result = $this->kill();
		        $result = json_decode($result,true);
		        if ($result['errno'] == 0) {
		        	// file_put_contents('/tmp/secondkill.txt', $result);
		        	break;
		        } else {
		        	echo $result['errno']."\r\n";
		        }
			} else {
				echo "时间还未到"."\r\n";
			}
	        
	    }
		
		
	}

	public function actionRun12()
	{

		while (true) {
			$date = date('Y-m-d H:i:s');
			echo $date;echo "\r\n";
			if ($date >= '2015-08-25 23:59:58') {
				$result = $this->kill2();
		        $result = json_decode($result,true);
		        if ($result['errno'] == 0) {
		        	// file_put_contents('/tmp/secondkill.txt', $result);
		        	break;
		        } else {
		        	echo $result['errno']."\r\n";
		        }
			} else {
				echo "时间还未到"."\r\n";
			}
	        
	    }
		
		
	}

	public function actionRun9()
	{
		while (true) {
			$date = date('Y-m-d H:i:s');
			if ($date >= '2015-08-24 20:59:58') {
				$result = $this->kill9();
		        $result = json_decode($result,true);
		        if ($result['errno'] == 0) {
		        	// file_put_contents('/tmp/secondkill.txt', $result);
		        	break;
		        } else {
		        	echo $result['errno']."\r\n";
		        }
			} else {
				echo "时间还未到"."\r\n";
			}
	        
	    }
	}

	public function actionRunyanglei()
	{
		while (true) {
			$date = date('Y-m-d H:i:s');
			if ($date >= '2015-08-25 11:59:58') {
				$result = $this->killbao();
		        $result = json_decode($result,true);
		        if ($result['errno'] == 0) {
		        	break;
		        } else {
		        	echo $result['errno']."\r\n";
		        }
			} else {
				echo "时间还未到"."\r\n";
			}
	        
	    }
	}	

	public function killbao()
	{	
		//yanglei
		// $sku_info = '{"sku_info":[{"sku_source":24,"num":1,"stock_amount_id":549672,"sku_source_arg":"276050"}]}';
		$sku_info = '{"sku_info":[{"sku_source":24,"num":1,"stock_amount_id":549999,"sku_source_arg":"276252"}]}';
		$session = 'PHPSESSID=5aes9t9h1c5mgs2go5fito4q25';
		$dvid = 'EB497446-6E3D-47B1-B15B-3C3D85D82EAB';
		$this->preAdd($sku_info,$session,$dvid);
		
		$user_addr_id = 136304;

		$ship_type = 0;

		$uri = "http://api.taoshij.com/user/order/addNew";
		// 参数数组
		$data = [
		        'sku_info' => $sku_info,
		        'user_addr_id' => $user_addr_id,
		        'ship_type' => 0,
		        'dvid' => $dvid,
		        'source' => 'iOS_4.1.0_AMCustomer_AppStore',
		];
		 
		$ch = curl_init ();
		// print_r($ch);
		curl_setopt ( $ch, CURLOPT_URL, $uri );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt($ch,CURLOPT_COOKIE,$session);  
		$return = curl_exec ( $ch );
		curl_close ( $ch );
		return $return;
	}

	public function kill2()
	{

		//zhangying
		$sku_info = '{"sku_info":[{"sku_source":24,"num":1,"stock_amount_id":558276,"sku_source_arg":"280737"}]}';
		$session = 'PHPSESSID=72jm3bktgm782d56r1fpu6aok0';
		$dvid = '333B77E1-BF95-44D3-A200-D90F8FF86BF6';
		$this->preAdd($sku_info,$session,$dvid);
		
		$user_addr_id = 136263;

		$ship_type = 0;

		$uri = "http://api.taoshij.com/user/order/addNew";
		// 参数数组
		$data = [
		        'sku_info' => $sku_info,
		        'user_addr_id' => $user_addr_id,
		        'ship_type' => 0,
		        'dvid' => $dvid,
		        'source' => 'iOS_4.1.0_AMCustomer_AppStore',
		];
		 
		$ch = curl_init ();
		// print_r($ch);
		curl_setopt ( $ch, CURLOPT_URL, $uri );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt($ch,CURLOPT_COOKIE,$session);  
		$return = curl_exec ( $ch );
		curl_close ( $ch );
		return $return;
	}

	public function kill9()
	{

		//zhangying
		$sku_info = '{"sku_info":[{"sku_source":24,"num":1,"stock_amount_id":549676,"sku_source_arg":"276053"}]}';
		$session = 'PHPSESSID=72jm3bktgm782d56r1fpu6aok0';
		$dvid = '333B77E1-BF95-44D3-A200-D90F8FF86BF6';
		$this->preAdd($sku_info,$session,$dvid);
		
		$user_addr_id = 136263;

		$ship_type = 0;

		$uri = "http://api.taoshij.com/user/order/addNew";
		// 参数数组
		$data = [
		        'sku_info' => $sku_info,
		        'user_addr_id' => $user_addr_id,
		        'ship_type' => 0,
		        'dvid' => $dvid,
		        'source' => 'iOS_4.1.0_AMCustomer_AppStore',
		];
		 
		$ch = curl_init ();
		// print_r($ch);
		curl_setopt ( $ch, CURLOPT_URL, $uri );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt($ch,CURLOPT_COOKIE,$session);  
		$return = curl_exec ( $ch );
		curl_close ( $ch );
		return $return;
	}

	/**
	 * action pre add
	 * @param  [type] $sku_info [description]
	 * @param  [type] $session  [description]
	 * @param  [type] $dvid     [description]
	 * @return [type]           [description]
	 */
	public function preAdd($sku_info,$session,$dvid)
	{

		$uri = "http://api.taoshij.com/user/order/preAdd";
		// 参数数组
		$data = [
		        'sku_info' => $sku_info,
		        'dvid' => $dvid,
		        'source' => 'iOS_4.1.0_AMCustomer_AppStore',
		];
		 
		$ch = curl_init ();
		// print_r($ch);
		curl_setopt ( $ch, CURLOPT_URL, $uri );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt($ch,CURLOPT_COOKIE,$session);  
		$return = curl_exec ( $ch );
		curl_close ( $ch );
		return $return;
	}
}

