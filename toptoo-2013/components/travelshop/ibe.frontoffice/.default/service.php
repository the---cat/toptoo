<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!defined('__JQUERY_UI_JS')) { define("__JQUERY_UI_JS", true); } ?>
<script type="text/javascript">
//<![CDATA[
var passengers = [];
//]]>
</script>
<? 
if ( isset ($arParams['EVER_SERVICES']) && strlen($arParams['EVER_SERVICES']) ) { // Если задан параметр компонента для обязательного показа списка услуг
  $everpresentCodes_ = explode(",", $arParams['EVER_SERVICES']);
  if ( is_array($everpresentCodes_) ) {
    foreach ( $everpresentCodes_ as $code ) {
      $everpresentCodes[] = trim($code);
    }
  }
  // Разделяем услуги на обязательные для показа и дополнительные
  $everpresent = array (); //Обязательные сервисы
  $others = array (); //Дополнительные сервисы
  if ( is_array($everpresentCodes) && count($everpresentCodes) ) {
    foreach ( $arResult['SERVICES'] as $service ) {
      if ( in_array( trim($service['CODE']), $everpresentCodes ) ) { // Если код услуги есть в списке обязательных для показа
        $everpresent[] = $service; // добавляем ее в список видимых
      } else { // В противном случае
        $others[] = $service; // добавляем ее в список невидимых
      }
    }
  } else {
    $everpresent = $arResult['SERVICES'];
  }
} else {
  $everpresent = $arResult['SERVICES'];
} ?>

<div class="services_wrap">
  <? $APPLICATION->IncludeComponent("bitrix:system.show_message", "", Array("MESSAGE" => $arResult['DISPLAY_ERROR'])); ?>
  <? //trace($arResult['SERVICES']); 

  if(!empty($arResult['SERVICES'])):

  /*
  foreach ( $arResult['SERVICES'] as &$srvs ) {
    foreach ( $srvs['PASSENGERS'] as &$psgrs ) {
      foreach ( $psgrs['FLIGHTS'] as &$flts ) {
        if ( $flts ) {
          $flts['FIELD']['~SELECTED'] = TRUE;
        }
      }
    }
  }
  */

$arTagForm = array(
    'action' => htmlspecialchars( $arResult['ACTION'] ),
    'id' => $arResult['~ID'],
    'name' => $arResult['~NAME'],
    'class' => 'services',
    'method' => strtolower( $arResult['~METHOD'] ),
    'onsubmit' => $arResult[ "ONSUBMIT" ],
);
foreach ( $arTagForm as $k => $v ) {
  $arTagForm[] = $k . '="' . $v . '"';
  unset( $arTagForm[$k] );
}
$strTagForm = implode( ' ', $arTagForm );
//trace($arResult['SERVICES']);
?>
  <h3 class="info_caption">
    <?=GetMessage('IBE_TITLE_SERVICES')?>
  </h3>
  <form <?= $strTagForm; ?>>
    <table>
      <? /*
      <thead>
        <tr>
          <th class="name"><?=GetMessage('TS_BOOKING_SERVICE_SERVICE') ?></th>
          <? 
          foreach($arResult['FLIGHTS'] as $flight): // Сегменты ?>
          <th style="width:<?= round(50/sizeof( $arResult['FLIGHTS'])); ?>%"><span class="code">
            <?= $flight['DEPARTURE']['CITY']['NAME'] . ' ('. $flight['DEPARTURE']['AIRPORT']['IATACODE'] . ')' ?>
            &nbsp;&ndash;
            <?= $flight['ARRIVAL']['CITY']['NAME'] . ' ('. $flight['ARRIVAL']['AIRPORT']['IATACODE'] . ')' ?>
            </span> <span class="number">
            <?=$flight['AK'].' '.$flight['FLIGHTNO'] ?>
            </span> </th>
          <? endforeach; ?>
        </tr>
      </thead>
      */ ?>
      <tbody>
        <? $lastService = end($arResult['SERVICES']); ?>
        <? foreach($arResult['SERVICES'] as $service): ?>
        <? $passengersCount = count($service['PASSENGERS']); ?>
        <? if( strlen($service['PREVIEW_TEXT']) ) { ?>
        <tr>
          <th class="service_info" colspan="<?= count($arResult['FLIGHTS']) ?>"> <? if($service['IMAGE_URL']): ?>
            <span class="img"><img alt="<?=$service['NAME'] ?>" src="<?=$service['IMAGE_URL'] ?>" /></span>
            <? endif; ?>
            <span class="service-name">
            <? if($service['DESCRIPTION_URL']): ?>
            <a href="<?=$service['DESCRIPTION_URL'] ?>">
            <?=$service['NAME'] ?>
            </a>
            <? else: ?>
            <?=$service['NAME'] ?>
            <? endif; ?>
            </span> </th>
        </tr>
        <? } ?>
        <tr class="service service-<?= $service['ID'] ?><? if($service == $lastService): ?> service-last<? endif; ?>">
          <th class="name">
            <? if(strlen($service['PREVIEW_TEXT'])) { ?>
            <span class="description"><?= $service['PREVIEW_TEXT'] ?></span>
            <? } else { ?>
            <? if($service['IMAGE_URL']): ?>
            <span class="img"><img alt="<?=$service['NAME'] ?>" src="<?=$service['IMAGE_URL'] ?>" /></span>
            <? endif; ?>
            <span class="service-name">
            <? if($service['DESCRIPTION_URL']): ?>
            <a href="<?=$service['DESCRIPTION_URL'] ?>"><?=$service['NAME'] ?></a>
            <? else: ?>
            <?=$service['NAME'] ?>
            <? endif; ?>
            </span>
            <? } ?>
            <? if($passengersCount > 1 && $service['ALLOW_TOGGLE_PASSENGER_LIST'] == 'Y'): ?>
            <span class="passenger-list-link">
              <a href="javascript:void(0);" onclick="togglePassengers(this, '<?= $service['ID'] ?>');"><?=GetMessage('TS_BOOKING_SERVICE_'.($service['SHOW_PASSENGER_LIST'] == 'Y' ? 'HIDE' : 'SHOW').'_PASSENGERS') ?></a>
            </span>
            <? endif; ?>
          </th>
          <? if($service['PASSENGERS'] && count($service['PASSENGERS']) > 1 ): // случай для более чем одного пассажира ?>
          <? if($service['PASSENGERS'][0]['FLIGHTS'][0]['FIELD']['TYPE'] == 'checkbox'): ?>
          <? foreach($service['PASSENGERS'][0]['FLIGHTS'] as $flightNum => $flight): ?>
          <td class="price group-<?= $service['ID'] ?>">
            <? if(!empty($flight)): ?>
            <?
          $uiq = strtolower($service['CODE']).'-'.$flightNum.'-'.$service['ID'];
          $sum = 0;
          $selectedCount = 0;

          foreach($service['PASSENGERS'] as $psgr) {
            $sum += $psgr['FLIGHTS'][$flightNum]['~BASE_PRICE'];
            if($psgr['FLIGHTS'][$flightNum]['FIELD']['~SELECTED']) {
              $selectedCount++;
            }
          }
    
          if($sum == 0) {
            $sum = GetMessage('TS_BOOKING_SERVICE_FREE');
          } else {
            $sum = CIBECurrency::GetStringFull($sum);
          }

          $bGroupSelected = ($selectedCount == count($service['PASSENGERS'])) ? true : false; ?>
            <? if ( !isset( $service['PASSENGERS'][0]['FLIGHTS']['0']['FIELDS'] ) ): ?>
            <input<? if($bGroupSelected): ?> checked="checked"<? endif; ?> name="all-<?=$service['PASSENGERS'][0]['FLIGHTS'][0]['FIELD']['NAME']?>" id="all-<?=$uiq ?>" onclick="checkAll(this, '<?=$uiq ?>');<?=$arResult[ 'ONCHANGE' ] ?>" type="checkbox" />
            <span class="price" id="sum-<?=$uiq ?>"><label for="all-<?=$uiq ?>"><?=$sum ?></label></span>
            <? endif; ?>
            <? else: //if(!empty($flight)): ?>
            &nbsp;
            <? endif; // if(!empty($flight)): ?>
          </td>
          <? endforeach; //foreach($service['PASSENGERS'][0]['FLIGHTS'] as $flightNum => $flight): ?>
          <? else: //if($service['PASSENGERS'][0]['FLIGHTS'][0]['FIELD']['TYPE'] == 'checkbox'): ?>
          <? for ($i = 0; $i < count($arResult['FLIGHTS']); $i++): ?>
          <td>&nbsp;</td>
          <? endfor; ?>
          <? endif; // if($service['PASSENGERS'][0]['FLIGHTS'][0]['FIELD']['TYPE'] == 'checkbox'):?>
        </tr>
        <? endif; //if($service['PASSENGERS'] && count($service['PASSENGERS']) > 1): // случай для более чем одного пассажира ?>
        <?
        $slots = $service['PASSENGERS'] ? $service['PASSENGERS'] : $service['GROUP_FLIGHTS'];
        $last = end($slots);
        
        foreach($slots as $passengerNum => $passenger): 
          if($service['PASSENGERS'] && count($service['PASSENGERS']) > 1 ): // случай для более чем одного пассажира? ?>
        <tr class="passenger passenger-<?= $service['ID'] ?><? if($passenger == $last): ?> passenger-last<? endif; ?>"<? if( ($service['SHOW_PASSENGER_LIST'] != 'Y' && $service['ALLOW_TOGGLE_PASSENGER_LIST'] == 'Y') || ($service['PASSENGERS'] && count($service['PASSENGERS']) >1 && $service['ALLOW_TOGGLE_PASSENGER_LIST'] != 'Y') ) : ?> style="display: none;"<? endif; ?>>
          <td class="name"><?=$passenger['NAME']['FIRSTNAME'].' '.$passenger['NAME']['LASTNAME'] ?></td>
          <? endif; ?>
          <? $flights = $passenger['FLIGHTS'] ? $passenger['FLIGHTS'] : array() ?>
          <? foreach($flights as $flightNum => $flight): ?>
          <? $uiq = strtolower($service['CODE']).'-'.$flightNum . (is_int($passengerNum) && isset($service['PASSENGERS'][0]['FLIGHTS'][0]['FIELDS']) ? '-' . $passengerNum : '').'-'.$service['ID']; ?>
          <td class="price cell-<?=$uiq ?>">
            <? if(!empty($flight) && $flight ): ?>
            <? if ( is_array($flight['FIELDS']) && count($flight['FIELDS']) ): // Если данная услуга является группой услуг ?>
            <? foreach ( $flight['FIELDS'] as $group => $subservice ):

              $field = $subservice['FIELD'];
              switch($field['TYPE']) {

                case 'checkbox': ?>
            <ins class="service-group">
            <div class="r"<?= strlen($field['ADDITIONAL_INFO']) && strlen($field['CAPTION']) ? ' title="' . htmlspecialchars($field['ADDITIONAL_INFO']) . '"' : '' ?>>
              <span class="caption"><?= strlen($field['CAPTION']) ? $field['CAPTION'] : $field['ADDITIONAL_INFO'] ?></span>
              <input<? if($field['~SELECTED']): ?> checked="checked"<? endif; ?> id="<?=$field['ID'] ?>" name="<?=$field['NAME'] ?>" onclick="checkOneFromGroup(this, '<?=$uiq ?>');<?=$arResult[ 'ONCHANGE' ] ?>;return true;" value="<?=$subservice['~PRICE'] ?>" type="checkbox" />
              <span class="price"><label for="<?=$field['ID'] ?>"><?=$subservice['~PRICE'] ? $subservice['PRICE'] : GetMessage('TS_BOOKING_SERVICE_FREE') ?></label></span>
            </div>
            </ins>
            <? break;

                  case 'select': ?>
            <ins class="service-group">
            <div class="r"<?= strlen($field['ADDITIONAL_INFO']) && strlen($field['CAPTION']) ? ' title="' . htmlspecialchars($field['ADDITIONAL_INFO']) . '"' : '' ?>>
              <span class="caption"><?= strlen($field['CAPTION']) ? $field['CAPTION'] : $field['ADDITIONAL_INFO'] ?></span>
              <select name="<?=$field['NAME'] ?>" id="<?=$field['ID'] ?>" onchange="resetSelectFromGroup(this, '<?=$uiq ?>');<?=$arResult[ 'ONCHANGE' ] ?>;return true;">
                <? foreach($field['OPTIONS'] as $option): ?>
                <option<? if($option['~SELECTED']): ?> selected="selected"<? endif; ?> value="<?=$option['VALUE'] ?>">
                <?=$option['CAPTION'] ?>
                </option>
                <? endforeach; // foreach($field['OPTIONS'] as $option) ?>
              </select>
              <span class="price"><label for="<?=$field['ID'] ?>"><?=$subservice['~PRICE'] ? $subservice['PRICE'] : GetMessage('TS_BOOKING_SERVICE_FREE') ?></label></span>
            </div>
            </ins>
            <? break;

                default:
                  break;
                }
                
                if(isset($field['NEEDCOMM']) && $field['NEEDCOMM']): ?>
            <div>
              <?= GetMessage('TS_BOOKING_SERVICE_COMMENT') ?>:
              <input type="text" name="<?= $field['NAME'] ?>_COMMENT_DATA" maxlength="200"/>
            </div>
                <? endif;
              endforeach; // foreach ( $flight['FIELDS'] as $group => $service )

            else: // Если данная услуга не является группой услуг  ///if ( is_array($flight['FIELDS']) &&  count($flight['FIELDS']) )

            $field = $flight['FIELD'];
            switch($field['TYPE']) {
              case 'select': ?>
            <select name="<?=$field['NAME'] ?>" onchange="<?=$arResult[ 'ONCHANGE' ] ?>;return true;">
              <? foreach($field['OPTIONS'] as $option): ?>
              <option<? if($option['~SELECTED']): ?> selected="selected"<? endif; ?> value="<?=$option['VALUE'] ?>">
              <?=$option['CAPTION'] ?>
              </option>
              <? endforeach; ?>
            </select>
            <span class="price"><?=$flight['~PRICE'] ? $flight['PRICE'] : GetMessage('TS_BOOKING_SERVICE_FREE') ?></span>
            <? break;
              
              case 'checkbox': ?>
            <input<? if($field['~SELECTED']): ?> checked="checked"<? endif; ?> id="<?=$field['NAME'] ?>" name="<?=$field['NAME'] ?>" onclick="checkSum('<?=$uiq ?>');<?=$arResult[ 'ONCHANGE' ] ?>;return true;" value="<?=$flight['~PRICE'] ?>" type="checkbox" />
            <span class="price">
            <label for="<?=$field['NAME'] ?>"><?=$flight['~PRICE'] ? $flight['PRICE'] : GetMessage('TS_BOOKING_SERVICE_FREE') ?></label>
            </span>
            <? break;
              default:
              break;
            } ?>
            <? if(isset($field['NEEDCOMM']) && $field['NEEDCOMM']): ?>
            <div>
              <?= GetMessage('TS_BOOKING_SERVICE_COMMENT') ?>:
              <input type="text" name="<?= $field['NAME'] ?>_COMMENT_DATA" maxlength="200"/>
            </div>
            <? endif ?>
            <? endif; // if ( is_array($flight['FIELDS']) &&  count($flight['FIELDS']) ) ?>
            <? endif; // if(!empty($flight)) ?>
          </td>
          <? endforeach; // foreach($flights as $flightNum => $flight) ?>
        </tr>
        <? //endif; ?>
        <? endforeach; // foreach($slots as $passengerNum => $passenger) ?>
        <? endforeach; // foreach($arResult['SERVICES'] as $service) ?>
      </tbody>
    </table>
    <?=$arResult['HIDDEN'] ?>
  </form>
  <? endif; //if(!empty($arResult['SERVICES']))?>
  <? if ( isset( $arResult['BUTTONS'] ) ): ?>
  <div class="buttons clearfix">
    <div class="c-back"><?=CTemplateToolsUtil::RenderField($arResult['BUTTONS']['BACK']) ?></div>
    <div class="c-next"><?=CTemplateToolsUtil::RenderField($arResult['BUTTONS']['FORWARD']) ?></div>
  </div>
  <? endif; ?>
</div>
<script type="text/javascript">
//<![CDATA[

var debug;
function togglePassengers(link, code) {
  var passengers = $('.passenger-'+code);
  passengers.toggle();
  var linkText;
  if(passengers.css('display') == 'none') {
    linkTest = '<?=GetMessage('TS_BOOKING_SERVICE_SHOW_PASSENGERS') ?>';
  } else {
    linkTest = '<?=GetMessage('TS_BOOKING_SERVICE_HIDE_PASSENGERS') ?>';
  }
  $(link).html(linkTest);
}

var currentPassenger;

// при клике по групповому чекбоксу, отметить все чекбоксы в группе
function checkAll(el, id) {
  $('.cell-'+id+' input:checkbox').each(function() {
    if ( el.checked && !$(this).is(':checked') ) {
      $(this).attr('checked', 'checked');
    } else if ( !el.checked && $(this).is(':checked') ) {
      $(this).removeAttr('checked');
    }
  });
  el.blur();
}

// проверка группового чекбокса
function checkSum(uiq) {
  var intCheckAll = 0;
  $('.cell-'+uiq+' input:checkbox').each(function(){
    if(this.checked) {
      intCheckAll++;
    }
  });

  if ( $('.cell-'+uiq+' :checkbox').length == intCheckAll ) {
    $('#all-'+uiq).attr('checked', 'checked');
  } else {
    $('#all-'+uiq).removeAttr('checked')
  }
  //$('#all-'+uiq).attr('checked', ($('.cell-'+uiq+' :checkbox').length == intCheckAll) ? 'checked' : '');
}

// при клике на чекбоксе в группе отметить только его
function checkOneFromGroup(el, id) {
  var thisCheck = el.checked;
  $('.cell-'+id+' input:checkbox').removeAttr('checked');
  el.checked = thisCheck;
}

// при выборе значения из списка в группе обнулять другие списки
function resetSelectFromGroup(el, id) {
  $('.cell-'+id+' select').each(function(index, select) {
    if(el.name != select.name) {
      select.selectedIndex = 0;
    }
  });
}

// нажатие кнопки "Выбрать", после выбора места
function approveSeat() {
        var cp = currentPassenger;
        var curSeat = $('#cabin-dialog-'+cp.flightNum+' .seat-selected-'+cp.passNum);

        var data = curSeat.children('input').val().split('|');
  $(cp.hidden).val('1');
  $(cp.hidden_text).val(data[0]);
  $(cp.checkbox).val(data[0]);
  $(cp.num).text(data[1]);
  $(cp.price).text(data[2]);
  $(cp.hidden_sigid).val(data[3]);
  $(cp.hidden_price).val(data[4]);

  $(cp.link).text('<?=GetMessage('TS_BOOKING_SERVICE_CHANGE_SEAT') ?>');
  cp.seatId = curSeat.attr('id');

        $(cp.checkbox + ':not(":checked")').focus().attr('checked', true);

  $('#cabin-dialog-'+cp.flightNum).dialog('close');
  <?= $arResult[ 'ONCHANGE' ] ?>;
}

function CabinSetup() {
  $('.cabin-dialog').dialog({
    autoOpen: false,
    resizable: false,
    dialogClass: "cabin-dialog-container",
    bgiframe: true, //для корректной работы в IE6
    close: function(event, ui) {
      var cp = currentPassenger;

      var seat = $('#cabin-dialog-'+cp.flightNum+' .seat-selected-'+cp.passNum);
      seat.children('span').text('');
      seat.removeClass('seat-selected seat-selected-'+cp.passNum);
      seat.attr('title', '<?=GetMessage('TS_BOOKING_SERVICE_CHOOSE_THIS_SEAT') ?>');

      if(cp.seatId) {
        var curSeat = $('#'+cp.seatId);

        curSeat.addClass('seat-selected seat-selected-'+cp.passNum);
        curSeat.children('span').text(cp.passNum);
        curSeat.children('span').css({
          'background-color': '#FFFFFF',
          'color': '#000000'
        });
        curSeat.attr('title', '<?=GetMessage('TS_BOOKING_SERVICE_SEAT_HERE') ?><br />'+cp.passName);
      }

      tooltip();

      $('#cabin-dialog-'+cp.flightNum+' .forward').hide();
    },
    draggable: false,
    modal: true,
    open: function(event, ui) {
      //$('select').hide();

      var cp = currentPassenger;

      $('#cabin-dialog-'+cp.flightNum+' .seat-selected span').css({
        'background-color': '#FFFFFF',
        'color': '#000000'
      });

      $('#cabin-dialog-'+cp.flightNum+' .seat-selected-'+cp.passNum+' span').css({
        'background-color': '#000000',
        'color': '#FFFFFF'
      });
    },
    width: 500
  });
}

$('.ts-ag-reservation-cabin .seat:not(.seat-occupied):not(.seat-selected)').click(function(){
  var cp = currentPassenger;

  if(!$(this).hasClass('seat-selected')) {
    var prevSeat = $('#cabin-dialog-'+cp.flightNum+' .seat-selected-'+cp.passNum);
    prevSeat.children('span').text('');
    prevSeat.removeClass('seat-selected seat-selected-'+cp.passNum);
    prevSeat.attr('title', '<?=GetMessage('TS_BOOKING_SERVICE_CHOOSE_THIS_SEAT') ?>');

    var curSeat = $(this);
    curSeat.addClass('seat-selected seat-selected-'+cp.passNum);
    curSeat.children('span').text(cp.passNum);
    curSeat.children('span').css({
      'background-color': '#000000',
      'color': '#FFFFFF'
    });
    curSeat.attr('title', '<?=GetMessage('TS_BOOKING_SERVICE_SEAT_HERE') ?><br />'+cp.passName);

    tooltip();

    <? /*
    var data = curSeat.children('input').val().split('|');
    $(cp.hidden).val('1');
    $(cp.hidden_text).val(data[0]);
    $(cp.checkbox).val(data[0]);
    $(cp.num).text(data[1]);
    $(cp.price).text(data[2]);
    $(cp.hidden_sigid).val(data[3]);
    $(cp.hidden_price).val(data[4]);
    $(cp.link).text('<?=GetMessage('TS_BOOKING_SERVICE_CHANGE_SEAT') ?>');
    cp.seatId = this.id;

    $(cp.checkbox+':not(:checked)').click();
    */ ?>

    //$('#cabin-dialog-'+cp.flightNum).dialog('close');

    $('#cabin-dialog-'+cp.flightNum+' .forward').show();
  }
});
//]]>
</script>
<?=$arResult[ 'SCRIPT' ] ?>
<script type="text/javascript">
//<![CDATA[
safeCall(CabinSetup);
//]]>
</script>
<? //trace($arResult); ?>
