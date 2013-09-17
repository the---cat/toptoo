<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? if ( defined("SHOW_404") || SHOW_404 == "Y") { $APPLICATION->IncludeFile("404.php"); return; } ?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?= LANG_CHARSET;?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/> <? // Запрет включения режима совместимости в IE ?>
<meta name="robots" content="all" />
<? $APPLICATION->ShowMeta("keywords") ?>
<? $APPLICATION->ShowMeta("description") ?>

<title><? $APPLICATION->ShowTitle(); ?><?=$arLang["SITE_NAME"] ? " | ".$arLang["SITE_NAME"] : "" ?></title>
<? define("__JQUERY_JS", true); ?>
<? define("__BROWSER_JS", true); ?>
<? define("__TOOLTIP_JS", true); ?>
<?=CIBECacheControl::RenderJSLink('/bitrix/templates/'.SITE_TEMPLATE_ID.'/js/jquery-1.7.2.min.js'); ?>
<?=CIBECacheControl::RenderJSLink('/bitrix/templates/'.SITE_TEMPLATE_ID.'/js/jquery.browser-2.3.min.js'); ?>
<?=CIBECacheControl::RenderJSLink('/bitrix/templates/'.SITE_TEMPLATE_ID.'/js/jquery.tooltip-1.3.js'); ?>
<? method_exists($APPLICATION, 'ShowHeadStrings') ? $APPLICATION->ShowHeadStrings() : ''?>
<? method_exists($APPLICATION, 'ShowHeadScripts') ? $APPLICATION->ShowHeadScripts() : ''?>
<link rel="shortcut icon" href="/favicon.ico" />
<? $APPLICATION->ShowCSS() ?>

<? $APPLICATION->ShowProperty( "print_link" ) ?>
<script type="text/javascript">
// <![CDATA[
function tooltip(selector) {
  switch (typeof selector) {
    case 'object':
      var titles = selector.find('[title]');
      break;

    case 'string':
      selector = $(selector);
      var titles = selector.find('[title]');
      break;

    default: // undefined
      var titles = $('[title]');
      break;
  }

  if (titles.length) {
    titles.tooltip({
      bodyHandler: function() {
        return $('<div class="arr"></div><div class="inner">'.concat(this.tooltipText, '</div>'));
      },
      showURL: false,
      track: true,
      top: 20,
      left: -75,
      width: 160,
      fixPNG: true
    });
  }
}
// ]]>
</script>
</head>
<body>
<? $APPLICATION->ShowPanel();?>

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
<div id="middle" class="content_wrapper clearfix">