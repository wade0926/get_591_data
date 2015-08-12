<?php
require_once("../db.php");
set_time_limit(0);
mysql_query("SET NAMES 'UTF8'");
date_default_timezone_set('Asia/Taipei');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>get 591</title>
</head>
<?php
//Step 1： 抓591 id
//get_591_id($para_totalRows = 15903);

//Step 2： 抓個人詳細資料
//設定撈取的限制次數
$get_limit = 200;

$sql = "SELECT 5_id,5_name_id,5_name FROM 591_link_data WHERE 5_name = '' AND 5_del = 0 LIMIT ".$get_limit;	
$res = mysql_query($sql);
	            
while($row = mysql_fetch_array($res))
{
	if($row['5_name'] == '')
	{			
		$target_url = 'http://www.591.com.tw/'.$row['5_name_id'];		
		$page_data = exec_curl($target_url);
		
		//找姓名
		preg_match_all("/<span class=\"name\">.*?<h3>(.*?)<\/h3>/s",$page_data,$name);
		$name = $name[1][0];
		
		//找就職公司
		preg_match_all("/<li>就職公司：<span>(.*?)<\/span> <span>(.*?)<\/span><\/li>/",$page_data,$company);
		$company = $company[1][0].' '.$company[2][0];
		
		//找行動電話
		preg_match_all("/<li>行動電話：<span id=\"phone-num\" class=\"org\">(.*?)<\/span><\/li>/",$page_data,$cell_phone);
		$cell_phone = $cell_phone[1][0];
				
		//找服務區域
		preg_match_all("/<li>服務區域：<span>(.*?)<\/span><\/li>/",$page_data,$serve_area);
		$serve_area = $serve_area[1][0];
		
		//找E-mail
		preg_match_all("/<li class=\"long\">E-mail：<span>(.*?)<\/span><\/li>/",$page_data,$mail);
		$mail = $mail[1][0];
				
		$sql = 'UPDATE 591_link_data SET 5_name = "'.$name.'",5_company = "'.$company.'",5_cell_phone = "'.$cell_phone.'",5_serve_area = "'.$serve_area.'",5_mail = "'.$mail.'" WHERE 5_id = '.$row['5_id'];		
		mysql_query($sql);		
	}	
}

echo 'done';			
?>
<body>
</body>
</html>

<?php
//抓591 id
function get_591_id($para_totalRows)
{
	//總頁數
	$total_pages = ceil($para_totalRows / 15);
	
	$j = 1;
	
	for($i = 1;$i <= $total_pages;$i++)
	{
		$para_firstRow = ($i - 1) * 15;
		echo '執行第'.$i.' 頁<br />';
		$target_url = 'http://www.591.com.tw/index.php?firstRow='.$para_firstRow.'&totalRows='.$para_totalRows.'&?&m=0&o=12&module=shop&action=list';
		$page_data = exec_curl($target_url);
		
		//找出該頁每個人的id
		preg_match_all("/<li link=\"(.*?)\" class/",$page_data,$link_data);
				
		foreach($link_data[1] as $row)
		{	
			$sql = "SELECT 5_id FROM 591_link_data WHERE 5_name_id = '".$row."' LIMIT 1";	
			$res = mysql_query($sql);	
			$rows = mysql_num_rows($res);
			
			//如果沒有重覆
			if($rows != 1)
			{
				$sql = "INSERT INTO 591_link_data (5_name_id) VALUES ('".$row."')";
				mysql_query($sql);
			}
			else
			{
				//echo $j.'. page：'.$i.' 的'.$row.' 重覆了<br />';
				$j++;
			}
		}	
	}
}

function exec_curl($target_url)
{
	$ch = curl_init($target_url); 
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 	
	$res = curl_exec ($ch);
	curl_close ($ch);
	
	return $res;
}
?>