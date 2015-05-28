!function(t){"use strict";var s=t.$win,o=t.$doc,i=[],e=function(){for(var t=0;t<i.length;t++)window.requestAnimationFrame.apply(window,[i[t].check])};t.component("scrollspy",{defaults:{target:!1,cls:"uk-scrollspy-inview",initcls:"uk-scrollspy-init-inview",topoffset:0,leftoffset:0,repeat:!1,delay:0},boot:function(){o.on("scrolling.uk.document",e),s.on("load resize orientationchange",t.Utils.debounce(e,50)),t.ready(function(s){t.$("[data-uk-scrollspy]",s).each(function(){var s=t.$(this);if(!s.data("scrollspy")){t.scrollspy(s,t.Utils.options(s.attr("data-uk-scrollspy")))}})})},init:function(){var s,o=this,e=this.options.cls.split(/,/),l=function(){var i=o.options.target?o.element.find(o.options.target):o.element,l=1===i.length?1:0,n=0;i.each(function(){var i=t.$(this),a=i.data("inviewstate"),r=t.Utils.isInView(i,o.options),c=i.data("ukScrollspyCls")||e[n].trim();!r||a||i.data("scrollspy-idle")||(s||(i.addClass(o.options.initcls),o.offset=i.offset(),s=!0,i.trigger("init.uk.scrollspy")),i.data("scrollspy-idle",setTimeout(function(){i.addClass("uk-scrollspy-inview").toggleClass(c).width(),i.trigger("inview.uk.scrollspy"),i.data("scrollspy-idle",!1),i.data("inviewstate",!0)},o.options.delay*l)),l++),!r&&a&&o.options.repeat&&(i.data("scrollspy-idle")&&clearTimeout(i.data("scrollspy-idle")),i.removeClass("uk-scrollspy-inview").toggleClass(c),i.data("inviewstate",!1),i.trigger("outview.uk.scrollspy")),n=e[n+1]?n+1:0})};l(),this.check=l,i.push(this)}});var l=[],n=function(){for(var t=0;t<l.length;t++)window.requestAnimationFrame.apply(window,[l[t].check])};t.component("scrollspynav",{defaults:{cls:"uk-active",closest:!1,topoffset:0,leftoffset:0,smoothscroll:!1},boot:function(){o.on("scrolling.uk.document",n),s.on("resize orientationchange",t.Utils.debounce(n,50)),t.ready(function(s){t.$("[data-uk-scrollspy-nav]",s).each(function(){var s=t.$(this);if(!s.data("scrollspynav")){t.scrollspynav(s,t.Utils.options(s.attr("data-uk-scrollspy-nav")))}})})},init:function(){var o,i=[],e=this.find("a[href^='#']").each(function(){i.push(t.$(this).attr("href"))}),n=t.$(i.join(",")),a=this.options.cls,r=this.options.closest||this.options.closest,c=this,p=function(){o=[];for(var i=0;i<n.length;i++)t.Utils.isInView(n.eq(i),c.options)&&o.push(n.eq(i));if(o.length){var l,p=s.scrollTop(),f=function(){for(var t=0;t<o.length;t++)if(o[t].offset().top>=p)return o[t]}();if(!f)return;c.options.closest?(e.closest(r).removeClass(a),l=e.filter("a[href='#"+f.attr("id")+"']").closest(r).addClass(a)):l=e.removeClass(a).filter("a[href='#"+f.attr("id")+"']").addClass(a),c.element.trigger("inview.uk.scrollspynav",[f,l])}};this.options.smoothscroll&&t.smoothScroll&&e.each(function(){t.smoothScroll(this,c.options.smoothscroll)}),p(),this.element.data("scrollspynav",this),this.check=p,l.push(this)}})}(UIkit);