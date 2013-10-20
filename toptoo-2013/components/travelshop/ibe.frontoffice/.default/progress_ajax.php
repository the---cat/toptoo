<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? //trace($arResult) ?>
<div id="progress_ajax">
  <? /*<img class="progress-img" src="<?= $templateFolder."/images/wait.png" ?>" alt="" /> */?>
  <? $APPLICATION->IncludeComponent(
  "bitrix:main.include",
  "",
  Array(
    "AREA_FILE_SHOW" => "sect", 
    "AREA_FILE_SUFFIX" => "spinner", 
    "EDIT_MODE" => "html", 
    "EDIT_TEMPLATE" => "standart.php",
    "AREA_FILE_RECURSIVE" => "N" 
  )
); ?>
</div>