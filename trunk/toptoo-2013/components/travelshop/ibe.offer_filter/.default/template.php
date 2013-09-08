<? if (!defined('B_PROLOG_INCLUDED') || true !== B_PROLOG_INCLUDED) {
  die();
}
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
<div class="ts_ag_offer_filter"><div id="ts_ag_offer_filter<?= $arResult[ "~UID" ] ?>">
  <h2><?= GetMessage('TS_IBE_OFFER_FILTER_TITLE') ?></h2>
<? foreach ($arResult['FILTER'] as $arFilter): ?>
<? if ($arFilter['~VISIBLE']): ?>
  <? $enabler_name = $arFilter['NAME']."_FILTER_ENABLED"; ?>
  <? if ( !isset( $arParams[ $enabler_name ] ) || $arParams[ $enabler_name ] !== "N" ): ?>
  <? if ($arFilter['~ENABLED']) {
    $iEnabledFiltersCount++;
  } ?>
  <h3 class="title<?= ($arFilter['~ENABLED'] ? ' enabled' : '') ?>">
    <input type="hidden" class="body-id" value="<?= $arFilter['CLASSNAME'] ?><?= $arResult[ "~UID" ] ?>-block" />
    <?= GetMessage($arFilter['TITLE']) ?> <span class="arr"></span>
  </h3>
  <? require dirname( __FILE__ ) . '/filter_body.php' ?>
<? endif; // !isset( $arParams[ $enabler_name ] ) || $arParams[ $enabler_name ] !== "N" ?>
<? endif; // $arFilter['~VISIBLE'] ?>
<? endforeach; ?>
<div id="disable-all-filters"><a href="javascript:void(0)" class="disable-all-filters"><?= GetMessage('TS_IBE_OFFER_FILTER_DISABLE_ALL_FILTERS') ?></a></div>
</div></div>
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

if ('function' == typeof autoSelect) {
  autoSelect();
}

// ]]>
</script>
<? endif; // if ($arResult['FILTER']) ?>
<? CIBEAjax::EndArea(); ?>
<? } // if ( CIBEAjax::StartArea() ) ?>
</span>
<? CIBEAjax::EndArea(); ?>
<? } // if ( CIBEAjax::StartArea( "#ts_ag_all_in_one_offer_filter_container" ) ) ?>