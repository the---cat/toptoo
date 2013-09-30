/*******************************************************************************
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 ******************************************************************************/

cm_touchslider = {
    output: function(/*string*/ msg) {
        if (console) {
            console.info(msg);
        }
    },
    
    /**
     * We start by creating the sliding grid out of the specified
     * element.  We'll look for each child with a class of cell when
     * we create the slide panel.
     */
    createSlidePanel: function(/*object*/ scroll) {
        var gridid = scroll.scroll_selector;
        var cellWidth = scroll.cellWidth;
        var padding = 0;
        var x = padding;
        this.scroll = scroll;

        $(gridid).each(function() {
/*
            $(this).parent().css({
                'width': scroll.viewportSize * cellWidth + 'px',
                'overflow': 'hidden'
            });

            $(this).find('th').each(function() {
                var cell = $(this);
                cell.css({
                    width: cellWidth + 'px',
                    position: 'absolute',
                    left: x + 'px',
                    top: padding + 'px'
                });

                x += cellWidth + padding;
            });

            $(this).css({
                'position': 'relative',
                'left': '0px',
                'width': x.toString().concat('px'),
                'height': scroll.height.toString().concat('px')
            });
*/
            x = $(this).find('th').length * (cellWidth + padding);

            /*
               Many of the mobile browsers resize the screen and therefore
               don't give accurate information about the size of the window.
               We need to save this information so we can use it later when
               we're sliding the grid.
             */
            cm_touchslider.width = x;
            cm_touchslider.colWidth = cellWidth + padding;
            
            try {
                if ('undefined' == typeof ibe_touch_event) {
                    ibe_touch_event = true;
                    document.createEvent('TouchEvent');
                }

                /*
                   Now that we've finished the layout we'll make our panel respond
                   to all of the touch events.
                 */
                cm_touchslider.makeTouchable(gridid);
            } catch (e) {
                /*
                 * Then we aren't on a device that supports touch
                 */
                $(this).css({
                  'overflow': 'hidden'
                });
            }
        });
    },
    
    /**
     * This function just binds the touch functions for the grid.
     * It is very important to stop the default, stop the
     * propagation, and return false.  If we don't then the touch
     * events might cause the regular browser behavior for touches
     * and the screen will start sliding around.
     */
    makeTouchable: function(/*string*/ gridid) {
         $(gridid).each(function() {
            this.ontouchstart = function(e) {
                cm_touchslider.touchStart($(this), e);
                //e.preventDefault();
                //e.stopPropagation();
                return true;
            };
            
            this.ontouchend = function(e) {
                //e.preventDefault();
                //e.stopPropagation();
                
                if (cm_touchslider.sliding) {
                    cm_touchslider.sliding = false;
                    cm_touchslider.touchEnd($(this), e);
                    return false;
                } else {
                    /*
                       We never slid so we can just return true
                       and perform the default touch end
                     */
                    return true;
                }
            };
            
            this.ontouchmove = function(e) {
                cm_touchslider.touchMove($(this), e);
                e.preventDefault();
                e.stopPropagation();
                return false;
            };
        });
    },
    
    /**
     * A little helper to parse off the 'px' at the end of the left
     * CSS attribute and parse it as a number.
     */
    getLeft: function(/*JQuery*/ elem) {
         return parseInt(elem.css('left').substring(0, elem.css('left').length - 2), 10);
    },
    
    /**
     * When the touch starts we add our sliding class a record a few
     * variables about where the touch started.  We also record the
     * start time so we can do momentum.
     */
    touchStart: function(/*JQuery*/ elem, /*event*/ e) {
         elem.css({
             '-webkit-transition-duration': '0'
         });
         
         cm_touchslider.startX = e.targetTouches[0].clientX;
         cm_touchslider.startLeft = cm_touchslider.getLeft(elem);
         cm_touchslider.touchStartTime = new Date().getTime();
         
    },
    
    /**
     * When the touch ends we need to adjust the grid for momentum
     * and to snap to the grid.  We also need to make sure they
     * didn't drag farther than the end of the list in either
     * direction.
     */
    touchEnd: function(/*JQuery*/ elem, /*event*/ e) {
         if (cm_touchslider.getLeft(elem) > 0) {
             /*
              * This means they dragged to the right past the first item
              */
             cm_touchslider.doSlide(elem, 0, '2s');
             
             elem.parent().removeClass('sliding');
             cm_touchslider.startX = null;
         } else if ((Math.abs(cm_touchslider.getLeft(elem)) + elem.parent().width()) > cm_touchslider.width) {
             /*
              * This means they dragged to the left past the last item
              */
             cm_touchslider.doSlide(elem, '-' + (cm_touchslider.width - elem.parent().width()), '2s');
             
             elem.parent().removeClass('sliding');
             cm_touchslider.startX = null;
         } else {
             /*
                This means they were just dragging within the bounds of the grid
                and we just need to handle the momentum and snap to the grid.
              */
             cm_touchslider.slideMomentum(elem, e);
         }
    },
    
    /**
     * If the user drags their finger really fast we want to push 
     * the slider a little farther since they were pushing a large 
     * amount. 
     */
    slideMomentum: function(/*jQuery*/ elem, /*event*/ e) {
         var slideAdjust = (new Date().getTime() - cm_touchslider.touchStartTime) * 10;
         var left = cm_touchslider.getLeft(elem);
         
         /*
            We calculate the momentum by taking the amount of time they were sliding
            and comparing it to the distance they slide.  If they slide a small distance
            quickly or a large distance slowly then they have almost no momentum.
            If they slide a long distance fast then they have a lot of momentum.
          */
         
         var changeX = 12000 * (Math.abs(cm_touchslider.startLeft) - Math.abs(left));
         
         slideAdjust = Math.round(changeX / slideAdjust);
         
         var newLeft = slideAdjust + left;
         
         /*
          * We need to calculate the closest column so we can figure out
          * where to snap the grid to.
          */
         var t = newLeft % cm_touchslider.colWidth;
         
         if ((Math.abs(t)) > ((cm_touchslider.colWidth / 2))) {
             /*
              * Show the next cell
              */
             newLeft -= (cm_touchslider.colWidth - Math.abs(t));
         } else {
             /*
              * Stay on the current cell
              */
             newLeft -= t;
         }
         
         if (cm_touchslider.slidingLeft) {
             var maxLeft = parseInt('-' + (cm_touchslider.width - elem.parent().width()), 10);
             /*
              * Sliding to the left
              */
             cm_touchslider.doSlide(elem, Math.max(maxLeft, newLeft), '0.5s');
         } else {
             /*
              * Sliding to the right
              */
             cm_touchslider.doSlide(elem, Math.min(0, newLeft), '0.5s');
         }
         
         elem.parent().removeClass('sliding');
         cm_touchslider.startX = null;
    },
    
    doSlide: function(/*jQuery*/ elem, /*int*/ x, /*string*/ duration) {
         elem.css({
             left: x + 'px',
             '-webkit-transition-property': 'left',
             '-webkit-transition-duration': duration
         });

         this.scroll.positionUpdate(x);
    },
    
    /**
     * While they are actively dragging we just need to adjust the
     * position of the grid using the place they started and the
     * amount they've moved.
     */
    touchMove: function(/*JQuery*/ elem, /*event*/ e) {
         if (!cm_touchslider.sliding) {
             elem.parent().addClass('sliding');
         }
         
         cm_touchslider.sliding = true;
         
         if (cm_touchslider.startX > e.targetTouches[0].clientX) {
             /*
              * Sliding to the left
              */
             elem.css('left', '-' + (cm_touchslider.startX - e.targetTouches[0].clientX - cm_touchslider.startLeft) + 'px');
             cm_touchslider.slidingLeft = true;
         } else {
             /*
              * Sliding to the right
              */
             var left = (e.targetTouches[0].clientX - cm_touchslider.startX + cm_touchslider.startLeft);
             elem.css('left', left + 'px');
             cm_touchslider.slidingLeft = false;
         }
         
    }
};