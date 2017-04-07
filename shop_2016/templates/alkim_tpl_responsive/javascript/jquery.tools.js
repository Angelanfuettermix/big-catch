/*!
 * jQuery Tools v1.2.6 - The missing UI library for the Web
 * 
 * overlay/overlay.js
 * overlay/overlay.apple.js
 * scrollable/scrollable.js
 * scrollable/scrollable.autoscroll.js
 * scrollable/scrollable.navigator.js
 * tooltip/tooltip.js
 * tooltip/tooltip.dynamic.js
 * tooltip/tooltip.slide.js
 * 
 * NO COPYRIGHTS OR LICENSES. DO WHAT YOU LIKE.
 * 
 * http://flowplayer.org/tools/
 * 
 */
(function(a){a.tools=a.tools||{version:"v1.2.6"},a.tools.overlay={addEffect:function(a,b,d){c[a]=[b,d]},conf:{close:null,closeOnClick:!0,closeOnEsc:!0,closeSpeed:"fast",effect:"default",fixed:!a.browser.msie||a.browser.version>6,left:"center",load:!1,mask:null,oneInstance:!0,speed:"normal",target:null,top:"10%"}};var b=[],c={};a.tools.overlay.addEffect("default",function(b,c){var d=this.getConf(),e=a(window);d.fixed||(b.top+=e.scrollTop(),b.left+=e.scrollLeft()),b.position=d.fixed?"fixed":"absolute",this.getOverlay().css(b).fadeIn(d.speed,c)},function(a){this.getOverlay().fadeOut(this.getConf().closeSpeed,a)});function d(d,e){var f=this,g=d.add(f),h=a(window),i,j,k,l=a.tools.expose&&(e.mask||e.expose),m=Math.random().toString().slice(10);l&&(typeof l=="string"&&(l={color:l}),l.closeOnClick=l.closeOnEsc=!1);var n=e.target||d.attr("rel");j=n?a(n):null||d;if(!j.length)throw"Could not find Overlay: "+n;d&&d.index(j)==-1&&d.click(function(a){f.load(a);return a.preventDefault()}),a.extend(f,{load:function(d){if(f.isOpened())return f;var i=c[e.effect];if(!i)throw"Overlay: cannot find effect : \""+e.effect+"\"";e.oneInstance&&a.each(b,function(){this.close(d)}),d=d||a.Event(),d.type="onBeforeLoad",g.trigger(d);if(d.isDefaultPrevented())return f;k=!0,l&&a(j).expose(l);var n=e.top,o=e.left,p=j.outerWidth({margin:!0}),q=j.outerHeight({margin:!0});typeof n=="string"&&(n=n=="center"?Math.max((h.height()-q)/2,0):parseInt(n,10)/100*h.height()),o=="center"&&(o=Math.max((h.width()-p)/2,0)),i[0].call(f,{top:n,left:o},function(){k&&(d.type="onLoad",g.trigger(d))}),l&&e.closeOnClick&&a.mask.getMask().one("click",f.close),e.closeOnClick&&a(document).bind("click."+m,function(b){a(b.target).parents(j).length||f.close(b)}),e.closeOnEsc&&a(document).bind("keydown."+m,function(a){a.keyCode==27&&f.close(a)});return f},close:function(b){if(!f.isOpened())return f;b=b||a.Event(),b.type="onBeforeClose",g.trigger(b);if(!b.isDefaultPrevented()){k=!1,c[e.effect][1].call(f,function(){b.type="onClose",g.trigger(b)}),a(document).unbind("click."+m).unbind("keydown."+m),l&&a.mask.close();return f}},getOverlay:function(){return j},getTrigger:function(){return d},getClosers:function(){return i},isOpened:function(){return k},getConf:function(){return e}}),a.each("onBeforeLoad,onStart,onLoad,onBeforeClose,onClose".split(","),function(b,c){a.isFunction(e[c])&&a(f).bind(c,e[c]),f[c]=function(b){b&&a(f).bind(c,b);return f}}),i=j.find(e.close||".close"),!i.length&&!e.close&&(i=a("<a class=\"close\"></a>"),j.prepend(i)),i.click(function(a){f.close(a)}),e.load&&f.load()}a.fn.overlay=function(c){var e=this.data("overlay");if(e)return e;a.isFunction(c)&&(c={onBeforeLoad:c}),c=a.extend(!0,{},a.tools.overlay.conf,c),this.each(function(){e=new d(a(this),c),b.push(e),a(this).data("overlay",e)});return c.api?e:this}})(jQuery);
(function(a){var b=a.tools.overlay,c=a(window);a.extend(b.conf,{start:{top:null,left:null},fadeInSpeed:"fast",zIndex:9999});function d(a){var b=a.offset();return{top:b.top+a.height()/2,left:b.left+a.width()/2}}var e=function(b,e){var f=this.getOverlay(),g=this.getConf(),h=this.getTrigger(),i=this,j=f.outerWidth({margin:!0}),k=f.data("img"),l=g.fixed?"fixed":"absolute";if(!k){var m=f.css("backgroundImage");if(!m)throw"background-image CSS property not set for overlay";m=m.slice(m.indexOf("(")+1,m.indexOf(")")).replace(/\"/g,""),f.css("backgroundImage","none"),k=a("<img src=\""+m+"\"/>"),k.css({border:0,display:"none"}).width(j),a("body").append(k),f.data("img",k)}var n=g.start.top||Math.round(c.height()/2),o=g.start.left||Math.round(c.width()/2);if(h){var p=d(h);n=p.top,o=p.left}g.fixed?(n-=c.scrollTop(),o-=c.scrollLeft()):(b.top+=c.scrollTop(),b.left+=c.scrollLeft()),k.css({position:"absolute",top:n,left:o,width:0,zIndex:g.zIndex}).show(),b.position=l,f.css(b),k.animate({top:f.css("top"),left:f.css("left"),width:j},g.speed,function(){f.css("zIndex",g.zIndex+1).fadeIn(g.fadeInSpeed,function(){i.isOpened()&&!a(this).index(f)?e.call():f.hide()})}).css("position",l)},f=function(b){var e=this.getOverlay().hide(),f=this.getConf(),g=this.getTrigger(),h=e.data("img"),i={top:f.start.top,left:f.start.left,width:0};g&&a.extend(i,d(g)),f.fixed&&h.css({position:"absolute"}).animate({top:"+="+c.scrollTop(),left:"+="+c.scrollLeft()},0),h.animate(i,f.closeSpeed,b)};b.addEffect("apple",e,f)})(jQuery);
/**
* @license
* jQuery Tools @VERSION Scrollable - New wave UI design
*
* NO COPYRIGHTS OR LICENSES. DO WHAT YOU LIKE.
*
* http://flowplayer.org/tools/scrollable.html
*
* Since: March 2008
* Date: @DATE
*/
(function($) {

// static constructs
$.tools = $.tools || {version: '@VERSION'};

$.tools.scrollable = {

conf: {
activeClass: 'active',
circular: false,
clonedClass: 'cloned',
disabledClass: 'disabled',
easing: 'swing',
initialIndex: 0,
item: '> *',
items: '.items',
keyboard: true,
mousewheel: false,
next: '.next',
prev: '.prev',
size: 1,
speed: 400,
vertical: false,
touch: true,
wheelSpeed: 0
}
};

// get hidden element's width or height even though it's hidden
function dim(el, key) {
var v = parseInt(el.css(key), 10);
if (v) { return v; }
var s = el[0].currentStyle;
return s && s.width && parseInt(s.width, 10);
}

function find(root, query) {
var el = $(query);
return el.length < 2 ? el : root.parent().find(query);
}

var current;

// constructor
function Scrollable(root, conf) {

// current instance
var self = this,
fire = root.add(self),
itemWrap = root.children(),
index = 0,
vertical = conf.vertical;

if (!current) { current = self; }
if (itemWrap.length > 1) { itemWrap = $(conf.items, root); }


// in this version circular not supported when size > 1
if (conf.size > 1) { conf.circular = false; }

// methods
$.extend(self, {

getConf: function() {
return conf;
},

getIndex: function() {
return index;
},

getSize: function() {
return self.getItems().size();
},

getNaviButtons: function() {
return prev.add(next);
},

getRoot: function() {
return root;
},

getItemWrap: function() {
return itemWrap;
},

getItems: function() {
return itemWrap.find(conf.item).not("." + conf.clonedClass);
},

move: function(offset, time) {
return self.seekTo(index + offset, time);
},

next: function(time) {
return self.move(conf.size, time);
},

prev: function(time) {
return self.move(-conf.size, time);
},

begin: function(time) {
return self.seekTo(0, time);
},

end: function(time) {
return self.seekTo(self.getSize() -1, time);
},

focus: function() {
current = self;
return self;
},

addItem: function(item) {
item = $(item);

if (!conf.circular) {
itemWrap.append(item);
next.removeClass("disabled");

} else {
itemWrap.children().last().before(item);
itemWrap.children().first().replaceWith(item.clone().addClass(conf.clonedClass));
}

fire.trigger("onAddItem", [item]);
return self;
},


/* all seeking functions depend on this */
seekTo: function(i, time, fn) {

// ensure numeric index
if (!i.jquery) { i *= 1; }

// avoid seeking from end clone to the beginning
if (conf.circular && i === 0 && index == -1 && time !== 0) { return self; }

// check that index is sane
if (!conf.circular && i < 0 || i > self.getSize() || i < -1) { return self; }
scrollable_position = i;
var item = i;

if (i.jquery) {
i = self.getItems().index(i);

} else {
item = self.getItems().eq(i);
}

// onBeforeSeek
var e = $.Event("onBeforeSeek");
if (!fn) {
fire.trigger(e, [i, time]);
processScrollableNavi(false);
if (e.isDefaultPrevented() || !item.length) { return self; }
}

var props = vertical ? {top: -item.position().top} : {left: -item.position().left};

index = i;
current = self;
if (time === undefined) { time = conf.speed; }

itemWrap.animate(props, time, conf.easing, fn || function() {
fire.trigger("onSeek", [i]);
});
processScrollableNavi()
return self;
}

});

// callbacks
$.each(['onBeforeSeek', 'onSeek', 'onAddItem'], function(i, name) {

// configuration
if ($.isFunction(conf[name])) {
$(self).on(name, conf[name]);
}

self[name] = function(fn) {
if (fn) { $(self).on(name, fn); }
return self;
};
});

// circular loop
if (conf.circular) {

var cloned1 = self.getItems().slice(-1).clone().prependTo(itemWrap),
cloned2 = self.getItems().eq(1).clone().appendTo(itemWrap);

cloned1.add(cloned2).addClass(conf.clonedClass);

self.onBeforeSeek(function(e, i, time) {

if (e.isDefaultPrevented()) { return; }

/*
1. animate to the clone without event triggering
2. seek to correct position with 0 speed
*/
if (i == -1) {
self.seekTo(cloned1, time, function() {
self.end(0);
});
return e.preventDefault();

} else if (i == self.getSize()) {
self.seekTo(cloned2, time, function() {
self.begin(0);
});
}

});

// seek over the cloned item

// if the scrollable is hidden the calculations for seekTo position
// will be incorrect (eg, if the scrollable is inside an overlay).
// ensure the elements are shown, calculate the correct position,
// then re-hide the elements. This must be done synchronously to
// prevent the hidden elements being shown to the user.

// See: https://github.com/jquerytools/jquerytools/issues#issue/87

var hidden_parents = root.parents().add(root).filter(function () {
if ($(this).css('display') === 'none') {
return true;
}
});
if (hidden_parents.length) {
hidden_parents.show();
self.seekTo(0, 0, function() {});
hidden_parents.hide();
}
else {
self.seekTo(0, 0, function() {});
}

}

// next/prev buttons
var prev = find(root, conf.prev).click(function(e) { e.stopPropagation(); self.prev(); }),
next = find(root, conf.next).click(function(e) { e.stopPropagation(); self.next(); });

if (!conf.circular) {
self.onBeforeSeek(function(e, i) {
setTimeout(function() {
if (!e.isDefaultPrevented()) {
prev.toggleClass(conf.disabledClass, i <= 0);
next.toggleClass(conf.disabledClass, i >= self.getSize() -1);
}
}, 1);
});

if (!conf.initialIndex) {
prev.addClass(conf.disabledClass);
}
}

if (self.getSize() < 2) {
prev.add(next).addClass(conf.disabledClass);
}

// mousewheel support
if (conf.mousewheel && $.fn.mousewheel) {
root.mousewheel(function(e, delta) {
if (conf.mousewheel) {
self.move(delta < 0 ? 1 : -1, conf.wheelSpeed || 50);
return false;
}
});
}

// touch event
if (conf.touch) {
var touch = {};

itemWrap[0].ontouchstart = function(e) {
var t = e.touches[0];
touch.x = t.clientX;
touch.y = t.clientY;
};

itemWrap[0].ontouchmove = function(e) {

// only deal with one finger
if (e.touches.length == 1 && !itemWrap.is(":animated")) {
var t = e.touches[0],
deltaX = touch.x - t.clientX,
deltaY = touch.y - t.clientY;

self[vertical && deltaY > 0 || !vertical && deltaX > 0 ? 'next' : 'prev']();
e.preventDefault();
}
};
}

if (conf.keyboard) {

$(document).on("keydown.scrollable", function(evt) {

// skip certain conditions
if (!conf.keyboard || evt.altKey || evt.ctrlKey || evt.metaKey || $(evt.target).is(":input")) {
return;
}

// does this instance have focus?
if (conf.keyboard != 'static' && current != self) { return; }

var key = evt.keyCode;

if (vertical && (key == 38 || key == 40)) {
self.move(key == 38 ? -1 : 1);
return evt.preventDefault();
}

if (!vertical && (key == 37 || key == 39)) {
self.move(key == 37 ? -1 : 1);
return evt.preventDefault();
}

});
}

// initial index
if (conf.initialIndex) {
self.seekTo(conf.initialIndex, 0, function() {});
}
}


// jQuery plugin implementation
$.fn.scrollable = function(conf) {

// already constructed --> return API
var el = this.data("scrollable");
//if (el) { return el; }

conf = $.extend({}, $.tools.scrollable.conf, conf);

this.each(function() {
el = new Scrollable($(this), conf);
$(this).data("scrollable", el);
});

return conf.api ? el: this;

};


})(jQuery);
(function(a){var b=a.tools.scrollable;b.autoscroll={conf:{autoplay:!0,interval:3e3,autopause:!0}},a.fn.autoscroll=function(c){typeof c=="number"&&(c={interval:c});var d=a.extend({},b.autoscroll.conf,c),e;this.each(function(){var b=a(this).data("scrollable"),c=b.getRoot(),f,g=!1;function h(){f=setTimeout(function(){b.next()},d.interval)}b&&(e=b),b.play=function(){f||(g=!1,c.bind("onSeek",h),h())},b.pause=function(){f=clearTimeout(f),c.unbind("onSeek",h)},b.resume=function(){g||b.play()},b.stop=function(){g=!0,b.pause()},d.autopause&&c.add(b.getNaviButtons()).hover(b.pause,b.resume),d.autoplay&&b.play()});return d.api?e:this}})(jQuery);
(function(a){var b=a.tools.scrollable;b.navigator={conf:{navi:".navi",naviItem:null,activeClass:"active",indexed:!1,idPrefix:null,history:!1}};function c(b,c){var d=a(c);return d.length<2?d:b.parent().find(c)}a.fn.navigator=function(d){typeof d=="string"&&(d={navi:d}),d=a.extend({},b.navigator.conf,d);var e;this.each(function(){var b=a(this).data("scrollable"),f=d.navi.jquery?d.navi:c(b.getRoot(),d.navi),g=b.getNaviButtons(),h=d.activeClass,i=d.history&&history.pushState,j=b.getConf().size;b&&(e=b),b.getNaviButtons=function(){return g.add(f)},i&&(history.pushState({i:0}),a(window).bind("popstate",function(a){var c=a.originalEvent.state;c&&b.seekTo(c.i)}));function k(a,c,d){b.seekTo(c),d.preventDefault(),i&&history.pushState({i:c})}function l(){return f.find(d.naviItem||"> *")}function m(b){var c=a("<"+(d.naviItem||"a")+"/>").click(function(c){k(a(this),b,c)});b===0&&c.addClass(h),d.indexed&&c.text(b+1),d.idPrefix&&c.attr("id",d.idPrefix+b);return c.appendTo(f)}l().length?l().each(function(b){a(this).click(function(c){k(a(this),b,c)})}):a.each(b.getItems(),function(a){a%j==0&&m(a)}),b.onBeforeSeek(function(a,b){setTimeout(function(){if(!a.isDefaultPrevented()){var c=b/j,d=l().eq(c);d.length&&l().removeClass(h).eq(c).addClass(h)}},1)}),b.onAddItem(function(a,c){var d=b.getItems().index(c);d%j==0&&m(d)})});return d.api?e:this}})(jQuery);
(function($) {
// static constructs
$.tools = $.tools || {version: '@VERSION'};

$.tools.tooltip = {

conf: {

// default effect variables
effect: 'toggle',
fadeOutSpeed: "fast",
predelay: 0,
delay: 30,
opacity: 1,
tip: 0,
            fadeIE: false, // enables fade effect in IE

// 'top', 'bottom', 'right', 'left', 'center'
position: ['top', 'center'],
offset: [0, 0],
relative: false,
cancelDefault: true,

// type to event mapping
events: {
def: "mouseenter,mouseleave",
input: "focus,blur",
widget: "focus mouseenter,blur mouseleave",
tooltip: "mouseenter,mouseleave"
},

// 1.2
layout: '<div/>',
tipClass: 'tooltip'
},

addEffect: function(name, loadFn, hideFn) {
effects[name] = [loadFn, hideFn];
}
};


var effects = {
toggle: [
function(done) {
var conf = this.getConf(), tip = this.getTip(), o = conf.opacity;
if (o < 1) { tip.css({opacity: o}); }
tip.show();
done.call();
},

function(done) {
this.getTip().hide();
done.call();
}
],

fade: [
function(done) {
var conf = this.getConf();
if (!$.browser.msie || conf.fadeIE) {
this.getTip().fadeTo(conf.fadeInSpeed, conf.opacity, done);
}
else {
this.getTip().show();
done();
}
},
function(done) {
var conf = this.getConf();
if (!$.browser.msie || conf.fadeIE) {
this.getTip().fadeOut(conf.fadeOutSpeed, done);
}
else {
this.getTip().hide();
done();
}
}
]
};


/* calculate tip position relative to the trigger */
function getPosition(trigger, tip, conf) {


// get origin top/left position
var top = conf.relative ? trigger.position().top : trigger.offset().top,
left = conf.relative ? trigger.position().left : trigger.offset().left,
pos = conf.position[0];

top -= tip.outerHeight() - conf.offset[0];
left += trigger.outerWidth() + conf.offset[1];

// iPad position fix
if (/iPad/i.test(navigator.userAgent)) {
top -= $(window).scrollTop();
}

// adjust Y
var height = tip.outerHeight() + trigger.outerHeight();
if (pos == 'center') { top += height / 2; }
if (pos == 'bottom') { top += height; }


// adjust X
pos = conf.position[1];
var width = tip.outerWidth() + trigger.outerWidth();
if (pos == 'center') { left -= width / 2; }
if (pos == 'left') { left -= width; }

return {top: top, left: left};
}



function Tooltip(trigger, conf) {

var self = this,
fire = trigger.add(self),
tip,
timer = 0,
pretimer = 0,
title = trigger.attr("title"),
tipAttr = trigger.attr("data-tooltip"),
effect = effects[conf.effect],
shown,

// get show/hide configuration
isInput = trigger.is(":input"),
isWidget = isInput && trigger.is(":checkbox, :radio, select, :button, :submit"),
type = trigger.attr("type"),
evt = conf.events[type] || conf.events[isInput ? (isWidget ? 'widget' : 'input') : 'def'];


// check that configuration is sane
if (!effect) { throw "Nonexistent effect \"" + conf.effect + "\""; }

evt = evt.split(/,\s*/);
if (evt.length != 2) { throw "Tooltip: bad events configuration for " + type; }


// trigger --> show
trigger.bind(evt[0], function(e) {

clearTimeout(timer);
if (conf.predelay) {
pretimer = setTimeout(function() { self.show(e); }, conf.predelay);

} else {
self.show(e);
}

// trigger --> hide
}).bind(evt[1], function(e) {
clearTimeout(pretimer);
if (conf.delay) {
timer = setTimeout(function() { self.hide(e); }, conf.delay);

} else {
self.hide(e);
}

});


// remove default title
if (title && conf.cancelDefault) {
trigger.removeAttr("title");
trigger.data("title", title);
}

$.extend(self, {

show: function(e) {

// tip not initialized yet
//if (!tip) {
/*
// data-tooltip
if (tipAttr) {
tip = $(tipAttr);

// single tip element for all
} else if (conf.tip) {
tip = $(conf.tip).eq(0);

// autogenerated tooltip
} else if (title) {
tip = $(conf.layout).addClass(conf.tipClass).appendTo(document.body)
.hide().append(title);

// manual tooltip
} else {*/
tip = trigger.next();

$('#tooltip_container').html(tip.html());
tip = $('#tooltip_container');
if (!tip.length) { tip = trigger.parent().next(); }
//}

if (!tip.length) { throw "Cannot find tooltip for " + trigger; }
//}

if (self.isShown()) { return self; }

// stop previous animation
tip.stop(true, true);

// get position
var pos = getPosition(trigger, tip, conf);

// restore title for single tooltip element
if (conf.tip) {
tip.html(trigger.data("title"));
}

// onBeforeShow
e = $.Event();
e.type = "onBeforeShow";
fire.trigger(e, [pos]);
if (e.isDefaultPrevented()) { return self; }


// onBeforeShow may have altered the configuration
pos = getPosition(trigger, tip, conf);

// set position
tip.css({position:'absolute', top: pos.top, left: pos.left});

shown = true;

// invoke effect
effect[0].call(self, function() {
e.type = "onShow";
shown = 'full';
fire.trigger(e);
});


// tooltip events
var event = conf.events.tooltip.split(/,\s*/);

if (!tip.data("__set")) {

tip.off(event[0]).on(event[0], function() {
clearTimeout(timer);
clearTimeout(pretimer);
});

if (event[1] && !trigger.is("input:not(:checkbox, :radio), textarea")) {
tip.off(event[1]).on(event[1], function(e) {

// being moved to the trigger element
if (e.relatedTarget != trigger[0]) {
trigger.trigger(evt[1].split(" ")[0]);
}
});
}

// bind agein for if same tip element
if (!conf.tip) tip.data("__set", true);
}

return self;
},

hide: function(e) {

if (!tip || !self.isShown()) { return self; }

// onBeforeHide
e = $.Event();
e.type = "onBeforeHide";
fire.trigger(e);
if (e.isDefaultPrevented()) { return; }

shown = false;

effects[conf.effect][1].call(self, function() {
e.type = "onHide";
fire.trigger(e);
});

return self;
},

isShown: function(fully) {
return fully ? shown == 'full' : shown;
},

getConf: function() {
return conf;
},

getTip: function() {
return tip;
},

getTrigger: function() {
return trigger;
}

});

// callbacks
$.each("onHide,onBeforeShow,onShow,onBeforeHide".split(","), function(i, name) {

// configuration
if ($.isFunction(conf[name])) {
$(self).on(name, conf[name]);
}

// API
self[name] = function(fn) {
if (fn) { $(self).on(name, fn); }
return self;
};
});

}


// jQuery plugin implementation
$.fn.tooltip = function(conf) {

// return existing instance
var api = this.data("tooltip");
if (api) { return api; }

conf = $.extend(true, {}, $.tools.tooltip.conf, conf);

// position can also be given as string
if (typeof conf.position == 'string') {
conf.position = conf.position.split(/,?\s/);
}

// install tooltip for each entry in jQuery object
this.each(function() {
api = new Tooltip($(this), conf);
$(this).data("tooltip", api);
});

return conf.api ? api: this;
};

}) (jQuery);
(function(a){var b=a.tools.tooltip;b.dynamic={conf:{classNames:"top right bottom left"}};function c(b){var c=a(window),d=c.width()+c.scrollLeft(),e=c.height()+c.scrollTop();return[b.offset().top<=c.scrollTop(),d<=b.offset().left+b.width(),e<=b.offset().top+b.height(),c.scrollLeft()>=b.offset().left]}function d(a){var b=a.length;while(b--)if(a[b])return!1;return!0}a.fn.dynamic=function(e){typeof e=="number"&&(e={speed:e}),e=a.extend({},b.dynamic.conf,e);var f=a.extend(!0,{},e),g=e.classNames.split(/\s/),h;this.each(function(){var b=a(this).tooltip().onBeforeShow(function(b,e){var i=this.getTip(),j=this.getConf();h||(h=[j.position[0],j.position[1],j.offset[0],j.offset[1],a.extend({},j)]),a.extend(j,h[4]),j.position=[h[0],h[1]],j.offset=[h[2],h[3]],i.css({visibility:"hidden",position:"absolute",top:e.top,left:e.left}).show();var k=a.extend(!0,{},f),l=c(i);if(!d(l)){l[2]&&(a.extend(j,k.top),j.position[0]="top",i.addClass(g[0])),l[3]&&(a.extend(j,k.right),j.position[1]="right",i.addClass(g[1])),l[0]&&(a.extend(j,k.bottom),j.position[0]="bottom",i.addClass(g[2])),l[1]&&(a.extend(j,k.left),j.position[1]="left",i.addClass(g[3]));if(l[0]||l[2])j.offset[0]*=-1;if(l[1]||l[3])j.offset[1]*=-1}i.css({visibility:"visible"}).hide()});b.onBeforeShow(function(){var a=this.getConf(),b=this.getTip();setTimeout(function(){a.position=[h[0],h[1]],a.offset=[h[2],h[3]]},0)}),b.onHide(function(){var a=this.getTip();a.removeClass(e.classNames)}),ret=b});return e.api?ret:this}})(jQuery);
(function(a){var b=a.tools.tooltip;a.extend(b.conf,{direction:"up",bounce:!1,slideOffset:10,slideInSpeed:200,slideOutSpeed:200,slideFade:!a.browser.msie});var c={up:["-","top"],down:["+","top"],left:["-","left"],right:["+","left"]};b.addEffect("slide",function(a){var b=this.getConf(),d=this.getTip(),e=b.slideFade?{opacity:b.opacity}:{},f=c[b.direction]||c.up;e[f[1]]=f[0]+"="+b.slideOffset,b.slideFade&&d.css({opacity:0}),d.show().animate(e,b.slideInSpeed,a)},function(b){var d=this.getConf(),e=d.slideOffset,f=d.slideFade?{opacity:0}:{},g=c[d.direction]||c.up,h=""+g[0];d.bounce&&(h=h=="+"?"-":"+"),f[g[1]]=h+"="+e,this.getTip().animate(f,d.slideOutSpeed,function(){a(this).hide(),b.call()})})})(jQuery);
