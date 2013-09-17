<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<? include( GetLangFileName( $_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/components/travelshop/ibe.frontoffice/.default"."/lang/", "/template.php" ) ); ?>
<? 
if ( $arResult["processor"] && $arResult["processor"] != "form_order" ) {

  if ( $arResult["choose_trip"]["depart"] && $arResult["choose_trip"]["arrival"] ) {
    $departure = $arResult["choose_trip"]["depart"];
    $arrival = $arResult["choose_trip"]["arrival"];
  } elseif ( is_array($arResult['FLIGHT']) || is_array($arResult['ORDER']['FLIGHT']) ) {
    $flights = is_array($arResult['FLIGHT']) ? $arResult['FLIGHT'] : $arResult['ORDER']['FLIGHT'];
    $depFlight = reset($flights);
    $departure = $depFlight["DEPARTURE"]["LOC_NAME"];
    if ( $arResult["choose_trip"]["~raw"]["RT_OW"] == "OW" ){
      $arrFlight = end($flights);
    } else {
      for( $i = 0; $i < count($flights) - 1; $i++ ){
        if ( $flights[$i]["DIRECTION"] != $flights[$i+1]["DIRECTION"] ) {
          $arrFlight = $flights[$i];
          break;
        }
      }
    }
    $arrival = $arrFlight["ARRIVAL"]["LOC_NAME"];
  } else {
    list($city, $misc) = explode('(', $arResult["choose_trip"]["~raw"]["depart"], 2 );
    $departure = $city;
    list($city, $misc) = explode('(', $arResult["choose_trip"]["~raw"]["arrival"], 2 );
    $arrival = $city;
  }

  $minPriceMess = "";
  $akMess = "";
  if ( $arParams["FARES_MODE"] == "CHARTER" ) {
    if ( $arResult["processor"] == "offer_charter" ) {
      $akNames = "";
      foreach ( $arResult["LOGOS"] as $ak ) {
        $akNames .= ($ak["TITLE"] ? $ak["TITLE"] : $ak["IATACODE"] ) . ( $ak != end($arResult["LOGOS"]) ? ', ' : '' );
      }
      $akMess = GetMessageExtended("TS_FRONTOFFICE_CHARTER_DESCRIPTION_AK", array( 'AK_NAMES' => $akNames )) ;
      $APPLICATION->SetTitle( GetMessageExtended("TS_FRONTOFFICE_CHARTER_OFFER_TITLE", array(
        "DEPARTURE" => $departure,
        "ARRIVAL" => $arrival
      )));
      ?>
 <script type="text/javascript">
// <![CDATA[
if ( $('#avail_form').length ) {
  $('#avail_form .direction .flights th.flight > span').text('<?= GetMessage("TS_FRONTOFFICE_CHARTER_TH") ?>');
}
// ]]>
</script>     
    <? }
  } else {
    if ( $arResult["processor"] == "offer" ) {
      $minPrice = reset($arResult["OFFER"]);
      $minPriceMess = GetMessageExtended("TS_FRONTOFFICE_OPTIMAL_DESCRIPTION_MIN_PRICE", array( 'MIN_PRICE' => $minPrice["PRICE"]["ALT_CAPTION_AMOUNT"]) );
      $akMess = GetMessageExtended("TS_FRONTOFFICE_OPTIMAL_DESCRIPTION_AK", array( 'AK_NUM' => count($arResult["LOGOS"]), 'ARRIVAL' => $arrival ) );
      $offerTitle = GetMessageExtended("TS_FRONTOFFICE_OPTIMAL_OFFER_TITLE", array(
        "DEPARTURE" => $departure,
        "ARRIVAL" => $arrival,
        "MIN_PRICE" => $minPrice["PRICE"]["ALT_CAPTION_AMOUNT"]
      ));
      $instruction = GetMessageExtended("TS_FRONTOFFICE_OPTIMAL_OFFER_INSTRUCTION", array(
        "DEPARTURE" => $departure,
        "ARRIVAL" => $arrival 
      )) ?>
<script type="text/javascript">
// <![CDATA[
if ( $('#ts_ag_reservation .header .instruction').length ) {
  $('#ts_ag_reservation .header .instruction').text('<?= $instruction ?>');
}
$.oAjaxSteps.cfg.title_map.offer = '<?= $offerTitle ?>';
// ]]>
</script>
    <? }
  }
  if ( $arrival && $departure ) {
    $description = GetMessageExtended("TS_FRONTOFFICE_" . $arParams["FARES_MODE"] . "_DESCRIPTION", array(
      "DEPARTURE" => $departure,
      "ARRIVAL" => $arrival,
      "AK" => $akMess,
      "MIN_PRICE" => $minPriceMess
    ));
    $h1 = GetMessageExtended("TS_FRONTOFFICE_" . $arParams["FARES_MODE"] . "_H1", array(
      "DEPARTURE" => $departure,
      "ARRIVAL" => $arrival 
    ));
  }
  
  if ( strlen($description) ) { $APPLICATION->SetPageProperty("description", $description); }
  if ( strlen($h1) ) {
  ?>
<script type="text/javascript">
// <![CDATA[
if ( $('#page-main .content-header>h1').length ) {
  $('#page-main .content-header>h1').text('<?= $h1 ?>');
}
// ]]>
</script>
<? }
} ?>