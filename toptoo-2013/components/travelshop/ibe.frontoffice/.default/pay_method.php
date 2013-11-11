<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? 
$form = &$arResult['FORM'];
$pay_methods = &$form['FIELDS'];

$group_names = array();
$bNoGorup = false;
foreach ( $form['FIELDS'] as $pay_method_num => $ar_paymethod ) {
  if ( isset($ar_paymethod['INPUT_VALUE']) ) {
    if ( !$ar_paymethod['GROUP_NAME'] ) {
      $bNoGorup = true;
      continue;
    }
    if ( $ar_paymethod['GROUP_NAME'] && !in_array($ar_paymethod['GROUP_NAME'], $group_names) ){
      $group_names[] = $ar_paymethod['GROUP_NAME'];
    }
  }
}
if ( $bNoGorup && count($group_names) ){ $group_names[] = 'NO_GROUP'; }

$ar_paymethod_by_groups = array();
foreach ( $form['FIELDS'] as $id => &$method ) {
  //Логотипы платежных систем
  if(!is_numeric($id) && strpos($id, '_div') === false && $id != 'TIMELIMIT') {
    $method_id = explode( "_", $method[ "~ID" ] );
    $method_numeric_id = $method_id[ 0 ];
    array_shift( $method_id );
    array_shift( $method_id );
    $method_id = implode( "_", $method_id );
    $main_method_id = end( explode( ".", reset( explode( "/", $method[ "XML_ID" ] ) ) ) );
    if ( $main_method_id == "CASH" ) { $main_method_id = "TS_CASH"; }
    $method_id = ToLower( strlen( $method_id ) == 0 ? $main_method_id : $method_id );
    $method['METHOD_ID'] = $method_id;

    $paysystem_picture_path = '';
    $ar_img_locations = array(
      "/images/paysystem_" . $method_numeric_id,
      "/images/paysystem_" . $method_id,
      "/bitrix/templates/" . SITE_TEMPLATE_ID . "/i/paysystem_" . $method_id,
      "/bitrix/components/travelshop/ibe.frontoffice/templates/.default/images/paysystem/" . $method_id
    );
    foreach( array( '.png', '.gif' ) as $dot_ext ) {
      foreach( $ar_img_locations as $img_location ) {
        if ( file_exists( $_SERVER["DOCUMENT_ROOT"] . $img_location . $dot_ext ) ) {
          $paysystem_picture_path = $img_location . $dot_ext;
          break 2;
        }
      }
    }
    $method['PS_PICTURE'] = ( strlen( $method_id ) > 0 && strlen( $paysystem_picture_path ) ) ? $paysystem_picture_path : false;
  }

  //Разбиваем на группы
  if ( count($group_names) ) {
    if ( isset( $method['INPUT_VALUE'] ) ) {
      $group_name = (string) $method['GROUP_NAME'] != '' ? (string) $method['GROUP_NAME'] : 'NO_GROUP'; //Платежные системы без группы кладем в "прочие"
      if ( FALSE !== $k = array_search($group_name, $group_names) ) {
        $ar_paymethod_by_groups[$k]['GROUP_NAME'] = strlen(GetMessage('IBE_FRONTOFFICE_PAY_METHOD_GROUP_' . $group_name)) ? GetMessage('IBE_FRONTOFFICE_PAY_METHOD_GROUP_' . $group_name) : $group_name;
        $ar_paymethod_by_groups[$k]['GROUP_CLASS'] = $method['~SELECTED'] && empty($ar_paymethod_by_groups[$k]['GROUP_CLASS']) ? ' selected no_close' : $ar_paymethod_by_groups[$k]['GROUP_CLASS'];
        $ar_paymethod_by_groups[$k]['PAYMETHODS'][] = array(
          'GROUP_ID' => $k,
          'GROUP_NAME' => $group_name,
          'PS_ID' => $method['ID']
        );
      }
    }
  }

}
ksort(&$ar_paymethod_by_groups);
$ar_paymethod_by_groups = count($ar_paymethod_by_groups) ? $ar_paymethod_by_groups : false;

//trace($form['FIELDS']);
//trace($ar_paymethod_by_groups);


function renderPayMethod( $method, $fields ){
  if ( !is_numeric($ar_group_settings['PS_ID']) && strpos($ar_group_settings['PS_ID'], '_div') === false && $ar_group_settings['PS_ID'] != 'TIMELIMIT' ) {
    $method_id = explode( "_", $method[ "~ID" ] );
    $method_id = $method_id[ 2 ];
    $main_method_id = end( explode( ".", reset( explode( "/", $method[ "XML_ID" ] ) ) ) );
    if ( $main_method_id == "CASH" ) { $main_method_id = "TS_CASH"; }
    $method_id = ToLower( strlen( $method_id ) == 0 ? $main_method_id : $method_id );
    if ( strlen( $method_id ) > 0 ) { $method['CLASS_NAME'] .= ' paysystem_' . $method_id; }
    if ( ToLower($method['ACTION']) == 'pcs' || ($method[ "ACTION" ] == "platron" && $method[ "SUBSYSTEM_ID" ]== 'CASH') ) { $method['CLASS_NAME'] .= ' paysystem_pcs'; }
    if ( ToUpper($method['GROUP_NAME']) == 'CARD' || $method['GROUP_NAME'] == 'ONLINE' ) {
      if ( isset( $method[ "~CRS_CURRENCY_" ] ) && $method[ "ACTION" ] == "platron" ){
        $method['CLASS_NAME'] .= ' paysystem_creditcard_ak';
      } else { $method['CLASS_NAME'] .= ' paysystem_creditcard'; }
    } 
  } ?>
  <li class="paymethod <? if($method['~SELECTED']){ ?> selected<? } ?>">
    <div class="item clearfix <?= $method['CLASS_NAME'] ?>">
      <input <? if($method['~SELECTED']){ ?> checked="checked"<? } ?> id="<?=$method['~ID'] ?>" name="<?=$method['NAME'] ?>" onclick="<?=$method['ONCLICK'] ?>" type="radio" value="<?=$method['VALUE'] ?>" />
      <label for="<?=$method['~ID'] ?>">
        <span id="sum_<?= $method['~ID'] ?>" class="summ">
          <?= $method['PAYMENT_AMOUNT_DESCRIPTION']  ? $method['PAYMENT_AMOUNT_DESCRIPTION'] : $method['FULL_PRICE'] ?>
        </span>
        <div class="paysystem_icon"></div>
        <span class="paysystem_name"><?=$method['CAPTION'] ?></span>
        
        <div style="display:none" id="paysystem_profile_<?=$method['~ID'] ?>">
          <? //trace($method) ?>
          <div class="paysystem_name_js"><?=$method['CAPTION'] ?></div>
          <div class="paysystem_descr_js">
            <? $gdsMsg = $pcsMsg = $ps_class = '';
            if ( isset( $method[ "~CRS_CURRENCY_" ] ) && $method[ "ACTION" ] == "platron" ): // оплата через GDS
              unset( $method['DESCRIPTION'][ "CRS_CURRENCY" ] );
              unset( $method['DESCRIPTION'][ "CRS_CURRENCY_CONVERSION" ] );

              $gdsMsg = GetMessageExtended( 'IBE_FRONTOFFICE_PAY_METHOD_PAYMENT_DESCRIPTION_TITLE_AK', $method );
              if ( $method[' ~CRS_CURRENCY'] != $method[ "~LOCAL_CURRENCY" ] || $method[ "~LOCAL_PAYMENT" ] != 0 ) {
                $gdsMsg .= ' ' . GetMessageExtended( 'IBE_FRONTOFFICE_PAY_METHOD_PAYMENT_DESCRIPTION_SUM_AK', $method );
                $gdsMsg .= $method[ "~CRS_CURRENCY" ] && $method[ "~CRS_CURRENCY" ] != $method[ "~LOCAL_CURRENCY" ] ? ' ' . GetMessage('IBE_FRONTOFFICE_PAY_METHOD_PAYMENT_DESCRIPTION_CUR_AK') . ($method[ "~LOCAL_PAYMENT" ] == 0 ? '.' : '') : '';
                $gdsMsg .= $method[ "~LOCAL_PAYMENT" ] != 0 ? ' ' . GetMessageExtended( "IBE_FRONTOFFICE_PAY_METHOD_PAYMENT_DESCRIPTION_LOC_AK", $method ) : '';
              }

              $gdsMsg .= '<br />' . GetMessage('IBE_FRONTOFFICE_PAY_METHOD_PAYMENT_DESCRIPTION_NOTE_AK');
              $MESS['IBE_FRONTOFFICE_PAY_METHOD_PAYMENT_DESCRIPTION_AK'] = $gdsMsg;
              $ps_class = 'AK';

            elseif ( strlen(GetMessage('IBE_FRONTOFFICE_PAY_METHOD_PAYMENT_DESCRIPTION_' . ToUpper($method['METHOD_ID']))) ):
               $ps_class = ToUpper($method['METHOD_ID']);

            elseif ( $method['GROUP_NAME'] == 'CARD' || $method['GROUP_NAME'] == 'ONLINE' ):
              $ps_class = 'AG';

            elseif ( $method[ "ACTION" ] == "platron" && $method[ "SUBSYSTEM_ID" ]== 'CASH' ):
              $ps_class = 'PCS';
              if ( (count($method['DESCRIPTION']) > 1 && count($method['DESCRIPTION']) > $nSubSys = count($method['SUBSYSTEMS'])) || count($method['DESCRIPTION']) == $nSubSys ){
                $pcsMsg = '<ul>';
                for ( $i=0; $i< $nSubSys; $i++ ) {
                  $pcsMsg .= '<li>' . $method['DESCRIPTION'][$i] . '</li>';
                  unset($method['DESCRIPTION'][$i]);
                }
                $pcsMsg .= '</ul>';
              }
              $MESS['IBE_FRONTOFFICE_PAY_METHOD_PAYMENT_DESCRIPTION_PCS'] = strlen($pcsMsg) ? $pcsMsg : GetMessage('IBE_FRONTOFFICE_PAY_METHOD_PAYMENT_DESCRIPTION_PCS');
              $MESS['IBE_FRONTOFFICE_PAY_METHOD_PAYMENT_DESCRIPTION_PCS'] .= ' <p>' . GetMessage('IBE_FRONTOFFICE_PAY_METHOD_PAYMENT_DESCRIPTION_CASH') . '</p>';

            elseif ( 
              $method[ "PS_CASH_TYPE" ] !== 'COUR' &&
              ( ($method[ "ACTION" ] == "platron" && $method[ "SUBSYSTEM_ID" ]== 'POSTPONED') || substr($method['PS_TYPE'], 0,11) !== 'ONLINE_SYNC' )
              ) :
              $ps_class = 'CASH';
            endif; ?>

          <? if ( $ps_class || $method['DESCRIPTION'] || array_key_exists($method['~ID'].'_div', $fields)): ?>
            <div class="description_left">
              <? if ( $method['DESCRIPTION'] ){ ?>
              <div><?= implode( "</div> <div>", ( $method['DESCRIPTION'] ) ) ?></div>
              <? } ?>

              <? // Опции
              $id = $method['~ID'];
              if ( array_key_exists($id.'_div', $fields) ) { ?>
              <div id="pay_method_options-<?=$id ?>" class="pay_method_options">
                <? foreach($fields[$id.'_div']['FIELDS'] as $option): ?>
                <? if($option['~TYPE'] == "text"): ?>
                <div class="option">
                  <label for="<?=$option['~ID'] ?>"><?=$option['CAPTION'] ?>:</label>
                  <input class="text" id="<?=$option['~ID'] ?>" name="<?=$option['NAME'] ?>" type="<?=$option['~TYPE'] ?>" value="<?=$option['VALUE'] ?>" />
                </div>
                <? elseif ($option['~TYPE'] == "hidden") : ?>
                <input type="hidden" name="<?=$option['NAME'] ?>" value="<?=$option['VALUE'] ?>">
                <? endif; ?>
                <? endforeach; ?>
              </div>
              <? } ?>
            </div>
            <div class="description_right">
              <div><?= GetMessage('IBE_FRONTOFFICE_PAY_METHOD_PAYMENT_DESCRIPTION_' . $ps_class ); ?></div>
              <? if ( strlen(GetMessage('IBE_FRONTOFFICE_PAY_METHOD_PAYMENT_COMMENT_'.$ps_class)) ) { ?>
              <div class="comment"><?= GetMessage('IBE_FRONTOFFICE_PAY_METHOD_PAYMENT_COMMENT_'.$ps_class) ?></div>
              <? } ?>

            </div>
            <? endif; ?>
          </div>
        </div>
      </label>
    </div>
  </li>
<? } ?>
<h3 class="info_caption"><?=GetMessage('IBE_PAY_METHOD_CAPTION')?></h3>
<div class="pay_method">
<?
$arTagForm = array(
    'action' => htmlspecialchars( $arResult['FORM']['ACTION'] ),
    'id' => $form['~ID'],
    'name' => $form['NAME'],
    'class' => 'payment',
    'method' => strtolower( $form['~METHOD'] ),
    'onsubmit' => $form[ "ONSUBMIT" ],
);
foreach ( $arTagForm as $k => $v ) {
  $arTagForm[] = $k . '="' . $v . '"';
  unset( $arTagForm[$k] );
}
$strTagForm = implode( ' ', $arTagForm );
?>
<form <?= $strTagForm; ?>>
    <table>
      <tbody>
        <tr>
          <td class="paymethods">
            <div class="paymethods_wrap">
            <? if ( $ar_paymethod_by_groups ): ?>
              <div class="pay_methods_groups">
                <? foreach ( $ar_paymethod_by_groups as $group_id => $ar_group ): ?>
                <h2 class="group_name<?= $ar_group['GROUP_CLASS'] ?>" id="group_<?= $group_id ?>"><span class="name"><?= $ar_group['GROUP_NAME'] ?></span></h2>
                <div class="paymethods_group<?= $ar_group == end($ar_paymethod_by_groups) ? ' last' : '' ?><?= strlen($ar_group['GROUP_CLASS']) ? ' opened' : '' ?>" id="paymethods_group_<?= $group_id ?>"<?= strlen($ar_group['GROUP_CLASS']) ? '' : ' style="display:none;"' ?>>

                  <ul class="pay-methods">
                  <? foreach ( $ar_group['PAYMETHODS'] as $cnt_group => $ar_group_settings ) {
                    $id = $ar_group_settings['PS_ID'];
                    $method = $form['FIELDS'][$id];
                    if(!is_numeric($id) && strpos($id, '_div') === false && $id != 'TIMELIMIT'){
                      renderPayMethod($method,$form['FIELDS']);
                    }
                  } ?>
                  </ul>
                </div>
                <? endforeach; ?>
              </div>
              <? else: //if ( $ar_paymethod_by_groups ): ?>
              <ul class="pay-methods">
                <? foreach($form['FIELDS'] as $id => $method) {
                  if(!is_numeric($id) && strpos($id, '_div') === false && $id != 'TIMELIMIT'){
                    renderPayMethod($method,$form['FIELDS']);
                  }
                } ?>
              </ul>
              <? endif; //if ( $ar_paymethod_by_groups ): ?>
            </div>
          </td>
          <td class="rules_and_coditions">
            <div class="timelimit">
                <div class="caption"><?= GetMessage('IBE_FRONTOFFICE_PAY_METHOD_TIMELIMIT') ?></div>
                <div class="info"><?= $pay_methods['TIMELIMIT']['TEXT'] ?></div>
              </div>

              <? if ( $GLOBALS['COMPONENT_SESSION']['ALL_IN_ONE_AGREEMENTS_DATA']['TRANSIT_VISA_REQUIRED']): ?>
              <div class="travsit_visa">
                <div class="caption"><?= GetMessage( 'IBE_FRONTOFFICE_PAY_METHOD_VISA' ); ?></div>
                <div class="info"><?= GetMessage( 'IBE_PREVIEW_TRANSIT_VISA_REQUIRED' ); ?></div>
              </div>
              <? endif; ?>
              <? if ( strlen( $GLOBALS['COMPONENT_SESSION']['ALL_IN_ONE_AGREEMENTS_DATA']['PENALTY'] ) ): ?>
              <div class="return-policy">
                <div class="caption">
                  <? if ( empty( $GLOBALS['COMPONENT_SESSION']['ALL_IN_ONE_AGREEMENTS_DATA']['TARIFF'] ) ): ?>
                  <?=GetMessage("IBE_FRONTOFFICE_PAY_METHOD_PENALTY") ?>
                  <? else: ?>
                  <a href="#" onclick="if(document.getElementById('tariff_info').style.display != 'block') {document.getElementById('tariff_info').style.display = 'block'; this.innerHTML = '<?=GetMessage("IBE_PREVIEW_PENALTY_HIDE") ?>';} else {document.getElementById('tariff_info').style.display = 'none';  this.innerHTML = '<?=GetMessage("IBE_FRONTOFFICE_PAY_METHOD_PENALTY") ?>';} return false;">
                  <?=GetMessage("IBE_FRONTOFFICE_PAY_METHOD_PENALTY") ?>
                  </a>
                  <? endif; ?>
                </div>
                <div class="info"><?= $GLOBALS['COMPONENT_SESSION']['ALL_IN_ONE_AGREEMENTS_DATA']['PENALTY'] ?></div>
                <div id="tariff_info" class="tariff_info" style="display:none">
                  <? foreach( $GLOBALS['COMPONENT_SESSION']['ALL_IN_ONE_AGREEMENTS_DATA']['TARIFF'] as $Tariff): ?>
                  <h4 class="tariff_title"><?=$Tariff['TITLE'] ?></h4>
                  <div class="tariff_condition"><?=$Tariff['CONDITION'] ?></div>
                  <? endforeach; ?>
                </div>
              </div>
              <? endif; ?>
              <div class="limits">
                <? /* Ограничения */ ?>
                <h3 class="limits_caption"><?=GetMessage('IBE_FRONTOFFICE_PAY_METHOD_LIMITS_CAPTION') ?></h3>
                <ul>
                  <? if ( is_array($arResult['ORDER']['LIMITS']) ): ?>
                  <? foreach ( $arResult['ORDER']['LIMITS'] as $limit ) { ?>
                  <li><?= $limit ?></li>
                  <? } ?>
                  <? endif; ?>
                  <li><?= GetMessage('IBE_FRONTOFFICE_PAY_METHOD_LIMITS_1') ?></li>
                  <li><?= GetMessage('IBE_FRONTOFFICE_PAY_METHOD_LIMITS_2') ?></li>
                  <li><?= GetMessage('IBE_FRONTOFFICE_PAY_METHOD_LIMITS_3') ?></li>
                </ul>
              </div>
              <div class="warning" onclick="scrollToOrder()"><?= GetMessage('IBE_FRONTOFFICE_PAY_METHOD_WARNING') ?></div>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="description_container clearfix">
      <div id="pay_method_selected_description" class="pay_method_description"> </div>
    </div>

<script type="text/javascript">
// <![CDATA[
function scrollToOrder() {
  $('html,body').animate({ scrollTop: ( $('#ts_ag_reservation_container__personal_data').offset().top ) }, 500);
}

function scrollToButton(){
  var button = $('#commit_forward_button');
  if ( $(window).scrollTop() + $(window).height() < button.offset().top + button.outerHeight() ) {
    setTimeout(function(){ $('html,body').animate({ scrollTop: ( button.offset().top ) }, 1000); },500 );    
  }
}

<? if ( $ar_paymethod_by_groups ){ ?>
function toggleGroup(group_id){
  var group_name = $('#' + group_id),
  group_div = $('#paymethods_' + group_id);

  if ( group_name.hasClass('opened') && !group_name.hasClass('no_close') ) {
    group_div.removeClass('opened').slideUp('fast', function(){ group_name.removeClass('opened'); });
  } else if( !group_name.hasClass('opened') ) {
    group_div.slideDown().addClass('opened');
    group_name.addClass('opened');
  }
}

$('.group_name').click(function(){ 
  var id = $(this).attr('id');
  toggleGroup(id);
  scrollToButton();
});
<? } ?>

$('.paymethods .item input:radio').click(function(){
  var el = $(this),
  item = el.closest('.item'),
  pm = el.closest('.paymethod'),
  ps_id = el.attr('id');
  if ( pm.hasClass('selected') ) return;
  $('.paymethod.selected').removeClass('selected');

  <? if ( $ar_paymethod_by_groups ){ ?>
  $('.group_name.no_close').removeClass('no_close');
  var groupNameId = pm.closest('.paymethods_group').attr('id');
  $( '#' + groupNameId.substr(11) ).addClass('no_close');
  <? } ?>

  pm.addClass('selected'); 

  $('#pay_method_selected_description').html( $('#paysystem_profile_'+ ps_id + ' .paysystem_descr_js').html() );

    if ( item.hasClass('pay_method_self') || ( item.hasClass('paysystem_ts_cash') && !item.hasClass('pay_method_cour') ) ){
    var method = $('#delivery_form #DeliveryMethod .method_self');
    if ( method.hasClass('method_self') ){
      var deliveryTitle = '<div class="self_title">' + method.find('.self_title').html() + '</div>',
      deliveryAddr = '<div class="comment">' + $('#adr_' + method.find('input').val() + ' .description').html() + '</div>';

      if ( $('#pay_method_selected_description .description_left').length ) {
        $('#pay_method_selected_description .description_left').append(deliveryTitle + deliveryAddr);
        method.hide();
        $('#adr_' + method.find('input').val()).hide();
      }
    }
  }
  
  scrollToButton();
});

$(document).ready(function(){
  var el = $('#paysystem').find('.paymethod.selected input');
   ps_id = el.attr('id');
   $('#pay_method_selected_description').html( $('#paysystem_profile_'+ ps_id + ' .paysystem_descr_js').html() );
});
// ]]>
</script>
<?=$form['HIDDEN'] ?>
</form>
</div>