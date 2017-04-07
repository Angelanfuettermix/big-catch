$(document).ready(function ($) {
  
  var _SlideshowTransitions = [
    {
      $Duration: 1000,
      $Delay: 100,
      $Cols: 8,
      $Rows: 4,
      $FlyDirection: 5,
      $Formation: $JssorSlideshowFormations$.$FormationZigZag,
      $Assembly: 1028,
      $ChessMode: {$Column: 3, $Row: 12},
      $Easing: {$Left: $JssorEasing$.$EaseInCubic, $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseOutQuad},
      $Opacity: 2
    },
    {
      $Duration: 1000,
      $Delay: 100,
      $Cols: 8,
      $Rows: 4,
      $Fade: true,
      $SlideOut: true,
      $FlyDirection: 2,
      $Formation: $JssorSlideshowFormations$.$FormationStraight,
      $Assembly: 264,
      $Easing: $JssorEasing$.$EaseInCubic
    },
    {
      $Duration: 1000,
      $Delay: 100,
      $Rows: 8,
      $Top: true,
      $Fade: true,
      $Zoom: true,
      $Formation: $JssorSlideshowFormations$.$FormationStraight
    },
    {
      $Duration: 1000,
      $Bottom: true,
      $Left: true,
      $Right: true,
      $Top: true,
      $Fade: true,
      $Move: true,
      $Easing: $JssorEasing$.$EaseInQuad
    },
    {
      $Duration: 1500,
      $Cols: 8,
      $Left: true,
      $Fade: true,
      $Move: true
    }
  ];

  var frontpage_slider = new $JssorSlider$('frontpage-slider', {
    $AutoPlay: true,
    $AutoPlayInterval: 6000,                // [Optional] Milliseconds
    $PlayOrientation: 1,                    // [Optional] 1 horizental, 2 vertical 
    $DragOrientation: 0,                    // [Optional] 0 no drag, 1 horizental, 2 vertical, 3 either
    $SlideshowOptions: {                    // Options which specifies enable slideshow or not
      $Class: $JssorSlideshowRunner$,       // Class to create instance of slideshow
      $Transitions: _SlideshowTransitions,  // Transitions to play slide, see jssor slideshow transition builder
      $TransitionsOrder: 1,                 // The way to choose transition to play slide, 1 Sequence, 0 Random
      $ShowLink: 2,                         // 0 After Slideshow, 2 Always
      $ContentMode: false                   // Whether to trait content as slide, otherwise trait an image as slide
    },
    $DirectionNavigatorOptions: {           // [Optional] Options to specify and enable direction navigator or not
      $Class: $JssorDirectionNavigator$,    // [Requried] Class to create direction navigator instance
      $ChanceToShow: 2,                     // [Required] 0 Never, 1 Mouse Over, 2 Always
      $AutoCenter: 0,                       // [Optional] Auto center arrows in parent container, 0 No, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
      $Steps: 1                             // [Optional] Steps to go for each navigation request, default value is 1
    },
    $NavigatorOptions: {                    // [Optional] Options to specify and enable navigator or not
      $Class: $JssorNavigator$,             // [Required] Class to create navigator instance
      $ChanceToShow: 2,                     // [Required] 0 Never, 1 Mouse Over, 2 Always
      $ActionMode: 1,                       // [Optional] 0 None, 1 act by click, 2 act by mouse hover, 3 both, default value is 1
      $AutoCenter: 1,                       // [Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
      $Steps: 1,                            // [Optional] Steps to go for each navigation request, default value is 1
      $Lanes: 1,                            // [Optional] Specify lanes to arrange items, default value is 1
      $SpacingX: 40,                        // [Optional] Horizontal space between each item in pixel, default value is 0
      $SpacingY: 0,                         // [Optional] Vertical space between each item in pixel, default value is 0
      $Orientation: 1                       // [Optional] The orientation of the navigator, 1 horizontal, 2 vertical, default value is 1
    }
  });
  
  // responsive code begin
  function ScaleFrontpageSlider() {
    var parentWidth = $('#frontpage-slider').parent().width();
    if (parentWidth) {
      frontpage_slider.$SetScaleWidth(parentWidth);
    } else {
      window.setTimeout(ScaleFrontpageSlider, 30);
    }
  }
  // Scale slider after document ready
  ScaleFrontpageSlider();
  if (!navigator.userAgent.match(/(iPhone|iPod|iPad|BlackBerry|IEMobile)/)) {
    // Capture window resize event
    $(window).bind('resize', ScaleFrontpageSlider);
  }
  // responsive code end
  
});