<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// Подключение необходимый css и js файлов
if(!defined("__JQUERY_JS")) {
	define("__JQUERY_JS", true);
	$GLOBALS["APPLICATION"]->AddHeadScript($templateFolder."/js/jquery-1.7.2.min.js");
}

if(!defined("__BROWSER_JS")) {
	define("__BROWSER_JS", true);
	$GLOBALS["APPLICATION"]->AddHeadScript($templateFolder."/js/jquery.browser-2.3.min.js");
}

if(!defined("__PERCIFORMES_JS")) {
	define("__PERCIFORMES_JS", true);
	$GLOBALS["APPLICATION"]->AddHeadScript($templateFolder."/js/jquery.perciformes.js");
}

if(!defined("__TOOLTIP_JS")) {
	define("__TOOLTIP_JS", true);
	$GLOBALS["APPLICATION"]->AddHeadScript($templateFolder."/js/jquery.tooltip-1.3.js");
}

if(!defined("__JQUERY_UI_CSS")) {
	define("__JQUERY_UI_CSS", true);
	$GLOBALS["APPLICATION"]->SetAdditionalCSS($templateFolder."/style.php?file=".$templateFolder."/css/jquery-ui-1.8.10.custom.css");
}

if(!defined("__JQUERY_UI_JS")) {
	define("__JQUERY_UI_JS", true);
	$GLOBALS["APPLICATION"]->AddHeadScript($templateFolder."/js/jquery-ui-1.8.10.custom.min.js");
}

if(!defined("__FORMTOOLS_JS")) {
	define("__FORMTOOLS_JS", true);
	$GLOBALS["APPLICATION"]->AddHeadScript("/bitrix/js/ibe/formtools.js");
}

if(!defined("__TOOLS_JS")) {
	define("__TOOLS", true);
	$GLOBALS["APPLICATION"]->AddHeadScript("/bitrix/js/ibe/tools.js");
}

if(!defined("__TOOLS_CSS")) {
	define("__TOOLS_CSS", true);
	$GLOBALS["APPLICATION"]->SetAdditionalCSS($templateFolder."/style.php?file=".$templateFolder."/css/point.css");
}

if( $USE_AUTOCOMPLETE ) { // Если используется автозаполнение
  if(!defined("__AUTOCOMPLETE_JS")) {
  	define("__AUTOCOMPLETE_JS", true);
  	$GLOBALS["APPLICATION"]->AddHeadString(CIBECacheControl::RenderJSLink($templateFolder."/js/jquery.autocomplete.min.js"));
  }
  
  if(!defined("__AUTOCOMPLETE_CSS")) {
  	define("__AUTOCOMPLETE_CSS", true);
  	$GLOBALS["APPLICATION"]->AddHeadString(CIBECacheControl::RenderCSSLink($templateFolder."/style.php?file=".$templateFolder."/css/jquery.autocomplete.css"));
  }
}
?>