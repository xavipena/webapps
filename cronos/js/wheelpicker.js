/*!
 * jquery-wheel-picker v1.0.0
 * Copyright 2021 Kuniyuki Masuo
 */

var __SW__ = {};
window.addEventListener('DOMContentLoaded', function() {
    
    __SW__.init();

});

__SW__.init = function() {
    __SW__.EVT_CAPTURE_CLASS_NAME = 'sw_event_capture';
    __SW__.Undone_Timer_For_Sselect_List_Event=false;
    __SW__.LIST_ITEM_HEIGHT = 'list-item-height';
    __SW__.SELECTED_LI_HEIGHT = 'selected-li-height';
    __SW__.DIFF_SELECTED_LI_HEIGHT = 'diff_selected-li-height';
    __SW__.LIST_ITEM_CNT = 'list-item-count';
    __SW__.SELECTED_LIST_ITEM_INDEX = 'selected-list-item-index';

    //capture wheel
    $('.wheel').each(function() {
    
        const wheel = $(this);
        const ul = wheel.find("ul").first();
            
        // .selected
        let init_index = ul.find('.selected').index();
        if (init_index < 0) init_index = 0;
        __SW__.selectListItemForIndex({
            index:init_index, 
            wheel:wheel, 
            animate_dur:0, 
            fireEventSelect:false
        });
        
        // wheel
        wheel.css({
            'padding-top': (wheel.innerHeight() - __SW__.getSelectedListItemHeight(ul))/2  + 'px'
        });        

        // capture
        if (wheel.attr('id') === undefined || wheel.attr('id') === '') wheel.attr('id', 'smp-whl-list-' + wheel.offset().top + '_' + wheel.offset().left);
        const capture = $(`<div class="${__SW__.EVT_CAPTURE_CLASS_NAME}"></div>`);
        capture.addClass(wheel.attr('id'));
        wheel.after(capture);
        
        // capture
        capture.width( wheel.outerWidth() );
        capture.height( wheel.outerHeight() );
        capture.css({

            top: wheel.position().top,
            left: wheel.position().left,
        } );

        // capture
        const capture_cont = $('<div></div>');
        capture.append(capture_cont);

        // list-item
        capture_cont.height(ul.outerHeight()+wheel.innerHeight());

        // capture
        capture.bind("scroll", {ul:ul}, __SW__.scroll_lists_sync_withCapture);
       
        // capture mouseDown
        capture.bind("mousedown", {ul:ul}, __SW__.mouseDownFllow);

        __SW__.generateMouseCaptureScreen();
        
        // listen touch Start/Move/End to prevent moving document on Smartphone.
        capture.bind('touchstart touchmove touchend', {ul:ul}, __SW__.prevent_moving_document_durring_dragging_out_of_list);
    });

    __SW__.MouseEvetCaptureScreen.bind("mousemove", function(event) {

        if (__SW__.mousedowned_capture) {

            __SW__.dragging = true;
            __SW__.mousedowned_capture.scrollTop(__SW__.mousedowned_capture.scrollTop()-event.originalEvent.movementY);
        }
    });
    
    __SW__.MouseEvetCaptureScreen.bind("mouseup", __SW__.finishDragging);
    __SW__.MouseEvetCaptureScreen.bind("mouseout", function(event) {
        if (__SW__.dragging) {

            __SW__.finishDragging();
        }
    });
}

/* --------------------------------------------------------- /
    scroll/click
/* --------------------------------------------------------- */
__SW__.mouseDownFllow = function(event) {
        
    __SW__.mousedowned_capture = $(this);
    __SW__.mousedowned_capture.related_ul = event.data.ul;
    
    __SW__.initDragging();
}

__SW__.initDragging = function(event) {

    __SW__.activeMouseCaptureScreen();
    __SW__.dragging = false;
}

__SW__.finishDragging = function(event) {

    __SW__.deactiveMouseCaptureScreen();
 
    if (__SW__.mousedowned_capture && __SW__.dragging == false) {

        __SW__.mousedowned_capture.clickFunc = __SW__.click_listItem_sync_withCapture;
        __SW__.mousedowned_capture.clickFunc(event);
    }

    __SW__.mousedowned_capture = false;
    __SW__.dragging = false;
}

__SW__.generateMouseCaptureScreen = function() {

    if (__SW__.MouseEvetCaptureScreen === undefined) {
        __SW__.MouseEvetCaptureScreen = $('<div id="sw_mouseCaptureScreenToSimulateDragging"></div');
        __SW__.MouseEvetCaptureScreen.appendTo($("body"));
    }    
}

__SW__.activeMouseCaptureScreen = function() {

    if (__SW__.MouseEvetCaptureScreen) {
        __SW__.MouseEvetCaptureScreen.show();
        $('body').addClass('sw_preventSelectText');
    }
}

__SW__.deactiveMouseCaptureScreen = function() {

    if (__SW__.MouseEvetCaptureScreen) {
        __SW__.MouseEvetCaptureScreen.hide();
        $('body').removeClass('sw_preventSelectText');
    }
}

__SW__.selectListItemForIndex = function({index, wheel, animate_dur=0, checkIndexChanged=true, fireEventSelect=true, callback=false}) {
    
    if (typeof(wheel) == 'string') {

        ul = $(`#${wheel}.wheel ul`);
    } else {

        ul = $(wheel).find('ul').first();
    }

    const parent_id = $(ul).parent('.wheel').attr('id');
    const capture = $(`.${__SW__.EVT_CAPTURE_CLASS_NAME}.${parent_id}`);

    __SW__.selectList({

        capture: capture,
        lists: ul,
        li_index: index,
        checkIndexChanged: checkIndexChanged,
        animate_dur: animate_dur,
        fireEventSelect: fireEventSelect,
        callback: callback
    });

    __SW__.stopScrollEventLisner = true;
    const li_h = __SW__.getListItemHeight(ul);
    capture.scrollTop(index * li_h);
    __SW__.stopScrollEventLisner = false;
}

__SW__.getIndexAndValOfSelectedListItem = function(wheel) {

    if (typeof(wheel) == 'string') {
        ul = $(`#${wheel}.wheel ul`);
    } else {
        ul = $(wheel).find('ul').first();
    }

    const selected_li = ul.find('.selected');
    return {index: selected_li.index(), value: selected_li.text()};

}

__SW__.scroll_lists_sync_withCapture = function(event) {
    
    if (__SW__.stopScrollEventLisner) return;
    
    const ul = event.data.ul;
    const li_h = __SW__.getListItemHeight(ul);
    const scroll_top = $(this).scrollTop();
    
    const selected_list_index = 0 < scroll_top ? Math.ceil(scroll_top/li_h) : 0;
    __SW__.selectList({capture:$(this), lists:ul, li_index:selected_list_index});    
}

__SW__.click_listItem_sync_withCapture = function(event) {
    
    const capture = this;    
    const ul = capture.related_ul;
    const clickY = event.pageY - capture.offset().top;
    const centerY = capture.outerHeight()/2;
    const directionY = clickY < centerY ? -1 : 1;
    const distCent = Math.abs(clickY - centerY);// distance from center;
    const distSelectedLI = distCent - __SW__.getSelectedListItemHeight(ul)/2;  // distance from the edge of selected item-list
    
    let index_diff;
    if ( distSelectedLI <= 0 ) {

        index_diff = 0;
    } else {

        const li_h = __SW__.getListItemHeight(ul);
        index_diff = Math.ceil(distSelectedLI / li_h) * directionY;
    }

    __SW__.selectList_Add({lists:ul, index_diff:index_diff, capture:capture});
}

__SW__.selectList = function({capture, lists, li_index, checkIndexChanged=true, animate_dur=50, fireEventSelect=true, callback=false}) {

    if (li_index < 0) {

        li_index = 0;

    } else if (__SW__.getListItemCount(lists) <= li_index ) {

        li_index = __SW__.getListItemCount(lists) - 1;
    }

    if (checkIndexChanged && __SW__.getSelectedListItemIndex(lists) == li_index) {

        return;
    }

    __SW__.saveSelectedListItemIndex(lists, li_index);

    const callback_afterScroll = function() {

        lists.find('li.selected').removeClass('selected');
        lists.find('li').eq(li_index).addClass('selected');
        if (callback) callback();
    }

    const li_h = __SW__.getListItemHeight(lists);
    __SW__.scrollList({

        lists:lists,
        dist:li_h*li_index*-1,
        animate_dur:animate_dur,
        callback:callback_afterScroll
    });

    if (fireEventSelect) {

        clearTimeout(__SW__.Undone_Timer_For_Sselect_List_Event); 
        __SW__.Undone_Timer_For_Sselect_List_Event = setTimeout(__SW__.fireEventSelectOfList.bind(this, lists, li_index), 150);
    }
}

__SW__.fireEventSelectOfList = function(lists, li_index) {

    lists.parent('.wheel').get(0).dispatchEvent(
        new CustomEvent('select', {detail:{ selected_index: li_index }})
    );
    __SW__.Undone_Timer_For_Sselect_List_Event = false;
}


__SW__.selectList_Add = function({lists, index_diff, capture, animate_dur=100}) {

    if (index_diff == 0) return;
    
    let new_index = __SW__.getSelectedListItemIndex(lists) + index_diff;            
    __SW__.selectList({capture:capture, lists:lists, li_index:new_index, checkIndexChanged:false, animate_dur:animate_dur});
}

__SW__.scrollList = function({lists, dist, animate_dur=50, callback=false}) {

    if (0 < animate_dur) {   

        lists.animate({top:dist}, animate_dur, 'swing',callback);
    } else {

        lists.css({top:dist});
        if (callback) callback();
    }
}

__SW__.getListItemHeight = function(lists) {

    if (lists.data(__SW__.LIST_ITEM_HEIGHT)>=0) {

        return lists.data(__SW__.LIST_ITEM_HEIGHT);
    }
    const li_h = lists.find('li:not(.selected)').first().outerHeight(true);
    lists.data(__SW__.LIST_ITEM_HEIGHT, li_h);
    return li_h;
}

__SW__.getSelectedListItemHeight = function(lists) {

    if (lists.data(__SW__.SELECTED_LI_HEIGHT)>=0) {

        return lists.data(__SW__.SELECTED_LI_HEIGHT);
    }

    const selectedLiHeight = lists.find('.selected').outerHeight(true);
    lists.data(__SW__.SELECTED_LI_HEIGHT, selectedLiHeight);
    return selectedLiHeight;
}

__SW__.getListItemCount = function(lists) {

    if (lists.data(__SW__.LIST_ITEM_CNT)>=0) {

        return lists.data(__SW__.LIST_ITEM_CNT);
    }
    const li_cnt = lists.find('li').length;
    lists.data(__SW__.LIST_ITEM_CNT, li_cnt);
    return li_cnt;
}

__SW__.getSelectedListItemIndex = function(lists) {
 
    if (0 <= lists.data(__SW__.SELECTED_LIST_ITEM_INDEX)) {
 
        return lists.data(__SW__.SELECTED_LIST_ITEM_INDEX);
    }
    
    return -1;
}

__SW__.saveSelectedListItemIndex = function(lists, new_selectedIndex) {
 
    lists.data(__SW__.SELECTED_LIST_ITEM_INDEX, new_selectedIndex);
    return new_selectedIndex;
}

/* --------------------------------------------------------- 
    listen touch Start/Move/End to prevent moving document on 
    Smartphone during dragging capture on out of list.
/* --------------------------------------------------------- */
__SW__.prevent_moving_document_durring_dragging_out_of_list = function(e) {

    const capture = this;

    if (e.type === 'touchmove') {

        // determine touch Verticle Directon
        const clientY = e.originalEvent.touches[0].clientY;
        let touchmove_direction = 0;
        if (capture.last_event.clientY < clientY) {

            touchmove_direction = 1;
        } else if (capture.last_event.clientY > clientY) {

            touchmove_direction = -1;
        }
        capture.last_event.clientY = clientY;

        const li_selected_index = __SW__.getSelectedListItemIndex(e.data.ul);
        // prevnet moving document if touch direction is out of list
        const li_cnt = __SW__.getListItemCount(e.data.ul);
        if (touchmove_direction > 0 && li_selected_index <= 0 ) {
 
            // unable UPPER moving becouse list is on first position
            if (e.cancelable) e.preventDefault();
            
        } else if (touchmove_direction < 0 && li_selected_index == __SW__.getListItemCount(e.data.ul)-1 ) {
 
            // unable LOWER moving becouse list is on last position
            if (e.cancelable) e.preventDefault();
        }

    
    } else if ( e.type === 'touchstart') {
        
        capture.last_event = {
            clientY  : e.originalEvent.touches[0].clientY
        };
    
    } else if ( e.type === 'touchend') {

        capture.last_event.clientY = undefined;
    }
}