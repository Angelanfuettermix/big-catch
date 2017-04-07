//for handle the putin
function put_it_in(data,mod_objekt) {
	show_loader('hide');
	if(typeof(data.error)!='undefined'){
		//alert(data.error);
	 	mod_url_to_go = check_ssl(data.error);
		setTimeout(function(){window.location.href = mod_url_to_go},100);
	}else{
        console.log(data.items);
		if(typeof(data.items)!='undefined'){
			// now give the new data
			$.each(data.items, function(json_id,json_data){
            console.log(json_data.update_select);
				$(json_data.update_select).effect('highlight', {color:'#482004'}, 750);
				$(json_data.update_select).html(json_data.update_text);
			});
		}
	}
}

function check_ssl(mod_url){
 	// SSL mod
	if(typeof mod_url != 'undefined'){
		var mod_href = '';
		if(typeof mod_ajax_request != 'undefined'){
			var mod_href = mod_url.replace(mod_ajax_normal_server,mod_ajax_ssl_server)
		}else{
			var mod_href = mod_url;
		}
		return mod_href;
	}
}

function show_loader(what){
	if(what == 'show'){
		$('#new_price').css({'height':'1.3em','display':'inline-block'}).html('<img src="images/loading.gif" />');
 	}else{
		$('#new_price').html('');
 	}
}

function do_variation_change_init(){
	// make first call
	if($('select[name^=id]').length || $('input[name^=id]').length){
		var checked_basename = check_ssl($('form:has(input[name=products_qty])').attr('action'));
		var options = {
			url:checked_basename,
			dataType:'json',
			data:{ajax:'ajax_calls',what:'get_price'},
	    beforeSubmit:  function(){show_loader('show')},  // pre-submit callback
	    success:       function(data){put_it_in(data);}   // post-submit callback
	  };
		$('form:has(input[name=products_qty])').ajaxSubmit(options);
	}

	// bind it
	$('select[name^=id],input[name^=id]').each(function(){
		var checked_basename = check_ssl($('form:has(input[name=products_qty])').attr('action'));
		var options = {
			url:checked_basename,
			dataType:'json',
			data:{ajax:'ajax_calls',what:'get_price'},
	  	beforeSubmit:  function(){show_loader('show')},  // pre-submit callback
	    success:       function(data){put_it_in(data);}   // post-submit callback
	  };
		// wenn beides gleichzeitig genutzt wird muss hier eine $this abfrage nach dem entsprechenden each input oder select gemacht werden
		if($('input[name^=id]').length){
			$(this).bind('click',function(){
				$('form:has(input[name=products_qty])').ajaxSubmit(options);
			});
		}else if($('select[name^=id]').length){
			$(this).bind('change',function(){
				$('form:has(input[name=products_qty])').ajaxSubmit(options);
			});
		};
	});

	// bind the keypress
	var search_timeout = undefined;
	$('input[name=products_qty]').bind('keyup', function() {
		if(search_timeout != undefined) {
	  	clearTimeout(search_timeout);
	  }
	  var $this = this; // save reference to 'this' so we can use it in timeout function
	  search_timeout = setTimeout(function() {
	  	search_timeout = undefined;
			var checked_basename = check_ssl($('form:has(input[name=products_qty])').attr('action'));
			var options = {
				url:checked_basename,
				dataType:'json',
				data:{ajax:'ajax_calls',what:'get_price'},
	      beforeSubmit:  function(){show_loader('show')},  // pre-submit callback
	      success:       function(data){put_it_in(data);}   // post-submit callback
	    };
			$('form:has(input[name=products_qty])').ajaxSubmit(options);
	  }, 750);
	});
}

$(document).ready(function(){
	do_variation_change_init();
});
