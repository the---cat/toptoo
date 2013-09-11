<?
/* Для режима USE_MERGED_STEPS = Y экран preview подключается с экрана personal_data */
if ( $arParams['USE_MERGED_STEPS'] === 'Y' )  {

  $arResultOrder = $arResult;

  $arResult = $arResultOrder['ORDER'];
  require( dirname( __FILE__ ).'/personal_data_order.php' );
  unset( $arResultOrder['ORDER'] );

  $arResult = $arResultOrder;
}
?>
<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/ibe/classes/ibe/json.php");

echo $arResult['SCRIPT'];
$form = $arResult['FORM'];
$pass_profiles_links_num = $cont_profiles_links_num = 3; // Кол-во выводимых ссылок-профилей
$uniform = defined("__UNIFORM_JS") && true ===__UNIFORM_JS ? true : false;
 ?>
<script type="text/javascript">
// <![CDATA[

function SetGender( el_name, gender ) {
  if ( !$('#' + el_name).length ) {
    return;
  }
  $('#' + el_name).val( gender );
  $('#' + el_name + '_SWITCHER').removeClass('M_selected').removeClass('F_selected');
  switch( gender ){
    case 'M':
      $('#' + el_name + '_SWITCHER').addClass('M_selected');
      break;
    case 'F':
      $('#' + el_name + '_SWITCHER').addClass('F_selected');
      break;
  }
}

function DeleteProfileB2B( profile_id ) {
  /* Убрать из всех блоков с профилями */
  for ( var i = 0; i < intBlocksOnPage; i++ ) {

    if ( !document.getElementById( 'table_popup' + i ) ){
      continue;
    }
    /* Выбрать "новый" если выбран удаляемый профиль */
    if ( $('#profile_link_' + i + '_' + profile_id ).hasClass( 'selected' ) ){
      onProfileClick( i, 'new' );
      onProfileClickB2B( i, 'new' );
    }

    /* Удалить ссылку */
    $('#profile_link_' + i + '_' + profile_id ).remove();
    
    /* Удалить строку из попап'а */
    $('#tr_popup' + i + '_' + profile_id  ).remove();
   
    /* Не осталось профилей, удалить весь .profiles_links */
    if ( !document.getElementById( 'table_popup' + i ).rows.length ) {
      $('#show_profiles_popup' + i ).parent().remove();
      $('#profiles_popup_block' + i).fadeOut();
    }
  }
}

// Лейблы-подсказки
aFieldsIdsToNames = {};
var aPlaceholdersPhraseCtcName = (arValidationRules["CTC_NAME"] == 0) ? '<?=GetMessage('TS_FRONTOFFICE_STEP3_NOT_REQUIRED')?>' : '<?=GetMessage('TS_FRONTOFFICE_STEP3_REQUIRED')?>';
/* /bitrix/templates/b2b/components/travelshop/ibe.frontoffice/.default/lang/ru */
aPlaceholdersPhrases = {
  'ru':{
    'PSGR_NAME':'<?= GetMessage( 'TS_FRONTOFFICE_STEP3_CYR' ) ?>',
    'PSGR_FNAME':'<?= GetMessage( 'TS_FRONTOFFICE_STEP3_CYR' ) ?>',
    'PSGR_MNAME':'<?= GetMessage( 'TS_FRONTOFFICE_STEP3_NOT_REQUIRED' ) ?>',
    'DOCNUMBER': ' ',
    'CTC_NAME': aPlaceholdersPhraseCtcName,
    'CTC_PHONE': '<?= GetMessage( 'TS_FRONTOFFICE_STEP3_CTC_PHONE_TIP' ) ?>',
    'CTC_PHONE_EXT': '<?= GetMessage( 'TS_FRONTOFFICE_STEP3_CTC_PHONE_EXT_TIP' ) ?>',
    'CTC_MAIL': '<?= GetMessage( 'TS_FRONTOFFICE_STEP3_CTC_MAIL_TIP' ) ?>'
  },
  'en':{
    'PSGR_NAME':'<?= GetMessage( 'TS_FRONTOFFICE_STEP3_PASS_LAT' ) ?>',
    'PSGR_FNAME':'<?= GetMessage( 'TS_FRONTOFFICE_STEP3_PASS_LAT' ) ?>',
    'PSGR_MNAME':'<?= GetMessage( 'TS_FRONTOFFICE_STEP3_NOT_REQUIRED' ) ?>',
    'DOCNUMBER': ' '
  }
};
function onDocTypeChangeB2B( block_id ) {
  var preset, bBlockDefined, bSelectIdsEnabled, obj, doctype, docnumber_length;

  preset = 'ru';
  bBlockDefined = typeof( block_id ) != "undefined";
  bSelectIdsEnabled = $('.custom-select-container').length;
  docnumber_length = 15;

  /* */
  doctype = $('#PSGRDATA_DOCTYPE_' + block_id + ' option:selected').val();
  if ( bSelectIdsEnabled ) {
    doctype = $("#csi_PSGRDATA_DOCTYPE_" + block_id ).val();
  }

  $.each( aPlaceholdersPhrases[preset], function( field_code_phrase, phrase ) {
    $.each( aFieldsIdsToNames, function( field_block_id, field_ids ) {
      if ( bBlockDefined && block_id != field_block_id ) {
        return true;
      }

      switch ( field_code_phrase ) {
        case 'PSGR_NAME':
        case 'PSGR_FNAME':
        case 'PSGR_MNAME':
          if ( doctype == 'ПСП' || !bCyrillicUsable ) {
            phrase = aPlaceholdersPhrases['en'][field_code_phrase];
          }
        break;
        case 'DOCNUMBER':
          if ( doctype == 'ПСП') {
            phrase = '<?= GetMessage( 'TS_FRONTOFFICE_STEP3_PASS_DOCNUMBER_9' ) ?>';
          } else if ( doctype == 'ПС') {
            phrase = '<?= GetMessage( 'TS_FRONTOFFICE_STEP3_PASS_DOCNUMBER_10' ) ?>';
          }
        break;
      }

      $.each( field_ids, function( field_id, field_code_cfg ) {
        if ( field_code_phrase == field_code_cfg ) {
          $( "#" + field_id ).attr( 'placeholder', phrase );
        }
      });
    });
  });

  /* Ограничение кол-ва символов */
  if ( doctype == 'ПСП') {
    docnumber_length = 9;
  } else if ( doctype == 'ПС') {
    docnumber_length = 10;
  }
  ControlFieldLength( 'PSGRDATA_DOCNUMBER_' + block_id, docnumber_length );
  $( '#PSGRDATA_DOCNUMBER_' + block_id ).attr( 'maxlength', docnumber_length );

  $('input').placeholder();
}

function onProfileClickB2B( block_id, profile_id ) {
  var arSelectFieldNames = ['GENDER','DOCTYPE','DOCCOUNTRY','FFAK',
    'BIRTHDATE_DAY','BIRTHDATE_MONTH','BIRTHDATE_YEAR',
    'DOCEXPIRATION_DAY','DOCEXPIRATION_MONTH','DOCEXPIRATION_YEAR'];
  profile_id_uc = profile_id.toUpperCase();
  profile_id = profile_id.toLowerCase();
  if ( typeof( profilesData[block_id] ) == "undefined"
        || typeof( profilesData[block_id][profile_id] ) == "undefined" ) {
    return;
  }

  for ( var field in profilesData[block_id][profile_id] ) {
    /* Сделать поля редактируемыми */
   $( "#"+field ).attr( "disabled", false );

    /* Обновить лейблы-подсказки */
    $( "#"+field ).blur();
  } /* profilesData */

  /* Выбор пола пассажира */
  SetGender( 'PSGRDATA_GENDER_' + block_id, profilesData[block_id][profile_id]['PSGRDATA_GENDER_' + block_id] );

  /* Подсветка выбранного профиля */
  $('.passenger' + ( block_id + 1 ) + ' .profiles_links li.profile_link').removeClass( 'selected' );
  $('.contact' + ( block_id ) + ' .profiles_links li.profile_link').removeClass( 'selected' );
  $('#profile_link_' + block_id + '_' + profile_id_uc).addClass( 'selected' );
  
   /*
  $('#data_form_'+block_id+'_profiles input:radio').removeAttr('checked');
  $('#IBE_PROFILE_' + profile_id + '_' + block_id).attr('checked', 'checked');
  */
  onDocTypeChangeB2B( block_id );

  /* Обновить кастомные селекты */
  if ( typeof( updateUniform ) != 'undefined' ) {
    updateUniform( $('#form_ibe_profile_' + profile_id + '_' + block_id), 'select' );
  }
}

/* Ограничение кол-ва символов */
function ControlFieldLength( id_field, chars_max ){
  var f = $( '#' + id_field );
  if ( f.length ) {
    var str = f.val();
    if ( str.length > chars_max ) {
      f.val( str.substring( 0, chars_max ) );
    }
  }
}
// ]]>
</script>
<? $arFieldsIdsToNames = array(); ?>
<? $APPLICATION->IncludeComponent( "bitrix:system.show_message", "", Array( "MESSAGE" => $arResult['DISPLAY_ERROR'] ) ); ?>

<h3 class="info_caption"><?=GetMessage('IBE_PERSONAL_DATA_CAPTION')?></h3>
<div class="personal_data">
  <form id="<?= $form['~ID'] ?>" name="<?= $form['NAME'] ?>" method="<?= $form['~METHOD'] ?>" action="<?= $form['ACTION'] ?>" onsubmit="<?=$form['ONSUBMIT']?>">
    <!-- Ввод данных пассажиров -->
    <? if ( $form['PASSENGERS'] ): ?>
    <div class="passengers clearfix">
      <? $bFirstPassenger = true;
    foreach ( $form['PASSENGERS'] as $k => $passenger ): ?>
      <div class="passenger passenger<?= ($k+1) ?>">
        <h3 class="caption">
          <?= GetMessage( 'TS_FRONTOFFICE_STEP3_PASSENGER' ) . ' ' . $passenger['~DATA']['PASSNUMBER'] . ':' ?>
          <span class="type"><?= GetMessage( 'IBE_PERSONAL_DATA_' . $passenger['~DATA']['PASSTYPE'] ) ?></span>
        </h3>
        
        <? if ( $passenger['PROFILES'] ): ?>
        <div class="profiles<? if(count($passenger['PROFILES']['FIELDS']) < 2) {?> no-profiles<? } ?>" id="<?= $passenger['PROFILES']['~ID'] ?>">
          <? foreach( $passenger['PROFILES']['FIELDS'] as $profile ): ?>
          <? if( $profile['VALUE'] == 'NEW' ): ?>
          <div class="profile_new profile">
            <div class="profile_checker">
              <? if(count($passenger['PROFILES']['FIELDS']) > 1): ?>
              <input id="<?= $profile['~ID'] ?>" name="<?= $profile['NAME'] ?>" onclick="<?= $profile['ONCLICK'] ?>; onProfileClickB2B(<?= $k ?>, '<?= $profile['VALUE']?>');" type="radio" value="<?= $profile['VALUE'] ?>" />
              <? else: ?>
              <input id="<?= $profile['~ID'] ?>" name="<?= $profile['NAME'] ?>" onclick="<?= $profile['ONCLICK'] ?>; onProfileClickB2B(<?= $k ?>, '<?= $profile['VALUE']?>');" type="hidden" value="<?= $profile['VALUE'] ?>" />
              <? endif; ?>
            </div>

            <? if ( $passenger['FORM_NEW'] ): ?>
            <div id="form_<?= ToLower($profile['~ID']) ?>" class="profile_form_container">
              <div id="pass_form_new_<?=$k?>" class="profile_form clearfix">
                <? foreach ( $passenger['FORM_NEW']['FIELDS'] as &$field ):
                if ( isset( $field['CODE']) ){
                  $arFieldsIdsToNames[$k][$field['~ID']] = $field['CODE'];
                }
                $fieldId = substr($field['~ID'], 9, -2);
                $field['CAPTION'] = strlen(GetMessage('TS_FRONTOFFICE_STEP3_PASS_' . $fieldId)) ? GetMessage('TS_FRONTOFFICE_STEP3_PASS_' . $fieldId) : $field['CAPTION'];

                switch($fieldId) {
                  case 'TYPE': ?>
                <div class="field <?= strtolower( $fieldId ) ?>" style="display: none;">
                  <input id="<?=$field['~ID'] ?>" name="<?=$field['NAME'] ?>" type="hidden" value="<?=$field['VALUE'] ?>" />
                </div>
                <? break;
                  case 'GENDER': ?>
                <div class="field <?= strtolower( $fieldId ) ?>">
                  <label class="title<? if ( $field['~REQUIRED'] ): ?> required required_title<? endif; ?>" for="<?= $field['~ID'] ?>" id="<?= $field['~ID'] . '_LABEL' ?>">
                    <?= GetMessage('IBE_FRONTOFFICE_PERSONAL_DATA_PLOFILES_TH_GENDER') ?>
                  </label>
                  <div class="gender_switcher clearfix" id="<?= $field['~ID'] ?>_SWITCHER">
                    <div class="gender_type gender_M" title="<?= GetMessage('IBE_FRONTOFFICE_PERSONAL_GENDER_M') ?>" onclick="SetGender( '<?= $field['~ID'] ?>', 'M');"></div>
                    <div class="gender_type gender_F" title="<?= GetMessage('IBE_FRONTOFFICE_PERSONAL_GENDER_F') ?>" onclick="SetGender( '<?= $field['~ID'] ?>', 'F');"></div>
                  </div>
                  <input type="hidden" id="<?= $field['~ID'] ?>" name="<?= $field['NAME'] ?>" <? if ( $field['~REQUIRED'] ): ?> class="required"<? endif; ?> />
                </div>
                <? break;
                  case 'PSGR_NAME': ?>
                <div class="field <?= strtolower( $fieldId ) ?>">
                  <label class="title<? if ( $field['~REQUIRED'] ): ?> required required_title<? endif; ?>" for="<?= $field['~ID'] ?>" id="<?= $field['~ID'] . '_LABEL' ?>">
                    <?= $field['CAPTION'] ?>
                  </label>
                  <input class="input-si text <?= strtolower( $fieldId ) ?>" id="<?= $field['~ID'] ?>" name="<?= $field['NAME'] ?>" type="text" value="<?= $field['VALUE'] ?>" size="<?= $field['SIZE'] ?>"  />
                </div>
                <? break;
                  case 'DOCNUMBER': ?>
                <div class="field <?= strtolower( $fieldId ) ?>">
                  <label class="title<? if ( $field['~REQUIRED'] ): ?> required required_title<? endif; ?>" for="<?= $field['~ID'] ?>" id="<?= $field['~ID'] . '_LABEL' ?>">
                    <?= $field['CAPTION'] ?>
                  </label>
                  <div class="subtitle">
                    <?= GetMessage('IBE_FRONTOFFICE_PERSONAL_SUBTITLE_NUMBER') ?>
                  </div>
                  <input class="input-si text <?= strtolower( $fieldId ) ?>" id="<?= $field['~ID'] ?>" name="<?= $field['NAME'] ?>" type="text" value="<?= $field['VALUE'] ?>" size="<?= $field['SIZE'] ?>"  />
                </div>
                <? break;
                  case 'PSGR_FNAME': ?>
                <div class="field <?= strtolower( $fieldId ) ?>">
                  <label class="title<? if ( $field['~REQUIRED'] ): ?> required required_title<? endif; ?>" for="<?= $field['~ID'] ?>" id="<?= $field['~ID'] . '_LABEL' ?>">
                    <?= $field['CAPTION'] ?>
                  </label>
                  <input class="input-si text <?= strtolower( $fieldId ) ?>" id="<?= $field['~ID'] ?>" name="<?= $field['NAME'] ?>" type="text" value="<?= $field['VALUE'] ?>" size="<?= $field['SIZE'] ?>"  />
                </div>
                <? break;
                  case 'PSGR_MNAME': /*?>
                <div class="field <?= strtolower( $fieldId ) ?>">
                  <label class="title<? if ( $field['~REQUIRED'] ): ?> required required_title<? endif; ?>" for="<?= $field['~ID'] ?>" id="<?= $field['~ID'] . '_LABEL' ?>">
                    <? if ( $field['~REQUIRED'] ): ?><? endif; ?>
                    <?= $field['CAPTION'] ?>
                  </label>
                  <input class="input-si text <?= strtolower( $fieldId ) ?>" id="<?= $field['~ID'] ?>" name="<?= $field['NAME'] ?>" type="text" value="<?= $field['VALUE'] ?>" size="<?= $field['SIZE'] ?>"  />
                </div>
                <? */ break;
                  case 'BIRTHDATE_DAY': 
                  case 'DOCEXPIRATION_DAY': ?>
                <div class="select-wrap field date <?= substr(strtolower( $fieldId ), 0, -4) ?><? if( $uniform ) { ?> custom<? } ?>">
                  <label class="title<? if ( $field['~REQUIRED'] ): ?> required required_title<? endif; ?>" for="<?= $field['~ID'] ?>" id="<?= $field['~ID'] . '_LABEL' ?>">
                    <?= strlen(GetMessage('IBE_FRONTOFFICE_PERSONAL_' . $fieldId)) ? GetMessage('IBE_FRONTOFFICE_PERSONAL_' . $fieldId) : $field['CAPTION'] ?>
                  </label>
                  <? if ( $uniform ) { ?>
                  <div class="date_wrap">
                    <div class="day_wrap">
                      <select id="<?=$field['~ID'] ?>" name="<?=$field['NAME'] ?>" class="day">
                        <? foreach($field['OPTION'] as $option): ?>
                        <option value="<?= $option['VALUE'] ?>">
                        <? if ( !$option['VALUE'] ) { ?>
                        __
                        <? } else { ?>
                        <?= str_pad($option['CAPTION'], 2, '0', STR_PAD_LEFT); ?>
                        <? } ?>
                        </option>
                        <? endforeach; ?>
                      </select>
                    </div>
                    <? } else { ?>
                    <select id="<?=$field['~ID'] ?>" name="<?=$field['NAME'] ?>" class="day">
                      <? foreach($field['OPTION'] as $option): ?>
                      <option value="<?= $option['VALUE'] ?>">
                      <?= !$option['VALUE'] && strlen(GetMessage('TS_FRONTOFFICE_STEP3_PASS_DAY')) ? GetMessage('TS_FRONTOFFICE_STEP3_PASS_DAY') : $option['CAPTION'] ?>
                      </option>
                      <? endforeach; ?>
                    </select>
                    <? } ?>
                    <? break;
                  case 'BIRTHDATE_MONTH':
                  case 'DOCEXPIRATION_MONTH': ?>
                    <? if ( $uniform ) { ?>
                    <div class="month_wrap">
                      <select id="<?=$field['~ID'] ?>" name="<?=$field['NAME'] ?>" class="month">
                        <? foreach($field['OPTION'] as $option): ?>
                        <option value="<?=$option['VALUE'] ?>">
                        <?= !$option['VALUE'] ? '__' : str_pad($option['VALUE'], 2, '0', STR_PAD_LEFT) ?>
                        </option>
                        <? endforeach; ?>
                      </select>
                    </div>
                    <? } else { ?>
                    <select id="<?=$field['~ID'] ?>" name="<?=$field['NAME'] ?>" class="month">
                      <? foreach($field['OPTION'] as $option): ?>
                      <option value="<?=$option['VALUE'] ?>">
                      <? if( $uniform ) { ?>
                      <?= !$option['VALUE'] ? '__' : $option['VALUE'] ?>
                      <? } else { ?>
                      <?=$option['CAPTION'] ?>
                      <? } ?>
                      </option>
                      <? endforeach; ?>
                    </select>
                    <? } ?>
                    <? break;
                  case 'BIRTHDATE_YEAR':
                  case 'DOCEXPIRATION_YEAR': ?>
                    <? if ( $uniform ) { ?>
                    <div class="year_wrap">
                      <? } ?>
                      <select id="<?=$field['~ID'] ?>" name="<?=$field['NAME'] ?>" class="year">
                        <? foreach($field['OPTION'] as $option): ?>
                        <option value="<?=$option['VALUE'] ?>">
                        <?= !$option['VALUE'] && $uniform ? '____': $option['CAPTION'] ?>
                        </option>
                        <? endforeach; ?>
                      </select>
                      <? if ( $uniform ) { ?>
                    </div>
                  </div>
                  <? } ?>
                </div>
                <? break;
                  case 'DOCTYPE': ?>
                <div class="cl"></div>
                <div class="field <?= strtolower( $fieldId ) ?><? if( $uniform ) { ?> custom<? } ?>">
                  <label class="title<? if ( $field['~REQUIRED'] ): ?> required required_title<? endif; ?>" for="<?= $field['~ID'] ?>" id="<?= $field['~ID'] . '_LABEL' ?>">
                    <?= $field['CAPTION'] ?>
                  </label>
                  <select id="<?= $field['~ID'] ?>" name="<?= $field['NAME'] ?>" onchange="<?= $field['ONCHANGE'] ?>; onDocTypeChangeB2B('<?=$k?>');">
                    <? foreach($field['OPTION'] as $option): ?>
                    <option value="<?=$option['VALUE'] ?>">
                    <?=$option['CAPTION'] ?>
                    </option>
                    <? endforeach; ?>
                  </select>
                </div>
                <? break;
                  case 'DOCCOUNTRY': ?>
                <div class="select-wrap field <?= strtolower( $fieldId ) ?><? if( $uniform ) { ?> custom<? } ?>">
                  <label class="title<? if ( $field['~REQUIRED'] ): ?> required required_title<? endif; ?>" for="<?= $field['~ID'] ?>" id="<?= $field['~ID'] . '_LABEL' ?>">
                    <?= $field['CAPTION'] ?>
                  </label>
                  <select id="<?=$field['~ID'] ?>" name="<?=$field['NAME'] ?>">
                    <? foreach($field['OPTION'] as $option): ?>
                    <option value="<?=$option['VALUE'] ?>">
                    <?=$option['CAPTION'] ?>
                    </option>
                    <? endforeach; ?>
                  </select>
                </div>
                <?  break;

                  // карта частолетающего пассажира
                case 'FFAK':  ?>
                <div class="ffak_wrap clearfix">
                  <label class="title" for="<?= $field['~ID'] ?>" id="<?= $field['~ID'] . '_LABEL' ?>">
                    <?= strlen(GetMessage('IBE_FRONTOFFICE_PERSONAL_' . $fieldId)) ? GetMessage('IBE_FRONTOFFICE_PERSONAL_' . $fieldId) : $field['CAPTION'] ?>
                  </label>
                  <div id="TR_<?= $field['~ID'] ?>" class="field <?= strtolower( $fieldId ) ?><? if( $uniform ) { ?> custom<? } ?>">
                    <? switch( $field['~TYPE'] ) {
                      case 'text': ?>
                    <input class="input-si text <?= strtolower( $fieldId ) ?>" id="<?= $field['~ID'] ?>" name="<?= $field['NAME'] ?>" type="text" value="<?= $field['VALUE'] ?>" size="<?= $field['SIZE'] ?>"  />
                    <? break;
                      case 'select': ?>
                    <select id="<?=$field['~ID'] ?>" name="<?=$field['NAME'] ?>" onchange="<?=$field['ONCHANGE'] ?>">
                      <? foreach($field['OPTION'] as $option): ?>
                      <option value="<?=$option['VALUE'] ?>">
                      <?=$option['CAPTION'] ?>
                      </option>
                      <? endforeach; ?>
                    </select>
                    <? break;
                    } ?>
                    <? if ( $field['~APPEND'] ): ?>
                    <?= $field['~APPEND'] ?>
                    <? endif; // ~APPEND  ?>
                  </div>
                  <?  break;
                // номер карты частолетающего пассажира
                case 'FFCARDNMBR': ?>
                  <div id="TR_<?= $field['~ID'] ?>" class="field <?= strtolower( $fieldId ) ?><? if( $uniform ) { ?> custom<? } ?>">
                    <? /*label class="title" for="<?= $field['~ID'] ?>" id="<?= $field['~ID'] . '_LABEL' ?>"><?= $field['CAPTION'] ?></label */?>
                    <? switch( $field['~TYPE'] ) {
                      case 'text': ?>
                    <div class="subtitle">
                      <?= GetMessage('IBE_FRONTOFFICE_PERSONAL_SUBTITLE_NUMBER') ?>
                    </div>
                    <input class="input-si text <?= strtolower( $fieldId ) ?>" id="<?= $field['~ID'] ?>" name="<?= $field['NAME'] ?>" type="text" value="<?= $field['VALUE'] ?>" />
                    <? break;
                      case 'select': ?>
                    <select id="<?=$field['~ID'] ?>" name="<?=$field['NAME'] ?>" onchange="<?=$field['ONCHANGE'] ?>">
                      <? foreach($field['OPTION'] as $option): ?>
                      <option value="<?=$option['VALUE'] ?>">
                      <?=$option['CAPTION'] ?>
                      </option>
                      <? endforeach; ?>
                    </select>
                    <? break;
                    } ?>
                    <? if ( $field['~APPEND'] ): ?>
                    <?= $field['~APPEND'] ?>
                    <? endif; // ~APPEND  ?>
                  </div>
                </div>
                <? break;
                // Доп. участник
                case 'FFRELATION': ?>
                <input id="<?= $field['~ID'] ?>" name="<?= $field['NAME'] ?>" type="<?= $field['~TYPE'] ?>" value="<?= $field['VALUE'] ?>" />
                <? break;
              default: break;
              } ?>
                <? endforeach; ?>
              </div>
            </div>
            <? endif; //if ( $passenger['FORM_NEW'] ) ?>
          </div>
          <? else: ?>
          <div class="profile <?= $profile[ 'CONTAINER_CLASS' ] ?>">
            <div class="profile_checker">
              <input id="<?= $profile['~ID'] ?>" name="<?= $profile['NAME'] ?>" onclick="<?= $profile['ONCLICK'] ?>; onProfileClickB2B(<?= ($k) ?>, '<?= $profile['VALUE'] ?>');" type="radio" value="<?= $profile['VALUE'] ?>" />
            </div>
            <div id="form_<?= ToLower($profile['~ID']) ?>" class="profile_form_container"></div>
          </div>
          <? endif; //if( $profile['VALUE'] == 'NEW' ) ?>
          <? endforeach; //foreach( $passenger['PROFILES']['FIELDS'] as $profile ) ?>
        </div>
        <? endif; //if ( $passenger['PROFILES'] ) ?>
      </div>
      <? endforeach; //foreach ( $form['PASSENGERS'] as $k => $passenger ) ?>
    </div>
    <? endif; // if ( $form['PASSENGERS'] )?>
    <!-- /Ввод данных пассажиров --> 
    
    <!-- Ввод контактных данных -->
    <? $BlockID = sizeof( $form['PASSENGERS'] ); ?>
    <? if ( $form['CONTACTS'] ): ?>
    <div class="contacts clearfix">
      <? if ( $form['CONTACTS']['PROFILES'] ): ?>
      <div class="contact<?= $BlockID ?>">
        <h3 class="caption"><?= GetMessage( 'TS_FRONTOFFICE_PASSENGERS_CONTACTS_INFO_CAPTION' ) ?></h3>

        <div class="clearfix profiles<? if(count($form['CONTACTS']['PROFILES']['FIELDS']) < 2) {?> no-profiles<? } ?>" id="contact_profiles">
          <? foreach( $form['CONTACTS']['PROFILES']['FIELDS'] as $profile ): ?>

          <? if( $profile['VALUE'] == 'NEW' ): ?>
          <div class="profile_new profile">
            <div class="profile_checker">
              <? if(count($form['CONTACTS']['PROFILES']['FIELDS']) > 1): ?>
              <input id="<?= $profile['~ID'] ?>" name="<?= $profile['NAME'] ?>" onclick="<?= $profile['ONCLICK'] ?>; onProfileClickB2B(<?=$BlockID ?>, '<?= $profile['VALUE']?>'); $('#profile-data-checkbox-div input').removeAttr('checked');" type="radio" value="<?= $profile['VALUE'] ?>" />
              <? else: ?>
              <input id="<?= $profile['~ID'] ?>" name="<?= $profile['NAME'] ?>" onclick="<?= $profile['ONCLICK'] ?>; onProfileClickB2B(<?=$BlockID ?>, '<?= $profile['VALUE']?>'); $('#profile-data-checkbox-div input').removeAttr('checked');" type="hidden" value="<?= $profile['VALUE'] ?>" />
              <? endif; ?>
            </div>
            <? if ( $form['CONTACTS']['FORM_NEW'] ): ?>
            <div id="form_<?= ToLower($profile['~ID']) ?>" class="profile_form_container">
              <div id="cont_form_new" class="profile_form clearfix">
                <? foreach ( $form['CONTACTS']['FORM_NEW']['FIELDS'] as &$field ): ?>
                <? $fieldId = substr( $field['~ID'], 12, -2 );                  
                  if ( isset($field['CODE']) ){ $arFieldsIdsToNames[$BlockID][$field['~ID']] = $field['CODE']; }
                  $field['CAPTION'] = strlen(GetMessage('TS_FRONTOFFICE_STEP3_CONT_' . $fieldId)) ? GetMessage('TS_FRONTOFFICE_STEP3_CONT_' . $fieldId) : $field['CAPTION']; ?>
                <div class="field <?= strtolower( $fieldId ) ?>">
                  <? switch ( $field['~TYPE'] ){
                  case 'checkbox':
                    if ( $field['CODE'] == 'NOTIFPREFER' ) {
                      $field['CHECKED'] = true;
                    }?>
                  <label for="<?= $field['~ID'] ?>" id="<?= $field['~ID'] ?>_LABEL" class="title<?= ($field['~REQUIRED'] ? ' required_title' : '' ) ?>">
                    <?= $field['CAPTION'] ?>
                  </label>
                  <input type="checkbox" id="<?=$field['~ID'] ?>" name="<?=$field['NAME'] ?>"<? if($field['CHECKED']): ?> checked="checked"<? endif; ?> value="<?=$field['VALUE'] ?>" />
                  <? break;

                  case 'select': ?>
                  <label for="<?= $field['~ID'] ?>" id="<?= $field['~ID'] ?>_LABEL" class="title<?= ($field['~REQUIRED'] ? ' required_title' : '' ) ?>">&#160;</label>
                  <select id="<?= $field['~ID'] ?>" name="<?= $field['NAME'] ?>" <? if ( $field['~REQUIRED'] ): ?> class="required"<? endif; ?>>
                    <? foreach ( $field['OPTION'] as $option ): ?>
                    <option value="<?= $option['VALUE'] ?>">
                    <?= !$option['VALUE'] ? $field['CAPTION'] : $option['CAPTION'] ?>
                    </option>
                    <? endforeach; ?>
                  </select>
                  <? break;

                  default: ?>
                  <label for="<?= $field['~ID'] ?>" id="<?= $field['~ID'] ?>_LABEL" class="title<?= ($field['~REQUIRED'] ? ' required_title' : '' ) ?>">
                    <?= strlen(GetMessage('IBE_FRONTOFFICE_PERSONAL_DATA_' . $field['CODE'] )) ? GetMessage('IBE_FRONTOFFICE_PERSONAL_DATA_' . $field['CODE'] ) : $field['CAPTION'] ?>
                  </label>
                  <input class="input-si" type="text" id="<?= $field['~ID'] ?>" name="<?= $field['~ID'] ?>" size="<?= $field['SIZE'] ?>" value="<?= $field['VALUE'] ?>" />
                  <? break;
                } ?>
                </div>
                <? endforeach; //foreach ( $form['CONTACTS']['FORM_NEW']['FIELDS'] as &$field ) ?>
                <? if ( $arParams['USE_MERGED_STEPS'] == 'Y' ): ?>
                <div class="buttons clearfix">
                  <? $arResult['FORWARD']['VALUE'] = strlen(GetMessage('IBE_FRONTOFFICE_BUTTON_NEXT')) ? GetMessage('IBE_FRONTOFFICE_BUTTON_NEXT') : $arResult['FORWARD']['VALUE'] ?>
                  <div class="c-continue">
                    <?=CTemplateToolsUtil::RenderField($arResult['FORWARD']) ?>
                  </div>
                </div>
                <? endif; ?>
              </div>
            </div>

            <? if ( !$USER->IsAuthorized() ): //Авторегистрация ?>
            <? foreach ( $arResult['FORM']['FIELDS'] as $Fields ): ?>
            <? if ( $Fields['~ID'] == 'auto_registration_container'): ?>
            <div id="<?= $Fields['~ID'] ?>" class="auto_registration clearfix">
              <? foreach ( $Fields['FIELDS'] as $Field ) : ?>
              <div class="row clearfix">
                <input type="<?=$Field['~TYPE']?>" id="<?=$Field['~ID']?>" name="<?=$Field['NAME']?>" value="<?=$Field['~TYPE']?>"<?= $Field['~CHECKED'] ? ' checked="checked"' : ''  ?>  />
                <label for="<?=$Field['~ID']?>">
                  <?= strlen(GetMessage('IBE_FRONTOFFICE_PERSONAL_DATA_' . ToUpper($Field['~ID']) )) ? GetMessage('IBE_FRONTOFFICE_PERSONAL_DATA_' . ToUpper($Field['~ID']) ) : $Field['CAPTION'] ?>
                </label>
              </div>
              <? endforeach; ?>
            </div>
            <? endif; ?>
            <? endforeach; //foreach ( $arResult['FORM']['FIELDS'] as $fields ) ?>
            <? endif; //if ( !$USER->IsAuthorized() ):?>
            <? endif; //if ( $form['CONTACTS']['FORM_NEW'] ) ?>
          </div>
          <? else: //if( $profile['VALUE'] == 'NEW' ) ?>
          <div class="profile <?= $profile[ 'CONTAINER_CLASS' ] ?>">
            <div class="profile_checker">
              <input id="<?= $profile['~ID'] ?>" name="<?= $profile['NAME'] ?>" onclick="<?= $profile['ONCLICK'] ?>; onProfileClickB2B(<?=$BlockID ?>, '<?= $profile['VALUE']?>');" type="radio" value="<?= $profile['VALUE'] ?>" />
            </div>
            <div id="form_<?= ToLower($profile['~ID']) ?>" class="profile_form_container"></div>
          </div>
          <? endif; //if( $profile['VALUE'] == 'NEW' ) ?>
          <? endforeach; //foreach( $form['CONTACTS']['PROFILES']['FIELDS'] as $profile ) ?>
        </div>
        <? endif; //if ( $form['CONTACTS']['PROFILES'] ) ?>
      </div>
    </div>
    <? endif; //if ( $form['CONTACTS'] ) ?>
    <!-- /Ввод контактных данных -->

    <?=$form['HIDDEN'] ?>
  </form>
</div>
<script type="text/javascript">
//<![CDATA[
/* Лейблы-подсказки к полям */
aFieldsIdsToNames = <?= array2json($arFieldsIdsToNames) ?>;

/* Инициализация лейблов-подсказок */
function InitPlaceholders() {
  for ( var i = 0; i < intBlocksOnPage - 1; i++ ) {
    onDocTypeChangeB2B(i);
  };
};
InitPlaceholders();

function ffakToggle(blockId) {
  var ffakVisibled = $('#PSGRDATA_FFAK_VISIBLED_' + blockId);
  if(ffakVisibled) {
    ffakVisibled.val(ffakVisibled.val() == "false" ? "true" : "false");
    $( '#TR_PSGRDATA_FFAK_' + blockId + ', #TR_PSGRDATA_FFCARDNMBR_' + blockId ).toggle();
  }
  return false;
}
//]]>
</script>
