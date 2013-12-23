<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// Использовать автозаполнение, если используются поля для ввода пунктов и разрешено автозаполнение
$USE_AUTOCOMPLETE = ( !count($arResult['points']) && $arParams["USE_AUTOCOMPLETE"] == "Y");
if( !defined("__HIGHSLIDE") ) { define('__HIGHSLIDE', false); }
if ( 'CHARTER' == $arParams['FARES_MODE'] && 
	( 	(count($arResult['select_countries_depart']['REFERENCE']) && count($arResult['select_points_depart']['REFERENCE']) ) ||
		(count($arResult['select_countries_arrival']['REFERENCE']) && count($arResult['select_points_arrival']['REFERENCE']) )
	) 
) { $USE_AUTOCOMPLETE = FALSE; } 
$USE_JQUERY_UI = true;

require_once(dirname(__FILE__).'/tools.php');
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/components/travelshop/ibe.frontoffice/templates/.default/template.php');
?>