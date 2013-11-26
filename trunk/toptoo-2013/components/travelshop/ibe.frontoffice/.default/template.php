<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $MESS;
include( GetLangFileName( $_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/components/travelshop/ibe.frontoffice/.default"."/lang/", "/template.php" ) );

$USE_AUTOCOMPLETE = ( !count($arResult['points']) && $arParams["USE_AUTOCOMPLETE"] == "Y" ) ; // Использовать автозаполнение, если используются поля для ввода пунктов и разрешено автозаполнение

$USE_JQUERY_UI = true;

require_once(dirname(__FILE__).'/tools.php');

$APPLICATION->AddHeadString(CIBECacheControl::RenderJSLink("/bitrix/js/ibe/tools.js"));
$APPLICATION->AddHeadString(CIBECacheControl::RenderJSLink("/bitrix/js/ibe/formtools.js"));

$frontofficeHelper = new frontofficeHelper();

if ( isset($arResult["processor"]) ) {
    $APPLICATION->SetPageProperty("TravelShopBookingCurrentStage", ToUpper($arResult["processor"]));
}
?>
<? if ( isset( $arResult['processor'] ) ): ?>
  <div id="ts_ag_reservation_curtain" style="overflow:hidden">
    <? /* Выполняется один раз, при любых значениях IBE_AJAX_MODE */ ?>
    <? if ( $arParams['USE_MERGED_STEPS'] === 'Y' && !$arParams['IBE_SECONDARY_CALL'] ): ?>
    <? /* экран ожидания (занавеска) */
      include( dirname( __FILE__ ) . '/progress_ajax.php' );
    ?>
    <script type="text/javascript">/*<![CDATA[*/
      /* Эффекты при смене этапов создания заказа */
      $.wWindow.switch_forms_to_search_mode = function() {
        //$("#form_order_in_form_order_step").hide();
        //$("#form_top_in_form_order_step").show();
        $("#ts_ag_reservation_container__form_order").hide();
        $("#form_top_submit_button").hide();
        $("#ts_ag_reservation_container__form_top").show();
        //$("#form_top_progress").show();
      }
      /* Скрыть и очистить экраны */
      $.wWindow.hide_all_steps = function() {
        //$("#ts_ag_reservation_container__form_top").hide();
        $("#ts_ag_reservation_container__offer").hide();
        $("#ts_ag_reservation_container__offer_lowcost").hide();
        $("#ts_ag_reservation_container__split_fares").hide();
        $("#ts_ag_reservation_container__order").hide();
        $("#ts_ag_reservation_container__order_lowcost").hide();
        $("#ts_ag_reservation_container__personal_data").hide();
        $("#ts_ag_reservation_container__service").hide();
        $("#ts_ag_reservation_container__pay_method").hide();
        $("#ts_ag_reservation_container__delivery").hide();
        $("#ts_ag_reservation_container__precommit").hide();
        $("#ts_ag_reservation_container__progress").hide();
        $("#ts_ag_reservation_container__itinerary").hide();
        $("#ts_ag_reservation_container__finish").hide();
        $("#ts_ag_reservation_container__offer").html('');
        $("#ts_ag_reservation_container__offer_lowcost").html('');
        $("#ts_ag_reservation_container__split_fares").html('');
        $("#ts_ag_reservation_container__order").html('');
        $("#ts_ag_reservation_container__order_lowcost").html('');
        $("#ts_ag_reservation_container__personal_data").html('');
        $("#ts_ag_reservation_container__service").html('');
        $("#ts_ag_reservation_container__pay_method").html('');
        $("#ts_ag_reservation_container__delivery").html('');
        $("#ts_ag_reservation_container__precommit").html('');
        $("#ts_ag_reservation_container__progress").html('');
        $("#ts_ag_reservation_container__itinerary").html('');
        $("#ts_ag_reservation_container__finish").html('');
      };
      $.wWindow.switch_forms_to_input_mode = function() {
        $("#form_top_progress").hide();
        $("#form_top_submit_button").show();
        $("#ts_ag_reservation_container__form_top").hide();
        $("#ts_ag_reservation_container__form_order").show();
      };
      $.wWindow.init({
        phrase_button_close: "<?= GetMessage( 'IBE_WWINDOW_CLOSE' ) ?>",
        phrase_message: $( '#progress_ajax' ).html(),
        phrase_text_id: "progress-text", // ID контейнера для кастомизированного сообщения
        open_animation: function( message, background, context ) {
          switch ( context ) {
            case "form_top_submit" :
            case "form_order_submit" : {
              $.wWindow.switch_forms_to_search_mode();
              $.wWindow.hide_all_steps();
              break;
            }
          }
          background.fadeIn( 100, function(){
            this.style.filter='progid:DXImageTransform.Microsoft.Alpha(style=opacity,opacity=80)';
          });
          message.fadeIn( 200 );
          $( '#progress_ajax' ).empty();
        },
        close_animation: function( message, background, context, bSuccess ) {
          switch ( context ) {
            case "form_top_submit" :
            case "form_order_submit" : {
              $.wWindow.switch_forms_to_input_mode();
              break;
            }
          }
          message.fadeOut( 200 );
          background.fadeOut( 100 );
        }
      });

    /*]]>*/</script>
    <? endif; ?>
    <? /* компонент в AJAX-режиме и это не жестко предопределенный AJAX-вызов */ ?>
    <? if ( $arParams[ "~IBE_AJAX_MODE" ] == "Y"
            && !$arResult[ "~IS_AJAX_MODE" ] ) : ?>
      <?
      // если это загрузка всей страницы, то подключаем базовые скрипты
      if ( !CIBEAjax::IsAjaxMode() ) {
        $APPLICATION->IncludeComponent(
          "travelshop:ibe.ajax",
          ""
        );
      }
      $APPLICATION->AddHeadString(CIBECacheControl::RenderJSLink("/bitrix/components/travelshop/ibe.frontoffice_all_in_one/js/ajax_steps.js"));
      $APPLICATION->AddHeadString(CIBECacheControl::RenderJSLink("/bitrix/components/travelshop/ibe.frontoffice_all_in_one/js/jquery.ba-hashchange.min.js"));
      ?>
      <?/* /?><script type="text/javascript">
          $.taconite.debug = true;
      </script><?/ */?>
      <? if ( !$arParams['IBE_SECONDARY_CALL'] ): ?>
      <script type="text/javascript">/*<![CDATA[*/
        ibe_ajax.on_after_post_loaded = false;
        ibe_ajax.delay_matrix_update_default = 700;
        ibe_ajax.delay_matrix_update_current = 700;
        ibe_ajax.default_areas_to_update = "#ts_ag_reservation_container,#ts_ag_reservation_stages_container,#ts_basket_container,#ts_ag_personal_menu_container,#ts_counter";

        /**
         * Выполняется перед переходом на след. шаг
         */
        ibe_ajax.on_before_post = function () {

          $('#container_error').hide();
          $('#container_error .content').html( '' );

          /* Создание "шторки" */
          $.wWindow.open( undefined, undefined, ibe_ajax.context );

          if ( typeof( $.oAjaxSteps ) != 'undefined' ) {
            $.oAjaxSteps.on_before_post();
          }

        };

        /**
         * Выполняется после загрузки шага
         */
        ibe_ajax.on_after_post = function () {
          if ( this.error_string === false ) {
            $.wWindow.close( true );
          }

          if ( typeof( tooltip ) != 'undefined' ) {
            tooltip();
          }

          if ( typeof( $.oAjaxSteps ) != 'undefined' ) {
            var bDisableScroll = this.error_string !== false;
            if ( $.oAjaxSteps.cfg.cur_step == 'offer' ){ // Issue 64: Отключение прокрутки
              bDisableScroll = true;
            }
            $.oAjaxSteps.on_after_post_ajax( bDisableScroll );
          }

          /* Обновить матрицу ак */
          if ($('#carrier_matrix').length
            && typeof( rebuildCarrierMatrix ) != "undefined" ) {
            setTimeout(function() {
              rebuildCarrierMatrix();
            }, ibe_ajax.delay_matrix_update_current );
          }

          ibe_ajax.delay_matrix_update_current = ibe_ajax.delay_matrix_update_default;
          ibe_ajax.on_after_post_loaded = true;
        };

        /**
         * Выполняется в случае ошибок связи
         */
        ibe_ajax.on_post_error = function( textStatus, errorThrown ) {
          $.wWindow.close( false );
          ibe_ajax.add_error(
            '<?= GetMessage('IBE_AJAX_TIMEOUT') ?>',
            '<?= GetMessage('IBE_AJAX_TIMEOUT') ?>'
          );
        };

        /**
         * Выполняется в случае ошибок приложения
         */
        ibe_ajax._old_do_on_error = ibe_ajax.do_on_error;
        ibe_ajax.do_on_error = function() {
          $.wWindow.close( false );
          ibe_ajax._old_do_on_error();
        };

        ibe_ajax._old_add_error = ibe_ajax.add_error;
        ibe_ajax.add_error = function( sText, sHTML ) {

          $('#container_error .content').html( sHTML );
          $('#container_error').show();

          if ( typeof( $.oAjaxSteps ) != 'undefined' ) {
            $.oAjaxSteps.do_scroll_to( $('#container_error') );
          }

          /* Ошибка на шаге бронирования, включить монитор именений экрана пассажиров */
          if ( typeof( ibe_all_in_one ) != 'undefined' ) {
            ibe_all_in_one.change_monitor.start();
          }
          if ( typeof( oPrecommit ) != 'undefined' ) {
            oPrecommit.Start();
          }

          ibe_ajax._old_add_error( sText, sHTML );
        };

        ibe_ajax.on_application_error = function() {
        }

        ibe_ajax.update_screen_on_error = false;

        /* Browser history */
        var hash_redirect_timer;
        if ( typeof( $.oAjaxSteps ) != 'undefined'
          && typeof( $.oAjaxSteps.hash_reset ) != 'undefined' ) {
          var ar_hash_step = window.location.hash.replace( /^#/, '' ).split('__');
          var base_next_page = "<?= $arParams['BASE_NEXT_PAGE'] ?>";
          if ( ar_hash_step[0].length ) {
            if ( ar_hash_step[0] == 'progress' ) {
              var redirect_url = '<?=
                  ibe_get_scheme()
                  . $_SERVER[ 'HTTP_HOST' ]
                  . filename_to_state( $GLOBALS['componentName'], 'process_order.php', '', $_SESSION['CALL_PAYSYSTEM_INST'], false )
              ?>';

              /* Первый возврат с плат. страницы */
              if ( base_next_page != "service" ) {

                /* Первая занавеска */
                $.wWindow.open();

                /* Показать форму поиска */
                $.oAjaxSteps.init( {'cur_step': 'form_order' } );
                $.oAjaxSteps.on_after_post_ajax( true );

                /* IE, FF */
                hash_redirect_timer = setTimeout( "window.top.location.href = '" + redirect_url + "'", 300 );
              }

            } else {
              $.oAjaxSteps.hash_reset();
            }
          }

          /* Пролистывание шагов */
          $(window).hashchange( function(){
            var ar_hash_step = window.location.hash.replace( /^#/, '' ).split('__');

            if ( ar_hash_step[0] != $.oAjaxSteps.cfg.cur_step ) {
              if ( !ar_hash_step[0].length ) {
                ar_hash_step[0] = 'form_order';
              }
              var step_text = $('#ts_ag_reservation_container__' + ar_hash_step[0] ).text().length;
              if ( step_text ) {
                $.oAjaxSteps.init( {'cur_step': ar_hash_step[0] } );
                ibe_ajax.delay_matrix_update_current = 0;
                ibe_ajax.on_after_post();
              }
            }
          });

        }

      /*]]>*/</script>
      <? endif; /* !IBE_SECONDARY_CALL */ ?>
    <? endif; // $arParams[ "~IBE_AJAX_MODE" ] == "Y" ?>

    <? if ( !$arParams['IBE_SECONDARY_CALL'] ): ?>
    <? /* Выполняется один раз до загрузки шагов, при любых значениях IBE_AJAX_MODE и USE_MERGED_STEPS */ ?>
    <script type="text/javascript">/*<![CDATA[*/
      var ibe_frontoffice_timer = function() {
        if ( typeof( initArrowBlock ) != 'undefined' ) {
          initArrowBlock();
        }
        if ( typeof( $.oAjaxSteps ) == 'undefined'
          && $('#carrier_matrix').length
          && typeof( rebuildCarrierMatrix ) != "undefined" ) {
          resizeAllowedNow = false;
          rebuildCarrierMatrix();
          resizeTimer = setTimeout('resizeAllowedNow = resizeAllowed', resizeDelay);
        }

        <?
        /* Переход с диплинка */
        if ( !$GLOBALS['COMPONENT_SESSION']['process_error_afterpost']
            && strlen( $_SESSION['deeplink_level'] ) ): ?>

          /* Восстановить выбранную ранее ячейку матрицы АК */
          var date_matrix = new Date();
          date_matrix.setTime( date_matrix.getTime() - 1000 );
          document.cookie = IBE_PHPSESSID + "_carrier_matrix_cell=; expires=" + date_matrix.toGMTString() + "; path=/";

          if ( typeof( $.oAjaxSteps ) != 'undefined'
            && !ibe_ajax.on_after_post_loaded ) {
            ibe_ajax.on_after_post();
          }
        <? endif; ?>

        <?
        /* Возврат с плат. страницы */
        if ( $GLOBALS['COMPONENT_SESSION']['process_error_afterpost'] ): ?>
          if ( typeof( $.oAjaxSteps ) != 'undefined'
            && $.oAjaxSteps.cfg.cur_step == 'precommit' ) {
            ibe_ajax.on_after_post();
            $.oAjaxSteps.hash_reset();
          }
        <? endif; ?>
      };
      /*]]>*/</script>
      
    <!-- Сообщение об ошибке -->
    <? if ( 1 ) { //$arResult['processor'] !== 'form_order' ) { ?>
    <div id="container_error" style="display:none" class="common-error">
      <div class="content">
          <?= htmlspecialcharsBack($arParams["MESSAGE"]) ?>
      </div>
    </div>
    <? } ?>

    <? endif; /* !IBE_SECONDARY_CALL */ ?>
  </div><? /* div#ts_ag_reservation_curtain */ ?>
<? endif; // $arResult['processor'] ?>
<? if ( $arParams['USE_MERGED_STEPS'] === 'Y' ): ?>
<div id="ts_ag_reservation_container__<?= $arResult['processor'] ?>">
<? else: ?>
<div id="ts_ag_reservation_container">
<? endif; ?>
<?
if ( $arParams['USE_MERGED_STEPS'] === 'Y' ) {
  $bOutputStarted = CIBEAjax::StartArea( '#ts_ag_reservation_container__' . $arResult['processor'] );
}
else {
  $bOutputStarted = CIBEAjax::StartArea( '#ts_ag_reservation_container' );
}
/* Шаблоны для каждого шага */
include( dirname( __FILE__ ) . '/template_reservation.php' );
?>
<? /* Выполняется один раз после загрузки шага, при любых значениях IBE_AJAX_MODE и USE_MERGED_STEPS */ ?>
<? if ( !$arParams['IBE_SECONDARY_CALL'] ): ?>
  <script type="text/javascript">/*<![CDATA[*/
    var timer = setTimeout(function() {
      ibe_frontoffice_timer();
    }, 190 );
  /*]]>*/</script>
  <? if (!$arResult['~PRINT']): // не выводить при печати ?>
  <? endif; /* !$arResult['~PRINT'] */ ?>
<? endif; /* !IBE_SECONDARY_CALL */ ?>
<?
if ( $bOutputStarted ) {
  CIBEAjax::EndArea();
}
?>
<? if ( $arParams['USE_MERGED_STEPS'] === 'Y' ): ?>

    <!-- Сообщение об ошибке -->
    <? if ( 0 ) {//$arResult['processor'] == 'form_order' ) { ?>
    <div id="container_error" style="display:none" class="common-error">
      <div class="content">
          <?= htmlspecialcharsBack($arParams["MESSAGE"]) ?>
      </div>
    </div>
    <? } ?>

</div><? /* .ts_ag_reservation_container__form_order */ ?>
<? else: ?>
</div><? /* .ts_ag_reservation_container */ ?>
<? endif; ?>