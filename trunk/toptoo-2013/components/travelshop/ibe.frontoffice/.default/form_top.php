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

/* �������� ��� */
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
        <? /*
        <div class="link-container"><?=CTemplateToolsPoint::Link("depart", GetMessage("TS_STEP1_SEARCHFORM_TOOLS_POINT_DEPARTURE_SHORT_TITLE"), GetMessage("TS_STEP1_SEARCHFORM_TOOLS_POINT_DEPARTURE_TITLE")); ?></div>
        */ ?>
      </div>
    </div>
    <div id="route_switch_top" class="route_switch point"></div>
    <div class="point arrival">
      <div class="location">
        <input class="text" id="arrival_top" name="arrival" type="text" value="<?=$arResult['arrival'] ?>" />
        <? /*
        <div class="link-container"><?=CTemplateToolsPoint::Link("arrival", GetMessage("TS_STEP1_SEARCHFORM_TOOLS_POINT_ARRIVAL_SHORT_TITLE"), GetMessage("TS_STEP1_SEARCHFORM_TOOLS_POINT_ARRIVAL_TITLE")); ?></div>
        */ ?>
      </div>
    </div>
  </fieldset>

  <fieldset class="dates">
    <div class="date date_to">
      <div class="date-container">
        <div id="dateto_top_formated" class="date_formated">
          <span class="day_month"></span>,
          <span class="dow"></span>
        </div>
        <? /*
        <input type="text" id="dateto_top_formated" value="<?=$arResult['d_to'] ?>" onclick="$('#dateto_top').focus();" />
        */ ?>
        <input type="text" id="dateto_top" name="dateto" maxlength="10" size="10" value="<?=$arResult['d_to'] ?>" />
      </div>
    </div>
    <div class="date date_back">
      <div id="add_dateback_top" class="add_dateback"><?= GetMessage('TS_STEP1_SEARCHFORM_ADD_ARRIVAL_DATE') ?></div>
      <div class="date-container" id="form_dateback_top">
        <div id="dateback_top_formated" class="date_formated">
          <span class="day_month"></span>,
          <span class="dow"></span>
        </div>
        <? /*
        <input type="text" id="dateback_top_formated" value="<?=$arResult['d_back'] ?>" onclick="$('#dateback_top').focus();" />
        */ ?>
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

// �������� ���������� ���� ����� ������� ��� ������
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

formInit();

<? if (file_exists(dirname(__FILE__)."/calendar_scripts.php")){
  $form = 'Top';
  require(dirname(__FILE__)."/calendar_scripts.php");
} ?>

<? 
  $JQ_CALENDAR_NUMBER_OF_MONTHS = intval( $arParams['JQ_CALENDAR_NUMBER_OF_MONTHS'] ) ? intval( $arParams['JQ_CALENDAR_NUMBER_OF_MONTHS'] ) : 1; // ���������� ������������ �� ��� ������� �� ����������� ���������. �� ��������� 1.
  $JQ_CALENDAR_STEP_MONTHS = intval( $arParams['JQ_CALENDAR_STEP_MONTHS'] ) ? intval( $arParams['JQ_CALENDAR_STEP_MONTHS'] ) : $JQ_CALENDAR_NUMBER_OF_MONTHS; // �� ������� ������� ���������� �� ��� �� ����������� ���������. �� ��������� ����� ���������� ������������ �������.
  $JQ_CALENDAR_SHOW_OTHER_MONTHS = ( "Y" ==  $arParams['JQ_CALENDAR_SHOW_OTHER_MONTHS'] ) ? "true" : "false"; // ���������� ��� �� �������� � ��������� �������. �� ��������� ���.
  $JQ_CALENDAR_SELECT_OTHER_MONTHS = ( "Y" ==  $arParams['JQ_CALENDAR_SELECT_OTHER_MONTHS'] ) ? "true" : "false"; // ��������� ����� ��� �� �������� � ��������� �������. �� ��������� ���.
  $JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR = ( isset($arParams['JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR']) && "Y" ==  $arParams['JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR'] || !isset($arParams['JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR']) ) ? "true" : "false"; // ��������� ����� ������ � ����. �� ��������� ���.
?>

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
      selectForwardDateTop(dateText);
      if ( "RT" == $("#form_top #rt-ow-val_top").val() ) {
      } else {
        $("#form_top .top_form_submit input").focus();
      };
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
      selectBackDateTop(dateText);
      $("#form_top .top_form_submit input").focus();
    },
    altField: "#dateback_top_formated",
    altFormat: "d M, D"
  });
  calendarBackTop.datepicker('setDate', defaultDateBack);
  tooltip(calendarBackTop.parent());

  /* ��� ������ */
  $('#dateto_top_formated .day_month').text( defaultDateTo.getDate() + ' ' + $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].monthNamesShort[defaultDateTo.getMonth()] );
  $('#dateto_top_formated .dow').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNamesShort[defaultDateTo.getDay()] );
  if (calendarBack<?= $form ?>.length) {
    $('#dateback_top_formated .day_month').text( defaultDateBack.getDate() + ' ' + $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].monthNamesShort[defaultDateBack.getMonth()] );
    $('#dateback_top_formated .dow').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNamesShort[defaultDateBack.getDay()] );
  }
}

$("#form_top #dateto_top_formated").click(function() {
   $("#form_top #dateto_top").focus();
});

$("#form_top #dateback_top_formated").click(function() {
   $("#form_top #dateback_top").focus();
});

safeCall(calendarsTopSetup);

 <? if( $USE_AUTOCOMPLETE ): // ���� ������������ �������������� ?>
  // ���������� � ����� ����� ������� Autocomplete
  $("#depart_top, #arrival_top").autocomplete("<?= $componentPath ?>/get_cities.php", {
      extraParams: {
        lang: "<?= LANGUAGE_ID ?>" // ���� ������
      },
      max: 40, // ������������ ���������� ������� � ������
      scrollHeight: 300, // ������ � px
      autoFill: false, // ������������� ����������� ������ ��������� �����
      autoFillEx: true, // ������������� ����������� ���� ������������ ������ ���� �����
      delay: 400, // �������� ����� ��������� ������� (� ms)
      minChars: 2, // ����������� ���������� ��������, ��� ������� ���������� ���������� ������
      matchSubset: false, // ���������� ������ ������, ���������� � ������ �������
      selectFirst: true, // ���� ���������� � true, �� �� ������� ������� Tab ��� Enter ����� ������� �� ��������, ������� � ������ ������ ����������� � �������� �����
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

<? if ( is_array($arResult["ROUTES"]) && count($arResult["ROUTES"]) ): // ���� ������ ���������� ���� ?>

 var routes = {
 <? $count = count($arResult["ROUTES"]);
   foreach ( $arResult["ROUTES"] as $code => $info ): // ������ ����� ������� � ���������� ����� � JS ?>
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
   $("#ts_ag_reservation form #arrival_top option").each( function (i) { // ������� ��� ������ ��������
     $(this).remove();
   });
   var depart = $("#ts_ag_reservation form #depart_top").val();
   if ( routes[depart]["ROUTES"] ) { // ���� ��� ���������� ������ ������ ������ ������ ��������
     for ( var code in routes[depart]["ROUTES"] ) {
       if ( routes[code] ) { // ��������� �� � ������
         $("#ts_ag_reservation form #arrival_top").append('<option value="' + code + '"' + ( currentArrivalTop == code ? ' selected="selected"' : '' ) + '>' + routes[code]["NAME"] + '</option>');
       }
     }
   }
   
 }
 
 buildArrivalListTop();
 $("#ts_ag_reservation form #depart_top").change( function () { buildArrivalListTop() } );

<? endif; // if ( is_array($arResult["ROUTES"]) && count($arResult["ROUTES"]) ): ?>


if ( typeof( $.oAjaxSteps ) != 'undefined' ) {
  /* ����� ��������� ����� � ������ */
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
  /* ��� �������� ����� � ������ "offer" */
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