<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<div class="form_tools clearfix">
  <div class="c-next">
  <? $APPLICATION->IncludeComponent
  ( 'travelshop:ibe.currency'
  , ''
  , array
    ( 'CURRENCY_DEFAULT' => $arResult['CURRENCY']
    , 'USE_MERGED_STEPS' => defined('IBE_USE_MERGED_STEPS') && IBE_USE_MERGED_STEPS ? IBE_USE_MERGED_STEPS : 'N'
    , 'IBE_AJAX_MODE' => defined('IBE_AJAX_MODE') && IBE_AJAX_MODE ? IBE_AJAX_MODE : 'N'
    )
  ); ?>
  </div>
</div>
<form action="<?= $arResult[ "ACTION" ] ?>" id="preview" method="post" name="preview" onsubmit="<?= $arResult[ "ONSUBMIT" ] ?>">
  <?=$arResult['SCRIPT'] ?>
  <? $arResult[ "ORDER" ] = array();
	$arResult[ "ORDER" ][ "BASKET" ] =& $arResult[ "BASKET" ];
	$arResult[ "ORDER" ][ "FLIGHT" ] =& $arResult[ "FLIGHT" ];
	$arResult[ "ORDER" ][ "bShowHeader" ] = false;
	$arResult[ "ORDER" ][ "~IS_CHARTER" ] = $arResult[ "~IS_CHARTER" ]; ?>
  
  <? $CheckboxServicesCheckedSum = 0;
  $bCheckboxServicesSum = 0;
  $bCheckboxServices = array(); // Массив с услугами, отображаемыми в виде отдельного блока с чекбоксом
  foreach ($arResult['ORDER']['BASKET']['PRODUCT'] as $Product) {
    // Если продукт является услугой, которая должна отображаться чекбоксом
    if ( $Product['~SERVICE'] && "CHECKBOX" == ToUpper($Product['SSRTYPE']) ) {
      $bCheckboxServices[] = $Product;
      $bCheckboxServicesSum += $Product['~SUM_PRICE'];
      if ( $Product['CHECKED'] ) {
        $CheckboxServicesCheckedSum += $Product['~SUM_PRICE'];
      }
    }
  } ?>

  <div class="order">
  <? // Перелеты ?>
  <div class="flights">
  <? foreach( $arResult['DIRECTIONS'] as $directionKey => $direction ): ?>
    <? $diretion_name =  $directionKey == 0 ? 'outbound' : 'inbound'; 
    $deparure = $arResult['FLIGHT'][reset($direction['FLIGHTS'])];
    $arrival = $arResult['FLIGHT'][end($direction['FLIGHTS'])];
    ?>
    <? $stops = count($direction['FLIGHTS']>1) && is_array($arrival['STOP_DURATION']) ? ' with_stops' : ''  ?>
    <table class="direction <?= $diretion_name ?><?= $stops ?>">
        <tbody class="direction_caption"><tr>
          <th class="dir_name" colspan="4">
            <span class="title"><?= GetMessage('TS_FRONTOFFICE_STEP3_ORDER_' . ToUpper($diretion_name) ) ?></span>
            <span class="route"><?= $arResult['FLIGHT'][$direction['FLIGHTS'][0]]['DEPARTURE']['LOC_NAME'] ?>&nbsp;&mdash;
              <?= $arResult['FLIGHT'][$direction['FLIGHTS'][count($direction['FLIGHTS'])-1]]['ARRIVAL']['LOC_NAME'] ?></span>
            </th>
            <th class="companies" colspan="3">
              <? foreach ( $direction['CARRIERS'] as $carrier ) { ?>
                <?= count($direction['CARRIERS']) > 1 && $carrier != end($direction['CARRIERS']) ? $carrier['TITLE'] . ', ' : $carrier['TITLE'] ?>
              <? } ?>
            </th>
            <th class="time_info" colspan="2">
              <? list($hh, $mm) = explode( ':', $direction['TOTAL_FLTIME'] ); ?>
              <?= GetMessage('TS_FRONTOFFICE_STEP2_OFFER_FLIGHT_DURATION') ?>
              <span class="time"><?= ( floor($hh) ? floor($hh) . '&nbsp;' . GetMessage('IBE_FRONTOFFICE_H') : '' ) . ( floor($hh) && floor($mm) ? '&nbsp;' : '' ) . ( floor($mm) ? floor($mm) . '&nbsp;' . GetMessage('IBE_FRONTOFFICE_MIN') : '' )?></span>
            </th>
        </tr>
      </tbody>
      <? foreach ( $direction['FLIGHTS'] as $fk ): ?>
        <? $flight = $arResult['FLIGHT'][$fk] ?>
        <? if( !empty( $flight['~OAK'] ) && $flight['~OAK'] != $flight['~AK'] ) { $oak = true; } else { $oak = false; } ?>
      <tbody class="flight<?= $flight == $deparure ? ' first' : '' ?><?= $flight == $arrival ? ' last' : '' ?>">
        <? if ( is_array($flight['STOP_DURATION']) ) { ?>
          <? //trace ($flight['SEGMENTS'][$seg_ct]);
            list($ah, $am) = explode ( ':', $arrival['ARRIVAL']['TIME'] );
            list($dh, $dm) = explode ( ':', $deparure['DEPARTURE']['TIME'] );
            $arrTime = intval($ah)*60 + intval($am);
            $depTime = intval($dh)*60 + intval($dh);
            $night = ( 
              ( $arrTime > 23*60 ) ||
              ( $arrTime < 5*60 ) ||
              ( $depTime < 7*60 ) ||
              ( $deparure['DEPARTURE']['DATE'] != $arrival['ARRIVAL']['DATE'] )
            ) ? true : false;
            $long = intval($flight['STOP_DURATION']['~HOURS']) > 3 ? true : false; ?>
          <tr class="stop_wrap">
            <td>&nbsp;</td>
            <td colspan="8"> 
              <div>
                <div class="stop<?= ( $night ? ' night' : '' ) . ( $long ? ' long' : '' ) ?>">
                  <?= $long ? GetMessage('TS_FRONTOFFICE_STEP2_OFFER_STOPOVER_LONG') : '' ?>
                  <?= $night ? GetMessage('TS_FRONTOFFICE_STEP2_OFFER_STOPOVER_NIGHT') : '' ?>
                  <?= GetMessage('TS_FRONTOFFICE_STEP2_OFFER_STOPOVER') ?>
                  <?= 
                  ( $flight['STOP_DURATION']['~HOURS'] ? $flight['STOP_DURATION']['~HOURS'] . '&nbsp;' . GetMessage('TS_FRONTOFFICE_STEP2_OFFER_STOPOVER_H') : '' ) .
                  ( $flight['STOP_DURATION']['~HOURS'] && $flight['STOP_DURATION']['~MINUTES'] ? '&nbsp;' : '' ) .
                  ( $flight['STOP_DURATION']['~MINUTES'] ? $flight['STOP_DURATION']['~MINUTES'] . '&nbsp;' . GetMessage('TS_FRONTOFFICE_STEP2_OFFER_STOPOVER_MIN') : '')
                  ?>
                </div>
              </div>
            </td>
          </tr>
          <tr class="stop_wrap_add"><td>&nbsp;</td><td colspan="8">&nbsp;</td></tr>
          <? } ?>
        <tr class="top">
        <?  $oak = ( !empty( $flight['~OAK'] ) && $flight['~OAK'] != $flight['~AK'] ); $oakClass = ''; $oakCode = '';
        if( $arResult['LOGOS'] ){
              $akTitle = $flight['TITLE'] ? $flight['TITLE'] : $flight['~AK']; ?>
        <td class="logo" rowspan="2"><div class="logo logo-normal-<?= $arResult['LOGOS'][$flight['~AK']]['IATACODE'] ?>" <?= $akTitle ? ' title="' . $akTitle . '"' : '' ?>>&nbsp;</div></td>
        <? } ?>
        <td class="time"><?= $flight['DEPARTURE']['TIME'] ?></td>
        <td class="point"><?= $flight['DEPARTURE']['LOC_NAME'] ?></td>
        <td class="separator" rowspan="2">&rarr;</td>
        <td class="time"><?= $flight['ARRIVAL']['TIME'] ?></td>
        <td class="point"><?= $flight['ARRIVAL']['LOC_NAME'] ?></td>
        <td class="duration" rowspan="2">
          <? list ($hh, $mm) = explode( ':', $flight['FLTIME']) ?>
          <?= GetMessage('TS_FRONTOFFICE_STEP2_OFFER_FLIGHT_DURATION') ?>
          <?= ( floor($hh) ? floor($hh) . '&nbsp;' . GetMessage('IBE_FRONTOFFICE_H') . '&nbsp;' : '' ) . ( floor($mm) ? floor($mm) . '&nbsp;' . GetMessage('IBE_FRONTOFFICE_MIN') : '' )?>
        </td>
        <td class="class" rowspan="2"><?= $flight['SERVICE_CLASS'] ?></td>
        <td class="flight">
          <?= $flight['~AK'] . '-' . $flight['NUMBER'] ?><? if( $oak ) { ?><sup class="oak" title="<?=GetMessage("TS_FRONTOFFICE_STEP2_OFFER_OAK_TITLE") ?> <?= $flight['OAK'] ?>">*</sup><? } ?>
        </td>
      </tr>
      <tr class="bottom">
        <td class="date">
          <?= floor($flight['DEPARTURE']['DAY']) . '&nbsp;' . GetMessage('IBE_FRONTOFFICE_MONTH_SHORT_' . $flight['DEPARTURE']['MONTH']) . ', ' . GetMessage('IBE_FRONTOFFICE_DOW_SHORT_' . $flight['DEPARTURE']['WEEKDAY']) ?>
        </td>
        <td class="point"><?= ($flight['DEPARTURE']['APT_NAME'] ? $flight['DEPARTURE']['APT_NAME'] . ', ' : '' ) . $flight['DEPARTURE']['~APT_CODE'] ?></td>
        <td class="date">
          <?= floor($flight['ARRIVAL']['DAY']) . '&nbsp;' . GetMessage('IBE_FRONTOFFICE_MONTH_SHORT_' . $flight['ARRIVAL']['MONTH']) . ', ' . GetMessage('IBE_FRONTOFFICE_DOW_SHORT_' . $flight['ARRIVAL']['WEEKDAY']) ?>
        </td>
        <td class="point"><?= ($flight['ARRIVAL']['APT_NAME'] ? $flight['ARRIVAL']['APT_NAME'] . ', ' : '' ) . $flight['ARRIVAL']['~APT_CODE'] ?></td>
        <td class="plane"><?= $flight['PLANE_NAME'] ?></td>
        </tr>
      </tbody>
      <? endforeach; ?>
    </table>
  <? endforeach; ?>
    <table>
      <tr>
        <td class="rules_and_conditions">
          <div class="local_time"><span class="icon"></span> <?= GetMessage('TS_FRONTOFFICE_STEP3_ORDER_LOCAL_TIME') ?></div>
          <div class="upt_info">
            <span class="icon"></span> <?= GetMessage('TS_FRONTOFFICE_STEP3_ORDER_FARE_RULES') ?>
            <? foreach ( $arResult['FARE_CODES'] as $FARE_CODE => $AUTO_HTTP_LINK ): ?>
            <a href="javascript:void(0)" onclick="window.open('<?= $AUTO_HTTP_LINK ?>', 'upt', 'toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=1, resizable=1, width=600, height=350')"><?= $FARE_CODE ?></a>
            <? endforeach; ?>
          </div>
        </td>
        <td class="rules_and_conditions">
          <? if ( strlen( $arResult['PENALTY'] ) ): ?>
          <div class="return-policy"><span class="icon"></span> <?= $arResult['PENALTY']; ?></div>
          <? endif; ?>
          <? if ( strlen( $arResult['TIMELIMIT_STRING'] ) ): ?>
          <div class="timelimit"><span class="icon"></span> <?= GetMessage('TS_FRONTOFFICE_STEP3_ORDER_TIMELIMIT') ?>
            <?= $arResult['~TIMELIMIT'] ?  ( gmdate('j', $arResult['~TIMELIMIT']) . '&nbsp' . GetMessage('IBE_FRONTOFFICE_MONTH_GEN_' . gmdate('n', $arResult['~TIMELIMIT'])) . '&nbsp;' . gmdate('Y', $arResult['~TIMELIMIT']) . ' ' . gmdate('G:i', $arResult['~TIMELIMIT']) ) : $arResult['TIMELIMIT_STRING'] ?></div>
          <? endif; ?>
          <? if ($arResult['TRANSIT_VISA_REQUIRED']): ?>
          <div class="transit_visa_required"><span class="icon"></span> <?= GetMessage('IBE_PREVIEW_TRANSIT_VISA_REQUIRED'); ?></div>
          <? endif; ?>
        </td>
        <td class="price" id="total_price">
          <?//= $arResult['ORDER']['BASKET']['TOTAL_PRICE_NO_SSR'] ? $arResult['ORDER']['BASKET']['TOTAL_PRICE_NO_SSR'] : $arResult['ORDER']['BASKET']['ALT_TOTAL_PRICE'] ?>
          <?= $arResult['ORDER']['BASKET']['TOTAL_PRICE_WITH_PAYMENT_FEE'] ?>
        </td>
      </tr>
    </table>
    </div>
  <? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/ibe/classes/js_lang/ibe_js.php"); ?>
  <?=GetIbeJsStrings(); ?>
  </div>

  <? if ( count($bCheckboxServices) ): // Выводим в виде чекбокса соответствующие услуги ?>
  <? //trace ($bCheckboxServices) ?>
  <div class="order_services">
    <h3 class="info_caption"><?=GetMessage('IBE_TITLE_SERVICES')?></h3>
    <div class="service_agree clearfix">
    <? foreach ( $bCheckboxServices as $Service ): ?>
      <div class="service">
        <div class="service_info">
        <? if($Service['IMAGE_URL']): ?>
          <span class="img"><img alt="<?=$Service['NAME'] ?>" src="<?=$Service['IMAGE_URL'] ?>" /></span>
        <? endif; ?>
          <span class="service-name">
          <? if($Service['DESCRIPTION_URL']): ?>
            <a href="<?=$Service['DESCRIPTION_URL'] ?>"><?=$Service['NAME'] ?></a>
          <? else: ?>
            <?=$Service['NAME'] ?>
          <? endif; ?>
          </span>
        </div>
        <div id="product_<?= $Service['IB_PROP_ID'] ?>">
        <? foreach( $Service['FIELDS'] as $Field ): ?>
          <input type="hidden" name="<?= $Field['NAME'] ?>" value="<?= $Field['VALUE'] ?>" />
        <? endforeach; ?>
          <label class="product" for="service_<?= $Service['IB_PROP_ID'] ?>">
            <span class="prew_text"><?= $Service['PREVIEW_TEXT'] ?></span>
            <input<?= $Service['CHECKED'] ? ' checked="checked"' : ''?> id="service_<?= $Service['IB_PROP_ID'] ?>" name="<?= $Service['IB_PROP_ID'] ?>" type="checkbox" onclick="if ( this.checked ) {
                  $('#product_<?= $Service['IB_PROP_ID'] ?> input').each(function(){ $(this).attr('value','1'); });
                  <? /*
                  iTotalPrice += <?=intval($Service['~BASE_SUM_PRICE'])?>;
                  $('#total_price').text(GetFormattedCurrency(iTotalPrice));
                  */ ?>
                } else {
                  if ( <?= array_key_exists('ALERT_FOR_CLOSE_TEXT', $Service) ? "confirm('{$Service['ALERT_FOR_CLOSE_TEXT']}')" : "true" ?> ) {
                    $('#product_<?= $Service['IB_PROP_ID'] ?> input').each(function(){ $(this).attr('value','N'); });
                    <? /*
                     iTotalPrice -= <?=intval($Service['~BASE_SUM_PRICE'])?>;
                    $('#total_price').text(GetFormattedCurrency(iTotalPrice));
                    */ ?>
                  } else {
                    this.checked = true;
                    <? /*
                    iTotalPrice += <?=intval($Service['~BASE_SUM_PRICE'])?>;
                    $('#total_price').text(GetFormattedCurrency(iTotalPrice));
                    */ ?>
                  }
                }" />
            <span class="price"><?= $Service['SUM_PRICE'] ?></span>
          </label>
        </div>
      </div>
    <? endforeach; ?>
    </div>
  </div>
  <? endif; // if ( count($bCheckboxServices) ) ?>
  <? //trace($arResult[ "ORDER" ][ "BASKET" ]) ?>

  <? /* Изменение для TSH-10681 */ 
  if ( isset( $arResult['BUTTONS'] ) && ( ( isset( $arResult['BUTTONS']['BACK'] ) && $arParams['USE_MERGED_STEPS'] !== 'Y' ) || ( isset( $arResult['BUTTONS']['FORWARD'])))): ?>
  <div class="buttons clearfix">
    <? if ( isset( $arResult['BUTTONS']['BACK'] ) && $arParams['USE_MERGED_STEPS'] !== 'Y' ): ?>
    <div class="c-back"><?= CTemplateToolsUtil::RenderField( $arResult['BUTTONS']['BACK'] ) ?></div>
    <? endif; ?>
    <? if ( isset( $arResult['BUTTONS']['FORWARD'] ) ): ?>
    <div class="c-next"><?= CTemplateToolsUtil::RenderField( $arResult['BUTTONS']['FORWARD'] ) ?></div>
    <? endif; ?>
  </div>
  <? endif; ?>
</form>
