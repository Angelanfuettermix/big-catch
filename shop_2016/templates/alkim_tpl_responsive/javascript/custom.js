
$.get('templates/alkim_tpl_responsive/css/javascript.css', function(css) {
  $('head').append('<style type="text/css">' + css + '</style>');
});

var bxSlider = new Array();

$(function() {

  
  $.fn.bxSlider.loadElements = function(selector, callback) {
    callback();
  };
  $('.menu.customers li.toggle').mouseover(function() {
    $(this).find('.pulldown').show();
  });
  $('.menu.customers li.toggle').mouseout(function() {
    $(this).find('.pulldown').hide();
  });
  

  var reloadSliderPager = function(slider) {
    var pager = slider.parent().parent().next('.bx-control').children('.pager');
    var visible = slider.getVisibleItems();
    var current = slider.getCurrentSlide();
    if (slider.children().length > visible) {
      pager.children('.from').text(current + 1);
      pager.children('.to').text(current + visible);
    } else {
      slider.parent().parent().next('.bx-control').hide();
    }
  };
  
  var initContentSlider = function() {
    $('.tabBox ul.products, .crossSelling ul.products, .reverseCrossSelling ul.products, .alsoPurchased ul.products').each(function(i) {
      bxSlider[i] = $(this).bxSlider({
        mode: 'horizontal',
        auto: false,
        autoControls: false,
        controls: false,
        autoHover: true,
        autoDelay: 3000,
        randomStart: false,
        pause: 3000,
        speed: 600,
        minSlides: 1,
        maxSlides: 6,
        moveSlides: 1,
        slideWidth: 225,
        slideMargin: 20,
        adaptiveHeight: false,
        pager: false,
        touchEnabled: true,
        infiniteLoop: false,
        onSlideAfter: function($slideElement, oldIndex, newIndex) {
          reloadSliderPager(bxSlider[i]);
        }
      });
      bxSlider[i].getVisibleItems = function() {
        var sliderWidth = this.parent().width();
        var item = this.children('li');
        var itemWidth = item.width() + parseInt(item.css('marginLeft')) + parseInt(item.css('marginRight'));
        return parseInt(sliderWidth / itemWidth);
      };
      reloadSliderPager(bxSlider[i]);
      // set viewports height
      bxSlider[i].parent('.bx-viewport').css('height', 'auto');


      var control = $(this).parent().parent().parent().find('.bx-control');
      control.children('.prev').click(function(e) {
        e.preventDefault();
        bxSlider[i].goToPrevSlide();
      });
      
      control.children('.next').click(function(e) {
        e.preventDefault();
        bxSlider[i].goToNextSlide();
      });
    });
  };
  
  var initContentAccordeonTabs = function() {
    // init tabs or accordeon by width
    $('.responsiveTabs').each(function() {
      /* BOC by Alkim Media (MK) - 2015-04-23_11:41 */
      //if (1 < $(this).find('> div').length) {
        $(this).responsiveTabs({
          activate: function(e, tab) {
            tab.panel.find('img').unveil();
            jQuery.each(bxSlider, function(i) {
              this.reloadSlider();
            });
          },
          activateState: function(e, state) {} // callback is only called for the first change event
        });
      //}
      /* EOC by Alkim Media (MK) */
    });
  };
  initContentSlider();
    
  /* BOC by Alkim Media (MK) - 2015-04-23_11:50 */
  $('.priceBox > .priceBoxInner').each(function() {
    $(this).width($(this).parent().width());
  });
  /* EOC by Alkim Media (MK) */
  
  setTimeout(initContentAccordeonTabs, 5);

  $('table.responsive').ngResponsiveTables({
    smallPaddingCharNo: 1000,
    mediumPaddingCharNo: 10000,
    largePaddingCharNo: 100000
  });
  
  // burger menu
  $('#burgermenu').click(function() {
    $(this).find('> div').toggle({
      done: function(a) {
        // Scroll to opened menu if its largen than the viewport
        if ('block' == $(a.elem).css('display')) {
          var viewport = {};
          viewport.top = $(window).scrollTop();
          viewport.bottom = viewport.top + $(window).height();
          var bounds = {};
          bounds.top = $(a.elem).offset().top;
          bounds.bottom = bounds.top + $(a.elem).outerHeight();
          if (viewport.bottom < bounds.bottom) {
            $('html, body').animate({
              scrollTop: ($(a.elem).parent().offset().top + $('#header .topBar')[0].offsetHeight)
            }, 'slow');
          }
        }
      }
    });
  });
  
  // checkout
  $('.shippingblock, .paymentblock').each(function(index) {
    if (1 < $(this).find('> h3').length) {
      $(this).accordion({
        header: '> h3',
        active: false,
        collapsible: true,
        heightStyle: 'content',
        create: function(event, ui) {
          // activate checked method
          if (1 == $(event.target).find('> h3 > input[type=radio]:checked').length) {
            $(event.target).accordion('option', 'active', $(event.target).find('> h3 > input[type=radio]:checked').attr('id') - 1);
          }
        },
        /* BOC by Alkim Media (MK) - 2015-04-22_14:15 */
        activate: function(event, ui){
          ui.newHeader.find('input[name="shipping"], input[name="payment"]').prop('checked', true);
        }
        /* EOC by Alkim Media (MK) */
      });
    }
  });
  
  // mobile login
  $('.topBar ul.menu.customers li.login a').click(function(e) {
    e.preventDefault();
    $('.topBar #loginSlide').slideToggle({
      'complete': function() {
        $('.topBar ul.menu.customers li.login i.fa.arrow').toggleClass('fa-arrow-down');
        $('.topBar ul.menu.customers li.login i.fa.arrow').toggleClass('fa-arrow-up');
      }
    });
  });
  
  // desktop login
  $('ul.menu.customers.desktop li.login > a').click(function(e) {
    e.preventDefault();
    $('ul.menu.customers.desktop #loginBox').slideToggle({
      'complete': function() {
        $('ul.menu.customers.desktop li.login i.fa.arrow').toggleClass('fa-arrow-down');
        $('ul.menu.customers.desktop li.login i.fa.arrow').toggleClass('fa-arrow-up');
      }
    });
  });
  
  /* BOC by Alkim Media (MK) - 2015-04-22_14:15 */
  correctStyle();
  $(window).resize(correctStyle);
  /* EOC by Alkim Media (MK) */
  
  var cartCountrySelect = $('.shoppingCart #cart_quantity select[name="country"]');
  if(cartCountrySelect.length > 0){
    cartCountrySelect.unbind();
    $(document).on('change', '.shoppingCart #cart_quantity select[name="country"]' , function(){
        var form = $(this).closest('form');
        var data = form.serialize();
        var url = form.attr('action');
        $.post(url, data, function(data){
            var ot = $(data).find('table.ordertotal');
            $('table.ordertotal').replaceWith(ot);
        });
    });
  }
  
  /* BOC by Alkim Media (MK) - 2015-05-20_21:08 */
  $('.thickbox').magnificPopup({ 
      type: 'iframe'
  });
  $('.imageContainer .thickbox').magnificPopup({ 
      type: 'image',
      gallery:{
        enabled:true
      }
  });
  /* EOC by Alkim Media (MK) */
  $("img").unveil();
});

function correctStyle(){
    el = $('.productDetails h1');
    if(el.length > 0){
        var subEl = el.find('span');
        /* BOC by Alkim Media (TP) - 2015-04-27 - get original width to calculate new */
        if (0 < subEl.length) {
          el.html(subEl.html());
          subEl = Array();
        }
        /* EOC by Alkim Media (TP) - 2015-04-27 - get original width to calculate new */
        var scrollWidth = el[0].scrollWidth;
        var innerWidth = el.innerWidth();
        
        if(scrollWidth > innerWidth){
            var scale = Math.floor(innerWidth/scrollWidth*0.8*10)/10;
            if(subEl.length > 0){
                subEl.css('font-size', scale+'em');
            }else{
                el.contents()
                .filter(function(){return this.nodeType === 3})
                .wrap('<span style="font-size: '+scale+'em;" />');
            }
        }
    }
    /* BOC by Alkim Media (MK) - 2015-05-06_23:15 */
    var el = $('.paymentblock > h3');
    if(el.length > 0){
        $('.paymentblock > h3 .module').css('font-size', '1em');
        var scale = 1;
        el.each(function(){
            var width = $(this).innerWidth();
            var contentWidth = $(this).find('.module').width();
            var t_scale = Math.floor(width/contentWidth*0.8*10)/10;
            if(t_scale < scale){
                scale = t_scale;
            }
        });
        $('.paymentblock > h3 .module').css('font-size', scale+'em');
    }
    /* EOC by Alkim Media (MK) */
    
    /* BOC by Alkim Media (TP) - 2015-04-27 - calculate with margin and padding */
    var sHeight = $('#topmenu .search').height()
      + parseInt($('#topmenu .search').css('padding-top'))
      + parseInt($('#topmenu .search').css('padding-bottom'))
      + parseInt($('#topmenu .search').css('margin-top'))
      + parseInt($('#topmenu .search').css('margin-bottom'));
    
    //if($('#topmenu').height() > 1.8 * $('#topmenu .search').height()){
        //$('#topmenu .search').height($('#topmenu').height()-parseInt($('#topmenu .search').css('padding-top'))-parseInt($('#topmenu .search').css('padding-bottom')));
    if ($('#topmenu').height() > sHeight) {
      var sHeight = $('#topmenu').height()
        - parseInt($('#topmenu .search').css('padding-top'))
        - parseInt($('#topmenu .search').css('padding-bottom'))
        - parseInt($('#topmenu .search').css('margin-top'))
        - parseInt($('#topmenu .search').css('margin-bottom'));
      $('#topmenu .search').height(sHeight);
    /* EOC by Alkim Media (TP) - 2015-04-27 - calculate with margin and padding */
    }else{
        $('#topmenu .search').css('height', 'auto');
    }
    
    var infoContainer = $('.infoContainer');
    var imageContainer = $('.imageContainer');
    if(infoContainer.css('clear') == 'both'){
        if(infoContainer.find('h1').length > 0){
            imageContainer.prepend(infoContainer.find('h1'));
        }
    }else{
        if(imageContainer.find('h1').length > 0){
            infoContainer.prepend(imageContainer.find('h1'));
        }
    }
}
