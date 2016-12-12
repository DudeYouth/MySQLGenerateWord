<?php

function list_tables($db) 
{ 
	$rs = mysqli_query($db,"SHOW TABLE status"); 
	$tables =[]; 
	while($v=mysqli_fetch_object($rs)){
		$tables[]=[
			'name'=>$v->Name,
			'comment'=>$v->Comment,
		];
	}
	mysqli_free_result($rs);
	return $tables; 
}
function connect(){
	$con=mysqli_connect("localhost","root","123456");
	if(!$con){
		die('Could not connect: ' . mysql_error());
	}
	mysqli_select_db($con,'weicai');
	return $con;
}
function list_columns($db,$tables){
	$str="<style>
			table tr td{
				border-right:1px solid #aaa;
				border-bottom:1px solid #aaa;
				padding:5px 0px;
				text-indent:1em;
			}
			table 
			{ 
				border-top:1px solid #aaa;
				border-left:1px solid #aaa;
				width:650px;
				color:#555;
			} 
		</style>";
	foreach($tables as $v){
		$str.="<table border='0' cellspacing='1' cellpadding='0'>";
		$str.="<tr style='font-weight:600;text-align:center;font-weight:blod;'><td colspan='5' >{$v['name']}({$v['comment']})</td></tr>";
		$str.="<tr style='font-weight:600;'><td>字段名</td><td>字段类型</td><td>约束</td><td>默认值</td><td>字段说明</td></tr>";
		$rs=mysqli_query($db,"show full columns from {$v['name']}");
		while($val=mysqli_fetch_object($rs)){
			$str.="<tr><td>{$val->Field}</td><td>{$val->Type}</td><td>".($val->Key=='PRI'?'主键':'')."</td><td>{$val->Default}</td><td>{$val->Comment}</td></tr>";
		}
		mysqli_free_result($rs);
		$str.='</table></br></br>';
	}
	return $str;
}
$db=connect();
$tables=list_tables($db);
$content=list_columns($db,$tables);
print_r($content);
$fp=fopen("weicai.doc","w+");
fwrite($fp,$content);
fclose($fp);
 ?>