<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if ( !CIBEAjax::IsAjaxMode() ) {
  $APPLICATION->IncludeComponent(
    "travelshop:ibe.ajax",
    ""
    );
}

echo GetIbeJsStrings();

?>
<?= $arResult['SCRIPT'] ?>

<div class="precommit">
<? if ( $arParams['USE_MERGED_STEPS'] === 'Y' ): ?>
<? require( $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/travelshop/ibe.frontoffice/templates/.default/basket.php" ); ?>
  <div class="wrap clearfix">
  <div class="buttons clearfix">
  <? //trace($arResult['COMMIT']) ?>
   <? foreach( $arResult['PAYMETHODS'] as $arPayMethod ) {
	 	if ( $arPayMethod['CHECKED'] == 'checked' ) {
	 		list($type, $misc) = explode( '_' , $arPayMethod['PS_TYPE'], 2 );
			if ( $arPayMethod['SUBSYSTEM_ID'] == 'CASH' || $arPayMethod['SUBSYSTEM_ID'] == 'POSTPONED' ) { $type = 'CASH'; }
			if ( $type != 'CASH' && $type != 'ONLINE' ) { list($type, $misc) = explode( '.' , $arPayMethod['PS_TYPE'], 2 ); }
			if ( $type != 'CASH' && $type != 'ONLINE' ) { $type = 'CASH'; }
		}
  } ?>
  <? if ( $arResult['COMMIT'] && is_array( $arResult['COMMIT']['FIELDS'] ) ) { ?>
  <div class="c-continue <?= $arResult['COMMIT']['CLASS']  ?>" id="<?= $arResult['COMMIT']['~ID'] ?>"<?= $arResult['COMMIT']['STYLE'] ? ' style="' . $arResult['COMMIT']['STYLE'] . '"' : '' ?>>
    <div class="button_buy" onclick="$('#<?= $arResult['COMMIT']['FIELDS'][0]['~ID'] ?>').click();">
      <?= GetMessage('IBE_FRONTOFFICE_BUTTON_COMMIT_CASH') ?>
      <span id="price_container_ticket" class="price"><?= $arResult['ORDER']['BASKET']['BASE_TOTAL_PRICE'] ?></span>
      <? /*
      <div class="button_loading_overlay">
        <div id="price_container_title" class="title">
          <?= GetMessage('IBE_FRONTOFFICE_BUTTON_COMMIT_' . ToUpper($type)) ?>
        </div>
        <div id="price_container_ticket" class="price">
          <?= $arResult['ORDER']['BASKET']['BASE_TOTAL_PRICE'] ?>
        </div>
        <span class="loading"></span>
       </div>
       */ ?>
      </div>
      <? global $IBE_WEB_LOG_OUTPUT; ?>
      <input id="<?= $arResult['COMMIT']['FIELDS'][0]['~ID'] ?>" name="<?= $arResult['COMMIT']['FIELDS'][0]['NAME'] ?>" class="<?=$arResult['COMMIT']['FIELDS'][0]['CLASS']  ?>" type="<?= $arResult['COMMIT']['FIELDS'][0]['~TYPE'] ?>" onclick="<?= $arResult['COMMIT']['FIELDS'][0]['ONCLICK'] ?>" value="<?= GetMessage('IBE_FRONTOFFICE_BUTTON_COMMIT_ONLINE') ?>" />
    </div>
    <?//= CTemplateToolsUtil::RenderField($arResult['COMMIT']) ?>
    <? } ?>
    <? if ( $arResult['BOOK'] && is_array( $arResult['BOOK']['FIELDS'] ) ) { ?>
    <div class="c-continue <?= $arResult['BOOK']['CLASS']  ?>" id="<?= $arResult['BOOK']['~ID'] ?>">
      <div class="button_wrap">
        <div class="button" onclick="$('#<?= $arResult['BOOK']['FIELDS'][0]['~ID'] ?>').click();">
          <div class="button_loading_overlay">
            <div id="price_container_title" class="title">
              <?= GetMessage('IBE_FRONTOFFICE_BUTTON_BOOK') ?>
            </div>
            <div id="price_container_ticket" class="price">
              <?= $arResult['ORDER']['BASKET']['BASE_TOTAL_PRICE'] ?>
            </div>
            <span class="loading"></span>
           </div>
          </div>
        </div>
        <input id="<?= $arResult['BOOK']['FIELDS'][0]['~ID'] ?>" name="<?= $arResult['BOOK']['FIELDS'][0]['NAME'] ?>" class="<?=$arResult['BOOK']['FIELDS'][0]['CLASS']  ?>" type="<?= $arResult['BOOK']['FIELDS'][0]['~TYPE'] ?>" onclick="<?= $arResult['BOOK']['FIELDS'][0]['ONCLICK'] ?>" value="<?= GetMessage('IBE_FRONTOFFICE_BUTTON_BOOK') ?>" />
      </div>
      <?//= CTemplateToolsUtil::RenderField($arResult['BOOK']) ?>
      <? } ?>
    </div>
  <? if ( $arResult[ "~SHOW_AGREEMENTS" ] ) : ?>
    <? $fares = array();
    foreach ( $arResult['ORDER']['BASKET']['PRODUCT'] as $product ) {
      foreach ($product['FARE'] as $fare) {
        if ( !array_key_exists( $fare['NAME'], $fares ) ) { $fares[$fare['NAME']] = $fare['HTTP_LINK']; }
      }
    }

    $tairiffAgreeTitle = GetMessage("IBE_FRONTOFFICE_PRECOMMIT_ACCEPT") . ' (';
    foreach ($fares as $fare => $link ) {
      $tairiffAgreeTitle .= $link != '' ? '<a  class="link" href="javascript:void(0)" onclick="window.open(\''
        . $link . '&title=' . urlencode( GetMessage( 'TS_FRONTOFFICE_STEP2_OFFER_TARIF_RULES' ) . ' ' . $fare )
        . '\', \'upt\', \'toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=1, resizable=1, width=600, height=350\')">'
        . $fare
        . '</a>'
        . ( $link != end($fares) ? ', ' : '' )
      : $fare . ( $link != end($fares) ? ', ' : '' );
    }
    $tairiffAgreeTitle .= '), ';
    $tairiffAgreeTitle .= $arResult['FRONTIER_ZONE'] ? '<br />' . GetMessage('IBE_FRONTOFFICE_PRECOMMIT_FRONTIER_ZONE') : '';
    $tairiffAgreeTitle .= $arResult['RUSSIAN_PASSPORT_NOT_ALLOWED'] ? '<br />' . GetMessage('IBE_FRONTOFFICE_PRECOMMIT_VISA_RULES') : '';
    $tairiffAgreeTitle .= '<br />' . GetMessage('IBE_FRONTOFFICE_PRECOMMIT_OFFER_CONTRACT');
    ?>
		<?//=CTemplateToolsUtil::RenderField($arResult['TARIFF_AGREE']) ?>
    <div style="display:none;">
		<? if ($arResult['FRONTIER_ZONE']) $arResult['TARIFF_AGREE']['ONCLICK'] = $arResult['FRONTIER_ZONE']['FIELD']['ONCLICK']; ?>
  	<input type="<?=$arResult['TARIFF_AGREE']['~TYPE']?>" id="<?=$arResult['TARIFF_AGREE']['~ID']?>" name="<?=$arResult['TARIFF_AGREE']['NAME']?>" onclick="<?=$arResult['TARIFF_AGREE']['ONCLICK']?>" value="<?=$arResult['TARIFF_AGREE']['VALUE']?>"  checked="checked" />
  	<? if ($arResult['FRONTIER_ZONE']): ?>
  	<?//=CTemplateToolsUtil::RenderField($arResult['FRONTIER_ZONE']['FIELD']) ?>
		<input type="<?=$arResult['FRONTIER_ZONE']['FIELD']['~TYPE']?>" id="<?=$arResult['FRONTIER_ZONE']['FIELD']['~ID']?>" name="<?=$arResult['FRONTIER_ZONE']['FIELD']['NAME']?>" onclick="<?=$arResult['FRONTIER_ZONE']['FIELD']['ONCLICK']?>" value="<?=$arResult['FRONTIER_ZONE']['FIELD']['VALUE']?>" checked="checked" />
		<? endif; ?>
    </div>
    <div class="warning"><?= $tairiffAgreeTitle ?></div>
	<? endif; ?>
  </div>
 <? else: // $arParams[ "USE_MERGED_STEPS" ] ?>
<? require( $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/travelshop/ibe.frontoffice/templates/.default/render_order.php" ); ?>
<? if ( $arResult[ "~SHOW_AGREEMENTS" ] ) : ?>
<div class="order">
  <? require( dirname( __FILE__ )."/agreement_checkboxes.php" ); ?>
</div>
<? endif; // $arResult[ "~SHOW_AGREEMENTS" ] ?>
<div class="buttons clearfix">
  <? if ( isset( $arResult['BACK'] ) ) : ?>
  <?= ($arResult['BACK'] ? CTemplateToolsUtil::RenderField($arResult['BACK']) : "" ) ?>
<? endif; ?>
<?= ($arResult['COMMIT'] ? CTemplateToolsUtil::RenderField($arResult['COMMIT']) : "" ) ?>
<?= ($arResult['BOOK'] ? CTemplateToolsUtil::RenderField($arResult['BOOK']) : "" ) ?>
</div>
<? endif; // $arParams[ "USE_MERGED_STEPS" ] ?>
</div>