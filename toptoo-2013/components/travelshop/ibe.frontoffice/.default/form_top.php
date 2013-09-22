<? if ( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) { die(); }

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/ibe/classes/js_lang/formtools.php");
$APPLICATION->AddHeadString(GetFormToolsStrings());
$APPLICATION->AddHeadString($arResult['SCRIPT']);
unset( $arResult['SCRIPT'] );

$minDate = 0;
$curMonth = date('n');
$curYear = date('y');
$curFullYear = date('Y');
$arMonths = explode(',', GetMessage('monthNamesShort'));
foreach($arMonths as &$month) {
//$month = mb_strtolower(str_replace('\'', '', $month), LANG_CHARSET);
  $month = str_replace('\'', '', $month);
}

/* Диапазон дат */
$bShowDateRange = ( isset( $GLOBALS['arParams']['DISPLAY_MATRIX'])
        && $GLOBALS['arParams']['DISPLAY_MATRIX'] == 'Y'
        && $GLOBALS['arParams']['FARES_DISPLAY_TYPE'] != 'SPLIT_FARES' );
?>
<script type="text/javascript">
// <![CDATA[
if(typeof tooltip != 'function'){
	//function tooltip(){};
}
// ]]>

function fnPassengersNotice(){
  var arStr = [];
  if ( !$( "#" + cfgPassengersNotice.id ).length ) {
    return;
  }
  var cntAdults = $("form#form_top #form_top_adult").val();
  var cntChildren = $("form#form_top #form_top_child").val();
  var cntInfants = $("form#form_top #form_top_infant").val();

  arStr.push( cfgPassengersNotice.adults + ' ' + cntAdults );
  if ( parseInt( cntChildren ) ){
    arStr.push( cfgPassengersNotice.children + ' ' + cntChildren );
  }
  if ( parseInt( cntInfants ) ) {
    arStr.push( cfgPassengersNotice.infants + ' ' + cntInfants );
  }
  $( "#" + cfgPassengersNotice.id ).html(
    cfgPassengersNotice.passengers + ': ' + arStr.join(" | ")
  );
}
</script>
<div class="form_wrap">
<form action="<?= $arResult['form']['action'] ?>" class="form-order clearfix <?= ( $arResult['rt_checked'] ? 'form_rt' : 'form_ow' ) ?>" method="post" name="reg_form" onsubmit="<?= $arResult['form']['onsubmit'] ?>" style="<?= $arResult['form']['style'] ?>" id="<?= $arResult['form']['~id'] ?>">
  <input name="next_page" type="hidden" value="<?= $arResult['next_page']; ?>" />
  <input name="date_format" type="hidden" value="site" />

  <fieldset class="route-types clearfix">
    <input type="hidden" name="RT_OW" id="rt-ow-val_top" value="<?= ( $arResult['rt_checked'] ? 'RT' : 'OW' ) ?>" />
    <div class="type type_rt<? if($arResult['rt_checked']){ ?> selected<? } ?>"><?=GetMessage("TS_STEP1_SEARCHFORM_ROUTE_TYPE_RT") ?></div>
    <div class="type type_ow<? if($arResult['ow_checked']){ ?> selected<? } ?>"><?=GetMessage("TS_STEP1_SEARCHFORM_ROUTE_TYPE_OW") ?></div>
  </fieldset>

  <fieldset class="route clearfix">
    <div class="point departure">
      <div class="location">
        <input class="text" id="depart_top" name="depart" type="text" value="<?=$arResult['depart'] ?>" />
        <div class="link-container"><?=CTemplateToolsPoint::Link("depart", GetMessage("TS_STEP1_SEARCHFORM_TOOLS_POINT_DEPARTURE_SHORT_TITLE"), GetMessage("TS_STEP1_SEARCHFORM_TOOLS_POINT_DEPARTURE_TITLE")); ?></div>
      </div>
    </div>
    <div id="route_switch_top" class="route_switch point"></div>
    <div class="point arrival">
      <div class="location">
        <input class="text" id="arrival_top" name="arrival" type="text" value="<?=$arResult['arrival'] ?>" />
        <div class="link-container"><?=CTemplateToolsPoint::Link("arrival", GetMessage("TS_STEP1_SEARCHFORM_TOOLS_POINT_ARRIVAL_SHORT_TITLE"), GetMessage("TS_STEP1_SEARCHFORM_TOOLS_POINT_ARRIVAL_TITLE")); ?></div>
      </div>
    </div>
  </fieldset>

  <fieldset class="dates">
    <div class="date date_to">
      <div class="date-container">
        <input type="text" id="dateto_top_formated" value="<?=$arResult['d_to'] ?>" onclick="$('#dateto_top').focus();" />
        <input type="text" id="dateto_top" name="dateto" maxlength="10" size="10" value="<?=$arResult['d_to'] ?>" />
      </div>
    </div>
    <div class="date date_back">
      <div id="add_dateback_top" class="add_dateback"><?= GetMessage('TS_STEP1_SEARCHFORM_ADD_ARRIVAL_DATE') ?></div>
      <div class="date-container" id="form_dateback_top">
        <input type="text" id="dateback_top_formated" value="<?=$arResult['d_back'] ?>" onclick="$('#dateback_top').focus();" />
        <input type="text" id="dateback_top" name="dateback" maxlength="10" size="10" value="<?=$arResult['d_back'] ?>" />
      </div>
    </div>
  </fieldset>
    
  <input type="hidden" name="adult" id="form_top_adult" value="<?= $arResult['select_pcl_adult_selected'] ?>" />
  <input type="hidden" name="child" id="form_top_child" value="<?= $arResult['select_pcl_child_selected'] ?>" />
  <input type="hidden" name="infant" id="form_top_infant" value="<?= $arResult['select_pcl_infant_selected'] ?>" />
  
  <fieldset class="submit top_form_submit"><input class="button" type="submit" value="<?= GetMessage('TS_STEP1_SEARCHFORM_SEARCH') ?>" id="<?= $arResult[ 'SUBMIT' ][ '~ID' ] ?>" style=" <?= $arResult[ 'SUBMIT' ][ 'STYLE' ] ?>" /></fieldset>
  <div class="form_top_notice" id="form_top_passengers_notice"></div>
  <script type="text/javascript">
  // <![CDATA[
  var cfgPassengersNotice = {
    id: 'form_top_passengers_notice',
    passengers: '<?= GetMessage("TS_STEP1_SEARCHFORM_PASSENGERS_TITLE") ?>',
    adults: '<?= GetMessage("TS_STEP1_SEARCHFORM_PASSENGERS_ADULTS") ?>',
    children: '<?= GetMessage("TS_STEP1_SEARCHFORM_PASSENGERS_CHILDREN") ?>',
    infants: '<?= GetMessage("TS_STEP1_SEARCHFORM_PASSENGERS_INFANTS") ?>'
  };
  fnPassengersNotice();
  // ]]>
  </script>
</form>
</div>
<div class="form_tools clearfix">
  <div class="c-next">
  <? $APPLICATION->IncludeComponent
  ( 'travelshop:ibe.currency'
  , ''
  , array
    ( 'CURRENCY_DEFAULT' => $arResult['CURRENCY']
    , 'USE_MERGED_STEPS' => defined('IBE_USE_MERGED_STEPS') && IBE_USE_MERGED_STEPS ? IBE_USE_MERGED_STEPS : 'N'
    , 'IBE_AJAX_MODE' => defined('IBE_AJAX_MODE') && IBE_AJAX_MODE ? IBE_AJAX_MODE : 'N'
    )
  ); ?>
  </div>
</div>


<? if ( isset( $arResult[ "PROGRESS" ] ) ) : ?>
<div class="progress_below_form" id="<?= $arResult[ "PROGRESS" ][ "~ID" ] ?>" style="<?= $arResult[ "PROGRESS" ][ "STYLE" ] ?>">
  <? include( dirname( __FILE__ )."/progress.php" ); ?>
</div>
<? endif; ?>
<script type="text/javascript">
// <![CDATA[
function switchRouteTypeTop(type_val){
  var type = type_val.toLowerCase();
  form = $('#<?= $arResult['form']['~id'] ?>');
  if ( form.find('.route-types .type_'+type).hasClass('selected') || form.hasClass('form_'+type) ) return;
  var prev_type_val = $('#rt-ow-val_top').val(),
  prev_type = prev_type_val.toLowerCase();
  form.find('.route-types .selected').removeClass('selected');
  form.find('.route-types .type_'+type).addClass('selected');
  form.removeClass('form_'+prev_type).addClass('form_'+type);
  $('#rt-ow-val_top').val(type_val);
}
$('#<?= $arResult['form']['~id'] ?> .route-types .type').click(function() {
  var type = $(this),
  type_val = type.hasClass('type_rt') ? 'RT' : 'OW';
  switchRouteTypeTop(type_val);
});
$('#add_dateback_top').click(function(){ switchRouteTypeTop('RT'); });

$('#route_switch_top').click(function(){
  var point = $('#depart_top').val();
  $('#depart_top').val($('#arrival_top').val());
  $('#arrival_top').val(point);
});

formInit();

// Выделяем содержимое поля ввода пунктов при фокусе
var points_fields = $('input#depart_top, input#arrival_top');

points_fields.mouseup(function(e) {
  $(e.target).select().focus();
  e.preventDefault();
});

points_fields.mousedown(function(e) {
  $(e.target).select().focus();
  e.preventDefault();
});

<? $arDateFormat = array();
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

<? 
  $JQ_CALENDAR_NUMBER_OF_MONTHS = intval( $arParams['JQ_CALENDAR_NUMBER_OF_MONTHS'] ) ? intval( $arParams['JQ_CALENDAR_NUMBER_OF_MONTHS'] ) : 1; // Количество отображаемых за раз месяцев во всплывающем календаре. По умолчанию 1.
  $JQ_CALENDAR_STEP_MONTHS = intval( $arParams['JQ_CALENDAR_STEP_MONTHS'] ) ? intval( $arParams['JQ_CALENDAR_STEP_MONTHS'] ) : $JQ_CALENDAR_NUMBER_OF_MONTHS; // На сколько месяцев сдвигаться за раз во всплывающем календаре. По умолчанию равно количеству отображаемых месяцев.
  $JQ_CALENDAR_SHOW_OTHER_MONTHS = ( "Y" ==  $arParams['JQ_CALENDAR_SHOW_OTHER_MONTHS'] ) ? "true" : "false"; // Показывать дни из соседних с выбранным месяцем. По умолчанию нет.
  $JQ_CALENDAR_SELECT_OTHER_MONTHS = ( "Y" ==  $arParams['JQ_CALENDAR_SELECT_OTHER_MONTHS'] ) ? "true" : "false"; // Разрешать выбор дня из соседних с выбранным месяцем. По умолчанию нет.
  $JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR = ( isset($arParams['JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR']) && "Y" ==  $arParams['JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR'] || !isset($arParams['JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR']) ) ? "true" : "false"; // Разрешать выбор месяца и года. По умолчанию нет.
?>

// Календарь 
var dateFormat = {<?=$arDateFormatJS?>};
var date_format = '<?= ($date_format = strtolower(str_replace('YYYY', 'YY', FORMAT_DATE))); ?>';
var defaultDeltaDays = 1;
var oneDay= 0/*1000*60*60*24*/;
var calendarToTop;
var calendarBackTop;
var defaultDateTo;
var defaultDateBack;

  // локализация календаря
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
  
  var minDate = new Date(<?=(time()+$arResult['DATE_OFFSET'])*1000 ?>);
  defaultDateTo = dateSiteToJS($('#dateto_top').val());
  defaultDateBack = dateSiteToJS($('#dateback_top').val());
 
  calendarToTop = $("#dateto_top");  
  calendarBackTop = $("#dateback_top");

function calendarsTopSetup() {
  calendarToTop.datepicker({ 
    showOn: 'both',
    buttonImage: '<?= $templateFolder ?>/images/date.png',
    buttonText: '<?=GetMessage('TS_SHORTFORM_CALENDAR_BUTTON') ?>',
    buttonImageOnly: true,
    
    showOtherMonths: <?= $JQ_CALENDAR_SHOW_OTHER_MONTHS ?>,
    selectOtherMonths: <?= $JQ_CALENDAR_SELECT_OTHER_MONTHS ?>,
    changeMonth: <?= $JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR ?>,
    changeYear: <?= $JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR ?>,
    stepMonths: <?= $JQ_CALENDAR_STEP_MONTHS ?>,
    numberOfMonths: <?= $JQ_CALENDAR_NUMBER_OF_MONTHS ?>,

    minDate: 0,
    maxDate: '+1y',
    onSelect: function(dateText) {
      selectForwardDateTop(dateText)
    },
    altField: "#dateto_top_formated",
    altFormat: "d M, D"
  });
  calendarToTop.datepicker('setDate', defaultDateTo);
  tooltip(calendarToTop.parent());
  
  calendarBackTop.datepicker({ 
    showOn: 'both',
    buttonImage: '<?= $templateFolder ?>/images/date.png',
    buttonText: '<?=GetMessage('TS_SHORTFORM_CALENDAR_BUTTON') ?>',
    buttonImageOnly: true,
    
    showOtherMonths: <?= $JQ_CALENDAR_SHOW_OTHER_MONTHS ?>,
    selectOtherMonths: <?= $JQ_CALENDAR_SELECT_OTHER_MONTHS ?>,
    changeMonth: <?= $JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR ?>,
    changeYear: <?= $JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR ?>,
    stepMonths: <?= $JQ_CALENDAR_STEP_MONTHS ?>,
    numberOfMonths: <?= $JQ_CALENDAR_NUMBER_OF_MONTHS ?>,

    minDate: 0,
    maxDate: '+1y',
    onSelect: function(dateText) { 
      selectBackDateTop(dateText)
    },
    altField: "#dateback_top_formated",
    altFormat: "d M, D"
  });
  calendarBackTop.datepicker('setDate', defaultDateBack);
  tooltip(calendarBackTop.parent());

  /* Дни недели */
  $('#dateto_top-day').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNames[defaultDateTo.getDay()] );
  $('#dateback_top-day').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNames[defaultDateBack.getDay()] );
}

safeCall(calendarsTopSetup);

// Выбор даты рейса "туда"
function selectForwardDateTop(dateText) {
  $('#dateto_top').val(dateText);
  $('#dateto_top-day').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNames[calendarToTop.datepicker('getDate').getDay()] );

  // если дата вылета становится больше даты возврата, то добавляем к дате возврата разницу дней между между датой возврата по умолчанию и датой вылета по умолчанию
  if (calendarToTop.datepicker('getDate') > calendarBackTop.datepicker('getDate')) {
    var newDate = calendarToTop.datepicker('getDate').getTime()+defaultDeltaDays*oneDay;
    newDate = new Date(newDate);
    calendarBackTop.datepicker('setDate', newDate);

    $('#dateback_top-day').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNames[newDate.getDay()] );
  }
}

// Выбор даты рейса "обратно"
function selectBackDateTop(dateText) {
  // если дата вылета становится больше даты возврата, устанавливаем дату вылета на день раньше
  if (calendarToTop.datepicker('getDate') > calendarBackTop.datepicker('getDate')) {
    var newDate = calendarBackTop.datepicker('getDate').getTime() - oneDay;
    newDate = new Date(newDate);
    calendarToTop.datepicker('setDate', newDate);

    $('#dateto_top-day').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNames[newDate.getDay()] );
  }
  $('#dateback_top').val(dateText);
  $('#dateback_top-day').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNames[calendarBackTop.datepicker('getDate').getDay()] );
}

// Преобразование строки с датой в формате сайте в объект javascript-даты
function dateSiteToJS(dateSite) {
  var dateObj = {'day':'', 'month':'', 'year':''};
  for (key in dateObj) {
    dateObj[key] = dateSite.substring(dateFormat[key]['begin'], dateFormat[key]['end']);
  }
  return (new Date(dateObj.year, dateObj.month - 1, dateObj.day));
}

/* Замена исходной функции Datepicker'a */
if ( typeof (tooltipChanged) == 'undefined' || !tooltipChanged ) {
  if(typeof tooltip == 'function') {
    /* Замена исходной функции Datepicker'a */
    var _updateDatepicker_o = $.datepicker._updateDatepicker;
    $.datepicker._updateDatepicker = function(inst){
    _updateDatepicker_o.apply(this, [inst]);
    if ( $(".ui-datepicker .ui-datepicker-prev").css('display') == 'none'
      || $(".ui-datepicker .ui-datepicker-next").css('display') == 'none') {
      $("#tooltip").hide();
    }
    tooltip($('#ui-datepicker-div'));
    }
  }
  var tooltipChanged = true;
}

// Выделяем содержимое поля ввода пунктов при фокусе
var points_fields = $('input#depart_top, input#arrival_top');

points_fields.mouseup(function(e) {
  $(e.target).select().focus();
  e.preventDefault();
});

points_fields.mousedown(function(e) {
  $(e.target).select().focus();
  e.preventDefault();
});

var clear_fields = $('#clear_depart_top, #clear_arrival_top');
if ( typeof(clearField) == undefined ) {
  function clearField( clear ) {
    $('input#'+clear.attr('id').substr(6)).val('').focus();
  }
}
clear_fields.click(function(){ clearField ($(this)); });

 <? if( $USE_AUTOCOMPLETE ): // Если используется автозаполнение ?>
  // подключаем к полям ввода пунктов Autocomplete
  $("#depart_top, #arrival_top").autocomplete("<?= $componentPath ?>/get_cities.php", {
      extraParams: {
        lang: "<?= LANGUAGE_ID ?>" // Язык поиска
      },
      max: 40, // Максимальное количество пунктов в ответе
      scrollHeight: 300, // Высота в px
      autoFill: false, // Автоматически подставлять первый найденный пункт
      autoFillEx: true, // Автоматически подставлять если присутствует только один пункт
      delay: 400, // Задержка перед отправкой запроса (в ms)
      minChars: 2, // Минимальное количество символов, при котором необходимо отправлять запрос
      matchSubset: false, // Показывать только пункты, совпдающие с маской запроса
      selectFirst: true, // Если установить в true, то по нажатию клавиши Tab или Enter будет выбрано то значение, которое в данный момент установлено в элементе ввода
      formatResult: function (row) {
        return row[0].concat(' (', row[1], ')');
      },
      formatItem: function (row, i, total) {
          return row[0] + '<b class="point_info"><em class="code">' + row[1] + '</em> <em class="country">(' + row[2] + ')</em></b>';
        }
    });

  $('#depart_top').result(function(event, data, formatted) {
    TryFocusObj( $('#arrival_top') );
  });

 <? endif; ?>

<? if ( is_array($arResult["ROUTES"]) && count($arResult["ROUTES"]) ): // Если задана маршрутная сеть ?>

 var routes = {
 <? $count = count($arResult["ROUTES"]);
   foreach ( $arResult["ROUTES"] as $code => $info ): // строим копию массива с маршрутной сетью в JS ?>
   "<?= $code ?>" : {
     "NAME" : "<?= $info["NAME"] ?>",
     "ROUTES" : {
   <? foreach ( $info["ROUTES"] as $point ): ?>
       "<?= $point ?>" : "<?= $point ?>"<? if ( end($info["ROUTES"]) !== $point ) echo "," ?>
   <? endforeach; // foreach ( $info["ROUTES"] as $point ) ?>
      }
   }<? if ( --$count ) echo "," ?>
 <? endforeach; // foreach ( $arResult["ROUTES"] as $code => $info ) ?>
 };
 
 var currentArrivalTop = '';
 function buildArrivalListTop() {
   
   currentArrivalTop = $("#ts_ag_reservation form #arrival_top option:selected").val();
   $("#ts_ag_reservation form #arrival_top option").each( function (i) { // Удаляем все пункты прибытия
     $(this).remove();
   });
   var depart = $("#ts_ag_reservation form #depart_top").val();
   if ( routes[depart]["ROUTES"] ) { // Если для выбранного пункта вылета заданы пункты прибытия
     for ( var code in routes[depart]["ROUTES"] ) {
       if ( routes[code] ) { // добавляем их в список
         $("#ts_ag_reservation form #arrival_top").append('<option value="' + code + '"' + ( currentArrivalTop == code ? ' selected="selected"' : '' ) + '>' + routes[code]["NAME"] + '</option>');
       }
     }
   }
   
 }
 
 buildArrivalListTop();
 $("#ts_ag_reservation form #depart_top").change( function () { buildArrivalListTop() } );

<? endif; // if ( is_array($arResult["ROUTES"]) && count($arResult["ROUTES"]) ): ?>


if ( typeof( $.oAjaxSteps ) != 'undefined' ) {
  /* Перед отправкой формы с экрана */
  function updateTopForm(){
    $("form#form_top #depart_top").val( $("form#form_order #depart").val() );
    $("form#form_top #arrival_top").val( $("form#form_order #arrival").val() );

    $("form#form_top #dateto_top").val( $("form#form_order #dateto").val() );
    $("form#form_top #dateback_top").val( $("form#form_order #dateback").val() );

    $("form#form_top #form_top_adult").val( $("form#form_order #adult").val() );
    $("form#form_top #form_top_child").val( $("form#form_order #child").val() );
    $("form#form_top #form_top_infant").val( $("form#form_order #infant").val() );

    if ( $("form#form_order #rt").is(':checked') === true ) {
      $("form#form_top #rt-ow_top").attr( "checked", true );
      $("form#form_top #rt-ow-val_top").val( "RT" );
      $("#form_dateback_title_top").show();
    }
    else {
      $("form#form_top #rt-ow_top").attr( "checked", false );
      $("form#form_top #rt-ow-val_top").val( "OW" );
      $("#form_dateback_title_top").hide();
    }

    defaultDateTo = dateSiteToJS($('#dateto_top').val());
    defaultDateBack = dateSiteToJS($('#dateback_top').val());
    calendarToTop = $("#dateto_top");
    calendarBackTop = $("#dateback_top");
    calendarsTopSetup();
    
    fnPassengersNotice();
  }
  $.oAjaxSteps.add_user_func_before( "form_top", function(){
    updateTopForm();
  } );
  $.oAjaxSteps.add_user_func_before( "form_order", function(){
    updateTopForm();
  } );
  /* При переходе назад с экрана "offer" */
  $.oAjaxSteps.add_user_func_back( "offer", function(){
    
    $("form#form_order #depart").val( $("form#form_top #depart_top").val() );
    $("form#form_order #arrival").val( $("form#form_top #arrival_top").val() );

    $("form#form_order #dateto").val( $("form#form_top #dateto_top").val() );
    $("form#form_order #dateback").val( $("form#form_top #dateback_top").val() );

    if ( $("form#form_top #rt-ow_top").is(':checked') === true ) {
      $("form#form_order #rt").attr( "checked", true );
      displayDateBack();
    }
    else {
      $("form#form_order #ow").attr( "checked", true );
      displayDateBack();
    }

    defaultDateTo = dateSiteToJS($('#dateto').val());
    defaultDateBack = dateSiteToJS($('#dateback').val());
    calendarTo = $("#dateto");
    calendarBack = $("#dateback");
    calendarsInit();

  } );
}

// ]]>
</script>