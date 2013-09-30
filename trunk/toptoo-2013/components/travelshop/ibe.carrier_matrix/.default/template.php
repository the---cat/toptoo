<? if (!defined('B_PROLOG_INCLUDED') || true !== B_PROLOG_INCLUDED) { die(); } ?>
<span id="ts_ag_carrier_matrix_container">
<? if ( CIBEAjax::StartArea( "#ts_ag_carrier_matrix_container" ) ) { ?>
<? if ($arResult['~ACTIVE_ROWS_COUNT'] < 4){
	$vieportHeight = ' style="height:' . ((43 * $arResult['~ACTIVE_ROWS_COUNT'])-1) . 'px;"';
}

if (!defined('__JS_IBE_CARRIER_MATRIX_SLIDER')) {
  define('__JS_IBE_CARRIER_MATRIX_SLIDER', true);
  echo CIBECacheControl::RenderJSLink($this->GetFolder() . '/js/touchslider.js');
}
?>
<h2 class="search_additional_info"><?

echo GetMessageExtended
( 'TS_IBE_CARRIER_MATRIX_TICKETS_PRICE'
, array
  ( 'CLASS' => $arResult['CLASS_STRING']
  , 'PASSENGERS' => $arResult['PASSENGERS_STRING']
  , 'CURRENCY' => GetMessage('TS_IBE_CARRIER_MATRIX_CURRENCY_' . $arResult['CURRENCY']) ? GetMessage('TS_IBE_CARRIER_MATRIX_CURRENCY_' . $arResult['CURRENCY']) : $arResult['CURRENCY_STRING']
  )
);

?></h2>

<div class="ts_ag_carrier_matrix">

<?

if ($arResult['~ACTIVE_COLUMNS_COUNT'] > 2 || $arResult['~ACTIVE_ROWS_COUNT'] > 2) {
  $matrixSize = $arResult['~ACTIVE_COLUMNS_COUNT'] - 1;
  $minViewportSize = 3; // минимальный размер области прокрутки (в столбцах)
  $minCellWidth = 120; // минимальная ширина ячейки в пикселях
  $maxCellWidth = 240; // максимальная ширина ячейки в пикселях
  $pageCount = ceil($matrixSize / $minViewportSize); // количество страниц
?>
<?
function variantsMess($n) {
	$n = floor($n);
	if ( $n == 1 || ( $n - floor($n/10) * 10 == 1 && $n - floor($n/100) * 100 != 11 ) ) return GetMessage('TS_IBE_CARRIER_MATRIX_VARIANTS_1');
	elseif ( ($n >1 && $n < 5) ||  ( $n - floor($n/10) * 10 > 1 && $n - floor($n/10) * 10 < 5 && $n - floor($n/100) * 100 >14  ) ) return GetMessage('TS_IBE_CARRIER_MATRIX_VARIANTS_2');
	else return GetMessage('TS_IBE_CARRIER_MATRIX_VARIANTS');
}
?>
	<table>
		<tr>
			<td class="legend main-column" id="cm_legend">
				<table>
					<tr>
						<th class="selected clickable all_variants" id="cm-TOTAL_all">
							<div class="wrap">
								<span class="label carriers-all"><?= GetMessage('TS_IBE_CARRIER_MATRIX_ALL_VARIANTS') ?></span>
								<? /*span class="count"><?= count($arResult['CARRIER_MATRIX']['CHANGES']['TOTAL']['ALL']['OFFERS']) ?></span */?>
							</div>
						</th>
						<? //trth class="all_variants_info">&nbsp;</th?>
					</tr>
					<? $arActiveRows = array(); ?>
					<? for ($iRow = 0; $iRow < count($arResult['CARRIER_MATRIX']['CHANGES']) - 1; $iRow++): ?>
					<? if ($arResult['CARRIER_MATRIX']['CHANGES'][$iRow]['~ACTIVE']): ?>
					<? $arActiveRows[] = $iRow ?>
					<? $arRow = $arResult['CARRIER_MATRIX']['CHANGES'][$iRow] ?>
					<tr>
						<th class="row-<?= $iRow ?> highlighted clickable one-string" id="cm-<?= $iRow ?>_all" onclick="_gaq.push(['_trackPageview', '/<?= SITE_ID ?>/avia/carrier_matrix/stops/<?= $iRow ?>/<?= $_SERVER["REMOTE_ADDR"] ?>/<?= $_SERVER["SERVER_NAME"] ?>/'])">
							<div class="wrap">
								<span class="label"><?= GetMessage('TS_IBE_CARRIER_MATRIX_ROW_TITLE_' . $iRow) ?></span>
								<span class="min_price"><?= GetMessage('TS_IBE_CARRIER_MATRIX_PRICE_FROM') . '&nbsp;' 
								. CIBECurrency::GetStringFull($arRow['ALL']['~MIN_PRICE'], $arResult['CURRENCY']) ?></span>
								<div class="arr"></div>
							</div>
							</th>
							<? /*th class="info">
								<span class="count"><?= count($arRow['ALL']['OFFERS']) . ' ' . variantsMess(count($arRow['ALL']['OFFERS'])) ?></span>
							</th*/?>
					</tr>
					<? endif; ?>
					<? endfor; ?>
				</table>
			</td>
			<td class="viewport main-column">
				<div class="viewport" id="cm_viewport"<?=$vieportHeight?>>
					<table id="carrier_matrix">
						<tr>
							<? $col_index = 0; ?>
							<? foreach ($arResult['CARRIER_MATRIX']['COMPANIES'] as $iCarrierIndex => $sCode): ?>
							<? if ('_MORE' != $sCode): ?>
							<th class="col-<?= $col_index ?> clickable logo logo-normal-<?= $arResult['LOGOS'][$sCode]['IATACODE'] ?>" id="cm-TOTAL_<?= $iCarrierIndex ?>" title="<?= $arResult['LOGOS'][$sCode]['TITLE'] ?>" onclick="_gaq.push(['_trackPageview', '/<?= SITE_ID ?>/avia/carrier_matrix/company/<?= $sCode ?>/<?= $_SERVER["REMOTE_ADDR"] ?>/<?= $_SERVER["SERVER_NAME"] ?>/'])">
								<div class="wrap">&nbsp;<div class="arr"></div></div>
							</th>
							<? else: ?>
							<th class="col-<?= $col_index ?> clickable diff_carriers" id="cm-TOTAL_<?= $iCarrierIndex ?>" onclick="_gaq.push(['_trackPageview', '/<?= SITE_ID ?>/avia/carrier_matrix/company/more/<?= $_SERVER["REMOTE_ADDR"] ?>/<?= $_SERVER["SERVER_NAME"] ?>/'])"><div class="wrap"><span class="carrier-more"><?= GetMessage('TS_IBE_CARRIER_MATRIX_MORE') ?></span><div class="arr"></div></div></th>
							<? endif; ?>
							<? $col_index++; ?>
							<? endforeach; ?>
							<? if (isset($arResult['CARRIER_MATRIX']['CHANGES']['TOTAL']['MORE'])): ?>
							<th class="col-<?= $col_index ?> clickable diff_carriers" id="cm-TOTAL_more" onclick="_gaq.push(['_trackPageview', '/<?= SITE_ID ?>/avia/carrier_matrix/company/more/<?= $_SERVER["REMOTE_ADDR"] ?>/<?= $_SERVER["SERVER_NAME"] ?>/'])"><div class="wrap"><span class="carrier-more"><?= GetMessage('TS_IBE_CARRIER_MATRIX_MORE') ?></span><div class="arr"></div></div></th>
							<? endif; ?>
						</tr>
						<? for ($iRow = 0; $iRow < count($arResult['CARRIER_MATRIX']['CHANGES']) - 1; $iRow++): ?>
						<? $arRow = $arResult['CARRIER_MATRIX']['CHANGES'][$iRow] ?>
						<? if ($arRow['~ACTIVE']): ?>
						<tr class="row-<?= $iRow ?>">
							<? $col_index = 0; ?>
							<? foreach ($arRow['COMPANY'] as $iCell => $arCell): ?>
							<? if (isset($arCell)): ?>
							<td class="col-<?= $col_index ?> clickable<?= (!$arCell['~MIN'] ? '' : ' min') ?>" id="cm-<?= $iRow ?>_<?= $iCell ?>" onclick="_gaq.push(['_trackPageview', '/<?= SITE_ID ?>/avia/carrier_matrix/offer/<?= $_SERVER["REMOTE_ADDR"] ?>/<?= $_SERVER["SERVER_NAME"] ?>/'])">
								<div class="wrap">
									<div class="radio"><span></span></div>                  
									<span class="min_price"><?= $arCell['MIN_PRICE'] ?></span>
									<span class="count"><?= count($arCell['OFFERS']) . ' ' . variantsMess(count($arCell['OFFERS'])) ?></span>
								</div>
							</td>
							<? else: ?>
							<td class="col-<?= $col_index ?>">&nbsp;</td>
							<? endif; ?>
							<? $col_index++; ?>
							<? endforeach; ?>
							<? if (isset($arResult['CARRIER_MATRIX']['CHANGES']['TOTAL']['MORE']) && isset($arRow['MORE'])): ?>
							<td class="col-<?= $col_index ?> clickable<?= (!$arRow['MORE']['~MIN'] ? '' : ' min') ?>" id="cm-<?= $iRow ?>_more" onclick="_gaq.push(['_trackPageview', '/<?= SITE_ID ?>/avia/carrier_matrix/offer/<?= $_SERVER["REMOTE_ADDR"] ?>/<?= $_SERVER["SERVER_NAME"] ?>/'])">
								<div class="wrap">
									<div class="radio"><span></span></div>
									<span class="min_price"><?= $arRow['MORE']['MIN_PRICE'] ?></span>
									<span class="count"><?= count($arRow['MORE']['OFFERS']) . ' ' . variantsMess(count($arRow['MORE']['OFFERS']))?></span>
								</div>
							</td>
							<? endif; ?>
							<? if (isset($arResult['CARRIER_MATRIX']['CHANGES']['TOTAL']['MORE']) && !isset($arRow['MORE'])): ?>
							<td class="col-<?= $col_index ?>">&nbsp;</td>
							<? endif; ?>
						</tr>
						<? endif; ?>
						<? endfor; ?>
						<tr class="spacer">
							<? for ($i = 0; $i < count($arResult['CARRIER_MATRIX']['CHANGES']['TOTAL']['COMPANY']) + (isset($arResult['CARRIER_MATRIX']['CHANGES']['TOTAL']['MORE']) ? 1 : 0) + 1; $i++): ?>
							<td><img src="/bitrix/images/1.gif" width="<?= $minCellWidth ?>" height="1" alt="" title="" /></td>
							<? endfor; ?>
						</tr>
					</table>
				</div>
			</td>
		</tr>
		<? if (1): //$matrixSize > $minViewportSize):
		$bShowNav = $matrixSize > $minViewportSize;
		?>
		<tr>
			<td colspan="2" class="page_selector" id="cm_page_selector">
				<span id="cm_slider_left" class="nav"<? if (!$bShowNav){ ?> style="display:none;"<? } ?>><span class="nav-prev">&nbsp;</span></span><?
				for ($page = 0; $page < $pageCount; $page++): ?><span class="page<?= ($page > 0 ? '' : ' selected') ?>" id="page_<?= $page ?>"<? if (!$bShowNav){ ?> style="display:none;"<? } ?>><span>&nbsp;</span></span><? endfor;
				?><span id="cm_slider_right" class="nav"<? if (!$bShowNav){ ?> style="display:none;"<? } ?>><span class="nav-next">&nbsp;</span></span>
			</td>
		</tr>
		<? endif; ?>
	</table>
<? } ?>
</div>
<?
if ($arResult['~ACTIVE_COLUMNS_COUNT'] > 2 || $arResult['~ACTIVE_ROWS_COUNT'] > 2) {
	$APPLICATION->SetPageProperty("CARRIER_MATRIX_SHOWN", "Y");
?>
<script type="text/javascript">
// <![CDATA[
var cellWidth = <?= $minCellWidth ?>;
var minHeaderWidth = 0;
var sliderLeftWidth = 0;
var sliderRightWidth = 0;
var legendWidth = 0;
var minCellWidth = <?= $minCellWidth ?>;
var maxCellWidth = <?= $maxCellWidth ?>;
var prevRulerWidth = 0;
var minViewportSize = <?= $minViewportSize ?>;
var viewportPos = 0;
var curPage = 0;
var pageCount = <?= $pageCount ?>;
var matrixSize = <?= $matrixSize ?>;
var viewportSize = (minViewportSize < matrixSize ? minViewportSize : matrixSize);
var maxViewportPos = matrixSize - viewportSize;

var resizeAllowed = true;
var resizeAllowedNow = true;
var resizeTimer = false;
var resizeDelay = 1;

var scrollingDuration = 'fast';

function carrierFilter(cell, arTargetOffers) {
  cell = $(cell);
  
  var date = new Date();
  if ( cell.attr( "id" ) == "cm-TOTAL_all" ) {
    date.setTime( date.getTime() - 1000 );
  } else {
    date.setTime( date.getTime() + ( 365 * 24 * 60 * 60 * 1000 ) );
  }
  document.cookie = "carrier_matrix_cell=" + ( cell.attr( "id" ) + ":" + arTargetOffers.join( "," ) ) + "; expires=" + date.toGMTString() + "; path=/";
  
  resizeAllowedNow = false;
  if (!cell.hasClass('selected')) {
    visibleFlightsCount = 0;
    $('#cm_legend .selected, #cm_viewport .selected').removeClass('selected');
    $('#cm_legend .highlighted, #cm_viewport .highlighted').removeClass('highlighted');

    var offersCount = <?= count($arResult['CARRIER_MATRIX']['CHANGES']['TOTAL']['ALL']['OFFERS']) ?>;
    var arOffersVisible = [];
    for (var offerIndex = 0; offerIndex < offersCount; offerIndex++) {
      arOffersVisible.push(false);
    }

    for (var offerIndex in arTargetOffers) {
      arOffersVisible[arTargetOffers[offerIndex]] = true;
    }

    for (var offerIndex = 0; offerIndex < offersCount; offerIndex++) {
      var curOffer = arOffers[offerIndex];
      curOffer.allowed = arOffersVisible[offerIndex];

      if (curOffer.allowed && curOffer.visible) {
        traverseOfferVariants(curOffer);
      }

      if (curOffer.allowed && !curOffer.visible) {
        curOffer.visible = curOffer.allowed;
        rebuildOfferVariants(offerIndex);
      }

      if (!curOffer.allowed && curOffer.visible) {
        curOffer.visible = curOffer.allowed;
        rebuildOfferVariants(offerIndex);
      }
    }

    cell.addClass('selected');

    var found = false;
    if (!found && (cell.hasClass('logo') || cell.hasClass('diff_carriers'))) {
      selectColumn(cell);
      found = true;
    }
    if (!found && cell.hasClass('one-string')) {
      selectRow(cell);
      found = true;
    }
    if (!found && 'cm-TOTAL_all' == cell.prop('id')) {
      $('#cm_legend th.one-string').addClass('highlighted');
      found = true;
    }

    visibleCountUpdate();
  }

  resizeTimer = setTimeout('resizeAllowedNow = resizeAllowed', resizeDelay);
}

<? if ($matrixSize > $minViewportSize): ?>
// Скроллинг влево
function scrollLeft() {
  var newPos = viewportPos - viewportSize;
  scrollToPosition(newPos);
}

// Скроллинг вправо
function scrollRight() {
  var newPos = viewportPos + viewportSize;
  scrollToPosition(newPos);
}

// Проверка позиции
function checkPosition(newPos) {
  if (newPos > maxViewportPos) {
    newPos = maxViewportPos;
  }

  if (newPos < 0) {
    newPos = 0;
  }

  return newPos;
}

// Скроллинг до позиции
function scrollToPosition(newPos) {
  resizeAllowedNow = false;
  var value;

  newPos = checkPosition(newPos);

  if (0 == newPos - viewportPos) {
    value = false;
  }
  else {
    value = true;
  }

  viewportPos = newPos;

  var browser = $.browser;
  var animate = !(browser.msie && parseInt(browser.version, 10) <= 8);

  if (value && animate) {
    $('#carrier_matrix').animate({
      'left': (-newPos * cellWidth).toString().concat('px')
    }, scrollingDuration, 'swing');
  }

  if (value && !animate) {
    $('#carrier_matrix').css('left', (-newPos * cellWidth).toString().concat('px'));
  }

  if (value) {
    navigationUpdate(false);
  }
  resizeTimer = setTimeout('resizeAllowedNow = resizeAllowed', resizeDelay);
}

// Переход на страницу
function scrollToPage(pageId) {
  var objCurPage = $('#cm_page_selector .selected');
  var curPageId = objCurPage.attr('id');

  if (pageId != curPageId) {
    scrollToPosition(pageId.substring(5) * viewportSize);
  }
}

// Обновление элементов навигации
function navigationUpdate(rebuilt) {
  var newPage = Math.ceil(viewportPos / viewportSize);

  if (rebuilt) {
    var newPageCount = Math.ceil(matrixSize / viewportSize);
  }

  if ( newPageCount <= 0 ){
    newPageCount = 0;
  }

  // Кол-во страниц увеличилось
  if (rebuilt && newPageCount > pageCount) {
    var pages = '';
    for (var page = pageCount; page < newPageCount; page++) {
      pages = pages + '<span class="page" id="page_' + page + '"><span>&nbsp;</span></span>';
    }
    if (pages.length) {
      $('#cm_slider_right').before(pages);
    }
    for (var page = pageCount; page < newPageCount; page++) {
      $('#cm_page_selector #page_' + page).bind('click', function() {
        scrollToPage($(this).attr('id'));
      });
    }
  }

  // Кол-во страниц уменьшилось
  if (rebuilt && newPageCount < pageCount) {
    for (var page = pageCount; page > newPageCount; page--) {
      $('#page_' + (page - 1)).remove();
    }
  }

  // Кол-во страниц изменилось и стало равно 1 -- убрать страницы
  if (rebuilt && 1 == newPageCount) {
    $('#cm_page_selector .page').hide();
  }

  // Кол-во страниц было равно 1 и изменилось -- показать страницы
  if (rebuilt && 1 == pageCount) {
    $('#cm_page_selector .page').show();
  }

  if (rebuilt) {
    pageCount = newPageCount;
  }

  // Изменилась текущая страница
  if (rebuilt || newPage != curPage) {
    curPage = newPage;
    $('#cm_page_selector .selected').removeClass('selected');
    $('#page_' + curPage).addClass('selected');
  }

  if (0 == maxViewportPos) {
    $('#cm_slider_left').hide();
    $('#cm_slider_right').hide();
  }
  else {
    $('#cm_slider_left').show();
    $('#cm_slider_right').show();
  }

  // Активация/деактивация левого слайдера
  if (curPage > 0) {
    $('#cm_slider_left').addClass('active').unbind( 'click' ).bind( 'click', function() {
      scrollLeft();
    });
  }
  else {
    $('#cm_slider_left.active').removeClass('active').unbind( 'click' );
  }

  // Активация/деактивация правого слайдера
  if (viewportPos < maxViewportPos) {
    $('#cm_slider_right').addClass('active').unbind( 'click' ).bind('click', function() {
      scrollRight();
    });
  }
  else {
    $('#cm_slider_right.active').removeClass('active').unbind( 'click' );
  }
}
<? endif; ?>

<? foreach ($arResult['CARRIER_MATRIX']['CHANGES'] as $sRow => $arRow) {
  foreach ($arRow['COMPANY'] as $iCell => $arCell) {
    if (isset($arCell)): ?>
$('#cm-<?= $sRow ?>_<?= $iCell ?>').unbind('click').bind( 'click', function() {
  arTargetOffers = [<?= implode(',', $arCell['OFFERS']) ?>];
  carrierFilter(this, arTargetOffers);
});
    <? endif;
}

  if (isset($arRow['ALL'])): ?>
$('#cm-<?= $sRow ?>_all').unbind('click').bind( 'click', function() {
  arTargetOffers = [<?= implode(',', $arRow['ALL']['OFFERS']) ?>];
  carrierFilter(this, arTargetOffers);
});
  <? endif;

  if (isset($arRow['MORE'])): ?>
$('#cm-<?= $sRow ?>_more').unbind('click').bind( 'click', function() {
  arTargetOffers = [<?= implode(',', $arRow['MORE']['OFFERS']) ?>];
  carrierFilter(this, arTargetOffers);
});
  <? endif;
} ?>

<? if ($matrixSize > $minViewportSize): ?>
$('#cm_slider_right.active').unbind('click').bind('click', function() {
  scrollRight();
});

$('#cm_slider_left.active').unbind('click').bind('click', function() {
  scrollLeft();
});

$('#cm_page_selector .page').unbind('click').bind('click', function() {
  scrollToPage($(this).attr('id'));
});
<? endif; ?>

function CarrierMatrix() {
}

CarrierMatrix.prototype =
{ 'scroll_selector': '#carrier_matrix'
, 'cellWidth': cellWidth
, 'viewportSize': viewportSize
, 'height': <?= (-1 + (43 * $arResult['~ACTIVE_ROWS_COUNT'])) ?>

// Обновление страницы по смещению
, 'positionUpdate': function(left) {
    viewportPos = checkPosition(Math.floor(-left / this.cellWidth));
    navigationUpdate(false);
  }
}

var carrier_matrix = new CarrierMatrix();

function rebuildCarrierMatrix() {
  if (!$('#cm-TOTAL_all').find(':visible').length) {
    return;
  }

  var availableWidth;

  if (!minHeaderWidth) {
    legendWidth = $('#cm_legend').outerWidth();

    minHeaderWidth = minCellWidth;
    var headers = $('#carrier_matrix tr:eq(0) th');
    headers.each(function() {
      var headerWidth = $(this).width();
      if (headerWidth > minHeaderWidth) {
        minHeaderWidth = headerWidth;
      }
    });
  }

  /* Установить минимальный размер области видимости */
  $('#cm_viewport').css('width', (minViewportSize * minCellWidth)-1 + 'px');
  /* Узнать новую ширину контейнера */
  var curRulerWidth = $('.ts_ag_carrier_matrix').width();
  /* Установить новый размер области видимости */
  $('#cm_viewport').css('width', (viewportSize * cellWidth)-1 + 'px');

  var changed = false;
  var rebuilt = false;

  if (prevRulerWidth != curRulerWidth) {
    changed = true;
    prevRulerWidth = curRulerWidth;

    availableWidth = curRulerWidth
      - sliderLeftWidth
      - sliderRightWidth
      - legendWidth;

    availableViewportSize = Math.floor(availableWidth / minHeaderWidth);
    availableViewportSize = (availableViewportSize < matrixSize ? availableViewportSize : matrixSize);
  }

  <? if ($matrixSize > $minViewportSize): ?>
  // Можно изменить кол-во столбцов в области видимости
  if (changed && availableViewportSize != viewportSize) {
    rebuilt = true;
    viewportSize = availableViewportSize;
    maxViewportPos = matrixSize - viewportSize;
    viewportPos = checkPosition(viewportPos);
  }
  <? endif; ?>

  // Можно изменить ширину столбца
  if (changed) {
    cellWidth = Math.floor( availableWidth / viewportSize );
    cellWidth = (cellWidth < maxCellWidth ? cellWidth : maxCellWidth);
    $('#cm_viewport').css('width', (viewportSize * cellWidth)-1 + 'px');
    $('#cm_viewport tr.spacer td img').css('width', cellWidth + 'px');
    $('#carrier_matrix').css('left', -(viewportPos * cellWidth) + 'px');
  }

<? if ($matrixSize > $minViewportSize): ?>
  if (changed && rebuilt) {
    carrier_matrix.cellWidth = cellWidth;
    carrier_matrix.viewportSize = viewportSize;
    cm_touchslider.createSlidePanel(carrier_matrix);
    navigationUpdate(true);
  }
<? endif; ?>

  <? /* TSH-13368: Восстановить выбранную ранее ячейку */ ?>
  <? if ( isset( $_COOKIE['carrier_matrix_cell'] ) ): ?>
  <? list( $cell_id, $sTargetOffers ) = explode( ':', $_COOKIE['carrier_matrix_cell'] ); ?>
    if ( "<?= $cell_id ?>" != "cm-TOTAL_all" ) {
      carrierFilter( $("#<?= $cell_id ?>"), [<?= $sTargetOffers ?>] );
    }
  <? endif; ?>
}

function selectColumn(cell) {
  var class_name;
  var col_index = 0;
  var found = false;

  while (!found && col_index < matrixSize) {
    class_name = 'col-'.concat(col_index);

    if (!cell.hasClass(class_name)) {
      col_index++;
    }
    else {
      found = true;
    }
  }

  if (found) {
    $('#carrier_matrix').find('td.'.concat(class_name)).addClass('highlighted');
  }
}

function selectRow(cell) {
  var class_name;
  var row_index = 0;
  var found = false;
  var active_rows = [<?= implode(', ', $arActiveRows) ?>];

  while (!found && row_index < <?= $arResult['~ACTIVE_ROWS_COUNT'] - 1; ?>) {
    class_name = 'row-'.concat(active_rows[row_index]);

    if (!cell.hasClass(class_name)) {
      row_index++;
    }
    else {
      found = true;
    }
  }

  if (found) {
    $('#carrier_matrix').find('tr.'.concat(class_name)).addClass('highlighted');
  }
}

$( window ).bind( 'resize', rebuildCarrierMatrix );
// ]]>
</script>
<?

} // if ($arResult['~ACTIVE_COLUMNS_COUNT'] > 2 || $arResult['~ACTIVE_ROWS_COUNT'] > 2)

CIBEAjax::EndArea();
} // if ( CIBEAjax::StartArea() ) ?>
</span>