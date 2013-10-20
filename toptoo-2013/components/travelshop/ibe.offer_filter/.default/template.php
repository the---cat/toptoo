<? if (!defined('B_PROLOG_INCLUDED') || true !== B_PROLOG_INCLUDED) { die(); } 
$arParams['PENALTY_TYPE_FILTER_ENABLED'] = 'N';
$arParams['SERVICE_CLASS_FILTER_ENABLED'] = 'N';
?>

<? if ( CIBEAjax::StartArea( "#ts_ag_all_in_one_offer_filter_container" ) ) { ?>
  <? if ( CIBEBufferedComponent::IsOutputEnabled() && !defined('__JS_IBE_OFFER_FILTER_TEMPLATE_DEFAULT') ): ?>
  <? define('__JS_IBE_OFFER_FILTER_TEMPLATE_DEFAULT', true); ?>
  <? if (file_exists($_SERVER['DOCUMENT_ROOT'] . ($sStyleFile = $this->GetFolder() . '/styles.css'))): ?>
  <?= CIBECacheControl::RenderCSSLink( $sStyleFile ) ?>
  <? endif; ?>
  <? if (file_exists($_SERVER['DOCUMENT_ROOT'] . ($sScriptFile = $this->GetFolder() . '/js/script.js'))): ?>
  <?= CIBECacheControl::RenderJSLink( $sScriptFile ) ?>
  <? endif; ?>
  <? endif; // !defined('__JS_IBE_OFFER_FILTER_TEMPLATE_DEFAULT') ?>
<span id="ts_ag_offer_filter_container">
<? if ( CIBEAjax::StartArea( "#ts_ag_offer_filter_container" ) ) { ?>
  <? $iEnabledFiltersCount = 0; ?>
  <? if ($arResult['FILTER']): ?>
  <? //trace ($arResult['FILTER']) ?>
  <div class="ts_ag_offer_filter">
    <div id="ts_ag_offer_filter<?= $arResult[ "~UID" ] ?>">
    <? $arFilter = $arResult['FILTER']['TRANSFERS'] ?>
    <? if ( is_array($arFilter) &&  $arFilter['~VISIBLE'] &&  ( !isset( $arParams[ $arFilter['NAME']."_FILTER_ENABLED" ] ) || $arParams[ $arFilter['NAME']."_FILTER_ENABLED" ] !== "N" ) ): ?>
    <? if ($arFilter['~ENABLED']) { $iEnabledFiltersCount++; } ?>
      <h3 class="title<?= ($arFilter['~ENABLED'] ? ' enabled' : '') ?>">
        <input type="hidden" class="body-id" value="<?= $arFilter['CLASSNAME'] ?><?= $arResult[ "~UID" ] ?>-block" />
        <?= GetMessage($arFilter['TITLE']) ?>
        <span class="arr"></span>
      </h3>
      <div class="filter filter-time <?= $arFilter['CLASSNAME'] ?><?= ($arFilter['~ENABLED'] ? ' enabled' : '') ?>" id="<?= $arFilter['CLASSNAME'] ?><?= $arResult[ "~UID" ] ?>-block">
        <? $itemIndex = 0; ?>
        <ul>
        <? foreach ($arFilter['ITEMS'] as $arTransfers) { ?>
          <li<?= ($arTransfers['~DISABLED'] ? ' class="disabled"' : '') ?>>
            <input type="checkbox" checked="checked"<?= ($arTransfers['~DISABLED'] ? ' disabled="disabled"' : '') ?> id="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?><?= $arResult[ "~UID" ] ?>" />
            <label for="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?><?= $arResult[ "~UID" ] ?>"><?= GetMessage('TS_IBE_OFFER_FILTER_TRANSFERS_FILTER_' . $arTransfers['TRANSFERS']); ?></label>
          </li>
            <? $itemIndex++;
          } ?>
        </ul>
      </div>
    <? endif; ?>

    <? $arFilter = $arResult['FILTER']['DEPTIME'] ?>
    <? if ( is_array($arFilter) &&  $arFilter['~VISIBLE'] &&  ( !isset( $arParams[ $arFilter['NAME']."_FILTER_ENABLED" ] ) || $arParams[ $arFilter['NAME']."_FILTER_ENABLED" ] !== "N" ) ): ?>
    <? if ($arFilter['~ENABLED']) { $iEnabledFiltersCount++; } ?>
      <h3 class="title<?= ($arFilter['~ENABLED'] ? ' enabled' : '') ?>">
        <input type="hidden" class="body-id" value="<?= $arFilter['CLASSNAME'] ?><?= $arResult[ "~UID" ] ?>-block" />
        <?= GetMessage($arFilter['TITLE']) ?>
        <span class="arr"></span>
      </h3>
      <div class="filter filter-time <?= $arFilter['CLASSNAME'] ?><?= ($arFilter['~ENABLED'] ? ' enabled' : '') ?>" id="<?= $arFilter['CLASSNAME'] ?><?= $arResult[ "~UID" ] ?>-block">
        <? foreach ($arFilter['MULTIPLE_ITEMS'] as $arSubfilter): ?>
        <div class="slider-box">
          <div class="direction_title <?= ToLower($arSubfilter['DIRECTION']) ?>"><?= GetMessage('TS_IBE_OFFER_FILTER_' . $arSubfilter['DIRECTION']) ?></div>
          <div class="time-range clearfix">
            <span id="<?= $arSubfilter['ITEM_PREFIX'] ?>filter-from" class="time_from"><?= $arSubfilter['MIN_VALUE_TEXT'] ?></span>
            <span id="<?= $arSubfilter['ITEM_PREFIX'] ?>filter-till" class="time_till"><?= $arSubfilter['MAX_VALUE_TEXT'] ?></span>
          </div>
          <div class="time-slider" id="<?= $arSubfilter['ITEM_PREFIX'] ?>filter">
            <input type="hidden" class="min-value" value="<?= $arSubfilter['MIN_VALUE'] ?>" />
            <input type="hidden" class="max-value" value="<?= $arSubfilter['MAX_VALUE'] ?>" />
          </div>
        </div>
        <? endforeach; ?>
      </div>
    <? endif; ?>

    <? $arFilter = $arResult['FILTER']['AIRPORT'] ?>
    <? if ( is_array($arFilter) &&  $arFilter['~VISIBLE'] &&  ( !isset( $arParams[ $arFilter['NAME']."_FILTER_ENABLED" ] ) || $arParams[ $arFilter['NAME']."_FILTER_ENABLED" ] !== "N" ) ): ?>
    <? if ($arFilter['~ENABLED']) { $iEnabledFiltersCount++; } ?>
      <? /*h3 class="title<?= ($arFilter['~ENABLED'] ? ' enabled' : '') ?>">
        <input type="hidden" class="body-id" value="<?= $arFilter['CLASSNAME'] ?><?= $arResult[ "~UID" ] ?>-block" />
        <?= GetMessage($arFilter['TITLE']) ?>
        <span class="arr"></span>
      </h3 */?>
      
        <? $itemIndex = 0;
          $maxItemIndex = count($arApt) - 1;
          $dir = 'from';
          $prevLoc = false;
          $prevPoint = true;
          foreach ($arFilter['ITEMS'] as $arApt) {
            if ((($bNewPoint = $arApt['~POINT'] && $prevLoc != $arApt['LOC_NAME']) || ($bTransfer = $prevPoint && !$arApt['~POINT'])) && $prevLoc) { 
              $dir = $dir == 'from' ? 'to' : ( $dir == 'to' ? 'stop' : '' );
              ?>
          </ul>
        </div>
            <? }
            if ($bNewPoint || $bTransfer) { ?>
        <h3 class="title<?= ($arFilter['~ENABLED'] ? ' enabled' : '') ?>">
          <input type="hidden" class="body-id" value="<?= $arFilter['CLASSNAME'] ?><?= $arResult[ "~UID" ] ?>-<?=$dir ?>-block" />
          <?= ($bNewPoint || $bTransfer) && strlen(GetMessage('TS_IBE_OFFER_FILTER_AIRPORT_FILTER_'.ToUpper($dir))) ? 
          GetMessage('TS_IBE_OFFER_FILTER_AIRPORT_FILTER_'.ToUpper($dir)) : 
          ( $bNewPoint ? $arApt['LOC_NAME'] : GetMessage('TS_IBE_OFFER_FILTER_TRANSFERS') ) ?>
          <span class="arr"></span>
        </h3>
        <div class="filter filter-time <?= $arFilter['CLASSNAME'] ?><?= ($arFilter['~ENABLED'] ? ' enabled' : '') ?>" id="<?= $arFilter['CLASSNAME'] ?><?= $arResult[ "~UID" ] ?>-<?= $dir?>-block">
        <ul>
            <? } ?>
          <li<?= ($arApt['~DISABLED'] ? ' class="disabled"' : '') ?>>
            <input type="checkbox" checked="checked"<?= ($arApt['~DISABLED'] ? ' disabled="disabled"' : '') ?> id="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?><?= $arResult[ "~UID" ] ?>" />
            <label for="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?><?= $arResult[ "~UID" ] ?>">
              <span class="code"><?= $arApt['~APT_CODE'] ?></span>
              <? if ( $arApt['~POINT'] ) { ?>
              <span class="name"><?= $arApt['APT_NAME'] ? $arApt['APT_NAME'] : $arApt['LOC_NAME'] ?></span>
              <? } else { ?>
              <span class="name"><?= $arApt['LOC_NAME'] ?><?= $arApt['APT_NAME'] ? ', ' . $arApt['APT_NAME'] : '' ?></span>
              <? } ?>
            </label>
          </li>
            <? 
            $prevLoc = $arApt['LOC_NAME'];
            $prevPoint = $arApt['~POINT'];
            $itemIndex++;
          } ?>
        </ul>
      </div>
    <? endif; ?>

    <? $arFilter = $arResult['FILTER']['CARRIER'] ?>
    <? if ( is_array($arFilter) &&  $arFilter['~VISIBLE'] &&  ( !isset( $arParams[ $arFilter['NAME']."_FILTER_ENABLED" ] ) || $arParams[ $arFilter['NAME']."_FILTER_ENABLED" ] !== "N" ) ): ?>
    <? if ($arFilter['~ENABLED']) { $iEnabledFiltersCount++; } ?>
      <h3 class="title<?= ($arFilter['~ENABLED'] ? ' enabled' : '') ?>">
        <input type="hidden" class="body-id" value="<?= $arFilter['CLASSNAME'] ?><?= $arResult[ "~UID" ] ?>-block" />
        <?= GetMessage($arFilter['TITLE']) ?>
        <span class="arr"></span>
      </h3>
      <div class="filter filter-time <?= $arFilter['CLASSNAME'] ?><?= ($arFilter['~ENABLED'] ? ' enabled' : '') ?>" id="<?= $arFilter['CLASSNAME'] ?><?= $arResult[ "~UID" ] ?>-block">
        <?  $itemIndex = 0; ?>
        <ul>
          <? foreach ($arFilter['ITEMS'] as $arCarrier) { ?>
          <li<?= ($arCarrier['~DISABLED'] ? ' class="disabled"' : '') ?>>
            <input type="checkbox" checked="checked"<?= ($arCarrier['~DISABLED'] ? ' disabled="disabled"' : '') ?> id="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?><?= $arResult[ "~UID" ] ?>" />
            <label class="logo-small-<?= $arCarrier['IATACODE'] ?>" for="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?><?= $arResult[ "~UID" ] ?>">
              <span class="name"><?= ToLower($arCarrier['TITLE']) ?></span> (<span class="code"><?= $arCarrier['CRTCODE'] ?></span>)
            </label>
          </li>
            <? $itemIndex++;
          } ?>
        </ul>
      </div>
    <? endif; ?>

    <? $arFilter = $arResult['FILTER']['DURATION'] ?>
    <? if ( is_array($arFilter) &&  $arFilter['~VISIBLE'] &&  ( !isset( $arParams[ $arFilter['NAME']."_FILTER_ENABLED" ] ) || $arParams[ $arFilter['NAME']."_FILTER_ENABLED" ] !== "N" ) ): ?>
    <? if ($arFilter['~ENABLED']) { $iEnabledFiltersCount++; } ?>
      <h3 class="title<?= ($arFilter['~ENABLED'] ? ' enabled' : '') ?>">
        <input type="hidden" class="body-id" value="<?= $arFilter['CLASSNAME'] ?><?= $arResult[ "~UID" ] ?>-block" />
        <?= GetMessage($arFilter['TITLE']) ?>
        <span class="arr"></span>
      </h3>
      <div class="filter filter-time <?= $arFilter['CLASSNAME'] ?><?= ($arFilter['~ENABLED'] ? ' enabled' : '') ?>" id="<?= $arFilter['CLASSNAME'] ?><?= $arResult[ "~UID" ] ?>-block">
        <? foreach ($arFilter['MULTIPLE_ITEMS'] as $arSubfilter): ?>
        <div class="slider-box">
          <div class="direction_title <?= ToLower($arSubfilter['DIRECTION']) ?>"><?= GetMessage('TS_IBE_OFFER_FILTER_' . $arSubfilter['DIRECTION']) ?></div>
          <div class="time-range clearfix">
            <span id="<?= $arSubfilter['ITEM_PREFIX'] ?>filter-from" class="time_from"><?= $arSubfilter['MIN_VALUE_TEXT'] ?></span>
            <span id="<?= $arSubfilter['ITEM_PREFIX'] ?>filter-till" class="time_till"><?= $arSubfilter['MAX_VALUE_TEXT'] ?></span>
          </div>
          <div class="time-slider" id="<?= $arSubfilter['ITEM_PREFIX'] ?>filter">
            <input type="hidden" class="min-value" value="<?= $arSubfilter['MIN_VALUE'] ?>" />
            <input type="hidden" class="max-value" value="<?= $arSubfilter['MAX_VALUE'] ?>" />
          </div>
        </div>
        <? endforeach; ?>
      </div>
    <? endif; ?>
    
  </div>
</div>
<script type="text/javascript">
// <![CDATA[
function getFilterIdsByFilterBody( filterBody ) {
  var arFilterIds = [];
<? foreach ($arResult['FILTER'] as $arFilter): ?>
  if (0 == arFilterIds.length && filterBody.hasClass('<?= $arFilter['CLASSNAME'] ?>')) {
<? if (array_key_exists('ITEMS', $arFilter)): ?>
    arFilterIds.push('<?= $arFilter['ITEM_PREFIX'] ?>filter');
<? endif; ?>
<? if (array_key_exists('MULTIPLE_ITEMS', $arFilter)): ?>
<? foreach ($arFilter['MULTIPLE_ITEMS'] as $arSubfilter): ?>
    arFilterIds.push('<?= $arSubfilter['ITEM_PREFIX'] ?>filter');
<? endforeach; ?>
<? endif; ?>
  }
<? endforeach; ?>
  return arFilterIds;
}

<?= $arResult['SCRIPT']; ?>

<? /* moved to js/script.js */ ?>
new CIBEOfferFilterScript( '#ts_ag_offer_filter<?= $arResult[ "~UID" ] ?>', <?= $iEnabledFiltersCount ?>, '<?= $arResult[ "~UID" ] ?>' );

if ('function' == typeof autoSelect) { autoSelect(); }

if ( typeof( $.oAjaxSteps ) != 'undefined' ) {
  $.oAjaxSteps.add_user_func_after( "offer", function(){
    setTimeout( function() {
      $('#ts_ag_offer_filter_container h3.title').each( function(){
        var el = $(this);
        if( !el.hasClass('enabled') ) { 
          el.click();
          //$('#' + el.find('input').val()).show();
        }
      });
    }, 500 );
  } );
}

// ]]>
</script>
<? endif; // if ($arResult['FILTER']) ?>
<? CIBEAjax::EndArea(); ?>
<? } // if ( CIBEAjax::StartArea() ) ?>
</span>
<? CIBEAjax::EndArea(); ?>
<? } // if ( CIBEAjax::StartArea( "#ts_ag_all_in_one_offer_filter_container" ) ) ?>