<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<? if ( defined("SHOW_404") || SHOW_404 == "Y") { return; } ?>
</div>

<?	
// Загружаем удаленный подвал
if ( isset($GLOBALS["FOOTER_INCLUDE_URL"]) && strlen($GLOBALS["FOOTER_INCLUDE_URL"]) ) {
	$APPLICATION->IncludeFile( "include.php", array( "URL" => $GLOBALS["FOOTER_INCLUDE_URL"], "CACHE_TIME" => isset($GLOBALS["FOOTER_INCLUDE_CACHE_TIME"]) ? $GLOBALS["FOOTER_INCLUDE_CACHE_TIME"] : "0" ) );
}
?>

<? $APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "sect", 
		"AREA_FILE_SUFFIX" => "counter", 
		"EDIT_MODE" => "html", 
		"EDIT_TEMPLATE" => "standart.php",
		"AREA_FILE_RECURSIVE" => "Y" 
	)
); ?>
<script type="text/javascript">
// <![CDATA[
tooltip();
// ]]>
</script>
</body>
</html>