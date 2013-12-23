<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/ibe/classes/ibe/template_tools.php");

if ( !class_exists("frontofficeHelper") ) {
  class frontofficeHelper {
  
  	function renderButton($arr=array(), $align="") {
  		$default = array(
  			'class' => 'button',
  			'type' => 'button',
  		);
  		$arr = array_merge($default, $arr);
  		$input = '<input';
  		foreach($arr as $attr => $value) {
  			if($attr == 'class' && $align) $value .= ' float-'.$align;
  			$input .= ' '.$attr.'="'.$value.'"';
  		}
  		$input .= ' />';
  		return $input;
  	}
  	
  }
}
?>

<script type="text/javascript">
// <![CDATA[ вывод необходимых скриптов
var phpVars = {
	'LANGUAGE_ID': '<?=CTemplateToolsUtil::addslashes(LANGUAGE_ID) ?>',
	'SM_VERSION': '<?=CTemplateToolsUtil::addslashes(SM_VERSION) ?>',
	'FORMAT_DATE': '<?=CTemplateToolsUtil::addslashes(FORMAT_DATE) ?>',
	'FORMAT_DATETIME': '<?=CTemplateToolsUtil::addslashes(FORMAT_DATETIME) ?>',
	'opt_context_ctrl': <?=($aUserOpt["context_ctrl"] == "Y" ? "true" : "false") ?>,
	'cookiePrefix': '<?=CTemplateToolsUtil::addslashes(COption::GetOptionString("main", "cookie_name", "BITRIX_SM")) ?>',
	'titlePrefix': '<?=CTemplateToolsUtil::addslashes(COption::GetOptionString("main", "site_name", $_SERVER["SERVER_NAME"])) ?> - ',
	'messLoading': '<?=CTemplateToolsUtil::addslashes(GetMessage("tools_lib_loading")) ?>',
	'messNoData': '<?=CTemplateToolsUtil::addslashes(GetMessage("tools_lib_no_data")) ?>'
}
// ]]>
</script>

<?
// Подключение необходимых css и js файлов
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

/*
if ($USE_JQUERY_UI && !defined('__JQUERY_UI_JS')) {
  define('__JQUERY_UI_JS', true);
  $GLOBALS["APPLICATION"]->AddHeadScript($templateFolder."/js/jquery-ui-widget-datepicker.custom.js");
}

if ($USE_JQUERY_UI && !defined('__JQUERY_UI_CSS')) {
  define('__JQUERY_UI_CSS', true);
  $GLOBALS["APPLICATION"]->SetAdditionalCSS($templateFolder."/style.php?file=".$templateFolder."/css/ui-datepicker.css");
}
*/

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

if(!defined("__IBE_JS")) {
  define("__IBE_JS", true);
  $GLOBALS["APPLICATION"]->AddHeadScript("/bitrix/js/ibe/ibe_js.js");
}

if( $USE_AUTOCOMPLETE ) { // Если используется автозаполнение
  if(!defined("__AUTOCOMPLETE_JS")) {
    define("__AUTOCOMPLETE_JS", true);
    $GLOBALS["APPLICATION"]->AddHeadScript($templateFolder."/js/jquery.autocomplete.min.js");
  }
  
  if(!defined("__AUTOCOMPLETE_CSS")) {
    define("__AUTOCOMPLETE_CSS", true);
    $GLOBALS["APPLICATION"]->SetAdditionalCSS($templateFolder."/style.php?file=".$templateFolder."/css/jquery.autocomplete.css");
  }
}

?>
