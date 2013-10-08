<?
if (!defined('B_PROLOG_INCLUDED') || true !== B_PROLOG_INCLUDED) {
  die();
}
?>
<%
  this.month_names = [""<? for ( $i = 1; $i <= 12; $i++ ) : ?>, "<?= GetMessage('IBE_SEARCH_HISTORY_MONTH_' . $i) ?>"<? endfor; ?>];
  this.month_names_short = [""<? for ( $i = 1; $i <= 12; $i++ ) : ?>, "<?= GetMessage('IBE_SEARCH_HISTORY_MONTH_SHORT_' . $i) ?>"<? endfor; ?>];
%>
<% if (this.items.length > 0) { %>
<ul class="clearfix">
<% for (var i in this.items) { %>
  <% if (this.items.hasOwnProperty(i)) {
    <% var item = this.items[i]; %>
    <li>
      <span class="retry" id="<%= item.RETRY_ID %>" title="<?= GetMessage('IBE_SEARCH_HISTORY_RETRY') ?>">
        <span class="point point_to"><%= item.POINTS[0].NAME_<?= $arResult['LANGUAGE_ID'] ?> %><% if ('OW' == item.PARAMS.RT_OW) { %>&nbsp;&ndash;&nbsp;<%= item.POINTS[1].NAME_<?= $arResult['LANGUAGE_ID'] ?> %><% } %></span>
        <span class="date date_to"><%= item.PARAMS.dateto.DAY %> <%= this.month_names_short[ item.PARAMS.dateto.MONTH ] %></span>
        <% if ('RT' == item.PARAMS.RT_OW) { %>
        <span class="point point_back"><%= item.POINTS[1].NAME_<?= $arResult['LANGUAGE_ID'] ?> %></span>
        <span class="date bate_back"><%= item.PARAMS.dateback.DAY %> <%= this.month_names_short[ item.PARAMS.dateback.MONTH ] %></span>
        <% } %>
      </span>
      <span class="delete" id="<%= item.DELETE_ID %>" title="<?= GetMessage('IBE_SEARCH_HISTORY_DELETE') ?>">&times;</span>
    </li>
  <% } %>
<% } %>
</ul>
<% } %>
