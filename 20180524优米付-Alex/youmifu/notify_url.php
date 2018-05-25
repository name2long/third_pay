<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
// write_log("notify");

#接收资料
#post方法
// write_log('post方法');
$data = array();
foreach ($_POST as $key => $value) {
	$data[$key] = $value;
	// write_log($key."=".$value);
}

#设定固定参数
$order_no = $data['orderNo']; //订单号
$mymoney = number_format($data['tradeAmt'], 2, '.', ''); //订单金额
$success_msg = $data['orderStatus'];//成功讯息
$success_code = "1";//文档上的成功讯息 0 未支付，1 成功，2失败
$sign = $data['signMsg'];//签名 大写
$echo_msg = "SUCCESS";//回调讯息

#根据订单号读取资料库
$params = array(':m_order' => $order_no);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

#获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];
if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}

#验签方式
$arr_order = array(#签名顺序
	'apiName',
	'notifyTime',
	'tradeAmt',
	'merchNo',
	'merchParam',
	'orderNo',
	'tradeDate',
	'accNo',
	'accDate',
	'orderStatus'
);
$signtext="";
foreach ($arr_order as $arr_key) {
	$signtext .= $arr_key . '=' . $data[$arr_key] . '&';
}
$signtext = substr($signtext, 0,-1).$pay_mkey;//验签字串
// write_log("signtext=".$signtext);
$mysign = mb_strtoupper(md5($signtext));//签名
// write_log("mysign=".$mysign);

#到账判断
if ($success_msg == $success_code) {
  if ( $mysign == $sign) {
		$result_insert = update_online_money($order_no, $mymoney);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");
			// write_log("会员信息不存在，无法入账");
			exit;
		}else if($result_insert == 0){
			echo ($echo_msg);
			// write_log($echo_msg.' at 0');
			exit;
		}else if($result_insert == -2){
			echo ("数据库操作失败");
			// write_log("数据库操作失败");
			exit;
		}else if($result_insert == 1){
			echo ($echo_msg);
			// write_log($echo_msg.' at 1');
			exit;
		} else {
			echo ("支付失败");
			// write_log("支付失败");
			exit;
		}
	}else{
		echo ('签名不正确！');
		// write_log("签名不正确！");
		exit;
	}
}else{
	echo ("交易失败");
	// write_log("交易失败");
	exit;
}
?>
