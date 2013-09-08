
// moved from template.php

function CIBEOfferFilterScript( main_selector, enabledFiltersCount_, uid_ ) {

  var ignoreSliderChange = false,
      enabledFiltersCount = enabledFiltersCount_,
      uid = uid_,
      getFilterIdsByFilterBodyLocal = getFilterIdsByFilterBody,
      lastSelectedFilterHead,
      lastSelectedFilterBody;    

  if ($.browser.webkit) {
    var filterBodyAnimationSpeed = '';
  }
  else {
    var filterBodyAnimationSpeed = 'fast';
  }

  $(main_selector + ' .title').click(function() {
    resizeAllowedNow = false;
    var filterHead = $(this);
    var filterBody = getFilterBodyFromHead( filterHead );
    var arFilterIds = getFilterIdsByFilterBodyLocal( filterBody );

    var prevEnabledFiltersCount = enabledFiltersCount;
    if (filterHead.hasClass('enabled')) {
      enabledFiltersCount--;
      filterHead.removeClass('enabled');
      var value = false;
    }
    else {
      enabledFiltersCount++;
      filterHead.addClass('enabled');
      var value = true;
      // запомнить открытый фильтр
      lastSelectedFilterHead = filterHead;
      lastSelectedFilterBody = filterBody;
    }
    filterBody.toggle( filterBodyAnimationSpeed );

    variantsCheckId++;
    var arFilterIdsLength = arFilterIds.length;
    for (var filterIndex = 0; filterIndex < arFilterIdsLength; filterIndex++) {
      arFilters[arFilterIds[filterIndex]].enabled = value;
    }
    for (var filterIndex = 0; filterIndex < arFilterIdsLength; filterIndex++) {
      filterUpdate(arFilterIds[filterIndex])
    }

    if (0 == enabledFiltersCount) {
      $(main_selector + ' #disable-all-filters').hide();
    }
    if (0 == prevEnabledFiltersCount) {
      $(main_selector + ' #disable-all-filters').show();
    }

    rebuildVariants();
    resizeTimer = setTimeout('resizeAllowedNow = resizeAllowed', resizeDelay);
  });

  $(main_selector + ' div.filter-time div.time-slider').each( function() {
    var minValue = parseInt( $( 'input.min-value', this ).val() );
    var maxValue = parseInt( $( 'input.max-value', this ).val() );
    $( this ).slider({
      range: true,
      min: minValue,
      max: maxValue,
      values: [minValue, maxValue],
      step: 5,
  
      start: function(event, ui) {
        resizeAllowedNow = false;
        variantsCheckId++;
      },
  
      slide: function(event, ui) {
        sliderUpdate($(ui.handle).parent().attr('id'), ui.values[0], ui.values[1]);
      },
  
      change: function(event, ui) {
        var curSlider = $(ui.handle).parent();
        var filterId = curSlider.attr('id');
        try {
          var curFilter = arFilters[filterId];
          var range = curFilter.range;
          var rangeLength = range.length;
          var value = arSelectLinks[curFilter.selectLink].selected;
  
          if (minValue == ui.values[0] && minValue != curFilter.values[0]) {
            value--;
          }
  
          if (minValue != ui.values[0] && minValue == curFilter.values[0]) {
            value++;
          }
  
          if (maxValue == ui.values[1] && maxValue != curFilter.values[1]) {
            value--;
          }
  
          if (maxValue != ui.values[1] && maxValue == curFilter.values[1]) {
            value++;
          }
  
          if (0 == arSelectLinks[curFilter.selectLink].selected && 0 != value) {
            $('#' + curFilter.selectLink).show('fast');
          }
  
          if (0 != arSelectLinks[curFilter.selectLink].selected && 0 == value) {
            $('#' + curFilter.selectLink).hide('fast');
          }
  
          arSelectLinks[curFilter.selectLink].selected = value;
          curFilter.values = ui.values;
  
          for (var rangeIndex = 0; rangeIndex < rangeLength; rangeIndex++) {
            var curElement = range[rangeIndex];
            var curItem = arItems[curElement.item];
            var newValue = (curElement.data >= ui.values[0] && curElement.data <= ui.values[1] ? true : false);
            if (newValue != curItem.value) {
              curItem.value = newValue;
              filterItemUpdate(curItem, false);
            }
          }
  
          if (!ignoreSliderChange) {
            rebuildVariants();
          }
        }
        catch (e) {
        }
  
        if (!ignoreSliderChange) {
          resizeTimer = setTimeout('resizeAllowedNow = resizeAllowed', resizeDelay);
        }
      }
    });
  });

  $(main_selector + ' div.filter div.select-all a').click(function() {
    var link = $(this);
    link.hide('fast');
    var curLink = link.attr('id');
    var filters = arSelectLinks[curLink].filters;
    $('#clear-'.concat(curLink)).show('fast');

    try {
      var curFilter = arFilters[filters[0]];
      switch (curFilter.type) {
        case 'CHECKBOX':
          resizeAllowedNow = false;
          var itemsCount = curFilter.items.length;
          curFilter.selected = itemsCount;
          for (var itemIndex = 0; itemIndex < itemsCount; itemIndex++) {
            var itemId = curFilter.items[itemIndex];
            var curItem = arItems[itemId];
            if (!curItem.value && !curItem.disabled) {
              curItem.value = true;
              $('#' + itemId + uid).attr('checked', 'checked');
              arSelectLinks[curFilter.selectLink].selected--;
              variantsCheckId++;
              filterItemUpdate(curItem, false);
            }
          }

          rebuildVariants();
          break;

        case 'RANGE':
          setSliders(filters);
          break;
      }
    }
    catch (e) {
      setSliders(filters);
    }

    if ($.browser.firefox) {
      $(window).resize();
    }

    resizeTimer = setTimeout('resizeAllowedNow = resizeAllowed', resizeDelay);
  });

  $(main_selector + ' div.filter div.clear-all a').click(function() {
    var link = $(this);
    link.hide('fast');
    var curLink = link.attr('id').substr(6);
    var filters = arSelectLinks[curLink].filters;
    $('#'.concat(curLink)).show('fast');

    try {
      var curFilter = arFilters[filters[0]];
      if ('CHECKBOX' == curFilter.type) {
        resizeAllowedNow = false;
        var itemsCount = curFilter.items.length;
        curFilter.selected = 0;
        for (var itemIndex = 0; itemIndex < itemsCount; itemIndex++) {
          var itemId = curFilter.items[itemIndex];
          var curItem = arItems[itemId];
          if (curItem.value && !curItem.disabled) {
            curItem.value = false;
            $('#'.concat(itemId).concat(uid)).removeAttr('checked');
            arSelectLinks[curFilter.selectLink].selected++;
            variantsCheckId++;
            filterItemUpdate(curItem, false);
          }
        }

        rebuildVariants();
      }
    }
    catch(e) {
    }

    if ($.browser.firefox) {
      $(window).resize();
    }

    resizeTimer = setTimeout('resizeAllowedNow = resizeAllowed', resizeDelay);
  });

  $(main_selector + ' #disable-all-filters').click(function() {
    resizeAllowedNow = false;
    $(this).hide('fast');

    var arProcessedFilters = [];

    for (var filterId in arFilters) {
      var curFilter = arFilters[filterId];

      if (curFilter.visible && curFilter.enabled) {
        arProcessedFilters.push(filterId);
        curFilter.enabled = false;
      }
    }

    $(main_selector + ' .title.enabled').each(function(){
      var filterHead = $(this);
      var filterBody = getFilterBodyFromHead( filterHead );
      filterHead.removeClass('enabled');
      filterBody.hide('fast');
      enabledFiltersCount--;
    });

    variantsCheckId++;
    for (var filterIndex in arProcessedFilters) {
      filterUpdate(arProcessedFilters[filterIndex]);
    }

    rebuildVariants();
    resizeTimer = setTimeout('resizeAllowedNow = resizeAllowed', resizeDelay);
  });

}