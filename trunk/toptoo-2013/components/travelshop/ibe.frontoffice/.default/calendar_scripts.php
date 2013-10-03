<? 

// ���������� �� form_order.php ��� ������������� � �� ������ ������� �����-�����

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
  die();
}

if ( defined( "__DATEPICKER" ) && __DATEPICKER == false ) {
  return;
}

$form = $form ? $form : '';
$form_id = $form ? '_' . ToLower($form) : '';


$arDateFormat = array();

if (defined('FORMAT_DATE')) {
  $arDateFormat = array( 
  'day' => array ('begin' => ($pos = strpos(FORMAT_DATE, 'D')), 'end' => $pos + 2),
  'month' => array ('begin' => ($pos = strpos(FORMAT_DATE, 'M')), 'end' => $pos + 2),
  'year' => array ('begin' => ($pos = strpos(FORMAT_DATE, 'Y')), 'end' => $pos + 4)
  );
  $arDateFormatJS = '"day":{"begin":'.strpos(FORMAT_DATE, 'D').',"end":'.(strpos(FORMAT_DATE, 'D') + 2).'},';
  $arDateFormatJS .= '"month":{"begin":'.strpos(FORMAT_DATE, 'M').',"end":'.(strpos(FORMAT_DATE, 'M') + 2).'},';
  $arDateFormatJS .= '"year":{"begin":'.strpos(FORMAT_DATE, 'Y').',"end":'.(strpos(FORMAT_DATE, 'Y') + 4).'}';
}

?>
//<script>
// ��������� 
var dateFormat = {<?=$arDateFormatJS?>};
var date_format = '<?= ($date_format = strtolower(str_replace('YYYY', 'YY', FORMAT_DATE))); ?>';
var defaultDeltaDays = 1;
var oneDay= 0/*1000*60*60*24*/;
var calendarTo<?= $form ?> = false;
var calendarBack<?= $form ?> = false;
var defaultDateTo;
var defaultDateBack;
var DatepickerCalledId = false;

// ��������� ���������� (��� �������� ��������, � �.�., ����� AJAX)
function calendarsInit() {
  // ����������� ���������
  $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'] = {
    closeText: '<?=GetMessage('closeText') ?>',
    prevText: '<?=GetMessage('prevText') ?>',
    nextText: '<?=GetMessage('nextText') ?>',
    currentText: '<?=GetMessage('currentText') ?>',
    monthNames: [<?=GetMessage('monthNames') ?>],
    monthNamesShort: [<?=GetMessage('monthNamesShort') ?>],
    dayNames: [<?=GetMessage('dayNames') ?>],
    dayNamesShort: [<?=GetMessage('dayNamesShort') ?>],
    dayNamesMin: [<?=GetMessage('dayNamesMin') ?>],
    dateFormat: '<?= $date_format; ?>', 
    firstDay: <?=GetMessage('firstDay') ?>,
    isRTL: <?=GetMessage('isRTL') ?>};
  $.datepicker.setDefaults($.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>']);
  
<? $arMinDate = getdate(CIBEOffer_getTimeLimit() - 12 * 3600); ?>
  minDate = new Date(<?= $arMinDate['year'] . ', ' . ($arMinDate['mon'] - 1) . ', ' . $arMinDate['mday']; ?>);

  calendarTo<?= $form ?> = $('#dateto<?= $form_id ?>');
  calendarBack<?= $form ?> = $('#dateback<?= $form_id ?>');

  defaultDateTo = dateSiteToJS(calendarTo<?= $form ?>.val());
  if (calendarBack<?= $form ?>.length) {
    defaultDateBack = dateSiteToJS(calendarBack<?= $form ?>.val());
  }

  /* ��� ������ */
  $('#dateto<?= $form_id ?>_formated .day_month').text( defaultDateTo.getDate() + ' ' + $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].monthNamesShort[defaultDateTo.getMonth()] );
  $('#dateto<?= $form_id ?>_formated .dow').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNamesShort[defaultDateTo.getDay()] );
  if (calendarBack<?= $form ?>.length) {
    $('#dateback<?= $form_id ?>_formated .day_month').text( defaultDateBack.getDate() + ' ' + $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].monthNamesShort[defaultDateBack.getMonth()] );
    $('#dateback<?= $form_id ?>_formated .dow').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNamesShort[defaultDateBack.getDay()] );
  }

  if (typeof tooltip == 'function') {
    /* ������ �������� ������� Datepicker'a */
    var _updateDatepicker_o = $.datepicker._updateDatepicker;
    $.datepicker._updateDatepicker = function(inst) {
      _updateDatepicker_o.apply(this, [inst]);
      if ( $(".ui-datepicker .ui-datepicker-prev").css('display') == 'none'
        || $(".ui-datepicker .ui-datepicker-next").css('display') == 'none') {
        $("#tooltip").hide();
      }
      tooltip($('#ui-datepicker-div'));
    }
  }
}

// ����� ���� ����� "����"
function selectForwardDate<?= $form ?>(dateText) {
  $('#dateto<?= $form_id ?>').val(dateText);
  var h = calendarTo<?= $form ?>.datepicker('getDate');
  $('#dateto<?= $form_id ?>_formated .day_month').text( h.getDate() + ' ' + $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].monthNamesShort[h.getMonth()] );
  $('#dateto<?= $form_id ?>_formated .dow').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNamesShort[h.getDay()] );

  // ���� ���� ������ ���������� ������ ���� ��������, �� ��������� � ���� �������� ������� ���� ����� ����� ����� �������� �� ��������� � ����� ������ �� ���������
  if (calendarBack<?= $form ?>.length && calendarTo<?= $form ?>.datepicker('getDate') > calendarBack<?= $form ?>.datepicker('getDate')) {
    var newDate = calendarTo<?= $form ?>.datepicker('getDate').getTime()+defaultDeltaDays*oneDay;
    newDate = new Date(newDate);
    calendarBack<?= $form ?>.datepicker('setDate', newDate);
    $('#dateback<?= $form_id ?>_formated .day_month').text( newDate.getDate() + ' ' + $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].monthNamesShort[newDate.getMonth()] );
    $('#dateback<?= $form_id ?>_formated .dow').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNamesShort[newDate.getDay()] );
  }
}

// ����� ���� ����� "�������"
function selectBackDate<?= $form ?>(dateText) {
  // ���� ���� ������ ���������� ������ ���� ��������, ������������� ���� ������ �� ���� ������
  if (calendarTo<?= $form ?>.datepicker('getDate') > calendarBack<?= $form ?>.datepicker('getDate')) {
    var newDate = calendarBack<?= $form ?>.datepicker('getDate').getTime() - oneDay;
    newDate = new Date(newDate);
    calendarTo<?= $form ?>.datepicker('setDate', newDate);
    $('#dateto<?= $form_id ?>_formated .day_month').text( newDate.getDate() + ' ' + $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].monthNamesShort[newDate.getMonth()] );
    $('#dateto<?= $form_id ?>_formated .dow').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNamesShort[newDate.getDay()] );
  }
  $('#dateback<?= $form_id ?>').val(dateText);
  var h = calendarBack<?= $form ?>.datepicker('getDate');
  $('#dateback<?= $form_id ?>_formated .day_month').text( h.getDate() + ' ' + $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].monthNamesShort[h.getMonth()] );
  $('#dateback<?= $form_id ?>_formated .dow').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNamesShort[h.getDay()] );
}

/*
// ��� ������ ��������� ����
$('#dateto<?= $form_id ?>').change(function(){
  // ���� ���� ������ ���������� ������ ���� ��������, �� ��������� � ���� �������� ������� ���� ����� ����� ����� �������� �� ��������� � ����� ������ �� ���������
  if (calendarBack<?= $form ?>.length && calendarTo<?= $form ?>.datepicker('getDate') > calendarBack<?= $form ?>.datepicker('getDate')) {
    var newDate = calendarTo<?= $form ?>.datepicker('getDate').getTime()+defaultDeltaDays*oneDay;
    newDate = new Date(newDate);
    calendarBack<?= $form ?>.datepicker('setDate', newDate);
    $('#dateback<?= $form_id ?>_formated .day_month').text( newDate.getDate() + ' ' + $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].monthNamesShort[newDate.getMonth()] );
    $('#dateback<?= $form_id ?>_formated .dow').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNamesShort[newDate.getDay()] );
  }
  var h = calendarTo<?= $form ?>.datepicker('getDate');
  $('#dateto<?= $form_id ?>_formated .day_month').text( h.getDate() + ' ' + $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].monthNamesShort[h.getMonth()] );
  $('#dateto<?= $form_id ?>_formated .dow').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNamesShort[h.getDay()] );
});

$('#dateback<?= $form_id ?>').change(function(){
  // ���� ���� ������ ���������� ������ ���� ��������, ������������� ���� ������ �� ���� ������
  if (calendarTo<?= $form ?>.datepicker('getDate') > calendarBack<?= $form ?>.datepicker('getDate')) {
    var newDate = calendarBack<?= $form ?>.datepicker('getDate').getTime() - oneDay;
    newDate = new Date(newDate);
    calendarTo<?= $form ?>.datepicker('setDate', newDate);
    $('#dateto<?= $form_id ?>_formated .day_month').text( newDate.getDate() + ' ' + $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].monthNamesShort[newDate.getMonth()] );
    $('#dateto<?= $form_id ?>_formated .dow').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNamesShort[newDate.getDay()] );
  } 
  var h = calendarBack<?= $form ?>.datepicker('getDate');
  $('#dateback<?= $form_id ?>_formated .day_month').text( h.getDate() + ' ' + $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].monthNamesShort[h.getMonth()] );
    $('#dateback<?= $form_id ?>_formated .dow').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNamesShort[h.getDay()] );
});
*/

// �������������� ������ � ����� � ������� ����� � ������ javascript-����
if ( typeof(dateSiteToJS) !== 'function' ) {
  function dateSiteToJS(dateSite) {
    if ( typeof( dateSite ) == 'undefined' ) {
      dateSite = '';
    }
    var dateObj = {'day':'', 'month':'', 'year':''};
    for (key in dateObj) {
      dateObj[key] = dateSite.substring(dateFormat[key]['begin'], dateFormat[key]['end']);
    }
    return (new Date(dateObj.year, dateObj.month - 1, dateObj.day));
  }
}

$(document).keydown(function(e) {
  if (27 == e.keyCode && false != DatepickerCalledId) {
    $('#date'.concat(DatepickerCalledId)).datepicker('hide');
  }
});

calendarsInit();