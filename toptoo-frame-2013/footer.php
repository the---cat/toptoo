<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? if ( defined("SHOW_404") || SHOW_404 == "Y") { return; } ?>
<div class="sect_bottom">
<? $APPLICATION->IncludeComponent(
  "bitrix:main.include",
  "",
  Array(
    "AREA_FILE_SHOW" => "sect", 
    "AREA_FILE_SUFFIX" => "bottom", 
    "EDIT_MODE" => "html", 
    "EDIT_TEMPLATE" => "standart.php",
    "AREA_FILE_RECURSIVE" => "N" 
  )
); ?>
</div>
<script type="text/javascript">
// <![CDATA[
tooltip();
// ]]>
</script>
</body>
</html>