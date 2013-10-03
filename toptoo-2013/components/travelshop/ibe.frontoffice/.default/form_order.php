<?  if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/ibe/classes/js_lang/formtools.php");
$APPLICATION->AddHeadString(GetFormToolsStrings());
$APPLICATION->AddHeadString($arResult['SCRIPT']);

/* Диапазон дат */
$bShowDateRange = ( isset( $GLOBALS['arParams']['DISPLAY_MATRIX'])
        && $GLOBALS['arParams']['DISPLAY_MATRIX'] == 'Y'
        && $GLOBALS['arParams']['FARES_DISPLAY_TYPE'] != 'SPLIT_FARES' );

//trace($arResult);
$minDate = 0;
$curMonth = date('n');
$curYear = date('y');
$curFullYear = date('Y');
$arMonths = explode(',', GetMessage('monthNamesShort'));
foreach($arMonths as &$month) {
//  $month = mb_strtolower(str_replace('\'', '', $month), LANG_CHARSET);
  $month = str_replace('\'', '', $month);
}
?>
<? // Вывод канонической ссылки
$matches = preg_split( "([\d]{2})", $APPLICATION->GetCurDir() );
if ( strlen($matches[0]) && strlen($matches[1]) ) { // Если пришли по диплинку вида http://avia.toptoo.com/!/MOW/PAR/23.08.2013+25.08.2013
  $APPLICATION->AddHeadString('<link rel="canonical" href="http://' . $_SERVER["HTTP_HOST"] . $matches[0] . '" />', true); // добавляем каноническую ссылку вида http://avia.toptoo.com/!/MOW/PAR/ 
}
?>
<div class="form_wrap"<?= $arResult['form']['~id'] ? ' id="'.$arResult['form']['~id'].'"' : '' ?>>
<? if ( $arResult[ "~SHOW_FORM" ] ) : ?>
  <? if ('CHARTER' != $arParams['FARES_MODE']): ?>
  <form action="<?= $arResult['form']['action'] ?>" class="form-order clearfix <?= ( $arResult['rt_checked'] ? 'form_rt' : 'form_ow' ) ?>" method="post" id="form_order" name="reg_form" onsubmit="<?= $arResult['form']['onsubmit'] ?>">
    <input name="next_page" type="hidden" value="<?= $arResult['next_page']; ?>" />
    <input name="date_format" type="hidden" value="site" />

    <!-- выбор типа рейса -->
    <fieldset class="route-types clearfix">
      <input type="hidden" name="RT_OW" id="RT_OW" value="<?= ( $arResult['rt_checked'] ? 'RT' : 'OW' ) ?>" />
      <div class="type type_rt<? if($arResult['rt_checked']){ ?> selected<? } ?>"><?=GetMessage("TS_STEP1_SEARCHFORM_ROUTE_TYPE_RT") ?></div>
      <div class="type type_ow<? if($arResult['ow_checked']){ ?> selected<? } ?>"><?=GetMessage("TS_STEP1_SEARCHFORM_ROUTE_TYPE_OW") ?></div>
    </fieldset>
    
    <div class="submit">
      <input id="form_order_submit" class="button" type="submit" value="<?=GetMessage("TS_STEP1_SEARCHFORM_SEARCH") ?>" />
    </div>

    <div class="main_fields clearfix">
      <fieldset class="route clearfix">
        <div class="point departure">
          <div class="location">
            <input class="text" id="depart" name="depart" type="text" value="<?=$arResult['depart'] ?>" />
            <? /*
            <div class="link-container"><?=CTemplateToolsPoint::Link("depart", GetMessage("TS_STEP1_SEARCHFORM_TOOLS_POINT_DEPARTURE_SHORT_TITLE"), GetMessage("TS_STEP1_SEARCHFORM_TOOLS_POINT_DEPARTURE_TITLE")); ?></div>
            */ ?>
          </div>
        </div>
        <div id="route_switch" class="route_switch point"></div>
        <div class="point arrival">
          <div class="location">
            <input class="text" id="arrival" name="arrival" type="text" value="<?=$arResult['arrival'] ?>" />
            <? /*
            <div class="link-container"><?=CTemplateToolsPoint::Link("arrival", GetMessage("TS_STEP1_SEARCHFORM_TOOLS_POINT_ARRIVAL_SHORT_TITLE"), GetMessage("TS_STEP1_SEARCHFORM_TOOLS_POINT_ARRIVAL_TITLE")); ?></div>
            */ ?>
          </div>
        </div>
      </fieldset>
      <? //trace($arResult) ?>
      <fieldset class="dates clearfix">
        <div class="date date_to">
          <div class="date-container">
            <div id="dateto_formated" class="date_formated">
              <span class="day_month"></span>,
              <span class="dow"></span>
            </div>
            <? /*
            <input type="text" id="dateto_formated" value="<?=$arResult['d_to'] ?>" onclick="$('#dateto').focus();" />
            */ ?>
            <input type="text" id="dateto" name="dateto" maxlength="10" size="10" value="<?=$arResult['d_to'] ?>" />
          </div>
        </div>
        <div class="date date_back">
          <div id="add_dateback" class="add_dateback"><?= GetMessage('TS_STEP1_SEARCHFORM_ADD_ARRIVAL_DATE') ?></div>
          <div class="date-container" id="form_dateback">
            <div id="dateback_formated" class="date_formated">
              <span class="day_month"></span>,
              <span class="dow"></span>
            </div>
            <? /*
            <input type="text" id="dateback_formated" value="<?=$arResult['d_back'] ?>" onclick="$('#dateback').focus();" />
            */ ?>
            <input type="text" id="dateback" name="dateback" maxlength="10" size="10" value="<?=$arResult['d_back'] ?>" />
          </div>
        </div>
      </fieldset>
    </div>

    <div class="add_fields clearfix">
      <? if ( $arResult[ "~REWARD_MODE" ] ) : ?>
      <input type="hidden" name="adult" value="1"/>
      <input type="hidden" name="child" value="0"/>
      <input type="hidden" name="infant" value="0"/>
      <? else : ?>
      <fieldset class="passengers clearfix">
        <!-- Взрослые -->
        <div class="passenger adult" id="form_adult_title">
          <label class="title" for="adult">
            <?=GetMessage("TS_STEP1_SEARCHFORM_PASSENGERS_ADULTS") ?>
          </label>
          <? if(count($arResult['select_pcl_adult']['REFERENCE_ID'])): ?>
          <div class="select_wrap">
          <select id="adult" name="adult">
            <? for($i=0; $i<count($arResult['select_pcl_adult']['REFERENCE_ID']); $i++): ?>
            <option<? if($arResult['select_pcl_adult']['REFERENCE_ID'][$i] == $arResult['select_pcl_adult_selected']): ?> selected="selected"<? endif; ?> value="<?=$arResult['select_pcl_adult']['REFERENCE_ID'][$i] ?>">
            <?=$arResult['select_pcl_adult']['REFERENCE'][$i] ?>
            </option>
            <? endfor; ?>
          </select>
          </div>
          <? endif; ?>
        </div>
        <div class="passenger child" id="form_child_title">
          <label class="title" for="child" title="<?=GetMessage("TS_STEP1_SEARCHFORM_PASSENGERS_CHILDREN_TITLE") ?>">
            <?=GetMessage("TS_STEP1_SEARCHFORM_PASSENGERS_CHILDREN") ?>
          </label>
          <? if(count($arResult['select_pcl_child']['REFERENCE_ID'])): ?>
          <div class="select_wrap">
          <select id="child" name="child">
            <? for($i=0; $i<count($arResult['select_pcl_child']['REFERENCE_ID']); $i++): ?>
            <option<? if($arResult['select_pcl_child']['REFERENCE_ID'][$i] == $arResult['select_pcl_child_selected']): ?> selected="selected"<? endif; ?> value="<?=$arResult['select_pcl_child']['REFERENCE_ID'][$i] ?>">
            <?=$arResult['select_pcl_child']['REFERENCE'][$i] ?>
            </option>
            <? endfor; ?>
          </select>
          </div>
          <? endif; ?>
        </div>
        <? if($arResult['allow_infants']): ?>
        <div class="passenger infant" id="form_infant_title">
          <label class="title" for="infant" title="<?=GetMessage("TS_STEP1_SEARCHFORM_PASSENGERS_INFANTS_TITLE") ?>">
            <?=GetMessage("TS_STEP1_SEARCHFORM_PASSENGERS_INFANTS") ?>
          </label>
          <? if(count($arResult['select_pcl_child']['REFERENCE_ID'])): ?>
          <div class="select_wrap">
          <select id="infant" name="infant">
            <? for($i=0; $i<count($arResult['select_pcl_infant']['REFERENCE_ID']); $i++): ?>
            <option<? if($arResult['select_pcl_infant']['REFERENCE_ID'][$i] == $arResult['select_pcl_infant_selected']): ?> selected="selected"<? endif; ?> value="<?=$arResult['select_pcl_infant']['REFERENCE_ID'][$i] ?>">
            <?=$arResult['select_pcl_infant']['REFERENCE'][$i] ?>
            </option>
            <? endfor; ?>
          </select>
          </div>
          <? endif; ?>
        </div>
        <? endif; ?>
      </fieldset>
      <? endif; ?>

      <fieldset class="preferences clearfix">
        <? if(!isset($arParams['DISPLAY_CLASS']) || $arParams['DISPLAY_CLASS'] == 'Y'): ?>
        <div class="preference class clearfix">
          <? if(count($arResult['select_cos']['REFERENCE_ID'])): ?>
          <input type="hidden" name="class" id="service_class" value="<?= $arResult['select_cos_selected'] ?>" />
          <label class="title"><?=GetMessage("TS_STEP1_SEARCHFORM_SERVICE_CLASS") ?></label>
          <div class="service_class_title<? if( $arResult['select_cos']['REFERENCE_ID'][0] == $arResult['select_cos_selected'] || '' == $arResult['select_cos_selected'] || $arResult['select_cos']['REFERENCE_ID'][1] == $arResult['select_cos_selected'] ){ ?> selected<? } ?>" onclick="$('#service_class').val('<?= $arResult['select_cos']['REFERENCE_ID'][0] ?>');">
            <?= GetMessage('TS_STEP1_SEARCHFORM_SERVICE_CLASS_ECONOMY') ?>
          </div>
          <div class="service_class_title<? if( $arResult['select_cos']['REFERENCE_ID'][2] == $arResult['select_cos_selected'] ){ ?> selected<? } ?>" onclick="$('#service_class').val('<?=$arResult['select_cos']['REFERENCE_ID'][2] ?>');">
            <?=$arResult['select_cos']['REFERENCE'][2] ?>
          </div>
          <script type="text/javascript">
          // <![CDATA[
          $('.class .service_class_title').click(function(){
            $('.class .service_class_title').removeClass('selected');
            $(this).addClass('selected');
          });
          // ]]>
          </script>
          <? endif; ?>
        </div>
        <? endif; ?>

        <? if ($arResult['ak_onlysearch'] == '' && (!isset($arParams['DISPLAY_COMPANY']) || $arParams['DISPLAY_COMPANY'] == 'Y')): ?>
        <div class="preference company clearfix">
          <label class="title" for="company"><?=GetMessage("TS_STEP1_SEARCHFORM_COMPANY") ?></label>
          <? if(count($arResult['select_faretype']['REFERENCE_ID'])): ?>
          <select id="company" name="company">
            <? for($i=0; $i<count($arResult['select_ak']['REFERENCE_ID']); $i++): ?>
            <option<? if($arResult['select_ak']['REFERENCE_ID'][$i] == $arResult['select_ak_selected']): ?> selected="selected"<? endif; ?> value="<?=$arResult['select_ak']['REFERENCE_ID'][$i] ?>">
            <?=$arResult['select_ak']['REFERENCE'][$i] ?>
            </option>
            <? endfor; ?>
          </select>
          <? endif; ?>
        </div>
        <? else: ?>
        <input name="company" type="hidden" value="<?=$arResult['ak_onlysearch']?>">
        <? endif; ?>

        <? if (!isset($arParams['DISPLAY_DIRECT']) || $arParams['DISPLAY_DIRECT'] == 'Y'): ?>
        <div class="preference direct clearfix">
          <input<? if($arResult['directonly']): ?> checked="checked"<? endif; ?> id="DirectOnly" name="DirectOnly" type="checkbox" value="1" />
          <label class="title" for="DirectOnly"><?=GetMessage("TS_STEP1_SEARCHFORM_FLIGHT_TYPE") ?></label>
        </div>
        <? endif; ?>

        <? if(isset($arParams['DISPLAY_CURRENCY']) && $arParams['DISPLAY_CURRENCY'] == 'Y'): ?>
        <? $APPLICATION->IncludeComponent("travelshop:ibe.currency", "in_form", array("CURRENCY_DEFAULT" => "RUR")); ?>
        <? endif; ?>
      </fieldset>
    </div>
  </form>
  <? endif; ?>
</div>
<script type="text/javascript">
// <![CDATA[
function switchRouteType(type_val){
  var type = type_val.toLowerCase();
  form = $('#form_order');
  if ( form.find('.route-types .type_'+type).hasClass('selected') || form.hasClass('form_'+type) ) return;
  var prev_type_val = $('#RT_OW').val(),
  prev_type = prev_type_val.toLowerCase();
  form.find('.route-types .selected').removeClass('selected');
  form.find('.route-types .type_'+type).addClass('selected');
  form.removeClass('form_'+prev_type).addClass('form_'+type);
  $('#RT_OW').val(type_val);
}
$('#form_order .route-types .type').click(function() {
  var type = $(this),
  type_val = type.hasClass('type_rt') ? 'RT' : 'OW';
  switchRouteType(type_val);
});
$('#add_dateback').click(function(){ 
  switchRouteType('RT');
  $("#ts_ag_reservation #dateback").focus();
});

$('#route_switch').click(function(){
  var point = $('#depart').val();
  $('#depart').val($('#arrival').val());
  $('#arrival').val(point);
});

// Выделяем содержимое поля ввода пунктов при фокусе
var points_fields = $('input#depart, input#arrival');

points_fields.mouseup(function(e) {
  $(e.target).select().focus();
  e.preventDefault();
});

points_fields.mousedown(function(e) {
  $(e.target).select().focus();
  e.preventDefault();
});

var clear_fields = $('#clear_depart, #clear_arrival');
function clearField( clear ) {
  $('input#'+clear.attr('id').substr(6)).val('').focus();
}
clear_fields.click(function(){ clearField ($(this)); });

formInit();

<? if (file_exists(dirname(__FILE__)."/calendar_scripts.php")){
  $form = '';
  require(dirname(__FILE__)."/calendar_scripts.php");
} else {
  require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/travelshop/ibe.frontoffice/templates/.default/calendar_scripts.php'); 
} ?>

<? 
  $JQ_CALENDAR_NUMBER_OF_MONTHS = intval( $arParams['JQ_CALENDAR_NUMBER_OF_MONTHS'] ) ? intval( $arParams['JQ_CALENDAR_NUMBER_OF_MONTHS'] ) : 1; // Количество отображаемых за раз месяцев во всплывающем календаре. По умолчанию 1.
  $JQ_CALENDAR_STEP_MONTHS = intval( $arParams['JQ_CALENDAR_STEP_MONTHS'] ) ? intval( $arParams['JQ_CALENDAR_STEP_MONTHS'] ) : $JQ_CALENDAR_NUMBER_OF_MONTHS; // На сколько месяцев сдвигаться за раз во всплывающем календаре. По умолчанию равно количеству отображаемых месяцев.
  $JQ_CALENDAR_SHOW_OTHER_MONTHS = ( "Y" ==  $arParams['JQ_CALENDAR_SHOW_OTHER_MONTHS'] ) ? "true" : "false"; // Показывать дни из соседних с выбранным месяцем. По умолчанию нет.
  $JQ_CALENDAR_SELECT_OTHER_MONTHS = ( "Y" ==  $arParams['JQ_CALENDAR_SELECT_OTHER_MONTHS'] ) ? "true" : "false"; // Разрешать выбор дня из соседних с выбранным месяцем. По умолчанию нет.
  $JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR = ( isset($arParams['JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR']) && "Y" ==  $arParams['JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR'] || !isset($arParams['JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR']) ) ? "true" : "false"; // Разрешать выбор месяца и года. По умолчанию нет.
?>
function calendarsSetup() {
  calendarTo.datepicker({ 
    showOn: 'both',
    buttonImage: '<?= $templateFolder ?>/images/date.png',
    buttonText: '<?=GetMessage('TS_SHORTFORM_CALENDAR_BUTTON') ?>',
    buttonImageOnly: true,
    showOtherMonths: <?= $JQ_CALENDAR_SHOW_OTHER_MONTHS ?>,
    selectOtherMonths: <?= $JQ_CALENDAR_SELECT_OTHER_MONTHS ?>,
    changeMonth: <?= $JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR ?>,
    changeYear: <?= $JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR ?>,
    minDate: minDate,
    maxDate: '+1y',
    stepMonths: <?= $JQ_CALENDAR_STEP_MONTHS ?>,
    numberOfMonths: <?= $JQ_CALENDAR_NUMBER_OF_MONTHS ?>,
    beforeShow: function() {
      $("#ui-datepicker-div").removeClass('dateback');
      $("#ui-datepicker-div").addClass('dateto');
    },
    onSelect: function(dateText) {
      selectForwardDate(dateText);
      if ( "RT" == $("#ts_ag_reservation #RT_OW").val() ) {
        setTimeout( function() {$("#ts_ag_reservation #dateback_formated").click(); }, 100 );
      } else {
        $("#ts_ag_reservation #form_order_submit").focus();
      };
    }
    //, altField: "#dateto_formated"
    //, altFormat: "d M, D"
  });
  calendarTo.datepicker('setDate', defaultDateTo);
  tooltip(calendarTo.parent());

  calendarBack.datepicker({ 
    showOn: 'both',
    buttonImage: '<?= $templateFolder ?>/images/date.png',
    buttonText: '<?=GetMessage('TS_SHORTFORM_CALENDAR_BUTTON') ?>',
    buttonImageOnly: true,
    showOtherMonths: <?= $JQ_CALENDAR_SHOW_OTHER_MONTHS ?>,
    selectOtherMonths: <?= $JQ_CALENDAR_SELECT_OTHER_MONTHS ?>,
    changeMonth: <?= $JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR ?>,
    changeYear: <?= $JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR ?>,
    minDate: minDate,
    maxDate: '+1y',
    stepMonths: <?= $JQ_CALENDAR_STEP_MONTHS ?>,
    numberOfMonths: <?= $JQ_CALENDAR_NUMBER_OF_MONTHS ?>,
    beforeShow: function() {
      $("#ui-datepicker-div").removeClass('dateto');
      $("#ui-datepicker-div").addClass('dateback');
    },
    onSelect: function(dateText) { 
      selectBackDate(dateText);
      $("#ts_ag_reservation #form_order_submit").focus();
    }
    //, altField: "#dateback_formated"
    //, altFormat: "d M, D"
  });
  calendarBack.datepicker('setDate', defaultDateBack);
  tooltip(calendarBack.parent());

  /* Дни недели */
  $('#dateto_formated .day_month').text( defaultDateTo.getDate() + ' ' + $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].monthNamesShort[defaultDateTo.getMonth()] );
  $('#dateto_formated .dow').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNamesShort[defaultDateTo.getDay()] );
  if (calendarBack<?= $form ?>.length) {
    $('#dateback_formated .day_month').text( defaultDateBack.getDate() + ' ' + $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].monthNamesShort[defaultDateBack.getMonth()] );
    $('#dateback_formated .dow').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNamesShort[defaultDateBack.getDay()] );
  }
}

$("#ts_ag_reservation #dateto_formated").click(function() {
   $("#ts_ag_reservation #dateto").focus();
});

$("#ts_ag_reservation #dateback_formated").click(function() {
   $("#ts_ag_reservation #dateback").focus();
});

safeCall(calendarsSetup);

 <? if( $USE_AUTOCOMPLETE ): // Если используется автозаполнение ?>
  // подключаем к полям ввода пунктов Autocomplete
  $("#depart, #arrival").autocomplete("<?= $componentPath ?>/get_cities.php", {
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
 
 var currentArrival = '';
 function buildArrivalList() {
   
   currentArrival = $("#ts_ag_reservation form #arrival option:selected").val();
   $("#ts_ag_reservation form #arrival option").each( function (i) { // Удаляем все пункты прибытия
     $(this).remove();
   });
   var depart = $("#ts_ag_reservation form #depart").val();
   if ( routes[depart]["ROUTES"] ) { // Если для выбранного пункта вылета заданы пункты прибытия
     for ( var code in routes[depart]["ROUTES"] ) {
       if ( routes[code] ) { // добавляем их в список
         $("#ts_ag_reservation form #arrival").append('<option value="' + code + '"' + ( currentArrival == code ? ' selected="selected"' : '' ) + '>' + routes[code]["NAME"] + '</option>');
       }
     }
   }
   
 }
 
 buildArrivalList();
 $("#ts_ag_reservation form #depart").change( function () { buildArrivalList() } );

<? endif; // if ( is_array($arResult["ROUTES"]) && count($arResult["ROUTES"]) ): ?>

// ]]>
</script>
<? endif; // ( $arResult[ "~SHOW_FORM" ] ) ?>
