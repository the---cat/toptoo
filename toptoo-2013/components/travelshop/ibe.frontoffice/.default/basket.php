<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->IncludeComponent('travelshop:ibe.basket', '', array(
  'PRECOMMIT' => 'Y',
  "USE_MERGED_STEPS" => 'Y',
  "IBE_AJAX_MODE" => $arParams[ 'IBE_AJAX_MODE' ],
  "ORDID" => $arResult['ORDER']['~ID'],
));
?>