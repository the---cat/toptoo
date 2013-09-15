<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); //trace($arResult);?>
<? if ( !CIBEAjax::IsAjaxMode() ) {
  $APPLICATION->IncludeComponent(
    "travelshop:ibe.ajax",
    ""
  );
} ?>
<? //trace($arResult) ?>
<? if ( CIBEAjax::StartArea( "#ts_basket_container" ) ) : ?>
<span id="ts_basket_container">
<? if ( isset( $arResult[ "BASKET" ] ) ): ?>

  <? if ( $arParams[ "USE_MERGED_STEPS" ] === "Y" ) : ?>

  <? if (0): ?>
  <div id="ts_basket">
    <? if ( isset( $arResult['BASKET']['OLD_TOTAL_PRICE'] ) ): ?>
      <span class="old_price"> <?= $arResult['BASKET']['OLD_TOTAL_PRICE'] ?><br /></span>
    <? endif; ?>
    <? if ( isset( $arResult['BASKET']['BASE_TOTAL_PRICE'] ) ): ?>
      <span class="price" id="price_container_basket"><?= $arResult['BASKET']['BASE_TOTAL_PRICE'] ?></span>
			<? if ( $arResult['BASKET']['TOTAL_PRICE'] != $arResult['BASKET']['BASE_TOTAL_PRICE'] ): ?>
			<span class="reference_price"><?= $arResult['BASKET']['TOTAL_PRICE'] ?></span>
			<? endif; ?>
    <? endif; ?>
  </div>
  <? endif; ?>
  <script type="text/javascript">/*<![CDATA[*/

    <? foreach( $arResult['PAYMETHODS'] as $arPayMethod ): ?>

      $("#sum_<?=$arPayMethod['ID'] ?>").html( "<?=$arPayMethod['FULL_PRICE'] ?>" );
      <? if ( $arPayMethod['CHECKED'] == 'checked' ): ?>
        $("#price_container_ticket").html( "<?=$arPayMethod['FULL_PRICE'] ?>" );
      <? endif; ?>
    <? endforeach; ?>

  /*]]>*/</script>
  <? endif; /* USE_MERGED_STEPS */ ?>

<? endif; ?>
<?= $arResult[ "SCRIPT" ] ?>
</span>
<? CIBEAjax::EndArea(); ?>
<? endif; // if ( CIBEAjax::StartArea() ?>