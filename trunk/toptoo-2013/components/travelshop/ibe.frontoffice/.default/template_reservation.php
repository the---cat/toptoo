<?php
// Выводим логотипы авиакомпаний
if ($arResult['LOGOS']){
  $logoStyles = '';
  $logoStyles .= '<style type="text/css">';
  foreach ($arResult['LOGOS'] as $arCompany){
    $logoStyles .=  ' .logo-small-'.$arCompany['IATACODE'].'{background-image:url('.$arCompany['LINK']['SMALL'].');}'.
            ' .logo-normal-'.$arCompany['IATACODE'].'{background-image:url('.$arCompany['LINK']['NORMAL'].');}';
  }
  $logoStyles .= '</style>';
  $APPLICATION->AddHeadString($logoStyles, true);
}
?>
<? if(!$arResult['~PRINT']): // не выводить при печати ?>
  <? /* `ts_ag_reservation_ajax` не используется */ ?>
  <div id="<?= $arResult[ "~IS_AJAX_MODE" ] ? "ts_ag_reservation_ajax" : "ts_ag_reservation" ?>">
<? endif; ?>
<?

/* Если есть ошибка - нужно вывести */
if ( isset( $arResult['display_error'] )
    && strlen( $arResult['display_error'] )
 ) {
  // Присутствует структурированный вариант  
  if (isset($arResult['ERROR'])) {
    $arError = array();

    foreach ($arResult['ERROR'] as $error) {
      $arError[] = ( 0 !== $error['CODE'] && GetMessage( 'TS_'.$error['TYPE'].'_ERROR_'.$error['CODE'] ) ? GetMessage( 'TS_'.$error['TYPE'].'_ERROR_'.$error['CODE'] ) : $error['TEXT']);
    }
    
    $sError = htmlspecialchars( implode( '<br /><br />', $arError ) );
  }
  else {
    $sError = htmlspecialchars( $arResult['display_error'] );
  }

  if ( $arParams['~IBE_AJAX_MODE'] === 'Y' ) {
    /* Первое подключение компонента  */
    /* Ошибка при открытии диплинка  */
    if ( !$arParams['IBE_SECONDARY_CALL'] || strlen( $_SESSION['deeplink_level'] ) ) {
      ShowError( $sError );
    }
  } else {
    ShowError( $sError );
  }
}

/* Отобразить примечания */
/* Для AJAX-режима можно */
if ( isset( $arResult['display_notice'] ) && strlen( $arResult['display_notice'] ) ) {
  ShowNote( $arResult['display_notice'] );
}

/* Модификация для update_personal */
if (isset($_POST['actions']) && strpos( $_POST['actions'], 'update_personal' ) !== false ) {
  $pass_count = $GLOBALS['COMPONENT_SESSION']['choose_trip']['adult'] + $GLOBALS['COMPONENT_SESSION']['choose_trip']['child'] + $GLOBALS['COMPONENT_SESSION']['choose_trip']['infant'];
  for($i = 0; $i < $pass_count; $i++)
  {
    if(isset($_POST['PSGRDATA_FFAK_VISIBLED_'.$i]))
    {
      $_SESSION['psgr_ffak_visibled'][$i] = $_POST['PSGRDATA_FFAK_VISIBLED_'.$i] == "true";
    }
  }
}

if ( ibe_check_component_params( 'USE_MERGED_STEPS', 'Y' ) ) {
  if ( !CIBEFrontofficeControllerManager::DoEvent( "BeforeTemplate", $this, $arParams, $arResult ) ) {
    return;
  }
}

/* Подключение шаблона шага */
if (file_exists($_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".SITE_TEMPLATE_ID."/components/".str_replace(":","/",$component->GetName())."/".$this->GetName()."/".$arResult[ "processor" ].".php")) {
  require($_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".SITE_TEMPLATE_ID."/components/".str_replace(":","/",$component->GetName())."/".$this->GetName()."/".$arResult[ "processor" ].".php");

} elseif (file_exists($_SERVER["DOCUMENT_ROOT"].$templateFolder."/".$arResult[ "processor" ].".php")){
  require( $_SERVER["DOCUMENT_ROOT"].$templateFolder."/".$arResult[ "processor" ].".php" );

} elseif(file_exists($_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/components/travelshop/ibe.frontoffice/templates/.default/".$arResult[ "processor" ].".php")) {
  require( $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/components/travelshop/ibe.frontoffice/templates/.default/".$arResult[ "processor" ].".php" );
}

if ( ibe_check_component_params( 'USE_MERGED_STEPS', 'Y' ) ) {
  if ( !CIBEFrontofficeControllerManager::DoEvent( "AfterTemplate", $this, $arParams, $arResult ) ) {
    return;
  }
}

?>
<div id="ts_ag_ga_container">
<script type="text/javascript">
  $(document).ready(function (){
    $("#ts_ag_reservation .button_buy").each(function (){ // Выбор перевозки
      $( this ).click(function() {
        yaCounter17328109.reachGoal("1_vybor_bileta");
        _gaq.push(["_trackEvent", "click", "1_vybor_bileta"]);
        // Новые коды статистики https://code.google.com/p/toptoo/issues/detail?id=58
        yaCounter17328109.reachGoal('dalee_odin');
        _gaq.push(['_trackEvent', 'Dalee', 'odin']);
        return true;
      });
    });
    $("#ts_ag_reservation form#personal").submit(function() { // Ввод данных о пассажирах
      yaCounter17328109.reachGoal("2_vvod_dannyh");
      _gaq.push(["_trackEvent", "click", "2_vvod_dannyh"]);
      // Новые коды статистики https://code.google.com/p/toptoo/issues/detail?id=58
      yaCounter17328109.reachGoal('kupit_dva');
      _gaq.push(['_trackEvent', 'Kupit', 'dva']);
      return true;
    });
    $("#ts_ag_reservation form#precommit").submit(function() { // Создание заказа
      yaCounter17328109.reachGoal("3_oplata");
      _gaq.push(["_trackEvent", "click", "3_oplata"]);
      // Новые коды статистики https://code.google.com/p/toptoo/issues/detail?id=58
      yaCounter17328109.reachGoal('kupit_oplata'); return true;
      _gaq.push(['_trackEvent', 'button', 'kupit_oplata']);
      return true;
    });
  });
</script>
</div>
<? if (!$arResult['~PRINT']): // не выводить при печати ?>
<script type="text/javascript">/* <![CDATA[ */
<? if ( $arParams['USE_MERGED_STEPS'] === 'Y' && $arParams['~IBE_AJAX_MODE'] === 'Y' ): ?>
  if ( typeof( $.oAjaxSteps ) != 'undefined' ) {
    $.oAjaxSteps.init({
      'cur_step': '<?= $arResult['processor'] ?>',
      'cur_inst': '<?= $GLOBALS['COMPONENT_SESSION']['__id'] ?>'
    });
  }
  <? endif; ?>
/* ]]> */</script>
<div class="clearfix"></div>
</div>
<? endif; ?>