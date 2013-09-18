<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
  <? $order =  $arResult['ORDER']; ?>
  <? $payed = $order['~ORDER']['PAYED'] == 'Y' ? true : false; ?>
  <h3 class="info_caption"><?= GetMessage('IBE_PRECOMMIT_ORDER_NO')?> <?=$order['ID']?>
    <?= $payed ? GetMessage('IBE_FRONTOFFICE_FINISH_ORDER_PAYED') : GetMessage('IBE_FRONTOFFICE_FINISH_ORDER_BOOKED') ?>
</h3>
<div class="finish">
  <div class="wrap">
  <? if ( $payed ) { ?>

    <div class="order_sent"><?= GetMessage('IBE_FRONTOFFICE_FINISH_ITINERARY_SENT') ?></div>
    <div class="order_description"><?= GetMessage('IBE_FRONTOFFICE_FINISH_PAYMETHOD_ONLINE') ?></div>
    <div class="ads"><?= GetMessage('IBE_FRONTOFFICE_FINISH_ADS') ?></div>
    <div class="thanks"><?= GetMessage('IBE_FRONTOFFICE_FINISH_THANKS_FOR_BUY') ?></div>

    <? if ( $arResult['ACTION']['PDF'] || $arResult['ACTION']['ROUTECOUPON'] ) { ?>
    <div class="inks">
      <? $itinerary =  $arResult['ACTION']['PDF'] ?  $arResult['ACTION']['PDF'] : $arResult['ACTION']['ROUTECOUPON'] ?>
      <a href="<?= $itinerary['HREF'] ?>" onclick="<?= $itinerary['ONCLICK'] ?>" class="link"><?= strlen(GetMessage('IBE_FRONTOFFICE_FINISH_ITINERARY_LINK')) ?  GetMessage('IBE_FRONTOFFICE_FINISH_ITINERARY_LINK') : $itinerary['CAPTION'] ?></a>
    </div>
    <? } ?>

  <? } else { ?>
    <div class="order_sent"><?= GetMessage('IBE_FRONTOFFICE_FINISH_ORDER_INFO_SENT') ?></div>

      <? if ( $order['PAYMENT']['~TYPE'] == 'CASH' ){
        if ( $order['DELIVERY']['~TYPE'] == 'COUR' ) { ?>
    <div class="order_description"><?= GetMessage('IBE_FRONTOFFICE_FINISH_PAYMETHOD_COUR') ?></div>
        <? } elseif (  $order['DELIVERY']['~TYPE'] == 'SELF'  ) { 
          $deliveryAddress = '';
          foreach ( $order['DELIVERY']['COMMENTS'] as $desc ) {
            $deliveryAddress .= $desc . ' ';
          }
          $deliveryParams = array(
            'TIMELIMIT' => $order['TIMELIMIT'] . ' ' . $order['TIMELIMIT_SUFFIX'],
            'DELIVERY' => $deliveryAddress,
          ); ?>
    <div class="order_description"><?= GetMessageExtended('IBE_FRONTOFFICE_FINISH_PAYMETHOD_SELF', $deliveryParams) ?></div>
        <? }
      } elseif ( $order['PAYMENT']['~TYPE'] == 'ONLINE_ASYNC' ) { 
        foreach ( $order['PAYMETHODS'] as $paymethod ) { 
          if ( $paymethod['CHECKED'] == 'checked' && $paymethod['ACTION'] == 'platron' && $paymethod['SUBSYSTEM_ID'] == 'CASH' ) { ?>
    <div class="order_description"><?= GetMessage('IBE_FRONTOFFICE_FINISH_PAYMETHOD_PCS') ?></div>
            <? break;
          } 
        } 
      } ?>

     <div class="ads"><?= GetMessage('IBE_FRONTOFFICE_FINISH_ADS') ?></div>
     <div class="thanks"><?= GetMessage('IBE_FRONTOFFICE_FINISH_THANKS_FOR_ORDER') ?></div>
     <? if ( $arResult['ACTION']['PRINT'] ) {?>
      <div class="links">
        <a href="<?= $arResult['ACTION']['PRINT']['HREF']?>" onclick="<?= $arResult['ACTION']['PRINT']['ONCLICK']?>" class="link"><?= GetMessage('IBE_FRONTOFFICE_FINISH_OLDER_LINK') ?></a>
      </div>
      <? } ?>
    <? } ?>
    </div>
    <? /*
    <div class="buttons">
      <? // Button "New order" at the last screen
      $arResult['ACTION']['NEW_ORDER']['CAPTION'] = GetMessage('TS_FRONTOFFICE_BUTTON_NEW_ORDER');
      $arResult['ACTION']['NEW_ORDER']['~TYPE'] = 'link';
      $arResult['ACTION']['NEW_ORDER']['CLASS'] = 'button';
      //$arResult['ACTION']['NEW_ORDER']['ONCLICK'] = "window.location='".$GLOBALS["APPLICATION"]->GetCurDir()."'";
      $arResult['ACTION']['NEW_ORDER']['HREF'] = $GLOBALS["APPLICATION"]->GetCurDir();
      ?>
      <div class="c-next"><?= CTemplateToolsUtil::RenderField($arResult['ACTION']['NEW_ORDER']) ?></div>
    </div>
    */ ?>
  </div>