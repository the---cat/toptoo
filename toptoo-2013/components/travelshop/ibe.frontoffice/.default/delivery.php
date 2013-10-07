<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) { die(); }
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/ibe/classes/js_lang/ibe_js.php');
$delivery_profiles_links_num = 2; // Количство выводимых ссылок-профилей
$uniform = defined("__UNIFORM_JS") && true ===__UNIFORM_JS ? true : false;

$dontUseProfiles = true;
?>

<script type="text/javascript">
// <![CDATA[
function onProfileClickDelivery( block_id, profile_id ) {
  profile_id_uc = profile_id.toUpperCase();
  profile_id = profile_id.toLowerCase();

  /* Подсветка выбранного профиля */
  $('.delivery' + block_id + ' .profiles_links li.profile_link').removeClass( 'selected' );
  $('#profile_link_' + block_id + '_' + profile_id_uc).addClass( 'selected' );

  /* Обновить кастомные селекты */
  if ( typeof( updateUniform ) != 'undefined' ) {
    updateUniform( $('#address_form'), 'select' );
  }
}

function DeleteProfileDelivery( profile_id ) {
  /* Убрать из всех блоков с профилями */
  for ( var i = 0; i < intBlocksOnPage; i++ ) {

    if ( !document.getElementById( 'table_popup' + i ) ){
      continue;
    }

    /* Выбрать "новый" если выбран удаляемый профиль */
    if ( $('#profile_link_' + i + '_' + profile_id ).hasClass( 'selected' ) ){
      onProfileClick( i, 'new' );
      onProfileClickDelivery( i, 'new' );
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

function ViewAddresFormB2B( block_id ) {
  var profiles_list_id = '#profiles_links_data_form_' + block_id + '_profiles';
  if ( $('#address_form').css('display') == 'none' ) {
    $(profiles_list_id).hide();
  } else {
    $(profiles_list_id).show();
  }
}

function showDeliveryDesc( method ) {
  console.log(method.attr('id'));
  if ( method.find('input').hasClass('input_self') ){
    var deliveryTitle = '<div class="self_title">' + method.find('.self_title').html() + '</div>',
    deliveryAddr = '<div class="comment">' + $('#adr_' + method.find('input').val() + ' .description').html() + '</div>';
    setTimeout( function(){
      if ( $('#pay_method_selected_description .description_left').length ) {
        $('#pay_method_selected_description .description_left').html(deliveryTitle + deliveryAddr);
        method.hide();
        $('#adr_' + method.find('input').val()).hide();
      }
    } , 10);
  }
}
// ]]>
</script>
<?= $arResult['SCRIPT']; ?>
<? $BlockID = $arResult['CNT_PASSENGERS'] + 1; ?>

<div class="delivery clearfix">
  <form name="<?= $arResult['FORM']['NAME']; ?>" action="<?=$arResult['FORM']['ACTION']; ?>" id="<?=$arResult['FORM']['~ID']; ?>" method="<?=$arResult['FORM']['~METHOD']; ?>"<? if ('Y' == $arParams['~IBE_AJAX_MODE']) { ?> onsubmit="ibe_ajax.post(this);return false;"<? } ?>>
    <? if ( $arResult['DISPLAY_ERROR'] ): ?>
    <h3 class="caption"><?= $arResult['DISPLAY_ERROR']?></h3>
    <? endif; ?>

    <div class="delivery<?= $BlockID ?> wrap">
    <? if (isset($arResult['FORM']['FIELDS'])): ?>
      <?= GetIbeJsStrings(); ?>
      <div id="select_location_block" class="location" style="display:none;">
        <? if (1 == count($arResult['FORM']['FIELDS']['select_location']['OPTION']) && 'display:none;' == $arResult['FORM']['FIELDS']['select_location']['STYLE'] && '' != $arResult['FORM']['FIELDS']['select_location']['OPTION'][0]['CAPTION'] ): ?>
        <input type="hidden" name="<?= $arResult['FORM']['FIELDS']['select_location']['NAME']; ?>" id="<?= $arResult['FORM']['FIELDS']['select_location']['~ID']; ?>" value="<?= $arResult['FORM']['FIELDS']['select_location']['OPTION'][0]['VALUE']; ?>" />
        <? endif; ?>

        <? if (array_key_exists('select_location', $arResult['FORM']['FIELDS']) && count($arResult['FORM']['FIELDS']['select_location']['OPTION']) > 1): ?>
        <select name="<?= $arResult['FORM']['FIELDS']['select_location']['NAME']; ?>" id="<?= $arResult['FORM']['FIELDS']['select_location']['~ID']; ?>" onchange="<?= $arResult['FORM']['FIELDS']['select_location']['ONCHANGE']; ?>">
          <? foreach ($arResult['FORM']['FIELDS']['select_location']['OPTION'] as $option): ?>
          <option value="<?= $option['VALUE']; ?>"<? if ($option['~SELECTED']): ?> selected="selected"<? endif; ?>>
          <?= $option['CAPTION']; ?>
          </option>
          <? endforeach; ?>
        </select>
        <? endif; ?>
      </div>

      <div id="DeliveryMethod" class="delivery_methods description_left">
        <? $ar_delivery_by_groups = array();
        foreach ($arResult['FORM']['FIELDS']['DELIVERYMETHODS'] as $k => $methods){
          $ar_delivery_by_groups[$k]['~TYPE'] = $methods['~TYPE'];
          $ar_delivery_by_groups[$k]['CLASS'] = $methods['CLASS'];
          $ar_delivery_by_groups[$k]['~ID'] = $methods['~ID'];
          $self_i = $cour_i = 0;
          foreach ( $methods['FIELDS'] as $i => $method ){
            if ( FALSE !== strpos($method['CLASS'], 'self') ) { //Самостоятельное получение
              $ar_delivery_by_groups[$k]['SELF'][$self_i] = $method;
              $ar_delivery_by_groups[$k]['SELF'][$self_i]['METHOD_KEY'] = $i;
              $self_i++;
            } elseif ( FALSE !== strpos($method['CLASS'], 'cour') ) { //Курьерская доставка
              $ar_delivery_by_groups[$k]['COUR'][$cour_i] = $method; 
              $ar_delivery_by_groups[$k]['COUR'][$cour_i]['METHOD_KEY'] = $i; 
              $cour_i++;
            } 
          }
        } ?>
        <? foreach ($arResult['FORM']['FIELDS']['DELIVERYMETHODS'] as $k => &$field):
          $bShowCustomSelf = is_array($ar_delivery_by_groups[$k]) && count($ar_delivery_by_groups[$k]['SELF']) == 1 ? true : false; 
          $bShowCustomCour = is_array($ar_delivery_by_groups[$k]) && count($ar_delivery_by_groups[$k]['COUR']) == 1 ? true : false; ?>

        <? if ( !$bShowCustomSelf && !$bShowCustomCour ) { ?>
        <h3 class="info_caption"><?=GetMessage('IBE_DELIVERY_CAPTION')?></h3>
        <? } ?>

        <div class="<?= $field['CLASS']; ?><?= $bShowCustomSelf || $bShowCustomCour ? ' delivery_groups' : '' ?>" id="<?= $field['~ID']; ?>">
          <? if (!count($field['FIELDS'])): ?>
          <div class="method"><?= GetMessage('IBE_DELIVERY_NO_DELIVERY_WAYS'); ?></div>
            <? continue; ?>
          <? endif; ?>

          <? if ( $bShowCustomCour ) { ?>
            <? $f2 = $ar_delivery_by_groups[$k]['COUR'][0]; ?>
          <div class="<?= $f2['CLASS']; ?> clearfix"<?=$f2['~id'] ? ' id="' . $f2['~id'] . '" ' : '' ?> onclick="<?= $f2['ONCLICK']; ?>;ViewAddresFormB2B('<?=$BlockID?>');">
            <h3 class="info_caption"><?=GetMessage('IBE_DELIVERY_CAPTION')?></h3>
            <div class="hidden_checker">
              <? foreach ($f2['FIELDS'] as $f3): ?>
              <input<?= ($f3['CHECKED'] ? ' checked="checked"' : '') ?> type="<?= $f3['~TYPE']; ?>" class="<?= $f3['CLASS']; ?> input_cour" id="<?= $f3['~ID']; ?>" value="<?= $f3['VALUE']; ?>" name="<?= $f3['NAME']; ?>" />
              <? endforeach; ?>
            </div>

            <div id="adr_<?=$f2['FIELDS'][0]['VALUE']; ?>" class="cour_title">
            <? foreach ($f2['FIELDS'][0]['DESCRIPTION'] as $desc): ?>
              <h3 class="caption"><?= $desc; ?></h3>
            <? endforeach; ?>
            </div>
            
          </div>
            <? unset($field['FIELDS'][ $ar_delivery_by_groups[$k]['COUR'][0]['METHOD_KEY'] ]);
            unset($ar_delivery_by_groups[$k]['COUR']);
          } ?>

          <? if ( count($field['FIELDS']) ): ?>
            <? foreach ($field['FIELDS'] as $f2): ?>
          <div class="<?= $f2['CLASS']; ?>"<?=$f2['~id'] ? ' id="' . $f2['~id'] . '" ' : '' ?> onclick="<?= $f2['ONCLICK']; ?>;ViewAddresFormB2B('<?=$BlockID?>');showDeliveryDesc($(this));">
              <? if ( $bShowCustomSelf && FALSE !== strpos($f2['CLASS'], 'self') ) { ?>
            <div class="hidden_checker">
              <? foreach ($f2['FIELDS'] as $f3): ?>
              <input<?= ($f3['CHECKED'] ? ' checked="checked"' : '') ?> type="<?= $f3['~TYPE']; ?>" class="<?= $f3['CLASS']; ?> input_self" id="<?= $f3['~ID']; ?>" value="<?= $f3['VALUE']; ?>" name="<?= $f3['NAME']; ?>" />
              <? endforeach; ?>
            </div>
            <div class="self_title"><?= GetMessage('IBE_FRONTOFFICE_DELIVERY_SELF_S') ?></div>
              <? } else { ?>
               <? foreach ($f2['FIELDS'] as $f3): ?>
            <input<?= ($f3['CHECKED'] ? ' checked="checked"' : '') ?> type="<?= $f3['~TYPE']; ?>" class="<?= $f3['CLASS']; ?>" id="<?= $f3['~ID']; ?>" value="<?= $f3['VALUE']; ?>" name="<?= $f3['NAME']; ?>" />
            <label for="<?= $f3['~ID']; ?>" class="title" title="<?= $f3['TITLE']; ?>"><?= $f3['CAPTION']; ?></label>
               <? endforeach; ?>
              <? } ?>
          </div>
            <? endforeach; ?>
          <? endif; ?>
        </div>
        <? endforeach; ?>
      </div>

      <div id="addresses" class="description_right">
      <? foreach ($arResult['FORM']['FIELDS']['DELIVERYMETHODS'] as $field): ?>
        <? foreach ($field['FIELDS'] as $f2): ?>
        <div id="adr_<?=$f2['FIELDS'][0]['VALUE']; ?>">
          <? foreach ($f2['FIELDS'][0]['DESCRIPTION'] as $desc): ?>
          <div class="description"><?= $desc; ?></div>
          <? endforeach; ?>
        </div>
        <? endforeach; ?>
      <? endforeach; ?>
      </div>

      <div id="address_form">
        <? if ( !$dontUseProfiles ) { ?>
        <div class="<?= $arResult['FORM']['FIELDS']['PROFILES']['FIELDS'][0]['CLASS']; ?>" id="<?= $arResult['FORM']['FIELDS']['PROFILES']['FIELDS'][0]['~ID']; ?>">
          <? foreach ($arResult['FORM']['FIELDS']['PROFILES']['FIELDS'][0]['FIELDS'] as $field): ?>
          <div class="profile_checker">
            <input type="<?= $field['~TYPE']; ?>" id="<?= $field['~ID']; ?>" name="<?= $field['NAME']; ?>" onclick="<?= $field['ONCLICK']; ?>; onProfileClickDelivery(<?= $BlockID ?>, '<?= $field['VALUE']?>');" value="<?= $field['VALUE']; ?>" />
            <label for="<?= $field['~ID']; ?>" id="<?= $field['~ID']; ?>_LABEL">
              <?= $field['CAPTION']; ?>
            </label>
          </div>
          <? endforeach; ?>
        </div>
        <? } ?>

        <div id="<?= $arResult['FORM']['FIELDS']['PROFILES']['FIELDS'][1]['~ID']; ?>" class="profile_form clearfix">
          <? foreach ($arResult['FORM']['FIELDS']['PROFILES']['FIELDS'][1]['FIELDS'] as $field): ?>
          <? $fieldId = substr($field['NAME'], 13, -2); ?>
          <div class="field <?= strtolower( $fieldId ) ?><? if( $uniform && 'select' == $field['~TYPE']) { ?> custom<? } ?>" id="<?= $field['~ID']; ?>_LABEL">
            <label for="<?= $field['~ID']; ?>" class="title">
              <?= $field['CAPTION']; ?>
            </label>
            <? if ('text' == $field['~TYPE']): ?>
            <input class="<?= $field['CLASS']; ?>" type="<?= $field['~TYPE']; ?>" id="<?= $field['~ID']; ?>" name="<?= $field['NAME']; ?>" onclick="<?= $field['ONCLICK']; ?>" size="<?= $field['SIZE']; ?>" />
            <? endif; ?>
            <? if ('select' == $field['~TYPE']): ?>
            <select class="<?= $field['CLASS']; ?>" name="<?= $field['NAME']; ?>" id="<?= $field['~ID']; ?>">
              <? foreach ($field['OPTIONS'] as $option): ?>
              <option value="<?= $option['VALUE']; ?>"<? if ($option['~SELECTED']): ?> selected="selected"<? endif; ?>>
              <?= $option['CAPTION']; ?>
              </option>
              <? endforeach; ?>
            </select>
            <? endif; ?>
          </div>
          <? endforeach; ?>
        </div>
      </div>
      <? endif; ?>
    </div>
    <?/* .deliveryX */?>
    <? if (isset($arResult['FORM']['BUTTONS'])): ?>
    <div class="buttons clearfix">
      <? foreach ($arResult['FORM']['BUTTONS'] as $field): ?>
      <div class="<?= $field['CLASS2']; ?>"><?= CTemplateToolsUtil::RenderField( $field ) ?></div>
      <? endforeach; ?>
    </div>
    <? endif; ?>
    <?= $arResult['HIDDEN']; ?>
  </form>
</div>