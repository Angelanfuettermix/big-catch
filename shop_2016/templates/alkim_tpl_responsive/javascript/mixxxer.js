var scrollable_position = 1;
 var valMap;
var slide_start_val;
var completed_message_shown = false;


function ajaxFileUpload() {
     $('#loading_upload_bar').show();
    $('#mixxxer_upload_button').hide();
    var options = {
            success:     function (data, status) {
            
                    init();
             
            $('#loading_upload_bar').hide();
            $('#mixxxer_upload_button').show();
            $('#mixxxer_file_input_wr').html($('#mixxxer_file_input_wr').html());
        },
        error: function (data, status, e) {
            $('#loading_upload_bar').hide();
            $('#mixxxer_upload_button').show();
        }
    
    };
    $('#new_mixxxer_upload_form').ajaxSubmit(options);
    //starting setting some animation when the ajax starts and completes
}

function put_it_in(data) {
    var reset_guided = false;
    
    
    if (typeof (data.items) != 'undefined') {
        // now give the new data
        
        $('[name^="mi_c_text"]').val('');
        
        $.each(data.items, function (json_id, json_data) {
            if (1 == 0) {} else if (json_data.update_select == '#max_cost') {
                $('[name="max_cost"]').val(json_data.update_text);
            } else if (json_data.update_select == '#my_text') {
                $('[name="my_text"]').val(json_data.update_text);
            } else if (json_data.update_select == '#mix_name') {
                $('[name="my_name"]').val(json_data.update_text);
            } else if (json_data.update_select == '#fb') {
                var info = json_data.update_text;
                var infos = info.split('|||||');
              /*  FB.ui({
                    method: 'feed',
                    name: infos[3],
                    link: infos[1],
                    picture: infos[2],
                    caption: '',
                    description: infos[0],
                    display: 'popup',
                    redirect_uri: 'http://www.alkim.de/fb/fb-mixxxer.php'
                }, function (response) {
                    if (response.post_id) {
                        alert('Post was published.');
                    } else {
                        alert('Post was not published.');
                    }
                });*/
                var url = 'https://www.facebook.com/dialog/feed?app_id='+$('#fbAppId').val()+'&display=popup&link='+infos[1]+'&picture='+infos[2]+'&name='+infos[3]+'&caption=&description='+infos[0]+'&redirect_uri=http://www.alkim.de/fb/fb-mixxxer.php';
                fenster = window.open(url, "Popupfenster", "width=700,height=400,resizable=yes");
                fenster.focus();
            } else if (json_data.update_select == '#active_items') {
                $('.mixxxer_item_box').removeClass('active_mixxxer_item_box');
                $('.mixxxer_item_box').removeClass('maximum_reached_mixxxer_item_box');
                $('.mixxxerItemRadio').attr('checked', false);
                $('.mixxxerItemQtyField').val(0);
                $('.mixxxer_item_qty').html('0');
                var act_items = json_data.update_text.split('|');
                $('.mixxxer_item_incr').show();
                $('.mixxxer_item_decr').hide();
                $.each(act_items, function (k, v) {
                    if (v != '') {
                        //console.log(v);
                        var v1 = v.split(';');
                        v1[1] = parseInt(v1[1]);
                        v1[2] = parseInt(v1[2]);
                        if(v1[3] == '0'){
                          v1[2] = 1;
                        }
                        $('.mixxxer_item_decr_' + v1[0]).show();
                        $('#mixxxer_item_box_' + v1[0]).addClass('active_mixxxer_item_box');
                        $('#mixxxerItemRadio' + v1[0]).attr('checked', true);
                        $('#mixxxerOption' + v1[0]).attr('selected', true);
                        $('#mixxxer_item_box_' + v1[0]).find('.mixxxer_item_qty').html(v1[1]);
                        $('#mixxxerItemQtyField' + v1[0]).val(v1[1]);
                        
                        //console.log(v1[0]+'-'+v1[1]+'-'+ v1[2]);
                        if (v1[1] >= v1[2] && v1[2] > 0) {
                            $('#mixxxer_item_box_' + v1[0]).addClass('maximum_reached_mixxxer_item_box');
                            $('.mixxxer_item_incr_' + v1[0]).hide();
                        }
                    }
                });
            } else if (json_data.update_select == '#prices') {
                $.each(json_data.update_text, function (k, v) {
                    $('#mi_price_' + k).html(v);
                });
            } else if (json_data.update_select == '#max_val') {
                    $('#curr_max_val').val(json_data.update_text);
                
            } else if (json_data.update_select == '#max_val_limit') {
                    $('#max_val').val(json_data.update_text);
                    if(typeof(valMap)!= 'undefined'){
                       
                        if(valMap.length>0){
                            $.each(valMap, function(k, v){
                       
                                if(parseFloat(v)==parseFloat(json_data.update_text)){
                                    $("#max_val_slider").slider('value',k);
                                }
                            });
                        }
                    }
                
                 
            } else if (json_data.update_select == '#reset_slider') {
                    var cont = true;
                    $.each(valMap, function(k, v){
               
                        if(parseFloat(v)>=parseFloat($('#curr_max_val').val()) && cont){
                            $("#max_val_slider").slider('value',k);
                         
                            cont = false;
                        }
                    });
                    processSliderValue(); 
                  
            }else if (json_data.update_select.indexOf('#--input=') != -1) {
                   
                   var sel = json_data.update_select.replace('#--input=', '');

                   $(sel).val(json_data.update_text);
                  
            } else if (json_data.update_select == '#reset_guided') {
                $('.mixxxer_continued').val(0);   
                reset_guided = true;
                completed_message_shown = false;
                  
            } else if (json_data.update_select == '#reload') {
                location.reload();
            } else if (json_data.update_select == '#comp_groups') {
                $('.mixxxer_disabled_overlay').remove();
                $('.mixxxer_disabled_overlay_text').remove();
                $('.mixxxer_item_box').removeClass('disabled');
                $('.mixxxerOption').attr('disabled', false);
                $.each(json_data.update_text, function (k, v) {
                    $('.mixxxer_item_box[ref*="' + v + '"]').each(function () {
                        var id = $(this).attr('id').replace('mixxxer_item_box_', '');
                        var text = $('[name="' + v + '"][rel="ref"]').val();
                        disable_item(id, text);
                    });
                });
               $('.mixxxer_item_box[rel!=""]').each(function () {
                    var that = $(this);
                    var comp = $(this).attr('rel');
                    if(comp != '' && comp != '|'){
                        var tComp = comp.split('|');
                        $.each(tComp, function(dummy, comp){
                    if(comp != ''){
                            if (comp.indexOf(',') != -1) {
                                var comps = comp.split(',');
                            } else {
                                var comps = new Array(comp);
                            }
                          
                                    var disabled = false;
                                    $.each(comps, function (aa, _comp) {
                                        if ($.inArray(_comp, json_data.update_text) == -1) {
                                            disabled = true;
                                 
                                }
                            });
                            if(disabled){
                                disable_item(that.attr('id').replace('mixxxer_item_box_', ''), $('[name="' + comp + '"][rel="only"]').val());
                            }
                                 }
                            });
                    }
                });
            } else if (json_data.update_select == '#dialog') {
                var dialog = $('<div style="display:none">' + json_data.update_text + '</div>').appendTo('body');
                dialog.dialog();
            } else {
                $(json_data.update_select).html(json_data.update_text);
            }
            
        });
        if($('.baseGroup .active_mixxxer_item_box').length == 0){
            $('.mixxxerItemSelectors.base').hide();
        }else{
            $('.mixxxerItemSelectors.base').show();
        }
        
        
        var upload_num = $('[name="mixxxer_upload_num"]').val();
        if ($('li.mixxxer_file').length >= upload_num) {
            $('#mixxxer_upload_form').hide();
        } else {
            $('#mixxxer_upload_form').show();
        }
        draw_max_val_bar();
        register_feature_links();
    }
    scrollDown();
    showHideTabs();
    if (guided_mixxxer == 1) {
        processTabs();
    }
    processSubTabs();
    //if(parseFloat($('#curr_max_val').val())<parseFloat($('#max_val').val())){
    if($('#mixxxer_rc_container .warning').length > 0){
          $('#mixxx_not_complete').slideDown(300);
          if(mixxxIncompleteNoCart == 1){
            $('#mixxxerAddCart').slideUp(300);
          }
        }else{
          $('#mixxx_not_complete').slideUp(300);
          if(mixxxIncompleteNoCart == 1){
            $('#mixxxerAddCart').slideDown(300);
          }
        }
    processScrollableNavi(true);
    if(reset_guided){
        $('h3.ui-state-active').next('.mixxxer_group').css('height', 'auto');
    }
}

function disable_item(mi_id, text) {
    var that = $('#mixxxer_item_box_' + mi_id);
    var height = that.height();
    var width = that.width();
    if (text == "") {
        text = "&nbsp;";
    }
    that.prepend('<div class="mixxxer_disabled_overlay_text"><div>' + text + '</div></div>');
    that.prepend('<div class="mixxxer_disabled_overlay">&nbsp;</div>');
    that.addClass('disabled');
    $('#mixxxerOption'+mi_id).attr('disabled', true);
}

function init() {
    $.get('mixxxer_ajax_helper.php', function (data) {
        put_it_in(data);
    });
}
function mixxxerInit() {
    $.get('mixxxer_ajax_helper.php', function (data) {
        put_it_in(data);
    });
}

function register_feature_links() {
    $('.feature_ajax_link').click(function () {
        $('#loading_feature_list_bar').show();
        $.get(this.href, function (data) {
            put_it_in(data);
            $('#loading_feature_list_bar').hide();
        });
        return false;
    });
    $('.feature_info_link').click(function () {
        var url = this.href;
        var dialog = $('<div style="display:none" title="' + $(this).attr('title') + '"></div>').appendTo('body');
        // load remote content
        dialog.load(
        url, {}, function (responseText, textStatus, XMLHttpRequest) {
            dialog.dialog();
        });
        //prevent the browser to follow the link
        return false;
    });
    $('.mixxxer_file .mixxxer_del_file').live('click', function (event) {
        event.preventDefault();
        var fid = $(this).attr('ref');
        $.get('mixxxer_ajax_helper.php?action=del_file&fid=' + fid, function (data) {
            put_it_in(data);
        });
    });
    $('.new_mix_link').click(function () {
        $('#mixer_tabs > ul > li:first > a').click();
        if($('.mixxxer_collapse_all').length>0){
          
          $('.ui-state-active .multiaccordion_open_0').click();
          $('body').scrollTop(0);
          $('html').scrollTop(0);
        }
    });
}

function processNavi(){
    
    var scrollPos = $('.mixxxerGroupsWr').scrollTop();
    var activeGroup = $('.mixxxer_group:visible').first();
    $('.mixxxer_group:visible').each(function(){
        if($(this).position().top <= 5){
            activeGroup = $(this);
        }
    });
    var activeHeading = activeGroup.find('h3.heading');
    $('h3.mixxxerFancyHeading').html(activeHeading.html()).width(activeHeading.width());
    var id = activeGroup.attr('data-id');  
    $('#mixxxer_tabs .scrollToLink').removeClass('active');
    $('#mixxxer_tabs .scrollToLink[data-id="'+id+'"]').addClass('active');
}
$(document).ready(function () {
  processNavi();
  $('#mixxxer_tabs .scrollToLink').click(function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var target = $('.mixxxer_group[data-id="'+id+'"]');
        console.log(target, target.position().top);
        var top = target.position().top;
        $('.mixxxerGroupsWr').animate({scrollTop:($('.mixxxerGroupsWr').scrollTop()+top)}, '500', 'swing');
  });
  $('.mixxxerGroupsWr').bind('scroll', processNavi);

  $('.hideOnMixxxer').css({overflow: "hidden"});
  $('.hideOnMixxxer').delay( 200 ).animate({
    height: 0,
    opacity: 0
  });
  
  
   $('.mixListToggler').click(function(){
    $('#mixList1, #mixList2').slideToggle(300);
    /*var api = $('#scrollable_items').data("scrollable");
    api.seekTo(0);*/
    return false;
   });
    $('.mixxxer_sg_tabs').tabs();
    /*$('#scrollable_items').scrollable({
        vertical: true,
        mousewheel: true
    });*/
    if($('.mixxxer_max_vals').length>0){
        valMap = new Array();
        var i = 0;
        $('.mixxxer_max_vals').each(function(){
            valMap[i] = $(this).val();
            i++;
        });
        //console.log(valMap);
        var c_pos = 0;
        var cont = true;
        $.each(valMap, function(k, v){
        
           if(parseFloat(v)>=parseFloat($('#max_val').val()) && cont){
              c_pos = k;
              cont = false;
           }
        });
        //console.log(c_pos);
        $("#max_val_slider").slider({
            min: 0,
            max: valMap.length - 1,
            slide: function(event, ui) {                        
                    var val = ui.value;
                    $('#mixxxer_slider_text').html(valMap[val]);
                    
                    
                                 
            },
            stop:function(){
                processSliderValue();
            },
            value:c_pos      
        });
        processSliderValue();
    }
    $('.mixxxer_continued').val(0);
    $('.mixxxer_item_info_tooltip').tooltip();
    $('.mixxxer_item_info_tooltip').click(function (event) {
        event.preventDefault();
    });
   /* $('#mixxxer_upload_button').click(function (event) {
        event.preventDefault();
          ajaxFileUpload(); 
        $('#mixxxer_upload_file').click();
    });*/
    
    $('#mixxxer_upload_file').live('change', function (event) {
        event.preventDefault();
        ajaxFileUpload();
    });
    $('.mixer_ajax_link').live('click', function () {
        $('#loading_feature_list_bar').show();
        $.get(this.href, function (data) {
            put_it_in(data);
            $('#loading_feature_list_bar').hide();
            //$(".ui-dialog-content").dialog("close");
        });
        return false;
    });
    
    $('.mixxxerItemRadio').live('click', function(){
        if($(this).attr('checked')){
            $(this).closest('.mixxxer_item_box').find('.mixxxer_item_incr').click();
        }else{
            $(this).closest('.mixxxer_item_box').find('.mixxxer_item_remove').click();
        }
            
    });
    
    $('.mixxxerSelect').live('change', function(){
        var itemId = $(this).val();
        var groupBox = $(this).closest('.mixxxer_group');
        if(groupBox.attr('multiselect')=='1'){
            groupBox.find('.mixxxer_item_remove:not(.mixxxer_item_decr_'+itemId+')').click();
        }
        if(!$('#mixxxer_item_box_'+itemId).hasClass('active_mixxxer_item_box')){
            $('#mixxxer_item_box_'+itemId).find('.mixxxer_item_incr').click();
        }
       
            
    });
    
    $('.mixxxerItemQtyField').live('keyup', function(){
         if($(this).val()!=''){
         $.get('mixxxer_ajax_helper.php?action=set_qty&id=' + $(this).attr('data-item') + '&qty='+parseInt($(this).val()), function(data){
            put_it_in(data);
         });
         }
    });
    
    
     $('.save_c_text').live('click', function () {
        $('#loading_feature_list_bar').show();
      
        var obj = $(this);
        $.get(this.href+'&text='+escape($(this).parent().find('[name^="mi_c_text["]').val()), function (data) {
            
           $('#loading_feature_list_bar').hide();
          
           var check = obj.parent().find('.save_check');
           check.show().delay(1000).fadeOut(300);
            put_it_in(data);
        });
        
        return false;
    });
    
    $('[name^="mi_c_text["]').blur(function(){
        $(this).parent().find('.save_c_text').click();
    });
    
    $('.load_from_id_link').click(function () {
        $('#loading_feature_list_bar').show();
        $.get(this.href + '&mix_id=' + $('[name="load_from_id"]').val(), function (data) {
            put_it_in(data);
            $('#loading_feature_list_bar').hide();
            register_feature_links();
            $('[name="load_from_id"]').val('');
        });
        return false;
    });
    $('[name="load_from_id"]').focus(function () {
        if ($(this).val() == 'ID-NUMMER') {
            $(this).val('');
        }
    });
    $('[name="load_from_id"]').blur(function () {
        if ($(this).val() == '') {
            $(this).val('ID-NUMMER');
        }
    });
    $('.mixer_info_link').click(function () {
        var url = this.href;
        var dialog = $('<div style="display:none" title="' + $(this).attr('title') + '"></div>').appendTo('body');
        // load remote content
        dialog.load(
        url, {}, function (responseText, textStatus, XMLHttpRequest) {
            dialog.dialog({
                width: 600
            });
        });
        //prevent the browser to follow the link
        return false;
    });
    $('[name="my_name"]').keyup(function () {
        $.get('mixxxer_ajax_helper.php?action=save_name&name=' + escape($('[name="my_name"]').val()), function (data) {});
    });
    $('[name="my_comment"]').keyup(function () {
        $.get('mixxxer_ajax_helper.php?action=save_comment&c=' + escape($('[name="my_comment"]').val()), function (data) {});
    });
    $('[name="max_cost"]').keyup(function () {
        $.get('mixxxer_ajax_helper.php?action=save_max_cost&max_cost=' + $('[name="max_cost"]').val(), function (data) {});
    });
    $('.del_max_cost').click(function () {
        $.get('mixxxer_ajax_helper.php?action=del_max_cost', function (data) {
            put_it_in(data);
        });
        return false;
    });
    $('.del_text').click(function () {
        $.get('mixxxer_ajax_helper.php?action=del_text', function (data) {
            put_it_in(data);
        });
        return false;
    });
    $('[name="my_text"]').keyup(function () {
        $.get('mixxxer_ajax_helper.php?action=save_text&text=' + $('[name="my_text"]').val(), function (data) {});
    });
    //$("#mixxxer_tabs").tabs();
    $("#mixxxer_accordion").accordion({
        autoHeight: false
    });
    if($('#mixxxer_multiaccordion').length>0){
        $('#mixxxer_multiaccordion').multiAccordion();
        $('.ui-state-default .multiaccordion_open_1').click();
        $('.ui-state-active .multiaccordion_open_0').click();
    }
    register_feature_links();
    init();
    $('.mixxxer_continue').click(function () {
            var cont = false;
            if($(this).parent().attr('rel')=='0'){
                cont = true;
            }else if($(this).parent().find('.active_mixxxer_item_box').length > 0){
                cont = true;
            }
                if(cont){
                    $(this).parent().find('.mixxxer_continued').val(1);
                    var res = processTabs();
                    if(!res){
                        $(this).closest('.mixxxer_group').next('h3:visible').click();
                    }
                }else{
                  var dialog = $('<div style="display:none">' + please_choose_item + '</div>').appendTo('body');
                  dialog.dialog();
                } 
                return false;
    });
    $('.mixxxer_back').click(function (event) {
        event.preventDefault();
        var wr = $(this).closest('.mixxxer_group');
        var found = false;
        if ($('#mixxxer_tabs').length > 0) {
            var pos = $('#mixxxer_tabs > ul > li > a[href="#' + wr.attr('id') + '"]').parent();
            var i = 0;
            while(found == false && i < 100){
                pos = pos.prev();
                if(pos.hasClass('hasActiveChildren')){
                    found = true;
                    pos.find('a').click();
                }
                i++;
            }
  
        } else if ($('#mixxxer_accordion').length > 0) {
            var pos = wr.prev('h3');
            var i = 0;
            while(found == false && i < 100){
                pos = pos.prev().prev('h3');
                if(pos.hasClass('hasActiveChildren')){
                    found = true;
                    pos.click();
                }
                i++;
            }
         }
    });
    /*var offset = jQuery("#mixxxer_rc_container").offset();
    menuYloc = offset.top;
   
    jQuery(window).scroll(function () {
        var contentHeight = jQuery("#contentwrap").height();
        var boxHeight = jQuery("#mixxxer_rc_container").height();
        offset = 20 + jQuery(document).scrollTop();
        if (offset < menuYloc) {
            offset = menuYloc;
        } else if ((offset + boxHeight) > (contentHeight - 80)) {
            offset = (contentHeight - boxHeight - 80);
        }
        //$('#my_comment').val(offset);
        //console.log(offset);
        jQuery("#mixxxer_rc_container").css('top', offset + "px");
    });*/
    if ($("#mixxxer_rc_container").height() > $("#mixxxer_right_col").height()) {
        $("#mixxxer_right_col").height($("#mixxxer_rc_container").height());
    }
    $('.mixxxer_expand_all').click(function (event) {
        event.preventDefault();
        $('h3.ui-state-default.hasActiveChildren').click();
    });
    $('.mixxxer_collapse_all').click(function (event) {
        event.preventDefault();
        $('h3.ui-state-active').click();
    });
    
    $('#mixxxer_summary_heading').click(function(){
         /* var api = $("#scrollable_items").data("scrollable");
    var num = api.getSize();
    var ind = api.getIndex();*/
    //console.log(ind);
    });
    
});

$(document).on('click touchstart', '#btn_toggle_rc_container', function(e){    
    e.preventDefault();
    if($(this).hasClass('active')){
        $('#mixxxer_right_col').animate({
            'right': -255
        });
        $(this).removeClass("active");
        $(this).html("&#10096;&#10096;");
    }else{
        $('#mixxxer_right_col').animate({
            'right': 1
        });
        $(this).addClass("active");
        $(this).html("&#10097;&#10097;");
    }
});



function  processSliderValue(send){
       var val = $("#max_val_slider").slider('value');
       
       $('#mixxxer_slider_text').html(valMap[val]);
       $('#max_val').val(valMap[val]);
       //draw_max_val_bar();
      // if(send){
          sendSliderValue(valMap[val]);
      // }
       
}

function sendSliderValue(val){
      
      $.ajax({
          url: mixxxer_ajax_helper+'&action=set_max_value&max_value='+val,
          type:'POST',
          success:function(data){
              put_it_in(data);
          }
       });
}


function showHideTabs() {
    $('.mixxxer_group').each(function () {
        var show = false;
        $(this).find('.mixxxer_item_box').each(function () {
            if ($(this).css('display') != 'none') {
                show = true;
            }
        });
        if (show) {
            $('a[href="#' + $(this).attr('id') + '"]').parent().show().addClass('hasActiveChildren');
            $(this).css('display', '');
            if ($('a[href="#' + $(this).attr('id') + '"]').parent().hasClass('ui-state-active')) {
                $(this).show();
            }
        } else {
            $('a[href="#' + $(this).attr('id') + '"]').parent().hide().removeClass('hasActiveChildren');
            $(this).hide();
        }
    });
}

function processTabs() {
    var ret = false;
    if ($('#mixxxer_tabs').length > 0) {
        $('#mixxxer_tabs > ul > li').hide();
        var the_end = false;
        $('#mixxxer_tabs > div').each(function () {
           if (!the_end) {
                var clink = true;
                var clink_2 = false;
                if (($(this).find('.mixxxer_continue').length > 0 && $(this).find('.mixxxer_continued').val() == 0) || $(this).find('.mixxxer_continue').length == 0) {
                    clink = false;
                }
                if ($(this).find('.mixxxer_continued').val() == 1) {
                    clink_2 = true;
                }
                var required_check = false;
                 if($(this).attr('rel')=='0'){
                    required_check = true;
                }else if($(this).find('.active_mixxxer_item_box').length > 0){
                    required_check = true;
                }else if($(this).find('.mixxxer_item_box:not(.disabled)').length == 0){
                    required_check = true;
                }
                
              
                if (
                                (($(this).find('.active_mixxxer_item_box').length > 0 && clink) 
                                    || 
                                    clink_2 
                                    || 
                                    $(this).find('.mixxxer_item_box:not(.disabled)').length == 0) 
                        
                        
                        && required_check
                        
                       ) {
                    $('#mixxxer_tabs > ul > li > a[href="#' + $(this).attr('id') + '"]').closest('li.hasActiveChildren').show();
                    /*$('#mixxxer_tabs > ul > li > a[href="#'+$(this).attr('id')+'"]').closest('li').next('li').show();
                  $('#mixxxer_tabs > ul > li > a[href="#'+$(this).attr('id')+'"]').closest('li').next('li').find('a').click();*/
                } else {
                    $('#mixxxer_tabs > ul > li > a[href="#' + $(this).attr('id') + '"]').closest('li.hasActiveChildren').show();
                    $('#mixxxer_tabs > ul > li > a[href="#' + $(this).attr('id') + '"]').closest('li').find('a').click();
                    ret = true;
                    the_end = true;
                   
                }
            }
        });
        if (!the_end && guided_mixxxer == 1 && !completed_message_shown) {
            var dialog = $('<div style="display:none">' + mixxx_complete + '</div>').appendTo('body');
            completed_message_shown = true;
            dialog.dialog();
        }
    } else if ($('#mixxxer_accordion').length > 0) {
        $('#mixxxer_accordion > h3').hide();
        var the_end = false;
        $('#mixxxer_accordion > div').each(function () {
            if (!the_end) {
                var isGroupContinued = true;
                var isGroupContinued2 = false;
                if (($(this).find('.mixxxer_continue').length > 0 && $(this).find('.mixxxer_continued').val() == 0)) {
                    isGroupContinued = false;
                }
                if ($(this).find('.mixxxer_continued').val() == 1) {
                    isGroupContinued2 = true;
                }
                
                var required_check = false;
                 if($(this).attr('rel')=='0'){
                    required_check = true;
                }else if($(this).find('.active_mixxxer_item_box').length > 0){
                    required_check = true;
                }
               
                //console.log(clink);
                //console.log(clink_2);
                //console.log($('#mixxxer_accordion > h3 > a[href="#'+$(this).attr('id')+'"]').closest('h3'));
                if ((($(this).find('.active_mixxxer_item_box').length > 0 && isGroupContinued) || isGroupContinued2 || $(this).find('.mixxxer_item_box:not(.disabled)').length == 0) && required_check) {
                   //GROUP IS CONTINUED > SHOW TITLE BUT DO NOT EXPAND
                   $('#mixxxer_accordion > h3 > a[href="#' + $(this).attr('id') + '"]').closest('h3.hasActiveChildren').show();
                } else if ($(this).prev().hasClass('hasActiveChildren')){
                    
                    $('#mixxxer_accordion > h3 > a[href="#' + $(this).attr('id') + '"]').closest('h3.hasActiveChildren').show();
                  //  if(goto){
                        $('#mixxxer_accordion > h3 > a[href="#' + $(this).attr('id') + '"]').click();
                        ret = true;
                   // }
                    the_end = true;
                }
            }
        });
        if (!the_end && guided_mixxxer == 1 && !completed_message_shown) {
          /*  var dialog = $('<div style="display:none">' + mixxx_complete + '</div>').appendTo('body');
            completed_message_shown = true;
            dialog.dialog();*/
        }
    }
    return ret;
}


function processSubTabs(){
   
    $('.mixxxer_sg_tabs').each(function(){
        var tabBox = $(this);
        tabBox.find('.ui-tabs-panel').each(function(){
            var thisPanel = $(this);
            var thisNav = tabBox.find('.ui-tabs-nav a[href="#'+thisPanel.attr('id')+'"]').parent('li');
            if(thisPanel.find('.mixxxer_item_box:not(.disabled)').length > 0){
                 thisNav.show().removeClass('disabled');
            }else{
                 thisNav.hide().addClass('disabled');
            }
            
        });
        if(!tabBox.is(':visible')){
            tabBox.find('.ui-tabs-nav li:not(.disabled)').first().find('a').click();
        }
    });

}

function processScrollableNavi(totop) {
   /* var api = $("#scrollable_items").data("scrollable");
    var num = api.getSize();
    var ind = api.getIndex();
    //console.log(num);
    //console.log(ind);
    if (num - 7 >= ind) {
        $('.next').removeClass('disabled').css('visibility', 'visible');
    } else {
        $('.next').addClass('disabled').css('visibility', 'hidden');
    }
    if(num<=6 && totop){
       api.begin(0); 
    }
    */
}



function scrollDown() {
   /* var api = $("#scrollable_items").data("scrollable");
    var num = api.getSize();
    
    
    api.seekTo(num-6);*/
   
}

function draw_max_val_bar(){
    var val = $('#curr_max_val').val();
    var max = $('#max_val').val();
    $('#mixxxer_slider_text').html(max);
    
    $('#text_curr_max_val').html(val);
    
    $('#text_max_val').html(max);
    var percent = Math.round(val/max*100);
     $('#text_curr_max_val_percent').html(percent);
    $('#max_val_bar_inner').css('width', percent+'%');

}
/*
 * jQuery UI Multi Open Accordion Plugin
 * Author	: Anas Nakawa (http://anasnakawa.wordpress.com/)
 * Date		: 22-Jul-2011
 * Released Under MIT License
 * You are welcome to enhance this plugin at https://code.google.com/p/jquery-multi-open-accordion/
 */
(function ($) {
    $.widget('ui.multiAccordion', {
        options: {
            active: 0,
            showAll: null,
            hideAll: null,
            _classes: {
                accordion: 'ui-accordion ui-widget ui-helper-reset ui-accordion-icons',
                h3: 'ui-accordion-header ui-helper-reset ui-state-default ui-corner-all',
                div: 'ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom',
                divActive: 'ui-accordion-content-active',
                span: 'ui-icon ui-icon-triangle-1-e',
                stateDefault: 'ui-state-default',
                stateHover: 'ui-state-hover'
            }
        },
        _create: function () {
            var self = this,
                options = self.options,
                $this = self.element,
                $h3 = $this.children('h3'),
                $div = $this.children('div');
            $this.addClass(options._classes.accordion);
            $h3.each(function (index) {
                var $this = $(this);
                $this.addClass(options._classes.h3).prepend('<span class="{class}"></span>'.replace(/{class}/, options._classes.span));
                if (self._isActive(index)) {
                    self._showTab($this)
                }
            }); // end h3 each
            $this.children('div').each(function (index) {
                var $this = $(this);
                $this.addClass(options._classes.div);
            }); // end each
            $h3.bind('click', function (e) {
                // preventing on click to navigate to the top of document
                e.preventDefault();
                var $this = $(this);
                var ui = {
                    tab: $this,
                    content: $this.next('div')
                };
                self._trigger('click', null, ui);
                if ($this.hasClass(options._classes.stateDefault)) {
                    self._showTab($this);
                } else {
                    self._hideTab($this);
                }
            });
            $h3.bind('mouseover', function () {
                $(this).addClass(options._classes.stateHover);
            });
            $h3.bind('mouseout', function () {
                $(this).removeClass(options._classes.stateHover);
            });
            // triggering initialized
            self._trigger('init', null, $this);
        },
        // destroying the whole multi open widget
        destroy: function () {
            var self = this;
            var $this = self.element;
            var $h3 = $this.children('h3');
            var $div = $this.children('div');
            var options = self.options;
            $this.children('h3').unbind('click mouseover mouseout');
            $this.removeClass(options._classes.accordion);
            $h3.removeClass(options._classes.h3).removeClass('ui-state-default ui-corner-all ui-state-active ui-corner-top').children('span').remove();
            $div.removeClass(options._classes.div + ' ' + options._classes.divActive).show();
        },
        // private helper method that used to show tabs
        _showTab: function ($this) {
            var $span = $this.children('span.ui-icon');
            var $div = $this.next();
            var options = this.options;
            $this.removeClass('ui-state-default ui-corner-all').addClass('ui-state-active ui-corner-top');
            $span.removeClass('ui-icon-triangle-1-e').addClass('ui-icon-triangle-1-s');
            $div.slideDown('fast', function () {
                $div.addClass(options._classes.divActive);
            });
            var ui = {
                tab: $this,
                content: $this.next('div')
            }
            this._trigger('tabShown', null, ui);
        },
        // private helper method that used to show tabs 
        _hideTab: function ($this) {
            var $span = $this.children('span.ui-icon');
            var $div = $this.next();
            var options = this.options;
            $this.removeClass('ui-state-active ui-corner-top').addClass('ui-state-default ui-corner-all');
            $span.removeClass('ui-icon-triangle-1-s').addClass('ui-icon-triangle-1-e');
            $div.slideUp('fast', function () {
                $div.removeClass(options._classes.divActive);
            });
            var ui = {
                tab: $this,
                content: $this.next('div')
            }
            this._trigger('tabHidden', null, ui);
        },
        // helper method to determine wether passed parameter is an index of an active tab or not
        _isActive: function (num) {
            var options = this.options;
            // if array
            if (typeof options.active == "boolean" && !options.active) {
                return false;
            } else {
                if (options.active.length != undefined) {
                    for (var i = 0; i < options.active.length; i++) {
                        if (options.active[i] == num) return true;
                    }
                } else {
                    return options.active == num;
                }
            }
            return false;
        },
        // return object contain currently opened tabs
        _getActiveTabs: function () {
            var $this = this.element;
            var ui = [];
            $this.children('div').each(function (index) {
                var $content = $(this);
                if ($content.is(':visible')) {
                    //ui = ui ? ui : [];
                    ui.push({
                        index: index,
                        tab: $content.prev('h3'),
                        content: $content
                    });
                }
            });
            return (ui.length == 0 ? undefined : ui);
        },
        getActiveTabs: function () {
            var el = this.element;
            var tabs = [];
            el.children('div').each(function (index) {
                if ($(this).is(':visible')) {
                    tabs.push(index);
                }
            });
            return (tabs.length == 0 ? [-1] : tabs);
        },
        // setting array of active tabs
        _setActiveTabs: function (tabs) {
            var self = this;
            var $this = this.element;
            if (typeof tabs != 'undefined') {
                $this.children('div').each(function (index) {
                    var $tab = $(this).prev('h3');
                    if (tabs.hasObject(index)) {
                        self._showTab($tab);
                    } else {
                        self._hideTab($tab);
                    }
                });
            }
        },
        // active option passed by plugin, this method will read it and convert it into array of tab indexes
        _generateTabsArrayFromOptions: function (tabOption) {
            var tabs = [];
            var self = this;
            var $this = self.element;
            var size = $this.children('h3').size();
            if ($.type(tabOption) === 'array') {
                return tabOption;
            } else if ($.type(tabOption) === 'number') {
                return [tabOption];
            } else if ($.type(tabOption) === 'string') {
                switch (tabOption.toLowerCase()) {
                case 'all':
                    var size = $this.children('h3').size();
                    for (var n = 0; n < size; n++) {
                        tabs.push(n);
                    }
                    return tabs;
                    break;
                case 'none':
                    tabs = [-1];
                    return tabs;
                    break;
                default:
                    return undefined;
                    break;
                }
            }
        },
        // required method by jquery ui widget framework, used to provide the ability to pass options
        // currently only active option is used here, may grow in the future
        _setOption: function (option, value) {
            $.Widget.prototype._setOption.apply(this, arguments);
            var el = this.element;
            switch (option) {
            case 'active':
                this._setActiveTabs(this._generateTabsArrayFromOptions(value));
                break;
            case 'getActiveTabs':
                var el = this.element;
                var tabs;
                el.children('div').each(function (index) {
                    if ($(this).is(':visible')) {
                        tabs = tabs ? tabs : [];
                        tabs.push(index);
                    }
                });
                return (tabs.length == 0 ? [-1] : tabs);
                break;
            }
        }
    });
    // helper array has object function
    // thanks to @Vinko Vrsalovic
    // http://stackoverflow.com/questions/143847/best-way-to-find-an-item-in-a-javascript-array
    Array.prototype.hasObject = (!Array.indexOf ?
    function (o) {
        var l = this.length + 1;
        while (l -= 1) {
            if (this[l - 1] === o) {
                return true;
            }
        }
        return false;
    } : function (o) {
        return (this.indexOf(o) !== -1);
    });
})(jQuery);

$(document).ready(function(){
    $('.thickbox').magnificPopup({type:'image'});
});
