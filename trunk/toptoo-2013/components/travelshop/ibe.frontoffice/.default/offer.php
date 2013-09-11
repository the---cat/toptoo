<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
echo $arResult['SCRIPT']; ?>

<div class="offers_tools clearfix">
  <? /*
  <div class="c-back">
    <div class="button_link back" id="<?= $arResult['BUTTONS']['BACK']['~ID'] ?>" onclick="<?= $arResult['BUTTONS']['BACK']['ONCLICK'] ?>">
      <?= GetMessage('TS_FRONTOFFICE_BUTTON_BACK_SEARCH') ?>
    </div>
  </div>
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
  */ ?>
  <div id="carrier_matrix" style="display:none;">
  <? // Подключение компонента матрицы авиакомпаний
  if ($arResult['~CARRIER_MATRIX']) {
    $APPLICATION->IncludeComponent
    ( 'travelshop:ibe.carrier_matrix'
    , $this->GetName()
    , $GLOBALS['IBE_FRONTOFFICE_OFFERS_RESULT']
    , false
    );
  } ?>
  </div>
</div>
<div class="clearfix">
  <div class="side_col"><div class="break_float"><div class="wrapper" id="ts_ag_all_in_one_offer_filter_container">
  <? $APPLICATION->IncludeComponent( 'travelshop:ibe.offer_filter', '' ); ?>
  </div></div></div>
  <div class="sect_main">
<div class="offers">
  <? foreach($arResult['OFFER'] as $offerKey => $offer): ?>
  <div class="offer" id="offer-<?= $offerKey ?>">
    <? //trace($offer) ?>
    <a name="offer-<?= $offerKey ?>"></a>
    <? $bValidOffer = !isset( $offer[ "ERROR_STRING" ] ); ?>
    <? $arTagForm = array(
      'action' => htmlspecialchars( $offer['~ACTION'] ),
      'id' => 'form' . $offer['~ID'],
      'method' => strtolower( $offer['~METHOD'] ),
      'onsubmit' => $offer['ONSUBMIT'],
    );
    foreach ( $arTagForm as $k => $v ) {
      $arTagForm[] = $k . '="' . $v . '"';
      unset( $arTagForm[$k] );
    }
    $strTagForm = implode( ' ', $arTagForm ); ?>
  <form <?= $strTagForm; ?>>
    <div class="flights">
      <? $colspan = 4; ?>
      <? foreach($offer['FLIGHTS'] as $directionKey => $flights): ?>
      <div class="direction <?= ToLower($flights['~DIRECTION_NAME']) ?>">
      <table class="variants">
        <thead>
          <tr>
            <th colspan="<?= $colspan - 2 ?>" class="dir_name">
              <span class="title"><?= $flights['DIRECTION_NAME'] ?></span>
              <span class="route"><?= $flights['DIRECTION_ORG']. ' &mdash; ' . $flights['DIRECTION_DEST'] ?></span>
            </th>
            <th class="companies">
              <? $companies = array();
              foreach ( $flights['FLIGHT'] as $flight ) {
                foreach ( $flight['SEGMENTS'] as $seg ) {
                  if ( !array_key_exists($seg['~AK'], $companies) ) { $companies[$seg['~AK']] = $seg['TITLE']; }
                }
              } 
              foreach ( $companies as $code => $title ) { ?>
              <?= ( $arResult['LOGOS'] && $arResult['LOGOS'][$code]['TITLE'] ? $arResult['LOGOS'][$code]['TITLE'] : $title )
                    . ( $title !== end($companies) ? ', ' : '' ) ?>
              <? } ?>
            </th>
            <th class="time_info"><?=GetMessage("TS_FRONTOFFICE_STEP2_OFFER_LOCAL_TIME") ?></th>
          </tr>
        </thead>
        <? foreach($flights['FLIGHT'] as $flightKey => $flight):
        $rowspan = (($segments = count($flight['SEGMENTS'])) > 1 ? ' rowspan="'.$segments.'"' : ""); ?>
        <tbody class="variant dir_<?= (0 == $directionKey ? 'to' : 'back') ?><? if($flight['INPUT']['~SELECTED']){ ?> selected<? } ?>" id="var-<?= $offerKey ?>-<?= $directionKey ?>-<?= $flightKey ?>">
          <? for($seg_ct=0; $seg_ct<$segments; $seg_ct++): ?>
          <tr class="flight<?= $segments > 1 ? ' with_stops' . ($seg_ct==0 ? ' first' : '' ) . ( $seg_ct == $segments-1 ? ' last' : '' ) : '' ?>">
            <? if($seg_ct == 0): ?>
            <td class="select"<?=$rowspan ?>>
              <input id="input-<?=$offer['~ID'].$flight['INPUT']['~ID']?>" type="<?=$flight['INPUT']['~TYPE'] ?>" name="<?=$flight['INPUT']['NAME'] ?>" value="<?=$flight['INPUT']['VALUE']?>"<? if($flight['INPUT']['~SELECTED']): ?> checked="checked"<? endif; ?><? if ( !$bValidOffer ) : ?> disabled="disabled"<? endif; ?> />
            </td>
            <? endif; ?>
            <? $akLogo = $arResult['LOGOS'][$flight['SEGMENTS'][$seg_ct]['~CODE']] ?>
            <td<?= ($arResult['LOGOS'] ? ' class="logo logo-normal-' . $akLogo['IATACODE'] . '"' : '') ?>>
              <div class="number">
              <? if ($arResult['LOGOS']): ?>
                <span class="ak-name" title="<?= $akLogo['TITLE']; ?>"><?= $akLogo['TITLE']; ?></span>
              <? endif; ?>
              <?=$flight['SEGMENTS'][$seg_ct]['~AK'] ?>&nbsp;<?=$flight['SEGMENTS'][$seg_ct]['NUMBER'] ?><? 
              if( !empty( $flight['SEGMENTS'][$seg_ct]['~OAK'] ) && $flight['SEGMENTS'][$seg_ct]['~OAK'] != $flight['SEGMENTS'][$seg_ct]['~AK'] ): 
                ?><sup class="oak" title="<?=GetMessage("TS_FRONTOFFICE_STEP2_OFFER_OAK_TITLE") ?> <?=$flight['SEGMENTS'][$seg_ct]['OAK'] ?>">*</sup>
              <? endif; ?>
                </div>
                <div class="plane"><?=$flight['SEGMENTS'][$seg_ct]['PLANE_NAME'] ?></div>
              </td>
              <td class="flight_info">
                <? if ( is_array($flight['SEGMENTS'][$seg_ct]['STOP_DURATION']) ) { ?>
                    <? //trace ($flight['SEGMENTS'][$seg_ct]);
                    list($ah, $am) = explode ( ':', $flight['SEGMENTS'][$seg_ct-1]['ARRIVAL']['TIME'] );
                    list($dh, $dm) = explode ( ':', $flight['SEGMENTS'][$seg_ct]['DEPARTURE']['TIME'] );
                    $arrTime = intval($ah)*60 + intval($am);
                    $depTime = intval($dh)*60 + intval($dh);
                    $night = ( 
                      ( $arrTime > 23*60 ) ||
                      ( $arrTime < 5*60 ) ||
                      ( $depTime < 7*60 ) ||
                      ( $flight['SEGMENTS'][$seg_ct]['DEPARTURE']['DATE'] != $flight['SEGMENTS'][$seg_ct-1]['ARRIVAL']['DATE'] )
                    ) ? true : false;
                    $long = intval($flight['SEGMENTS'][$seg_ct]['STOP_DURATION']['~HOURS']) > 3 ? true : false; ?>
                <div class="stop_wrap">
                  <div class="stop<?= ( $night ? ' night' : '' ) . ( $long ? ' long' : '' ) ?>">
                    <?= $long ? GetMessage('TS_FRONTOFFICE_STEP2_OFFER_STOPOVER_LONG') : '' ?>
                    <?= $night ? GetMessage('TS_FRONTOFFICE_STEP2_OFFER_STOPOVER_NIGHT') : '' ?>
                    <?= GetMessage('TS_FRONTOFFICE_STEP2_OFFER_STOPOVER') ?>
                    <?= 
                    ( $flight['SEGMENTS'][$seg_ct]['STOP_DURATION']['~HOURS'] ? $flight['SEGMENTS'][$seg_ct]['STOP_DURATION']['~HOURS'] . '&nbsp;' . GetMessage('TS_FRONTOFFICE_STEP2_OFFER_STOPOVER_H') : '' ) .
                    ( $flight['SEGMENTS'][$seg_ct]['STOP_DURATION']['~HOURS'] && $flight['SEGMENTS'][$seg_ct]['STOP_DURATION']['~MINUTES'] ? '&nbsp;' : '' ) .
                    ( $flight['SEGMENTS'][$seg_ct]['STOP_DURATION']['~MINUTES'] ? $flight['SEGMENTS'][$seg_ct]['STOP_DURATION']['~MINUTES'] . '&nbsp;' . GetMessage('TS_FRONTOFFICE_STEP2_OFFER_STOPOVER_MIN') : '')
                    ?>
                  </div>
                </div>
                  <? } ?>
                <div class="departure">
                  <span class="time"><?= $flight['SEGMENTS'][$seg_ct]['DEPARTURE']['TIME'] ?></span>
                  <span class="date"><?= $flight['SEGMENTS'][$seg_ct]['DEPARTURE']['DATE'] ?></span>
                  <div class="point"><?= $flight['SEGMENTS'][$seg_ct]['DEPARTURE']['LOC_NAME'] ?></div>
                  <div class="airport"><?= $flight['SEGMENTS'][$seg_ct]['DEPARTURE']['APT_NAME'] ? $flight['SEGMENTS'][$seg_ct]['DEPARTURE']['APT_NAME'] : $flight['SEGMENTS'][$seg_ct]['DEPARTURE']['LOC_NAME'] ?> <span class="code">/ <?= $flight['SEGMENTS'][$seg_ct]['DEPARTURE']['~APT_CODE'] ?></span></div>
                </div>
                <div class="arrival">
                  <span class="time"><?= $flight['SEGMENTS'][$seg_ct]['ARRIVAL']['TIME'] ?></span>
                  <span class="date"><?= $flight['SEGMENTS'][$seg_ct]['ARRIVAL']['DATE'] ?></span>
                  <div class="point"><?= $flight['SEGMENTS'][$seg_ct]['ARRIVAL']['LOC_NAME'] ?></div>
                  <div class="airport"><?= $flight['SEGMENTS'][$seg_ct]['ARRIVAL']['APT_NAME'] ? $flight['SEGMENTS'][$seg_ct]['ARRIVAL']['APT_NAME'] : $flight['SEGMENTS'][$seg_ct]['ARRIVAL']['LOC_NAME'] ?> <span class="code">/ <?= $flight['SEGMENTS'][$seg_ct]['ARRIVAL']['~APT_CODE'] ?></span></div>
                </div>
              </td>
              <td class="add_info">
              <? if ( $segments < 2 && empty($flight['~STOPS']) ) : ?>
                <div class="no_stops"><?= GetMessage('TS_FRONTOFFICE_STEP2_OFFER_NO_STOPS') ?></div>
              <? endif; ?>

              <? if( $arResult[ 'SHOW_SEAT_WARNING' ] ): ?>
                <div class="seats<? if ( $flight['ALL_SEATS']['~COUNT'] < 4 ){ ?> last_seats<? } ?>"<?=$rowspan ?>>
                <? if ( $flight['ALL_SEATS']['~COUNT'] > 3 ): ?><?= GetMessage('TS_FRONTOFFICE_STEP2_OFFER_SEATS_INFO') ?>
                <? else: ?><?= $flight[ 'ALL_SEATS' ][ 'INFO' ] ?><? endif; ?>
                </div>
              <? endif; ?>

                <div class="service_class">
                  <?=$flight['SEGMENTS'][$seg_ct]['SERVICE_CLASS'] ?> <span class="service_code">(<?=$flight['SEGMENTS'][$seg_ct]['~SERVICE_CODE'] ?>)</span>
                </div>

                <div class="flight_duration">
                <? if( $segments > 1 ){ $duration = $flight['SEGMENTS'][$seg_ct]['DURATION']; }
                else { $duration = $flight['~TIME']; }
                list ($hh, $mm) = explode( ':', $duration ) ?>
                <?= GetMessage('TS_FRONTOFFICE_STEP2_OFFER_FLIGHT_DURATION') ?>
                <?= ( floor($hh) ? floor($hh) . '&nbsp;' . GetMessage('IBE_FRONTOFFICE_H') : '' ) . ( floor($hh) && floor($mm) ? '&nbsp;' : '' ) . ( floor($mm) ? floor($mm) . '&nbsp;' . GetMessage('IBE_FRONTOFFICE_MIN') : '' )?>
                </div>
              </td>
          </tr>
          <? endfor; ?>
        </tbody>
        <? endforeach; //foreach($flights['FLIGHT'] as $flightKey => $flight):?>
      </table>
      </div>
      <? endforeach; //foreach($offer['FLIGHTS'] as $directionKey => $flights): ?>
    </div>
    <? if ( $bValidOffer ) : ?>
      <?=$offer['HIDDEN'] ?>
    <? endif; ?>
    <table>
      <tr>
        <td class="rules_and_conditions">
          <div class="return_policy"><span class="icon"></span> <?=rtrim($offer['PENALTY'], '.'); ?></div>
        </td>
        <td class="rules_and_conditions">
          <div class="price_note"><span class="icon"></span> <?=GetMessage("TS_FRONTOFFICE_STEP2_OFFER_INCLUDING_ALL_TAXES") ?></div>
        </td>
        <td class="submit">
          <? if ( $bValidOffer ) : ?>
          <div class="button" onclick="$(this).closest('.submit').find('input').trigger('click');">
            <?= GetMessage('TS_FRONTOFFICE_STEP2_OFFER_BUY') ?>
            <span class="price"><?=$offer['PRICE']['CAPTION_AMOUNT'] ?></span>
          </div>
          <input class="button_hidden" type="submit" value="<?=GetMessage("TS_FRONTOFFICE_STEP2_OFFER_SELECT") ?>" />
          <? else : ?>
          <div class="common-error">
            <div class="content">
              <?= strlen( $offer[ "ERROR_STRING_ADDITIONAL" ] ) > 0 ? "<span style='display:none'>".nl2br( htmlspecialchars( $offer[ "ERROR_STRING_ADDITIONAL" ] ) )."</span>" : "" ?>
              <?= htmlspecialchars( $offer[ "ERROR_STRING" ] ) ?>
            </div>
          </div>
          <? endif; ?>
        </td>
      </tr>
      <? if ( 0 ): //$offer[ 'PRICE' ][ '~FORCE_VERBOSE_FEES' ] && strlen( $offer[ 'PRICE' ][ 'FORCED_VERBOSE_FEES' ] ) > 0 ) : ?>
      <tr><td class="debug_info" colspan="3">
        <div class="verbose_fees"><?= $offer[ 'PRICE' ][ 'FORCED_VERBOSE_FEES' ] ?></div>
      </td></tr>
      <? endif; // if ( $offer[ 'PRICE' ][ '~FORCE_VERBOSE_FEES' ] && strlen( $offer[ 'PRICE' ][ 'FORCED_VERBOSE_FEES' ] ) > 0 ) ?>
    </table>
  </form>
</div>
<? endforeach; //foreach($arResult['OFFER'] as $offerKey => $offer): ?>
</div>

</div></div>
<?=$arResult["SCRIPT2"] ?>
<script type="text/javascript">
//<![CDATA[
$('.variant').click(function(event) {
  var target = $(event.target);
  if ('INPUT' != target.prop('tagName')) {
    target.closest('tbody.variant').find('td.select input').click();
  }
});

$('.variant input').click(function(e) {
  var target = $(this);
  var new_variant = target.closest('tbody.variant');
  var direction = new_variant.closest('table');
  var prev_variant = direction.find('tbody.selected');
  var form = direction.closest('form');

  prev_variant.removeClass('selected')
  new_variant.addClass('selected');
});
//]]>
</script>
