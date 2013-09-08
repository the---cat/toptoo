<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? IncludeTemplateLangFile(__FILE__); ?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?= LANG_CHARSET;?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/> <? // Запрет включения режима совместимости в IE ?>
<meta name="robots" content="all" />
<? $APPLICATION->ShowMeta("keywords") ?>
<? $APPLICATION->ShowMeta("description") ?>

<title><?=$arLang["SITE_NAME"] ? $arLang["SITE_NAME"]." &#151; " : "" ?><? $APPLICATION->ShowTitle(); ?></title>
<? define("__JQUERY_JS", true); ?>
<? define("__BROWSER_JS", true); ?>
<? define("__TOOLTIP_JS", true); ?>
<?=CIBECacheControl::RenderJSLink('/bitrix/templates/'.SITE_TEMPLATE_ID.'/js/jquery-1.5.1.min.js'); ?>
<?=CIBECacheControl::RenderJSLink('/bitrix/templates/'.SITE_TEMPLATE_ID.'/js/jquery.browser-2.3.min.js'); ?>
<?=CIBECacheControl::RenderJSLink('/bitrix/templates/'.SITE_TEMPLATE_ID.'/js/jquery.tooltip-1.3.js'); ?>
<? method_exists($APPLICATION, 'ShowHeadStrings') ? $APPLICATION->ShowHeadStrings() : ''?>
<? method_exists($APPLICATION, 'ShowHeadScripts') ? $APPLICATION->ShowHeadScripts() : ''?>
<link rel="shortcut icon" href="/favicon.ico" />
<? $APPLICATION->ShowCSS() ?>
</head>
<body>
<? $APPLICATION->ShowPanel();?>

<div id="page">
<? // Подключаем файл с параметрами загрузки шапки и подвала
$APPLICATION->IncludeComponent(
  "bitrix:main.include",
  "",
  Array(
    "AREA_FILE_SHOW" => "sect",
    "AREA_FILE_SUFFIX" => "parameters",
    "EDIT_MODE" => "text",
    "EDIT_TEMPLATE" => "standart.php",
    "AREA_FILE_RECURSIVE" => "Y"
  )
);
// Загружаем удаленную шапку
if ( isset($GLOBALS["HEADER_INCLUDE_URL"]) && strlen($GLOBALS["HEADER_INCLUDE_URL"]) ) {
  $APPLICATION->IncludeFile( "include.php", array( "URL" => $GLOBALS["HEADER_INCLUDE_URL"], "CACHE_TIME" => isset($GLOBALS["HEADER_INCLUDE_CACHE_TIME"]) ? $GLOBALS["HEADER_INCLUDE_CACHE_TIME"] : "0" ) );
}
?>
  <div id="middle" class="width_limit">
    <div class="content_wrapper clearfix">
      <div class="not-found">
        <div class="title"><?=GetMessage('404_TITLE') ?></div>
        <div class="description"><?=GetMessage('404_DESC') ?></div>
      </div>
    </div>
  </div>
<?	
// Загружаем удаленный подвал
if ( isset($GLOBALS["FOOTER_INCLUDE_URL"]) && strlen($GLOBALS["FOOTER_INCLUDE_URL"]) ) {
	$APPLICATION->IncludeFile( "include.php", array( "URL" => $GLOBALS["FOOTER_INCLUDE_URL"], "CACHE_TIME" => isset($GLOBALS["FOOTER_INCLUDE_CACHE_TIME"]) ? $GLOBALS["FOOTER_INCLUDE_CACHE_TIME"] : "0" ) );
}
?>	
</div>
</body>
</html>