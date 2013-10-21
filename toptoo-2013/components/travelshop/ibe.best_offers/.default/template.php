<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) { die(); }

//trace($arResult);
//trace($arResult["BEST_OFFERS"]);
?>
<? if ( isset($arResult["BEST_OFFERS"]) && count($arResult["BEST_OFFERS"]) ): ?>

<div class="best-offers">
  <table>
  <? $numOffers = 0;
  $offerButtons = array(); ?>
  <? foreach ( $arResult["BEST_OFFERS"] as $offers ): ?>
  <? if ( FALSE != $offers ): ?>
  <? $numOffers++; ?>
  <? if ( $numOffers == 1 ) { ?><tr><? } ?>
  <? foreach ( $offers["OFFERS"] as $offer ): ?>
   <?  $offerOrig = $arResult["OFFER"][$offer["OFFER_INDEX"]];
          $arTagForm = array(
            'action' => htmlspecialchars( $offerOrig['~ACTION'] ),
            'id' => 'bestoffer_form_' . $offer["OFFER_INDEX"], //$offer['~ID'],
            'method' => strtolower( $offerOrig['~METHOD'] ),
            'onsubmit' => $offerOrig['ONSUBMIT'],
          );
          foreach ( $arTagForm as $k => $v ) {
            $arTagForm[] = $k . '="' . $v . '"';
            unset( $arTagForm[$k] );
          }
          $strTagForm = implode( ' ', $arTagForm );
          $outbound = $offerOrig["FLIGHTS"][0]["FLIGHT"][$offer["FLIGHT_INDICES"][0]];
          $inbound = $offerOrig["FLIGHTS"][1]["FLIGHT"][$offer["FLIGHT_INDICES"][1]];
  ?>
<? //trace($offer) ?>

  <td class="best-offer <?= ToLower($offers["CODE"]) ?> <?= floor($numOffers/2) == $numOffers/2 ? 'even' : 'odd'  ?>">
    <div class="profile-name">
      <?= strlen( GetMessage( "BEST_OFFERS_PROFILE_" . $offers["CODE"] ) ) ? GetMessage( "BEST_OFFERS_PROFILE_" . $offers["CODE"] ) : $offers["CODE"] ?>
    </div>
    <div class="info">
      <div class="flights">
        <? $departure = reset($outbound['SEGMENTS']);
        $arrival = end($outbound['SEGMENTS']); ?>
        <table class="flight outbound">
          <tr>
            <th class="caption" colspan="2">
            <span class="title"><?= GetMessage('BEST_OFFERS_DIRECTION_' . $outbound['DIRECTION'] ) ?></span>
            <span calss="date"><?= $departure['DEPARTURE']['DAY'] . ' ' . GetMessage('IBE_BEST_OFFERS_MONTH_GEN_' . $departure['DEPARTURE']['MONTH']) . ', ' ?></span>
            <span class="dow"><?= GetMessage('IBE_FRONTOFFICE_DOW_' . $departure['DEPARTURE']['WEEKDAY']) ?></span>
            </th>
          </tr>
          <tr>
            <td>
              <div class="time">
                <span class="dep_time"><?= $departure['DEPARTURE']['TIME'] . '&nbsp;&ndash;' ?></span>
                <span class="arr_time"><?= $arrival['ARRIVAL']['TIME'] ?></span>
              </div>
              <div class="stops_class">
                <span class="stops">
                <? if ( count($outbound['SEGMENTS']) > 1 ): ?>
                  <?= GetMessage("BEST_OFFERS_CONNECTION") ?>
                  <? foreach ( $outbound['SEGMENTS'] as $k => $segment ): ?>
                    <? if ( $segment != end($outbound['SEGMENTS']) ): ?>
                    <span class="stop"><?= $segment['ARRIVAL']['LOC_NAME'] . ', ' ?></span>
                    <? endif; // foreach ( $outbound['SEGMENTS'] as $k => $segment ) ?>
                  <? endforeach; ?>
                <? else: ?>
                <?= GetMessage("BEST_OFFERS_NO_CONNECTION") ?>
                <? endif; // if ( count($outbound['SEGMENTS']) > 1 ) ?>
                </span>
                <span class="class">
                  <? $service_class= array(); 
                  if ( count($outbound['SEGMENTS']) > 1 ){
                    foreach( $outbound['SEGMENTS'] as $segment ) {
                      if ( !in_array($segment['SERVICE_CLASS'], $service_class) ) $service_class[] = $segment['SERVICE_CLASS'];
                    }
                    $service_class= implode(', ', $service_class);
                  } else {
                    $service_class= $departure['SERVICE_CLASS']; 
                  }
                  ?>
                  <?= $service_class ?>
                </span>
              </div>
              <div class="airport">
              <?= GetMessage('BEST_OFFERS_FROM') ?> <?= $departure['DEPARTURE']['APT_NAME'] ? $departure['DEPARTURE']['APT_NAME'] : $departure['DEPARTURE']['LOC_NAME'] ?>
              </div>
            </td>
            <td>
              <div class="companies">
              <? foreach ( $offerOrig["COMPANY"] as $company ): ?>
                <? if ($arResult["LOGOS"]): ?>
                <span class="company"><?= ToLower($arResult["LOGOS"][$company["~CODE"]]["TITLE"]) . ($company !== end($offerOrig["COMPANY"]) ? ', ' : '' ) ?></span>
                <? endif; ?>
              <? endforeach; ?>
              </div>
              <div class="duration_seats">
                <span class="duration">
                <? list ($hh, $mm) = explode( ':', $outbound['~TIME']) ?>
                <?= ( floor($hh) ? floor($hh) . '&nbsp;' . GetMessage('BEST_OFFERS_HOUR') : '' ) . ( floor($hh) && floor($mm) ? '&nbsp;' : '' ) . ( floor($mm) ? floor($mm) . '&nbsp;' . GetMessage('BEST_OFFERS_MINUTE') : '' )?>
                </span>
                <span class="seats">
                  <?= $outbound['ALL_SEATS']['~COUNT'] > 4 ? GetMessage('BEST_OFFERS_SEATS_A_LOT') : GetMessage('BEST_OFFERS_SEATS') . $outbound['ALL_SEATS']['~COUNT'] ?>
                </span>
              </div>
              <div class="plane">
              <? if ( count($outbound['SEGMENTS']) > 1 ): ?>
                <? foreach ( $outbound['SEGMENTS'] as $k => $segment ): ?>
                <?= ToLower($segment['PLANE_NAME']) ?><?= $k < count($outbound['SEGMENTS']) - 1 ? ', ' : '' ?>
                <? endforeach; ?>
              <? else: ?>
                 <?= ToLower($departure['PLANE_NAME']) ?>
              <? endif; ?>
              </div>
            </td>
          </tr>
        </table>

        <? if ( is_array($inbound) ): ?>
        <? $departure = reset($inbound['SEGMENTS']);
        $arrival = end($inbound['SEGMENTS']); ?>
        <table class="flight inbound">
          <tr>
            <th class="caption" colspan="2">
            <span class="title"><?= GetMessage('BEST_OFFERS_DIRECTION_' . $inbound['DIRECTION'] ) ?></span>
            <span calss="date"><?= $departure['DEPARTURE']['DAY'] . ' ' . GetMessage('IBE_BEST_OFFERS_MONTH_GEN_' . $departure['DEPARTURE']['MONTH']) . ', ' ?></span>
            <span class="dow"><?= GetMessage('IBE_FRONTOFFICE_DOW_' . $departure['DEPARTURE']['WEEKDAY']) ?></span>
            </th>
          </tr>
          <tr>
            <td>
              <div class="time">
                <span class="dep_time"><?= $departure['DEPARTURE']['TIME'] . '&nbsp;&ndash;' ?></span>
                <span class="arr_time"><?= $arrival['ARRIVAL']['TIME'] ?></span>
              </div>
              <div class="stops_class">
                <span class="stops">
                <? if ( count($inbound['SEGMENTS']) > 1 ): ?>
                  <?= GetMessage("BEST_OFFERS_CONNECTION") ?>
                  <? foreach ( $inbound['SEGMENTS'] as $k => $segment ): ?>
                    <? if ( $segment != end($inbound['SEGMENTS']) ): ?>
                    <span class="stop"><?= $segment['ARRIVAL']['LOC_NAME'] . ', ' ?></span>
                    <? endif; // foreach ( $outbound['SEGMENTS'] as $k => $segment ) ?>
                  <? endforeach; ?>
                <? else: ?>
                <?= GetMessage("BEST_OFFERS_NO_CONNECTION") ?>
                <? endif; // if ( count($outbound['SEGMENTS']) > 1 ) ?>
                </span>
                <span class="class">
                  <? $service_class= array(); 
                  if ( count($inbound['SEGMENTS']) > 1 ){
                    foreach( $inbound['SEGMENTS'] as $segment ) {
                      if ( !in_array($segment['SERVICE_CLASS'], $service_class) ) $service_class[] = $segment['SERVICE_CLASS'];
                    }
                    $service_class= implode(', ', $service_class);
                  } else {
                    $service_class= $departure['SERVICE_CLASS']; 
                  }
                  ?>
                  <?= $service_class ?>
                </span>
              </div>
              <div class="airport">
                <?= GetMessage('BEST_OFFERS_FROM') ?> <?= $departure['DEPARTURE']['APT_NAME'] ? $departure['DEPARTURE']['APT_NAME'] : $departure['DEPARTURE']['LOC_NAME'] ?>
              </div>
            </td>
            <td>
              <div class="companies">
              <? foreach ( $offerOrig["COMPANY"] as $company ): ?>
                <? if ($arResult["LOGOS"]): ?>
                <span class="company"><?= ToLower($arResult["LOGOS"][$company["~CODE"]]["TITLE"]) . ($company !== end($offerOrig["COMPANY"]) ? ', ' : '' ) ?></span>
                <? endif; ?>
              <? endforeach; ?>
              </div>
              <div class="duration_seats">
                <span class="duration">
                <? list ($hh, $mm) = explode( ':', $inbound['~TIME']) ?>
                <?= ( floor($hh) ? floor($hh) . '&nbsp;' . GetMessage('BEST_OFFERS_HOUR') : '' ) . ( floor($hh) && floor($mm) ? '&nbsp;' : '' ) . ( floor($mm) ? floor($mm) . '&nbsp;' . GetMessage('BEST_OFFERS_MINUTE') : '' ) ?>
                </span>
                <span class="seats">
                  <?= $inbound['ALL_SEATS']['~COUNT'] > 4 ? GetMessage('BEST_OFFERS_SEATS_A_LOT') : GetMessage('BEST_OFFERS_SEATS') . $inbound['ALL_SEATS']['~COUNT'] ?>
                </span>
              </div>
              <div class="plane">
              <? if ( count($inbound['SEGMENTS']) > 1 ): ?>
                <? foreach ( $inbound['SEGMENTS'] as $k => $segment ): ?>
                <?= ToLower($segment['PLANE_NAME']) ?><?= $k < count($inbound['SEGMENTS']) - 1 ? ', ' : '' ?>
                <? endforeach; ?>
              <? else: ?>
                 <?= ToLower($departure['PLANE_NAME']) ?>
              <? endif; ?>
              </div>
            </td>
          </tr>
        </table>
        <? endif; ?>
      </div>

      <?
      $offerButtons[$numOffers]['CODE'] = ToLower($offers["CODE"]);
      $offerButtons[$numOffers]['FORM'] = $strTagForm;
      $offerButtons[$numOffers]['HIDDEN'] =  $offerOrig["HIDDEN"];
      $offerButtons[$numOffers]['OUTBOUND']['NAME'] = $outbound["INPUT"]["NAME"];
      $offerButtons[$numOffers]['OUTBOUND']['VALUE'] = $outbound["INPUT"]["VALUE"];
      $offerButtons[$numOffers]['INBOUND']['NAME'] = $inbound["INPUT"]["NAME"];
      $offerButtons[$numOffers]['INBOUND']['VALUE'] = $inbound["INPUT"]["VALUE"];
      $offerButtons[$numOffers]['PRICE'] = $offerOrig["PRICE"]["CAPTION_AMOUNT"];
      $offerButtons[$numOffers]['ONCLICK'] = '$(\'#bestoffer_form_' . $offer["OFFER_INDEX"] .'\').submit();'; 
      $offerButtons[$numOffers]['SHOW_MORE_LINK'] = ( count($offerOrig["FLIGHTS"][0]["FLIGHT"]) > 1 || count($offerOrig["FLIGHTS"][1]["FLIGHT"]) > 1 ) ? $offer["OFFER_INDEX"] : false;
      ?>
    </div>
  </td>
  <? endforeach; // foreach ( $offers["OFFERS"] ) ?>
  <? if ( $numOffers == 1 ) { ?><td class="separator">&nbsp;</td><? } ?>
  <? if ( $numOffers == 2 ) { ?>
  </tr>
  <tr>
  <? for ( $i = 1; $i < 3; $i++ ) { ?>
  <td class="hr <?=$offerButtons[$i]['CODE'] ?>">
    <div>
      <? if ( $offerButtons[$i]['SHOW_MORE_LINK'] ) { ?>
      <div class="goto-offer"><a class="link" href="#offer-<?= $offerButtons[$i]['SHOW_MORE_LINK'] ?>"><?= GetMessage("BEST_OFFERS_SELECT_FLIGHTS") ?></a></div>
      <? } ?>
    </div>
  </td>
  <? if ( $i == 1 ) { ?><td class="separator">&nbsp;</td><? } ?>
  <? } ?>
  </tr>
  <tr>
  <? for ( $i = 1; $i < 3; $i++ ) { ?>
    <td class="price <?=$offerButtons[$i]['CODE'] ?>">
      <div>
      <form <?= $offerButtons[$i]['FORM'] ?>>
        <?= $offerButtons[$i]['HIDDEN'] ?>
        <input type="hidden" name="<?= $offerButtons[$i]['OUTBOUND']['NAME']  ?>" value="<?= $offerButtons[$i]['OUTBOUND']['VALUE'] ?>" />
        <input type="hidden" name="<?= $offerButtons[$i]['INBOUND']['NAME']  ?>" value="<?= $offerButtons[$i]['INBOUND']['VALUE'] ?>" />
        <button class="button_buy" type="submit">
          <?= GetMessage('BEST_OFFERS_SELECT') ?>
          <span class="price"><?= $offerButtons[$i]['PRICE'] ?></span>
        </button>
        <? /* <input class="button_hidden" type="submit" value="<?= GetMessage("BEST_OFFERS_SELECT") ?>" /> */ ?>
      </form>
    </div>
    </td>
    <? if ( $i == 1 ) { ?><td class="separator">&nbsp;</td><? } ?>
  <? } ?>
  </tr>
  <? $numOffers = 0;
      } ?>
  <? endif; // if ( FALSE != $offer ) ?>
  <? endforeach; // foreach ( $arResult["BEST_OFFERS"] as $offer ) ?>
  </table>
</div>
<script type="text/javascript">
  // <![CDATA[
  $(document).ready(function(){
    $(".goto-offer a").click(function(event){
      event.preventDefault();

      var full_url = this.href;

      var parts = full_url.split("#");
      var trgt = parts[1];

      var target_offset = $("#"+trgt).offset();
      var target_top = target_offset.top;

      $('html, body').animate({scrollTop:target_top}, 500, 'easeInSine');
  });
  });
  // ]]>
  </script>
<? endif; // if ( isset($arResult["BEST_OFFERS"]) && count($arResult["BEST_OFFERS"]) ) ?>
