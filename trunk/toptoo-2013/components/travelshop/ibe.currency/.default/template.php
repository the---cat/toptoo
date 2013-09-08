<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) { die(); }

?>
<div id="ts_ag_currency">
<? if (CIBEAjax::StartArea('#ts_ag_currency')): ?>
  <form action="<?=$arResult['FORM']['ACTION'] ?>" id="<?=$arResult['FORM']['~ID'] ?>" method="<?=$arResult['FORM']['~METHOD'] ?>">
    <?= $arResult['FORM']['HIDDEN'] ?>
    <ul>
      <? foreach ($arResult['FORM']['FIELDS']['CURRENCY']['OPTION'] as $key => $currency): ?>
      <li<? if ($currency['~SELECTED']){?> class="selected"<?}?>><input type="radio" name="<?= $arResult['FORM']['FIELDS']['CURRENCY']['NAME'] ?>" id="currency_<?= $key ?>" value="<?= $currency['VALUE'] ?>" <? if ($currency['~selected']): ?>checked="checked"<? else: ?>onclick="on_currency_submit()"<? endif; ?>><label for="currency_<?= $key ?>"><?=$key ?></label>
      <? endforeach; ?>
    </ul>
  </form>
  <script type="text/javascript">/*<![CDATA[*/
function on_currency_submit() {
  <? if ( $arParams['USE_MERGED_STEPS'] === 'Y' ) : ?>
  ibe_ajax.context = "currency";
  ibe_ajax.post( $("#<?=$arResult['FORM']['~ID'] ?>"), '<?=$arResult['FORM']['ACTION'] ?>', '#ts_ag_reservation_container__form_order,#ts_ag_reservation_container__form_top,#ts_ag_reservation_container__offer,#ts_ag_all_in_one_offer_filter_container,#ts_ag_offer_filter_container,#ts_ag_carrier_matrix_container,#ts_ag_currency' );
  ibe_ajax.context = "";
  <? else : ?>
  $("#<?=$arResult['FORM']['~ID'] ?>").submit();
  <? endif; ?>
}
/*]]>*/</script>
<? CIBEAjax::EndArea(); ?>
<? endif; ?>
</div>