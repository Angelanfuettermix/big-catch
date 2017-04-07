(function($,undefined) {
    $.fn.cloneSelects = function(withDataAndEvents, deepWithDataAndEvents) {
        var $clone = this.clone(withDataAndEvents, deepWithDataAndEvents);
        var $origSelects = $('select', this);
        var $clonedSelects = $('select', $clone);
        $origSelects.each(function(i) {
            $clonedSelects.eq(i).val($(this).val());
        });
        return $clone;
    }
})(jQuery);
$(document).ready(function(){
    $('body').append('<div id="amWorkbench" style="display:none;"></div>');
    load_start();
    
    function reset_it(){
          $('.am_new_ov_form').slideUp(500, function(){
          $('.am_new_ov_form').remove();
          });
          
          
          
          
      
    }
    
    function slide_it_up(){
        $('.am_options_values_wr').slideUp(500);
        $('.am_options_expand').show();
        $('.am_options_collapse').hide();
    }

    
    
    $('.am_options_collapse').live('click', function(){
         slide_it_up();
       
    
    });
                      
    














    $('.am_options_expand').live('click', function(){
         $(this).parent().find('.am_options_collapse').show();
         id = $(this).attr('id').replace('am_po_id_', '');
         bu = $(this);
         $.ajax({
            url: "mixxxer_manager.php?what=get_ov&po_id=" + id,
            success: function(data){
                   bu.parent().parent().next('.am_options_values_wr').find('.am_ov_table').html(data);
                   slide_it_up();
                  
                   bu.parent().parent().next('.am_options_values_wr').slideDown(500);
                   bu.parent().find('.am_options_collapse').show();
                   bu.parent().find('.am_options_expand').hide();
                   
                  
            }          
          });
         
         
         
         
        
         $(this).hide();
         return false;
         
    
    });




    $('.am_profile_option').live('focus', function(){
       
    
    });


    $('.am_duplicate_po').live('click', function(){
        
            id = $(this).attr('option');
          
            $.ajax({
                url: "mixxxer_manager.php?what=duplicate_po&option=" + id,
                success: function(data){
                        load_start();
                    
            }          
          });
        return false;
    
    });

    $('.am_add_ov').live('click', function(){
        reset_it();
        id = $(this).attr('id').replace('am_add_ov_', '');
        link = $(this);
        $.ajax({
            url: "mixxxer_manager.php?what=new_ov&option=" + link.attr('option'),
            success: function(data){
                   
                   link.parent().append(data);
                   link.hide();
                   $('.am_cancel_add_ov').show();
                    
                    $(".mytags").each(function(){
                       var id = $(this).attr('id').replace('fn_', '');
                      
                       $(this).tagit({itemName:id, availableTags:comp_groups, allowSpaces:true,onTagAdded: function(event, tag) {
        if(jQuery.inArray(tag.find('.tagit-label').html(), comp_groups)==-1){
          comp_groups.push(tag.find('.tagit-label').html());
        }
        
        //console.log(comp_groups);
        
    }});
                    
                    });
                   $('.am_new_ov_form').slideDown(500);
                   register_ac();
            }          
          });
        return false;
    
    });
    
    $('.am_cancel_add_ov').live('click', function (){
       reset_it();
       $('.am_add_ov').show();
       $(this).hide();
       return false;
    });

    $('.am_edit_ov').live('click', function(){
        reset_it();
        id = $(this).attr('id').replace('am_edit_ov_', '');
        link = $(this);
        $.ajax({
            url: "mixxxer_manager.php?what=new_ov&ov=" + id + "&option=" + link.attr('option'),
            success: function(data){
                 
                   link.parent().parent().append(data);
                   $('.am_new_ov_form').slideDown(500);
                   $(".mytags").each(function(){
                       var id = $(this).attr('id').replace('fn_', '');
                   
                       $(this).tagit({itemName:id, availableTags:comp_groups, allowSpaces:true,onTagAdded: function(event, tag) {
       if(jQuery.inArray(tag.find('.tagit-label').html(), comp_groups)==-1){
          comp_groups.push(tag.find('.tagit-label').html());
        }
        //console.log(comp_groups);
    }});
                    
                    });
                   register_ac();
                   
                   
            }          
          });
        return false;
    
    });
   
   function register_ac(){

                   
    $('[name="mi_product"]').autocomplete({
			source: "mixxxer_manager.php?what=ajax&action=search_product",
			minLength: 3,
			select: function( event, ui ) {
			       
             $('[name="mi_product"]').val(ui.item.value);
             $.each(ui.item.t, function(index, val){
                $('[name="ov_name_'+index+'"]').val(val);
             });
             $.each(ui.item.d, function(index, val){
               
                $('[name="mi_description_'+index+'"]').val(val);
             });
             
			
				}
		});
   } 
    
    $('.am_save_ov').live('click', function(){
       po = $(this).attr('option');
       
       var options = {
            		url:'mixxxer_manager.php?what=save_ov',            		
                success:       function(data){
                                
                                  reset_it();
                
                               
                                  $.ajax({
                                      url: "mixxxer_manager.php?what=get_ov&po_id=" + po,
                                      success: function(data){
                                                    $('#am_wr_' + po).html(data);
                                                     
                                              }          
                                  });

                                }  
      };
	   $('.new_ov_form').ajaxSubmit(options);
	   return false;
    
    });
    
    
    $('.am_delete_ov').live('click', function(){
       if (myConfirm()){
            ov_id = $(this).attr('ov');
       item = $(this).parent().parent().parent();
       $.ajax({
          url: 'mixxxer_manager.php?what=delete_ov&ov_id=' + ov_id,            		
                success:       function(data){
                             item.fadeOut(400);   
                                  

                }       
          });
       
  	   return false;
       
       }
       
    
    });
    
    
    
    
    
    
    
    
    
    $('.am_edit_po').live('click', function(){
        $('.am_new_po_form').slideUp(500, function(){
          $(this).parent().parent().parent().hide();
          $(this).remove();
          
          });
        
          po_id = $(this).attr('option');
          bu = $(this);
          $.ajax({
              url: "mixxxer_manager.php?what=edit_po&po_id=" + po_id,
              success: function(data){
                     
                     bu.parent().parent().next('.am_options_values_wr').find('.am_ov_table').html(data);
                    $(".mytags").each(function(){
                       var id = $(this).attr('id').replace('fn_', '');
                   
                       $(this).tagit({itemName:id, availableTags:comp_groups, allowSpaces:true,onTagAdded: function(event, tag) {
        if(jQuery.inArray(tag.find('.tagit-label').html(), comp_groups)==-1){
          comp_groups.push(tag.find('.tagit-label').html());
        }
        //console.log(comp_groups);
    }
    });
                    
                    }); 
                     bu.parent().parent().next('.am_options_values_wr').slideDown(500);
              }          
          });
          
        
        
        return false;
    });
    
    
    
    $('.am_new_po').live('click', function(){
        $('.am_new_po_form').slideUp(500, function(){
          $(this).parent().parent().parent().hide();
          $(this).remove();
          
          });
        
          po_id = $(this).attr('option');
          bu = $(this);
          $.ajax({
              url: "mixxxer_manager.php?what=edit_po&po_id=" + po_id,
              success: function(data){
                     
                     bu.parent().parent().next('.am_options_values_wr').find('.am_ov_table').html(data);
                     bu.hide();
                      $(".mytags").each(function(){
                       var id = $(this).attr('id').replace('fn_', '');
                   
                       $(this).tagit({itemName:id, availableTags:comp_groups, allowSpaces:true,onTagAdded: function(event, tag) {
        if(jQuery.inArray(tag.find('.tagit-label').html(), comp_groups)==-1){
          comp_groups.push(tag.find('.tagit-label').html());
        }
        //console.log(comp_groups);
    }});
                    
                    });
                     bu.parent().parent().next('.am_options_values_wr').slideDown(500);
              }          
          });
          
        
        
        return false;
    });
    $('.am_cancel_new_po').live('click', function(){
       slide_it_up();
       
       $('.am_new_po').show();
       
       return false;
    });
    
    
    
    
    
    $('.am_save_po').live('click', function(){
       
       var options = {
            		url:'mixxxer_manager.php?what=save_po',            		
                success:       function(data){
                                
                                  $('.am_new_po_form').slideUp(500, function(){
                                        $('.am_new_po_form').remove();
                                        slide_it_up(); 
                                        load_start();
                                  });
                                  /*
                                  $.ajax({
                                      url: "mixxxer_manager.php?what=get_ov&po_id=" + po,
                                      success: function(data){
                                                    $('#am_wr_' + po).html(data);
                                                     
                                              }          
                                  });
                                  */
                                }  
      };
	   $('form.edit_po').ajaxSubmit(options);
	   return false;
    
    });
    
    
    
    
    $('.am_delete_po').live('click', function(){
       
       if (myConfirm()){
            po_id = $(this).attr('option');
            item = $(this).parent().parent();
       $.ajax({
          url: 'mixxxer_manager.php?what=delete_po&po_id=' + po_id,            		
                success:       function(data){
                                
                                  item.fadeOut(400); 

                }       
          });
       
  	   return false;
       
       }
       
    
    });
    
    
   
    
    
    
    
    $('.am_sel_ov_as_attr').live('click', function(){
       ov_id = $(this).attr('ov');
       po_id = $(this).attr('po');
       checkbox = $(this);
       if($(this).attr('checked')){
            $.ajax({
                url: 'mixxxer_manager.php?what=sel_ov_as_attr&new=1&ov_id=' + ov_id + "&po_id=" + po_id,            		
                success:       function(data){
                                checkbox.attr('checked', true);
                                load_attr_set(ov_id);  

                }       
            });
            
       
       }else{
           $.ajax({
                url: 'mixxxer_manager.php?what=sel_ov_as_attr&new=0&ov_id=' + ov_id + "&po_id=" + po_id,            		
                success:       function(data){
                                 
                                 checkbox.attr('checked', false); 
                                 clear_attr_set(ov_id);
                }       
            });
           
           
       
       }
       
       
        
        return false;
    });
    
    
    $('.am_save_attr').live('click', function(){
       ov_id = $(this).attr('ov');
       save_attr(ov_id);
       return false;
    });
    
    
    
    $('.am_show_manager').live('click', function(){
         caller=$(this);
         $.ajax({
                url: 'mixxxer_manager.php?noscript=true',            		
                success:       function(data){
                                
                                $('.am_man_wr').append(data); 
                                load_start();
                }       
         });
         return false;
    });
    
    
    $('.am_save_profile').live('click', function(){
        pn = $('#new_profile_name').val();
        if (pn != ""){
             $.ajax({
                url: 'mixxxer_manager.php?what=save_profile&profile_name=' + pn,            		
                success:       function(data){
                                    
                                    $('#new_profile_name').val('');
                                    load_start();
                }       
                });
        }
        return false;
    });
    
    $('.load_profile_button').live('click', function(){
        pn = $('#load_profile').val();
        
             $.ajax({
                url: 'mixxxer_manager.php?what=load_profile&profile_name=' + pn,            		
                success:       function(data){
                                   alert(data);
                                   load_start();
                                    
                               
                }       
                });
                               
        return false;
    });
    
    
    
    
    $('.am_delete_profile').live('click', function(){
        pn = $('#load_profile').val();
           if(myConfirm()){
             $.ajax({
                url: 'mixxxer_manager.php?what=delete_profile&profile_name=' + pn,            		
                success:       function(data){
                                   alert(data);
                                   load_start();
                                    
                               
                }       
                });
            }
                               
        return false;
    });
    
    
     function save_attr(ov_id){
        
        var options = {
                		url:'mixxxer_manager.php?what=save_attr',            		
                    success:       function(data){
                                   $('#check_attr_'+ov_id).show().delay(1000).fadeOut(500);
                                   $('#am_attr_form_' + ov_id).find('.am_save_attr img').css('opacity', 1);
                                     
                                    }  
          };
        $('#am_attr_form_' + ov_id).find('.am_save_attr img').css('opacity', 0.5);  
        var newForm = $('#am_attr_form_' + ov_id).cloneSelects(true);
        var newFormParent = $('<form method="POST" enctype="multipart/form-data" />');
        newFormParent.append(newForm);
        newFormParent.find('[type=file]').replaceWith();
        $('#am_attr_form_' + ov_id).find('[type=file]').after($('#am_attr_form_' + ov_id).find('[type=file]').clone());
        newFormParent.append($('#am_attr_form_' + ov_id).find('[type=file]').first());
        $('#amWorkbench').append(newFormParent);
        newFormParent.ajaxSubmit(options);
       
    }
    
    
    
    
    
    
    function fill_all_attr_set(){
      $('.am_sel_ov_as_attr:checked').each(function(){
         ov_id = $(this).attr('ov');
         load_attr_set(ov_id);
      
      });
    
    } 
    
    
    
    function load_attr_set(ov_id){
          $.ajax({
                url: "mixxxer_manager.php?what=get_attr_set&as_ov_id=" + ov_id,
                success: function(data){
                    $('#am_attr_settings_' + ov_id).html(data);
                    }          
                });
    
    }
    
    function clear_attr_set(ov_id){
          $('#am_attr_settings_' + ov_id).html('');
    
    }
    
    
    function load_start(){
    $.ajax({
          url: "mixxxer_manager.php?what=start",
          success: function(data){
              $('#am_wr').html(data);
              }          
          });
    
    }
    
    function myConfirm(){
      return confirm('Wirklich?');
    
    }
    
    
    $('.ajax_form').submit(function(){
      var form = $(this);
      $(this).find('button').attr('disabled', true);
       var options = {
            		          		
                success:       function(data){
                                   form.find('button').attr('disabled', false);   
                                
                                 
                                }  
      };
      
	   $(this).ajaxSubmit(options);
	   

	   if ($(this).find('input[name=delete]').attr('checked')==true){
        $(this).fadeOut(300);
     }
     return false;
    
    });
    
    
    
    
    
    
    
});
