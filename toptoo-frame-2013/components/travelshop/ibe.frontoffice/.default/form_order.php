<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<? if ( $arResult[ "~SHOW_FORM" ] ) : ?>
  <? if ( $arParams['FARES_MODE'] == 'CHARTER' ): ?>
  <? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/ibe/classes/js_lang/formtools.php");
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

  <? if ( strlen($arParams['RESERVATION_URL']) ){
    if ( preg_match('/.php$/', $arParams['RESERVATION_URL']) ){
      $arResult['form']['action'] = $arParams['RESERVATION_URL'] . '?tsi_frontoffice_cmd=order_switch';
    } elseif (  preg_match('/\/$/', $arParams['RESERVATION_URL']) ) {
      $arResult['form']['action'] = $arParams['RESERVATION_URL'] . 'index.php?tsi_frontoffice_cmd=order_switch';
    } else {
      $arResult['form']['action'] = $arParams['RESERVATION_URL'] . '/index.php?tsi_frontoffice_cmd=order_switch';
    }
  } ?>

<div class="from_order_wrap clearfix">
  <div class="debug"></div>
  <form action="<?= $arResult['form']['action']; ?>" class="form-order form-order-charter clearfix" method="post" id="form_order" name="reg_form" onSubmit="<?= $arResult['form']['onsubmit']; ?>" target="_top">
    <input name="next_page" type="hidden" value="<?= $arResult['next_page']; ?>" />
    <input name="date_format" type="hidden" value="site" />
    <div class="caption">
      <h2><?= GetMessage('TS_STEP1_SEARCHFORM_CAPTION') ?></h2>
      <h3><?= GetMessage('TS_STEP1_SEARCHFORM_SUBTITLE') ?></h3>
    </div>
    <? // Выбор городов вылета и прилёта ?>
    <fieldset class="route clearfix">
      <div class="point point_dep">
        <div class="location">
          <label class="title" for="depart"><?= GetMessage('TS_STEP1_SEARCHFORM_DEPARTURE'); ?></label>
          <? if (count($arResult['select_countries_depart']['REFERENCE']) > 0 && count($arResult['select_points_depart']['REFERENCE']) > 0): ?>
            <? // Страна вылета
            $selectedItem = '';
            foreach ( $arResult['select_countries_depart']['REFERENCE'] as $refKey => $refVal ) {
              if ( $arResult['select_countries_depart']['REFERENCE_ID'][$refKey] == $arResult['select_countries_depart_selected'] ) {
                $selectedItem = $refVal;
                break;
              }
            } 
            $selectedItem = $selectedItem ? $selectedItem : reset($arResult['select_countries_depart']['REFERENCE']);
            ?>
          <div class="select_wrap">
            <span class="value" id="country_depart_alt"><?= $selectedItem ?></span>
            <select id="country_depart" name="country_depart">
              <? foreach ( $arResult['select_countries_depart']['REFERENCE'] as $refKey => $refVal ): ?>
              <? $bSelected = ($arResult['select_countries_depart']['REFERENCE_ID'][$refKey] == $arResult['select_countries_depart_selected']); ?>
              <option value="<?= $arResult['select_countries_depart']['REFERENCE_ID'][$refKey]; ?>" <? echo ($bSelected ? ' selected="selected"' : ''); ?>>
              <?= $refVal; ?>
              </option>
              <? endforeach; ?>
            </select>
          </div>
          <? // Город вылета
          $selectedItem = '';
          foreach ( $arResult['select_points_depart']['REFERENCE'] as $refKey => $refVal ){
            if ( $arResult['select_points_depart']['REFERENCE_ID'][$refKey] == $arResult['select_points_depart_selected'] ) {
              $selectedItem = $refVal;
              break;
            }
          } 
          $selectedItem = $selectedItem ? $selectedItem : reset($arResult['select_points_depart']['REFERENCE']);
          ?>
          <div class="select_wrap">
            <span class="value" id="depart_alt"><?= $selectedItem ?></span>
            <select id="depart" name="depart">
              <? foreach($arResult['select_points_depart']['REFERENCE'] as $refKey => $refVal): ?>
              <? $bSelected = ($arResult['select_points_depart']['REFERENCE_ID'][$refKey] == $arResult['select_points_depart_selected']); ?>
              <option value="<?= $arResult['select_points_depart']['REFERENCE_ID'][$refKey]; ?>" <? echo ($bSelected ? ' selected="selected"' : ''); ?>>
              <?= $refVal; ?>
              </option>
              <? endforeach; ?>
            </select>
          </div>
          <? else: ?>
          <div class="input_wrap">
            <input class="text" id="depart" name="depart" type="text" value="<?= $arResult['depart']; ?>" />
            <div class="clear_field" id="clear_depart">&nbsp;</div>
          </div>
          <? endif; ?>
        </div>
      </div>
      <div class="point point_arr">
        <div class="location">
          <label class="title" for="arrival"><?= GetMessage('TS_STEP1_SEARCHFORM_ARRIVAL'); ?></label>
          <? if (count($arResult['select_countries_arrival']['REFERENCE']) && count($arResult['select_points_arrival']['REFERENCE'])): ?>
          <? // Страна прилёта 
          $selectedItem = '';
            foreach ( $arResult['select_countries_arrival']['REFERENCE'] as $refKey => $refVal ) {
              if ( $arResult['select_countries_arrival']['REFERENCE_ID'][$refKey] == $arResult['select_countries_arrival_selected'] ) {
                $selectedItem = $refVal;
                break;
              }
            } 
            $selectedItem = $selectedItem ? $selectedItem : reset($arResult['select_countries_arrival']['REFERENCE']);
            ?>
          <div class="select_wrap">
            <span class="value" id="country_arrival_alt"><?= $selectedItem ?></span>
            <select id="country_arrival" name="country_arrival">
              <? foreach($arResult['select_countries_arrival']['REFERENCE'] as $refKey => $refVal): ?>
              <? $bSelected = ($arResult['select_countries_arrival']['REFERENCE_ID'][$refKey] == $arResult['select_countries_arrival_selected']); ?>
              <option value="<?= $arResult['select_countries_arrival']['REFERENCE_ID'][$refKey]; ?>" <? echo ($bSelected ? ' selected="selected"' : ''); ?>>
              <?= $refVal; ?>
              </option>
              <? endforeach; ?>
            </select>
          </div>
          <? // Город прилёта
          $selectedItem = '';
          foreach ( $arResult['select_points_arrival']['REFERENCE'] as $refKey => $refVal ){
            if ( $arResult['select_points_arrival']['REFERENCE_ID'][$refKey] == $arResult['select_points_arrival_selected'] ) {
              $selectedItem = $refVal;
              break;
            }
          } 
          $selectedItem = $selectedItem ? $selectedItem : reset($arResult['select_points_arrival']['REFERENCE']);
          ?>
          <div class="select_wrap">
            <span class="value" id="arrival_alt"><?= $selectedItem ?></span>
            <select id="arrival" name="arrival">
              <? foreach($arResult['select_points_arrival']['REFERENCE'] as $refKey => $refVal): ?>
              <? $bSelected = ($arResult['select_points_arrival']['REFERENCE_ID'][$refKey] == $arResult['select_points_arrival_selected']); ?>
              <option value="<?= $arResult['select_points_arrival']['REFERENCE_ID'][$refKey]; ?>" <? echo ($bSelected ? ' selected="selected"' : ''); ?>>
              <?= $refVal; ?>
              </option>
              <? endforeach; ?>
            </select>
          </div>
          <? else: ?>
          <div class="input_wrap">
            <input class="text" id="arrival" name="arrival" type="text" value="<?= $arResult['arrival']; ?>" />
            <div class="clear_field" id="clear_arrival">&nbsp;</div>
          </div>
          <? endif; ?>
        </div>
      </div>
    </fieldset>

    <? // Выбор даты вылета ?>
    <fieldset class="dates">
      <div class="date date_to">
        <label class="title" for="dateto"><?= GetMessage('TS_STEP1_SEARCHFORM_DEPARTURE_DATE'); ?></label>
        <div class="date-container">
          <div id="dateto_formated" class="date_formated">
            <span class="day_month"></span>,
            <span class="dow"></span>
          </div>
          <input type="text" class="text" id="dateto" name="dateto" maxlength="10" size="10" value="<?=$arResult['d_to'] ?>" />
        </div>
      </div>
    </fieldset>
    
    <fieldset class="passengers">
      <? // Взрослые ?>
      <div class="passenger adult" id="form_adult_title">
        <label class="title" for="adult"><?= GetMessage('TS_STEP1_SEARCHFORM_PASSENGERS_ADULTS'); ?></label>
        <? if (count($arResult['select_pcl_adult']['REFERENCE_ID'])): ?>
        <div class="select_wrap">
          <span class="value" id="adult_alt"><?=$arResult['select_pcl_adult_selected']?></span>
          <select id="adult" name="adult">
            <? for ($i = 0; $i < count($arResult['select_pcl_adult']['REFERENCE_ID']); $i++): ?>
            <option<? if ($arResult['select_pcl_adult']['REFERENCE_ID'][$i] == $arResult['select_pcl_adult_selected']): ?> selected="selected"<? endif; ?> value="<?= $arResult['select_pcl_adult']['REFERENCE_ID'][$i]; ?>">
            <?= $arResult['select_pcl_adult']['REFERENCE'][$i]; ?>
            </option>
            <? endfor; ?>
          </select>
        </div>
        <? endif; ?>
      </div>
      <? // Дети ?>
      <div class="passenger child" id="form_child_title">
        <label class="title" for="child" title="<?= GetMessage('TS_STEP1_SEARCHFORM_PASSENGERS_CHILDREN_TITLE'); ?>"><?= GetMessage('TS_STEP1_SEARCHFORM_PASSENGERS_CHILDREN'); ?></label>
        <? if (count($arResult['select_pcl_child']['REFERENCE_ID'])): ?>
        <div class="select_wrap">
          <span class="value" id="child_alt"><?=$arResult['select_pcl_child_selected']?></span>
          <select id="child" name="child">
            <? for ($i = 0; $i < count($arResult['select_pcl_child']['REFERENCE_ID']); $i++): ?>
            <option<? if ($arResult['select_pcl_child']['REFERENCE_ID'][$i] == $arResult['select_pcl_child_selected']): ?> selected="selected"<? endif; ?> value="<?= $arResult['select_pcl_child']['REFERENCE_ID'][$i]; ?>">
            <?= $arResult['select_pcl_child']['REFERENCE'][$i]; ?>
            </option>
            <? endfor; ?>
          </select>
        </div>
        <? endif; ?>
      </div>
      <? // Младенцы ?>
      <div class="passenger infant" id="form_infant_title">
        <label class="title" for="infant" title="<?= GetMessage('TS_STEP1_SEARCHFORM_PASSENGERS_INFANTS_TITLE'); ?>"><?= GetMessage('TS_STEP1_SEARCHFORM_PASSENGERS_INFANTS'); ?></label>
        <? if (count($arResult['select_pcl_infant']['REFERENCE_ID'])): ?>
        <div class="select_wrap">
          <span class="value" id="infant_alt"><?=$arResult['select_pcl_infant_selected']?></span>
          <select id="infant" name="infant">
            <? for ($i = 0; $i < count($arResult['select_pcl_infant']['REFERENCE_ID']); $i++): ?>
            <option<? if ($arResult['select_pcl_infant']['REFERENCE_ID'][$i] == $arResult['select_pcl_infant_selected']): ?> selected="selected"<? endif; ?> value="<?= $arResult['select_pcl_infant']['REFERENCE_ID'][$i]; ?>">
            <?= $arResult['select_pcl_infant']['REFERENCE'][$i]; ?>
            </option>
            <? endfor; ?>
          </select>
        </div>
        <? endif; ?>
      </div>
    </fieldset>
    <fieldset class="submit">
      <button class="button" type="submit" id="form_order_submit"><span class="bg"><span><?= GetMessage('TS_STEP1_SEARCHFORM_SEARCH'); ?></span></span></button>
    </fieldset>

    <fieldset class="type">
      <input type="hidden" id="RT_OW" name="RT_OW" value="<? if ($arResult['rt_checked']) { ?>RT<? } else { ?>OW<? } ?>" />
      <div class="checker<? if ($arResult['rt_checked']){ ?> selected<? } ?>" id="rt_charter">
        <?= GetMessage('TS_STEP1_SEARCHFORM_ROUTE_TYPE_RT_CHARTER') ?>
      </div>
    </fieldset>

  </form>
</div>


<script type="text/javascript">
// <![CDATA[
$('#rt_charter').click(function(){
  var rt_ow, rt_ow_prev = $('#RT_OW').val();
  if ( rt_ow_prev == 'RT' ){
    $(this).removeClass('selected');
    rt_ow = 'OW';
  } else {
    $(this).addClass('selected');
    rt_ow = 'RT';
  }
  $('#RT_OW').val( rt_ow );
})

function setPointValue(id) {
  var point, loc = $(id);
  if ( loc.is('select') ){
    point = loc.find('option:selected').length ? loc.find('option:selected').text() : loc.find('option:first').text();
  } else {
    point = loc.val();
  }
  $(id+'_alt').text(point);
};

$('#depart, #arrival').change(function(){ setPointValue('#'+$(this).attr('id')) });

<? $countries_count = 0; ?>
var countries =
{ <? foreach ($arResult['COUNTRIES'] as $country_code => $arCountry): ?>
<?= ($countries_count ? ', ' : ''); ?>
<? $countries_count++; ?>
'<?= $country_code; ?>':
  { <? $points_count = 0; ?>
<? foreach ($arCountry['POINTS'] as $point_code): ?>
<?= ($points_count ? '  , ' : ''); ?>'<?= $point_code; ?>':'<?= $arResult['points'][$point_code]['NAME']; ?>'
<? $points_count++; ?>
<? endforeach; ?>
  }
<? endforeach; ?>
};

function buildDepartList() {
  var dep_country = $('#country_depart');
  var dep_city = $('#depart');
  if (dep_country.length && dep_city.length) {
    var dep_country_value = dep_country.val();
    var old_value = dep_city.val();
    var selected = false;
    dep_city.find('option').each(function(i) {
      $(this).remove();
    });

    var options = '';
    for (var code in countries[dep_country_value]) {
      if (countries[dep_country_value].hasOwnProperty(code)) {
        options = options.concat('<option value="', code, '"', (old_value != code ? '' : ' selected="selected"'), '>', countries[dep_country_value][code], '</option>');
      }
      }
    dep_city.html(options);
    setPointValue('#depart');
  }
}

function buildArrivalList() {
  var arr_country = $('#country_arrival');
  var arr_city = $('#arrival');
  if (arr_country.length && arr_city.length) {
    var arr_country_value = arr_country.val();
    var old_value = arr_city.val();
    var selected = false;
    arr_city.find('option').each(function(i) {
      $(this).remove();
    });

    var options = '';
    for (var code in countries[arr_country_value]) {
      if (countries[arr_country_value].hasOwnProperty(code)) {
        options = options.concat('<option value="', code, '"', (old_value != code ? '' : ' selected="selected"'), '>', countries[arr_country_value][code], '</option>');
      }
      }
    arr_city.html(options);
    setPointValue('#arrival');
  }
}

buildDepartList();
buildArrivalList();

$('#country_depart').change(function () {
  buildDepartList();
});

$('#country_arrival').change(function () {
  buildArrivalList();
});


$('#form_order select').change(function(){
  var el = $(this),
  alt = $('#'+el.attr('id')+'_alt');
  if ( alt.length ){
    alt.text(el.find('option:selected').text());
  }
});

formInit();

<? if (file_exists(dirname(__FILE__)."/calendar_scripts.php")){
  $form = '';
  require(dirname(__FILE__)."/calendar_scripts.php");
} else {
  require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/travelshop/ibe.frontoffice/templates/.default/calendar_scripts.php'); 
} ?>

<? 
  $JQ_CALENDAR_NUMBER_OF_MONTHS = intval( $arParams['JQ_CALENDAR_NUMBER_OF_MONTHS'] ) ? intval( $arParams['JQ_CALENDAR_NUMBER_OF_MONTHS'] ) : 2; // Количество отображаемых за раз месяцев во всплывающем календаре. По умолчанию 1.
  $JQ_CALENDAR_STEP_MONTHS = intval( $arParams['JQ_CALENDAR_STEP_MONTHS'] ) ? intval( $arParams['JQ_CALENDAR_STEP_MONTHS'] ) : $JQ_CALENDAR_NUMBER_OF_MONTHS; // На сколько месяцев сдвигаться за раз во всплывающем календаре. По умолчанию равно количеству отображаемых месяцев.
  $JQ_CALENDAR_SHOW_OTHER_MONTHS = ( "Y" ==  $arParams['JQ_CALENDAR_SHOW_OTHER_MONTHS'] ) ? "true" : "false"; // Показывать дни из соседних с выбранным месяцем. По умолчанию нет.
  $JQ_CALENDAR_SELECT_OTHER_MONTHS = ( "Y" ==  $arParams['JQ_CALENDAR_SELECT_OTHER_MONTHS'] ) ? "true" : "false"; // Разрешать выбор дня из соседних с выбранным месяцем. По умолчанию нет.
  $JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR = ( isset($arParams['JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR']) && "Y" ==  $arParams['JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR'] || !isset($arParams['JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR']) ) ? "true" : "false"; // Разрешать выбор месяца и года. По умолчанию нет.
?>

function calendarsSetup() {
  calendarTo.datepicker({ 
    showOn: 'both',
    buttonImage: '<?= $templateFolder ?>/images/calendar.png',
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
      if ( !$('#ui-datepicker-div').hasClass('dateto') ) {
        $('#ui-datepicker-div').addClass('dateto');  
      }
      calendarTo.closest('.date-container').addClass('active');
    },
    onSelect: function(dateText) {
      selectForwardDate(dateText);
        $('#form_order #form_order_submit').focus();
    },
    onClose: function(){
      calendarTo.closest('.date-container').removeClass('active');
    }
  });
  calendarTo.datepicker('setDate', defaultDateTo);
  tooltip(calendarTo.parent());
}

$("#form_order #dateto_formated").click(function() {
   $("#form_order #dateto").focus();
});

safeCall(calendarsSetup);

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
// ]]>
</script>

<? else: ?>
<? require($_SERVER["DOCUMENT_ROOT"].'/bitrix/components/travelshop/ibe.frontoffice/templates/.default/form_order.php'); ?>
<? endif; ?>
<? endif; // ( $arResult[ "~SHOW_FORM" ] ) ?>