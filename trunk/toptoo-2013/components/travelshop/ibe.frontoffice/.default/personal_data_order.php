<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<form action="<?= $arResult[ "ACTION" ] ?>" id="preview" method="post" name="preview" onsubmit="<?= $arResult[ "ONSUBMIT" ] ?>">
  <?=$arResult['SCRIPT'] ?>
  <? $arResult[ "ORDER" ] = array();
	$arResult[ "ORDER" ][ "BASKET" ] =& $arResult[ "BASKET" ];
	$arResult[ "ORDER" ][ "FLIGHT" ] =& $arResult[ "FLIGHT" ];
	$arResult[ "ORDER" ][ "bShowHeader" ] = false;
	$arResult[ "ORDER" ][ "~IS_CHARTER" ] = $arResult[ "~IS_CHARTER" ]; ?>
  <div class="order">
  <? //trace($arResult) ?>
  <div class="flights">
  <? foreach( $arResult['DIRECTIONS'] as $directionKey => $direction ): ?>
    <? $diretion_name =  $directionKey == 0 ? 'outbound' : 'inbound'; ?>
    <table class="direction <?= $diretion_name ?>">
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
      <tbody class="flight">
        <tr class="top">
        <?  $oak = ( !empty( $flight['~OAK'] ) && $flight['~OAK'] != $flight['~AK'] ); $oakClass = ''; $oakCode = '';
        if( $arResult['LOGOS'] ){
          if( $oak ) { $oakClass = ' oak'; $oakCode = '<span class="oak_star">*</span>'; }
              $akTitle = $flight['TITLE'] ? $flight['TITLE'] : $flight['~AK']; ?>
        <td class="logo logo-normal-<?= $arResult['LOGOS'][$flight['~AK']]['IATACODE'] . $oakClass ?>" rowspan="2"<?= $akTitle ? ' title="' . $akTitle . '"' : '' ?>><?= $oakCode ?></td>
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
          <?= $arResult['ORDER']['BASKET']['TOTAL_PRICE_NO_SSR'] ? $arResult['ORDER']['BASKET']['TOTAL_PRICE_NO_SSR'] : $arResult['ORDER']['BASKET']['ALT_TOTAL_PRICE'] ?>
        </td>
      </tr>
    </table>
    </div>
  <? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/ibe/classes/js_lang/ibe_js.php"); ?>
  <?=GetIbeJsStrings(); ?>
  </div>
</form>
