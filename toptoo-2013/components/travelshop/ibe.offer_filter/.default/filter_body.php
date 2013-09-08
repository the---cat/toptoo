<div class="filter filter-time <?= $arFilter['CLASSNAME'] ?><?= ($arFilter['~ENABLED'] ? ' enabled' : '') ?>" id="<?= $arFilter['CLASSNAME'] ?><?= $arResult[ "~UID" ] ?>-block">
<? /* // если это не фильтр PENALTY_TYPE и если в фильтре больше одного элемента, показать кнопку "Выделить все"
  if( $arFilter[ 'NAME' ] != 'PENALTY_TYPE' && ( !isset( $arFilter['ITEMS'] ) || count( $arFilter['ITEMS'] ) != 1 ) ): ?>
  <div class="select-all"><a href="javascript:void(0)" id="<?= $arFilter['ITEM_PREFIX'] ?>link">
    <?= GetMessage('TS_IBE_OFFER_FILTER_SELECT_ALL_ITEMS') ?>
    </a></div>
  <? endif; ?>
  <? if ('CHECKBOX' == $arFilter['~TYPE'] && $arFilter['CHANGEABLE']): ?>
  <div class="clear-all"><a href="javascript:void(0)" id="clear-<?= $arFilter['ITEM_PREFIX'] ?>link">
    <?= GetMessage('TS_IBE_OFFER_FILTER_CLEAR_ALL_ITEMS') ?>
    </a></div>
  <? endif; 
  */ ?>
  <? switch ($arFilter['NAME']) {
  case 'TIME':
  case 'DURATION': ?>
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
<? break;

  case 'AIRPORT':
    $itemIndex = 0;
    $maxItemIndex = count($arApt) - 1;
    $prevLoc = false;
    $prevPoint = true;
    foreach ($arFilter['ITEMS'] as $arApt) {
      if ((($bNewPoint = $arApt['~POINT'] && $prevLoc != $arApt['LOC_NAME']) || ($bTransfer = $prevPoint && !$arApt['~POINT'])) && $prevLoc) { ?>
  </ul>
      <? }

      if ($bNewPoint || $bTransfer) { ?>
  <div class="point"><?= $bNewPoint ? $arApt['LOC_NAME'] : GetMessage('TS_IBE_OFFER_FILTER_TRANSFERS'); ?></div>
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
<? break;

  case 'CARRIER':
    $itemIndex = 0; ?>
  <ul>
    <? foreach ($arFilter['ITEMS'] as $arCarrier) { ?>
    <li<?= ($arCarrier['~DISABLED'] ? ' class="disabled"' : '') ?>>
      <input type="checkbox" checked="checked"<?= ($arCarrier['~DISABLED'] ? ' disabled="disabled"' : '') ?> id="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?><?= $arResult[ "~UID" ] ?>" />
      <label class="logo-small-<?= $arCarrier['IATACODE'] ?>" for="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?><?= $arResult[ "~UID" ] ?>">
        <?= $arCarrier['TITLE'] ?> (<?= $arCarrier['CRTCODE'] ?>)
      </label>
    </li>
      <? $itemIndex++;
    } ?>
  </ul>
<? break;

  case 'SERVICE_CLASS':
    $itemIndex = 0; ?>
  <ul>
    <? foreach ($arFilter['ITEMS'] as $arServiceClass) { ?>
    <li<?= ($arServiceClass['~DISABLED'] ? ' class="disabled"' : '') ?>>
      <input type="checkbox" checked="checked"<?= ($arServiceClass['~DISABLED'] ? ' disabled="disabled"' : '') ?> id="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?><?= $arResult[ "~UID" ] ?>" />
      <label for="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?><?= $arResult[ "~UID" ] ?>">
        <?= $arServiceClass['SERVICE_CLASS'] ?>
      </label>
    </li>
      <? $itemIndex++;
    } ?>
  </ul>
<? break;

  case 'TRANSFERS':
    $itemIndex = 0; ?>
  <ul>
    <? foreach ($arFilter['ITEMS'] as $arTransfers) { ?>
    <li<?= ($arTransfers['~DISABLED'] ? ' class="disabled"' : '') ?>>
      <input type="checkbox" checked="checked"<?= ($arTransfers['~DISABLED'] ? ' disabled="disabled"' : '') ?> id="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?><?= $arResult[ "~UID" ] ?>" />
      <label for="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?><?= $arResult[ "~UID" ] ?>">
        <?= GetMessage('TS_IBE_OFFER_FILTER_TRANSFERS_FILTER_' . $arTransfers['TRANSFERS']); ?>
      </label>
    </li>
      <? $itemIndex++;
    } ?>
  </ul>
<? break;

  case 'PENALTY_TYPE':
    $itemIndex = 0; ?>
  <ul>
    <? foreach ( $arFilter['ITEMS'] as $arPenaltyType ):?>
    <? // распечатка checkbox подключения конкретного типа возврата ?>
    <li<?= ($arServiceClass['~DISABLED'] ? ' class="disabled"' : '') ?> style="display: none">
      <input checked="checked" type="checkbox" id="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?><?= $arResult[ "~UID" ] ?>" />
      <label for="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?><?= $arResult[ "~UID" ] ?>">
        <?= $arPenaltyType[ 'PENALTY_TYPE' ] ?>
      </label>
    </li>
    <? /* по задумке для типа возврата не интересна прямая логика чекбокса, а интересна обратная, т.е.
            показывать все за исключением некоторых предложений, либо показывать все.
            Чекбокс прямой логики скрывается, а вместо него подкладывается чекбокс с обратной логикой, который управляет
            скрытым */ ?>
    <li<?= ($arServiceClass['~DISABLED'] ? ' class="disabled"' : '') ?>>
      <input type="checkbox" class="fictional" id="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?><?= $arResult[ "~UID" ] ?>_contrast" onclick="
            $( '#<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?><?= $arResult[ "~UID" ] ?>' ).attr( 'checked', !this.checked ).triggerHandler( 'click' );
        " />
      <label for="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?><?= $arResult[ "~UID" ] ?>_contrast">
        <?= $arPenaltyType[ 'PENALTY_TYPE_CONTRAST' ] ?>
      </label>
    </li>
    <? $itemIndex++; ?>
    <? endforeach; ?>
  </ul>
<? break;
} ?>
</div>