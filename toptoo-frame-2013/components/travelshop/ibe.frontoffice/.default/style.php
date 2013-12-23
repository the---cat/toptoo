<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

function __add_http_host_to_addr($html_code, $http_host)
{
	global $PATH;
	
	if ( !strlen($http_host) )
		$http_host =  'http://'.$_SERVER['HTTP_HOST'];
		
	$html_code = preg_replace('`url\(([^)]*)\)`U', 'url('.$http_host.$PATH.'/\1)', $html_code);
	$html_code = preg_replace('`url\(..\/([^)]*)\)`U', 'url('.$http_host.$PATH.'/\1)', $html_code);
	return $html_code;
}

// Формируем массив $arRequest["REQUEST"] с переданными данными: в элементе ["NAME"] хранится оригинальное значение, здесь NAME - имя параметра
foreach ( $_REQUEST as $k => $v )
{
	if( !is_array($v) )
	{
		$arRequest["REQUEST"][Trim($k)] = Trim($v);
	}
	else
	{
		foreach( $v as $kk => $vv )
		{
			$arRequest["REQUEST"][Trim($k)][Trim($kk)] = Trim($vv);
		}
	}
}

$filename = urldecode($_SERVER['DOCUMENT_ROOT'].$arRequest["REQUEST"]["file"]);
$filename = str_replace("?", "", $filename);
$DIR = dirname($filename);
$PATH = dirname(urldecode($arRequest["REQUEST"]["file"]));
$file = substr($filename, strlen($DIR)+1);

// Если имя файла задано в запросе и файл существует
if ( strlen($filename) && file_exists($filename) )
{
	// Считываем файл с диска и возвращаем в броузер
	
	$filesize = filesize($filename);
	$f = fopen($filename, "rb");
	$cur_pos = 0;
	$size = $filesize-1;
	
	if ($sapi=="cgi") header("Status: 200 OK"); else header("HTTP/1.1 200 OK");

	header("Content-Type: text/css; name=\"".$file."\"");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
	header("Expires: 0"); 
	header("Pragma: public");
	
	/*
	if( $handle = fopen($filename, "r") )
	{
		$contents = fread($handle);
		fclose($handle);
	}
	*/
	
	$contents = $APPLICATION->GetFileContent($filename);
	print __add_http_host_to_addr( $contents );
	
	die();
}
else
{
	if ($sapi=="cgi") header("Status: 404 OK"); else header("HTTP/1.1 404 OK");
}
?>