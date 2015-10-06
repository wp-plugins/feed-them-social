!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):e("object"==typeof exports?require("jquery"):window.jQuery||window.Zepto)}(function(e){var t,i,a,n,o,r,s="Close",l="BeforeClose",c="AfterClose",d="BeforeAppend",u="MarkupParse",p="Open",f="Change",m="mfp",g="."+m,h="mfp-ready",v="mfp-removing",y="mfp-prevent-close",C=function(){},b=!!window.jQuery,w=e(window),I=function(e,i){t.ev.on(m+e+g,i)},k=function(t,i,a,n){var o=document.createElement("div");return o.className="mfp-"+t,a&&(o.innerHTML=a),n?i&&i.appendChild(o):(o=e(o),i&&o.appendTo(i)),o},x=function(i,a){t.ev.triggerHandler(m+i,a),t.st.callbacks&&(i=i.charAt(0).toLowerCase()+i.slice(1),t.st.callbacks[i]&&t.st.callbacks[i].apply(t,e.isArray(a)?a:[a]))},T=function(i){return i===r&&t.currTemplate.closeBtn||(t.currTemplate.closeBtn=e(t.st.closeMarkup.replace("%title%",t.st.tClose)),r=i),t.currTemplate.closeBtn},E=function(){e.magnificPopup.instance||(t=new C,t.init(),e.magnificPopup.instance=t)},P=function(){var e=document.createElement("p").style,t=["ms","O","Moz","Webkit"];if(void 0!==e.transition)return!0;for(;t.length;)if(t.pop()+"Transition"in e)return!0;return!1};C.prototype={constructor:C,init:function(){var i=navigator.appVersion;t.isIE7=-1!==i.indexOf("MSIE 7."),t.isIE8=-1!==i.indexOf("MSIE 8."),t.isLowIE=t.isIE7||t.isIE8,t.isAndroid=/android/gi.test(i),t.isIOS=/iphone|ipad|ipod/gi.test(i),t.supportsTransition=P(),t.probablyMobile=t.isAndroid||t.isIOS||/(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent),a=e(document),t.popupsCache={}},open:function(i){var n;if(i.isObj===!1){t.items=i.items.toArray(),t.index=0;var r,s=i.items;for(n=0;n<s.length;n++)if(r=s[n],r.parsed&&(r=r.el[0]),r===i.el[0]){t.index=n;break}}else t.items=e.isArray(i.items)?i.items:[i.items],t.index=i.index||0;if(t.isOpen)return void t.updateItemHTML();t.types=[],o="",t.ev=i.mainEl&&i.mainEl.length?i.mainEl.eq(0):a,i.key?(t.popupsCache[i.key]||(t.popupsCache[i.key]={}),t.currTemplate=t.popupsCache[i.key]):t.currTemplate={},t.st=e.extend(!0,{},e.magnificPopup.defaults,i),t.fixedContentPos="auto"===t.st.fixedContentPos?!t.probablyMobile:t.st.fixedContentPos,t.st.modal&&(t.st.closeOnContentClick=!1,t.st.closeOnBgClick=!1,t.st.showCloseBtn=!1,t.st.enableEscapeKey=!1),t.bgOverlay||(t.bgOverlay=k("bg").on("click"+g,function(){t.close()}),t.wrap=k("wrap").attr("tabindex",-1).on("click"+g,function(e){t._checkIfClose(e.target)&&t.close()}),t.container=k("container",t.wrap)),t.contentContainer=k("content"),t.st.preloader&&(t.preloader=k("preloader",t.container,t.st.tLoading));var l=e.magnificPopup.modules;for(n=0;n<l.length;n++){var c=l[n];c=c.charAt(0).toUpperCase()+c.slice(1),t["init"+c].call(t)}x("BeforeOpen"),t.st.showCloseBtn&&(t.st.closeBtnInside?(I(u,function(e,t,i,a){i.close_replaceWith=T(a.type)}),o+=" mfp-close-btn-in"):t.wrap.append(T())),t.st.alignTop&&(o+=" mfp-align-top"),t.wrap.css(t.fixedContentPos?{overflow:t.st.overflowY,overflowX:"hidden",overflowY:t.st.overflowY}:{top:w.scrollTop(),position:"absolute"}),(t.st.fixedBgPos===!1||"auto"===t.st.fixedBgPos&&!t.fixedContentPos)&&t.bgOverlay.css({height:a.height(),position:"absolute"}),t.st.enableEscapeKey&&a.on("keyup"+g,function(e){27===e.keyCode&&t.close()}),w.on("resize"+g,function(){t.updateSize()}),t.st.closeOnContentClick||(o+=" mfp-auto-cursor"),o&&t.wrap.addClass(o);var d=t.wH=w.height(),f={};if(t.fixedContentPos&&t._hasScrollBar(d)){var m=t._getScrollbarSize();m&&(f.marginRight=m)}t.fixedContentPos&&(t.isIE7?e("body, html").css("overflow","hidden"):f.overflow="hidden");var v=t.st.mainClass;return t.isIE7&&(v+=" mfp-ie7"),v&&t._addClassToMFP(v),t.updateItemHTML(),x("BuildControls"),e("html").css(f),t.bgOverlay.add(t.wrap).prependTo(t.st.prependTo||e(document.body)),t._lastFocusedEl=document.activeElement,setTimeout(function(){t.content?(t._addClassToMFP(h),t._setFocus()):t.bgOverlay.addClass(h),a.on("focusin"+g,t._onFocusIn)},16),t.isOpen=!0,t.updateSize(d),x(p),i},close:function(){t.isOpen&&(x(l),t.isOpen=!1,t.st.removalDelay&&!t.isLowIE&&t.supportsTransition?(t._addClassToMFP(v),setTimeout(function(){t._close()},t.st.removalDelay)):t._close())},_close:function(){x(s);var i=v+" "+h+" ";if(t.bgOverlay.detach(),t.wrap.detach(),t.container.empty(),t.st.mainClass&&(i+=t.st.mainClass+" "),t._removeClassFromMFP(i),t.fixedContentPos){var n={marginRight:""};t.isIE7?e("body, html").css("overflow",""):n.overflow="",e("html").css(n)}a.off("keyup"+g+" focusin"+g),t.ev.off(g),t.wrap.attr("class","mfp-wrap").removeAttr("style"),t.bgOverlay.attr("class","mfp-bg"),t.container.attr("class","mfp-container"),!t.st.showCloseBtn||t.st.closeBtnInside&&t.currTemplate[t.currItem.type]!==!0||t.currTemplate.closeBtn&&t.currTemplate.closeBtn.detach(),t._lastFocusedEl&&e(t._lastFocusedEl).focus(),t.currItem=null,t.content=null,t.currTemplate=null,t.prevHeight=0,x(c)},updateSize:function(e){if(t.isIOS){var i=document.documentElement.clientWidth/window.innerWidth,a=window.innerHeight*i;t.wrap.css("height",a),t.wH=a}else t.wH=e||w.height();t.fixedContentPos||t.wrap.css("height",t.wH),x("Resize")},updateItemHTML:function(){var i=t.items[t.index];t.contentContainer.detach(),t.content&&t.content.detach(),i.parsed||(i=t.parseEl(t.index));var a=i.type;if(x("BeforeChange",[t.currItem?t.currItem.type:"",a]),t.currItem=i,!t.currTemplate[a]){var o=t.st[a]?t.st[a].markup:!1;x("FirstMarkupParse",o),t.currTemplate[a]=o?e(o):!0}n&&n!==i.type&&t.container.removeClass("mfp-"+n+"-holder");var r=t["get"+a.charAt(0).toUpperCase()+a.slice(1)](i,t.currTemplate[a]);t.appendContent(r,a),i.preloaded=!0,x(f,i),n=i.type,t.container.prepend(t.contentContainer),x("AfterChange")},appendContent:function(e,i){t.content=e,e?t.st.showCloseBtn&&t.st.closeBtnInside&&t.currTemplate[i]===!0?t.content.find(".mfp-close").length||t.content.append(T()):t.content=e:t.content="",x(d),t.container.addClass("mfp-"+i+"-holder"),t.contentContainer.append(t.content)},parseEl:function(i){var a,n=t.items[i];if(n.tagName?n={el:e(n)}:(a=n.type,n={data:n,src:n.src}),n.el){for(var o=t.types,r=0;r<o.length;r++)if(n.el.hasClass("mfp-"+o[r])){a=o[r];break}n.src=n.el.attr("data-mfp-src"),n.src||(n.src=n.el.attr("href"))}return n.type=a||t.st.type||"inline",n.index=i,n.parsed=!0,t.items[i]=n,x("ElementParse",n),t.items[i]},addGroup:function(e,i){var a=function(a){a.mfpEl=this,t._openClick(a,e,i)};i||(i={});var n="click.magnificPopup";i.mainEl=e,i.items?(i.isObj=!0,e.off(n).on(n,a)):(i.isObj=!1,i.delegate?e.off(n).on(n,i.delegate,a):(i.items=e,e.off(n).on(n,a)))},_openClick:function(i,a,n){var o=void 0!==n.midClick?n.midClick:e.magnificPopup.defaults.midClick;if(o||2!==i.which&&!i.ctrlKey&&!i.metaKey){var r=void 0!==n.disableOn?n.disableOn:e.magnificPopup.defaults.disableOn;if(r)if(e.isFunction(r)){if(!r.call(t))return!0}else if(w.width()<r)return!0;i.type&&(i.preventDefault(),t.isOpen&&i.stopPropagation()),n.el=e(i.mfpEl),n.delegate&&(n.items=a.find(n.delegate)),t.open(n)}},updateStatus:function(e,a){if(t.preloader){i!==e&&t.container.removeClass("mfp-s-"+i),a||"loading"!==e||(a=t.st.tLoading);var n={status:e,text:a};x("UpdateStatus",n),e=n.status,a=n.text,t.preloader.html(a),t.preloader.find("a").on("click",function(e){e.stopImmediatePropagation()}),t.container.addClass("mfp-s-"+e),i=e}},_checkIfClose:function(i){if(!e(i).hasClass(y)){var a=t.st.closeOnContentClick,n=t.st.closeOnBgClick;if(a&&n)return!0;if(!t.content||e(i).hasClass("mfp-close")||t.preloader&&i===t.preloader[0])return!0;if(i===t.content[0]||e.contains(t.content[0],i)){if(a)return!0}else if(n&&e.contains(document,i))return!0;return!1}},_addClassToMFP:function(e){t.bgOverlay.addClass(e),t.wrap.addClass(e)},_removeClassFromMFP:function(e){this.bgOverlay.removeClass(e),t.wrap.removeClass(e)},_hasScrollBar:function(e){return(t.isIE7?a.height():document.body.scrollHeight)>(e||w.height())},_setFocus:function(){(t.st.focus?t.content.find(t.st.focus).eq(0):t.wrap).focus()},_onFocusIn:function(i){return i.target===t.wrap[0]||e.contains(t.wrap[0],i.target)?void 0:(t._setFocus(),!1)},_parseMarkup:function(t,i,a){var n;a.data&&(i=e.extend(a.data,i)),x(u,[t,i,a]),e.each(i,function(e,i){if(void 0===i||i===!1)return!0;if(n=e.split("_"),n.length>1){var a=t.find(g+"-"+n[0]);if(a.length>0){var o=n[1];"replaceWith"===o?a[0]!==i[0]&&a.replaceWith(i):"img"===o?a.is("img")?a.attr("src",i):a.replaceWith('<img src="'+i+'" class="'+a.attr("class")+'" />'):a.attr(n[1],i)}}else t.find(g+"-"+e).html(i)})},_getScrollbarSize:function(){if(void 0===t.scrollbarSize){var e=document.createElement("div");e.style.cssText="width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;",document.body.appendChild(e),t.scrollbarSize=e.offsetWidth-e.clientWidth,document.body.removeChild(e)}return t.scrollbarSize}},e.magnificPopup={instance:null,proto:C.prototype,modules:[],open:function(t,i){return E(),t=t?e.extend(!0,{},t):{},t.isObj=!0,t.index=i||0,this.instance.open(t)},close:function(){return e.magnificPopup.instance&&e.magnificPopup.instance.close()},registerModule:function(t,i){i.options&&(e.magnificPopup.defaults[t]=i.options),e.extend(this.proto,i.proto),this.modules.push(t)},defaults:{disableOn:0,key:null,midClick:!1,mainClass:"",preloader:!0,focus:"",closeOnContentClick:!1,closeOnBgClick:!0,closeBtnInside:!0,showCloseBtn:!0,enableEscapeKey:!0,modal:!1,alignTop:!1,removalDelay:0,prependTo:null,fixedContentPos:"auto",fixedBgPos:"auto",overflowY:"auto",closeMarkup:'<button title="%title%" type="button" class="mfp-close">&times;</button>',tClose:"Close (Esc)",tLoading:"Loading..."}},e.fn.magnificPopup=function(i){E();var a=e(this);if("string"==typeof i)if("open"===i){var n,o=b?a.data("magnificPopup"):a[0].magnificPopup,r=parseInt(arguments[1],10)||0;o.items?n=o.items[r]:(n=a,o.delegate&&(n=n.find(o.delegate)),n=n.eq(r)),t._openClick({mfpEl:n},a,o)}else t.isOpen&&t[i].apply(t,Array.prototype.slice.call(arguments,1));else i=e.extend(!0,{},i),b?a.data("magnificPopup",i):a[0].magnificPopup=i,t.addGroup(a,i);return a};var S,_,O,z="inline",M=function(){O&&(_.after(O.addClass(S)).detach(),O=null)};e.magnificPopup.registerModule(z,{options:{hiddenClass:"hide",markup:"",tNotFound:"Content not found"},proto:{initInline:function(){t.types.push(z),I(s+"."+z,function(){M()})},getInline:function(i,a){if(M(),i.src){var n=t.st.inline,o=e(i.src);if(o.length){var r=o[0].parentNode;r&&r.tagName&&(_||(S=n.hiddenClass,_=k(S),S="mfp-"+S),O=o.after(_).detach().removeClass(S)),t.updateStatus("ready")}else t.updateStatus("error",n.tNotFound),o=e("<div>");return i.inlineElement=o,o}return t.updateStatus("ready"),t._parseMarkup(a,{},i),a}}});var B,j="ajax",L=function(){B&&e(document.body).removeClass(B)},F=function(){L(),t.req&&t.req.abort()};e.magnificPopup.registerModule(j,{options:{settings:null,cursor:"mfp-ajax-cur",tError:'<a href="%url%">The content</a> could not be loaded.'},proto:{initAjax:function(){t.types.push(j),B=t.st.ajax.cursor,I(s+"."+j,F),I("BeforeChange."+j,F)},getAjax:function(i){B&&e(document.body).addClass(B),t.updateStatus("loading");var a=e.extend({url:i.src,success:function(a,n,o){var r={data:a,xhr:o};x("ParseAjax",r),t.appendContent(e(r.data),j),i.finished=!0,L(),t._setFocus(),setTimeout(function(){t.wrap.addClass(h)},16),t.updateStatus("ready"),x("AjaxContentAdded")},error:function(){L(),i.finished=i.loadError=!0,t.updateStatus("error",t.st.ajax.tError.replace("%url%",i.src))}},t.st.ajax.settings);return t.req=e.ajax(a),""}}});var H,A=function(i){if(i.data&&void 0!==i.data.title)return i.data.title;var a=t.st.image.titleSrc;if(a){if(e.isFunction(a))return a.call(t,i);if(i.el)return i.el.attr(a)||""}return""};e.magnificPopup.registerModule("image",{options:{markup:'<div class="mfp-figure"><div class="mfp-close"></div><figure><div class="mfp-img"></div><figcaption><div class="mfp-bottom-bar"><div class="mfp-title"></div><div class="mfp-counter"></div></div></figcaption></figure></div>',cursor:"mfp-zoom-out-cur",titleSrc:"title",verticalFit:!0,tError:'<a href="%url%">The image</a> could not be loaded.'},proto:{initImage:function(){var i=t.st.image,a=".image";t.types.push("image"),I(p+a,function(){"image"===t.currItem.type&&i.cursor&&e(document.body).addClass(i.cursor)}),I(s+a,function(){i.cursor&&e(document.body).removeClass(i.cursor),w.off("resize"+g)}),I("Resize"+a,t.resizeImage),t.isLowIE&&I("AfterChange",t.resizeImage)},resizeImage:function(){var e=t.currItem;if(e&&e.img&&t.st.image.verticalFit){var i=0;t.isLowIE&&(i=parseInt(e.img.css("padding-top"),10)+parseInt(e.img.css("padding-bottom"),10)),e.img.css("max-height",t.wH-i)}},_onImageHasSize:function(e){e.img&&(e.hasSize=!0,H&&clearInterval(H),e.isCheckingImgSize=!1,x("ImageHasSize",e),e.imgHidden&&(t.content&&t.content.removeClass("mfp-loading"),e.imgHidden=!1))},findImageSize:function(e){var i=0,a=e.img[0],n=function(o){H&&clearInterval(H),H=setInterval(function(){return a.naturalWidth>0?void t._onImageHasSize(e):(i>200&&clearInterval(H),i++,void(3===i?n(10):40===i?n(50):100===i&&n(500)))},o)};n(1)},getImage:function(i,a){var n=0,o=function(){i&&(i.img[0].complete?(i.img.off(".mfploader"),i===t.currItem&&(t._onImageHasSize(i),t.updateStatus("ready")),i.hasSize=!0,i.loaded=!0,x("ImageLoadComplete")):(n++,200>n?setTimeout(o,100):r()))},r=function(){i&&(i.img.off(".mfploader"),i===t.currItem&&(t._onImageHasSize(i),t.updateStatus("error",s.tError.replace("%url%",i.src))),i.hasSize=!0,i.loaded=!0,i.loadError=!0)},s=t.st.image,l=a.find(".mfp-img");if(l.length){var c=document.createElement("img");c.className="mfp-img",i.el&&i.el.find("img").length&&(c.alt=i.el.find("img").attr("alt")),i.img=e(c).on("load.mfploader",o).on("error.mfploader",r),c.src=i.src,l.is("img")&&(i.img=i.img.clone()),c=i.img[0],c.naturalWidth>0?i.hasSize=!0:c.width||(i.hasSize=!1)}return t._parseMarkup(a,{title:A(i),img_replaceWith:i.img},i),t.resizeImage(),i.hasSize?(H&&clearInterval(H),i.loadError?(a.addClass("mfp-loading"),t.updateStatus("error",s.tError.replace("%url%",i.src))):(a.removeClass("mfp-loading"),t.updateStatus("ready")),a):(t.updateStatus("loading"),i.loading=!0,i.hasSize||(i.imgHidden=!0,a.addClass("mfp-loading"),t.findImageSize(i)),a)}}});var Q,N=function(){return void 0===Q&&(Q=void 0!==document.createElement("p").style.MozTransform),Q};e.magnificPopup.registerModule("zoom",{options:{enabled:!1,easing:"ease-in-out",duration:300,opener:function(e){return e.is("img")?e:e.find("img")}},proto:{initZoom:function(){var e,i=t.st.zoom,a=".zoom";if(i.enabled&&t.supportsTransition){var n,o,r=i.duration,c=function(e){var t=e.clone().removeAttr("style").removeAttr("class").addClass("mfp-animated-image"),a="all "+i.duration/1e3+"s "+i.easing,n={position:"fixed",zIndex:9999,left:0,top:0,"-webkit-backface-visibility":"hidden"},o="transition";return n["-webkit-"+o]=n["-moz-"+o]=n["-o-"+o]=n[o]=a,t.css(n),t},d=function(){t.content.css("visibility","visible")};I("BuildControls"+a,function(){if(t._allowZoom()){if(clearTimeout(n),t.content.css("visibility","hidden"),e=t._getItemToZoom(),!e)return void d();o=c(e),o.css(t._getOffset()),t.wrap.append(o),n=setTimeout(function(){o.css(t._getOffset(!0)),n=setTimeout(function(){d(),setTimeout(function(){o.remove(),e=o=null,x("ZoomAnimationEnded")},16)},r)},16)}}),I(l+a,function(){if(t._allowZoom()){if(clearTimeout(n),t.st.removalDelay=r,!e){if(e=t._getItemToZoom(),!e)return;o=c(e)}o.css(t._getOffset(!0)),t.wrap.append(o),t.content.css("visibility","hidden"),setTimeout(function(){o.css(t._getOffset())},16)}}),I(s+a,function(){t._allowZoom()&&(d(),o&&o.remove(),e=null)})}},_allowZoom:function(){return"image"===t.currItem.type},_getItemToZoom:function(){return t.currItem.hasSize?t.currItem.img:!1},_getOffset:function(i){var a;a=i?t.currItem.img:t.st.zoom.opener(t.currItem.el||t.currItem);var n=a.offset(),o=parseInt(a.css("padding-top"),10),r=parseInt(a.css("padding-bottom"),10);n.top-=e(window).scrollTop()-o;var s={width:a.width(),height:(b?a.innerHeight():a[0].offsetHeight)-r-o};return N()?s["-moz-transform"]=s.transform="translate("+n.left+"px,"+n.top+"px)":(s.left=n.left,s.top=n.top),s}}});var W="iframe",D="//about:blank",R=function(e){if(t.currTemplate[W]){var i=t.currTemplate[W].find("iframe");i.length&&(e||(i[0].src=D),t.isIE8&&i.css("display",e?"block":"none"))}};e.magnificPopup.registerModule(W,{options:{markup:'<div class="mfp-iframe-scaler"><div class="mfp-close"></div><iframe class="mfp-iframe" src="//about:blank" frameborder="0" allowfullscreen></iframe></div>',srcAction:"iframe_src",patterns:{youtube:{index:"youtube.com",id:"v=",src:"//www.youtube.com/embed/%id%?autoplay=1"},vimeo:{index:"vimeo.com/",id:"/",src:"//player.vimeo.com/video/%id%?autoplay=1"},gmaps:{index:"//maps.google.",src:"%id%&output=embed"}}},proto:{initIframe:function(){t.types.push(W),I("BeforeChange",function(e,t,i){t!==i&&(t===W?R():i===W&&R(!0))}),I(s+"."+W,function(){R()})},getIframe:function(i,a){var n=i.src,o=t.st.iframe;e.each(o.patterns,function(){return n.indexOf(this.index)>-1?(this.id&&(n="string"==typeof this.id?n.substr(n.lastIndexOf(this.id)+this.id.length,n.length):this.id.call(this,n)),n=this.src.replace("%id%",n),!1):void 0});var r={};return o.srcAction&&(r[o.srcAction]=n),t._parseMarkup(a,r,i),t.updateStatus("ready"),a}}});var Z=function(e){var i=t.items.length;return e>i-1?e-i:0>e?i+e:e},q=function(e,t,i){return e.replace(/%curr%/gi,t+1).replace(/%total%/gi,i)};e.magnificPopup.registerModule("gallery",{options:{enabled:!1,arrowMarkup:'<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',preload:[0,2],navigateByImgClick:!0,arrows:!0,tPrev:"Previous (Left arrow key)",tNext:"Next (Right arrow key)",tCounter:"%curr% of %total%"},proto:{initGallery:function(){var i=t.st.gallery,n=".mfp-gallery",r=Boolean(e.fn.mfpFastClick);return t.direction=!0,i&&i.enabled?(o+=" mfp-gallery",I(p+n,function(){i.navigateByImgClick&&t.wrap.on("click"+n,".mfp-img",function(){return t.items.length>1?(t.next(),!1):void 0}),a.on("keydown"+n,function(e){37===e.keyCode?t.prev():39===e.keyCode&&t.next()})}),I("UpdateStatus"+n,function(e,i){i.text&&(i.text=q(i.text,t.currItem.index,t.items.length))}),I(u+n,function(e,a,n,o){var r=t.items.length;n.counter=r>1?q(i.tCounter,o.index,r):""}),I("BuildControls"+n,function(){if(t.items.length>1&&i.arrows&&!t.arrowLeft){var a=i.arrowMarkup,n=t.arrowLeft=e(a.replace(/%title%/gi,i.tPrev).replace(/%dir%/gi,"left")).addClass(y),o=t.arrowRight=e(a.replace(/%title%/gi,i.tNext).replace(/%dir%/gi,"right")).addClass(y),s=r?"mfpFastClick":"click";n[s](function(){t.prev()}),o[s](function(){t.next()}),t.isIE7&&(k("b",n[0],!1,!0),k("a",n[0],!1,!0),k("b",o[0],!1,!0),k("a",o[0],!1,!0)),t.container.append(n.add(o))}}),I(f+n,function(){t._preloadTimeout&&clearTimeout(t._preloadTimeout),t._preloadTimeout=setTimeout(function(){t.preloadNearbyImages(),t._preloadTimeout=null},16)}),void I(s+n,function(){a.off(n),t.wrap.off("click"+n),t.arrowLeft&&r&&t.arrowLeft.add(t.arrowRight).destroyMfpFastClick(),t.arrowRight=t.arrowLeft=null})):!1},next:function(){t.direction=!0,t.index=Z(t.index+1),t.updateItemHTML()},prev:function(){t.direction=!1,t.index=Z(t.index-1),t.updateItemHTML()},goTo:function(e){t.direction=e>=t.index,t.index=e,t.updateItemHTML()},preloadNearbyImages:function(){var e,i=t.st.gallery.preload,a=Math.min(i[0],t.items.length),n=Math.min(i[1],t.items.length);for(e=1;e<=(t.direction?n:a);e++)t._preloadItem(t.index+e);for(e=1;e<=(t.direction?a:n);e++)t._preloadItem(t.index-e)},_preloadItem:function(i){if(i=Z(i),!t.items[i].preloaded){var a=t.items[i];a.parsed||(a=t.parseEl(i)),x("LazyLoad",a),"image"===a.type&&(a.img=e('<img class="mfp-img" />').on("load.mfploader",function(){a.hasSize=!0}).on("error.mfploader",function(){a.hasSize=!0,a.loadError=!0,x("LazyLoadError",a)}).attr("src",a.src)),a.preloaded=!0}}}});var K="retina";e.magnificPopup.registerModule(K,{options:{replaceSrc:function(e){return e.src.replace(/\.\w+$/,function(e){return"@2x"+e})},ratio:1},proto:{initRetina:function(){if(window.devicePixelRatio>1){var e=t.st.retina,i=e.ratio;i=isNaN(i)?i():i,i>1&&(I("ImageHasSize."+K,function(e,t){t.img.css({"max-width":t.img[0].naturalWidth/i,width:"100%"})}),I("ElementParse."+K,function(t,a){a.src=e.replaceSrc(a,i)}))}}}}),function(){var t=1e3,i="ontouchstart"in window,a=function(){w.off("touchmove"+o+" touchend"+o)},n="mfpFastClick",o="."+n;e.fn.mfpFastClick=function(n){return e(this).each(function(){var r,s=e(this);if(i){var l,c,d,u,p,f;s.on("touchstart"+o,function(e){u=!1,f=1,p=e.originalEvent?e.originalEvent.touches[0]:e.touches[0],c=p.clientX,d=p.clientY,w.on("touchmove"+o,function(e){p=e.originalEvent?e.originalEvent.touches:e.touches,f=p.length,p=p[0],(Math.abs(p.clientX-c)>10||Math.abs(p.clientY-d)>10)&&(u=!0,a())}).on("touchend"+o,function(e){a(),u||f>1||(r=!0,e.preventDefault(),clearTimeout(l),l=setTimeout(function(){r=!1},t),n())})})}s.on("click"+o,function(){r||n()})})},e.fn.destroyMfpFastClick=function(){e(this).off("touchstart"+o+" click"+o),i&&w.off("touchmove"+o+" touchend"+o)}}(),E()}),jQuery(document).ready(function(){jQuery(".popup-gallery-twitter").each(function(){jQuery(this).magnificPopup({delegate:"a.fts-twitter-link-image",type:"image",tLoading:"Loading image #%curr%...",mainClass:"fts-instagram-img-mobile",removalDelay:100,mainClass:"fts-instagram-fade",gallery:{enabled:!0,navigateByImgClick:!0,preload:[0,1]},image:{tError:'<a href="%url%">The image #%curr%</a> could not be loaded.',titleSrc:function(e){return e.el.parents(".fts-tweeter-wrap").find(".fts-twitter-text").html()}}})}),jQuery(".popup-gallery-fb").each(function(){jQuery(this).magnificPopup({delegate:"a.fts-view-album-photos-large",type:"image",tLoading:"Loading image #%curr%...",mainClass:"fts-instagram-img-mobile",removalDelay:100,mainClass:"fts-instagram-fade",gallery:{enabled:!0,navigateByImgClick:!0,preload:[0,1]},image:{tError:'<a href="%url%">The image #%curr%</a> could not be loaded.',titleSrc:function(e){return e.el.parents(".fts-fb-photo-post-wrap").find(".fts-jal-fb-description-wrap").html()}}})}),jQuery(".popup-gallery-fb-posts").each(function(){jQuery(this).magnificPopup({delegate:"a.fts-fb-large-photo",type:"image",tLoading:"Loading image #%curr%...",mainClass:"fts-instagram-img-mobile",removalDelay:100,mainClass:"fts-instagram-fade",gallery:{enabled:!0,navigateByImgClick:!0,preload:[0,1]},image:{tError:'<a href="%url%">The image #%curr%</a> could not be loaded.',titleSrc:function(e){return e.el.parents(".fts-fb-photo-post-wrap").find(".fts-jal-fb-message").html()}}})}),jQuery(".popup-gallery").each(function(){jQuery(this).magnificPopup({delegate:"a.fts-instagram-img-link",type:"image",tLoading:"Loading image #%curr%...",mainClass:"fts-instagram-img-mobile",removalDelay:100,mainClass:"fts-instagram-fade",gallery:{enabled:!0,navigateByImgClick:!0,preload:[0,1]},image:{tError:'<a href="%url%">The image #%curr%</a> could not be loaded.',titleSrc:function(e){return e.el.parent(".fts-instagram-wrapper").find(".fts-instagram-caption").html()}}})}),jQuery(".fts-vine-wrapper").each(function(){jQuery(this).magnificPopup({delegate:"a.fts-vine-thumbnail",tLoading:"Loading image #%curr%...",mainClass:"fts-vine-vid-popup",type:"inline",removalDelay:100,midClick:!0,gallery:{enabled:!0,navigateByImgClick:!0,preload:[0,1]}})}),jQuery(".popup-video-gallery-fb").each(function(){jQuery(this).magnificPopup({delegate:"a.fts-view-fb-videos-btn",tLoading:"Loading image #%curr%...",type:"iframe",preloader:!1,mainClass:"fts-fb-vid-popup",removalDelay:100,midClick:!0,callbacks:{open:function(){if(matchMedia("only screen and (max-device-width: 1006px)").matches){var e=event.target.id,t=jQuery("#"+e).data("poster"),i=jQuery(".fts-fb-vid-popup video");i.attr("poster",t)}}},iframe:{markup:'<div class="mfp-iframe-scaler"><div class="mfp-close"></div><video class="mfp-iframe" src="//about:blank" autoplay controls></video><script>jQuery(".fts-fb-vid-popup video").click(function(){jQuery(this).trigger(this.paused ? this.paused ? "play" : "play" : "pause")});</script></div>'},gallery:{enabled:!0,navigateByImgClick:!0,tCounter:'<span class="mfp-counter">%curr% of %total%</span>',preload:[0,1]}})}),jQuery("video").click(function(){jQuery(this).trigger(this.paused?this.paused?"play":"play":"pause")})});

