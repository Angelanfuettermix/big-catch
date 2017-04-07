$(document).ready(function() {
  
  function load_ss(order, page){
        
              
        $.ajax({
                         url: 'attribute_manager.php?what=search_suggest&page=' + page + '&key=' + $('#sesu_input').val(),
                         async:true,
                         success: function(data){
                                
                                $('#search_suggest_list').remove();
                                
                                if (data != ""){
                                   
                                    a = data.split('<sesu_start>');
                                    b = a[1].split('<sesu_end>');
                                    c =b[0];
                                    temp = '<div id="search_suggest_list">' + c + '</div>';
                                    $('#sesu_input').after(temp);
                                    

                                }
                                
                                
        
        
                         }
                         
        
        
              });
  
  }
  
  
  $('#sesu_input').live('keyup', function(event){
   
       var len = $('#sesu_input').val().length; 
       if (len>=3){
             
              $('#search_suggest_list').remove();
               temp = "";
              load_ss("", 1);
      }
     
                
	     
              
       
      
	     

      
      
      
      
  });
  
  $('.search_suggest_list_item').live('click', function(){
      bu = $(this);
      name = bu.attr('pname');
      id =  bu.attr('pid');
      $('#p2attr').val(id);
      $('#p2atrr_name').html(name);
      $('#search_suggest_list').remove();
      
  
  });
  
 
});