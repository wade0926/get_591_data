<?php
require_once("../db.php");
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
$para_firstRow = 0;
$para_totalRows = 15904;
$target_url = 'http://www.591.com.tw/index.php?firstRow='.$para_firstRow.'&totalRows='.$para_totalRows.'&?&m=0&o=12&module=shop&action=list';
$page_data = get_link($target_url);

//找出各個連結
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
		
	}
}

?>
<body>
</body>
</html>

<?php
function get_link($target_url)
{
	$ch = curl_init($target_url); 
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 	
	$res = curl_exec ($ch);
	curl_close ($ch);
	
	return $res;
}
?>