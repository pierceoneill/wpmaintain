/*
* JohnyDepp
* https://github.com/muicss/johnnydepp
*/
this.depp||function(n,e){depp=function(){var n={},u=function(){},f={},o={},a={},s={},d={};function p(n){throw new Error("Depp Error: "+n)}function r(n,e){var t=function r(n,i){i=i||[];var c=[],o=[];return n.forEach(function(t){if(0<=i.indexOf(t)&&p("Circular reference"),!(t in f))return c.push("#"+t);f[t].forEach(function(n){if("#"==n[0]){var e=i.slice();e.push(t),e=r([n.slice(1)],e),c=c.concat(e[0]),o=o.concat(e[1])}else o.push(n)})}),[c,o]}(n);t[0].length?i(t[0],function(){r(n,e)}):e(t[1])}function i(n,t){var e,r,i=n.length,c=i;if(0==i)return t();for(e=function(n,e){if(e)return t(n);--c||t()};i--;)(r=n[i])in a?e(r,a[r]):(o[r]=o[r]||[]).push(e)}function l(n,e){var t=o[n];if(a[n]=e,t)for(;t.length;)t[0](n,e),t.splice(0,1)}return n.define=function(n){var e;for(var t in n)t in f&&p("Bundle already defined"),e=n[t],f[t]=e.push?e:[e],l("#"+t)},n.config=function(n){for(var e in n)d[e]=n[e]},n.require=function(n,e,t){r(n=n.push?n:[n],function(n){i(n,function(n){n?(t||u)(n):(e||u)()}),n.forEach(function(n){var t,r,i,c,e,o,f;n in s||(s[n]=!0,t=n,r=l,e=document,o=d.before||u,f=t.replace(/^(css|img)!/,""),/(^css!|\.css$)/.test(t)?(i=!0,(c=e.createElement("link")).rel="stylesheet",c.href=f):/(^img!|\.(png|gif|jpg|svg)$)/.test(t)?(c=e.createElement("img")).src=f:((c=e.createElement("script")).src=t,c.async=!1),c.onload=c.onerror=c.onbeforeload=function(n){var e=n.type[0];if(i&&"hideFocus"in c)try{c.sheet.cssText.length||(e="e")}catch(n){18!=n.code&&(e="e")}if("b"==e){if(!n.defaultPrevented)return;e="e"}r(t,"e"==e)},o(t,c),e.head.appendChild(c))})})},n.done=function(n){f[n]=[],l("#"+n)},n.isDefined=function(n){return n in f},n.reset=function(){f={},o={},a={},s={},d={}},n}(),(e=n.createEvent("HTMLEvents")).initEvent?e.initEvent("depp-load",!1,!1):e=new Event("depp-load"),n.dispatchEvent(e)}(document);
/*
* https://github.com/shaunbowe/jquery.visibilityChanged
*/
!function(i){var n={callback:function(){},runOnLoad:!0,frequency:100,previousVisibility:null},c={checkVisibility:function(i,n){if(jQuery.contains(document,i[0])){var e=n.previousVisibility,t=i.is(":visible");n.previousVisibility=t;var u=null==e;u?n.runOnLoad&&n.callback(i,t,u):e!==t&&n.callback(i,t,u),setTimeout(function(){c.checkVisibility(i,n)},n.frequency)}}};i.fn.visibilityChanged=function(e){var t=i.extend({},n,e);return this.each(function(){c.checkVisibility(i(this),t)})}}(jQuery);

/*
* https://github.com/coderitual/bounty
*/
!function(t,e){"object"==typeof exports&&"object"==typeof module?module.exports=e():"function"==typeof define&&define.amd?define([],e):"object"==typeof exports?exports.bounty=e():t.bounty=e()}(this,function(){return function(t){function e(a){if(r[a])return r[a].exports;var n=r[a]={exports:{},id:a,loaded:!1};return t[a].call(n.exports,n,n.exports,e),n.loaded=!0,n.exports}var r={};return e.m=t,e.c=r,e.p="/",e(0)}([function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a=r(1);Object.defineProperty(e,"default",{enumerable:!0,get:function(){return(t=a,t&&t.__esModule?t:{default:t}).default;}})},function(t,e,r){"use strict";function a(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var n=a(r(2)),l=r(5),i=a(r(10));e.default=function(t){var e,r=t.el,a=t.value,o=t.initialValue,c=void 0===o?null:o,u=t.lineHeight,f=void 0===u?1.35:u,d=t.letterSpacing,s=void 0===d?1:d,p=t.animationDelay,v=void 0===p?100:p,y=t.letterAnimationDelay,h=void 0===y?100:y,g=(0,l.select)(r),b=window.getComputedStyle(g),m=parseInt(b.fontSize,10),x=(m*f-m)/2+m/10,_=m*f-x,M=Date.now(),j=0,w=m*f+x;g.innerHTML="";var P,O,N,S,A,D,E=l.append.call(g,"svg"),B=(e=l.append.call(E,"svg"),l.attr).call(e,"mask","url(#mask-"+M+")"),F=l.append.call(E,"defs");S=F,A=M,(D=(D=(D=(D=(D=(D=(D=(D=(D=(D=(D=(D=(D=(D=(D=(D=(D=(D=(D=(D=(D=(D=(D=(D=l.append.call(S,"linearGradient"),l.attr).call(D,"id","gradient-"+A),l.attr).call(D,"x1","0%"),l.attr).call(D,"y1","0%"),l.attr).call(D,"x2","0%"),l.attr).call(D,"y2","100%"),l.append).call(D,"stop"),l.attr).call(D,"offset","0"),l.attr).call(D,"stop-color","white"),l.attr).call(D,"stop-opacity","0"),l.select).call(D,"#gradient-"+A),l.append).call(D,"stop"),l.attr).call(D,"offset","0.2"),l.attr).call(D,"stop-color","white"),l.attr).call(D,"stop-opacity","1"),l.select).call(D,"#gradient-"+A),l.append).call(D,"stop"),l.attr).call(D,"offset","0.8"),l.attr).call(D,"stop-color","white"),l.attr).call(D,"stop-opacity","1"),l.select).call(D,"#gradient-"+A),l.append).call(D,"stop"),l.attr).call(D,"offset","1"),l.attr).call(D,"stop-color","white"),l.attr).call(D,"stop-opacity","0"),P=F,O=M,(N=(N=(N=(N=(N=(N=(N=l.append.call(P,"mask"),l.attr).call(N,"id","mask-"+O),l.append).call(N,"rect"),l.attr).call(N,"x",0),l.attr).call(N,"y",0),l.attr).call(N,"width","100%"),l.attr).call(N,"height","100%"),l.attr).call(N,"fill","url(#gradient-"+O+")");var k=function(t,e){for(var r=String(t).replace(/ /g," ").split(""),a=String(t).search(/\d/);e.length>r.length;){var n=e[e.length-r.length-1+a];r.splice(a,0,isNaN(parseInt(n,10))?n:"0")}return r},I=String(c||"0"),C=k(String(a),I),G=k(I,String(a)),q=C.map(function(t,e){var r,a,n,i,o,c,u,d,s,p,v,y,h=e+"-"+M;return isNaN(parseInt(t,10))||isNaN(parseInt(G[e],10))?{isDigit:!1,node:(p=B,v=t,(y=(y=l.append.call(p,"g"),l.append).call(y,"text"),l.text).call(y,v)),value:t,offset:{x:0,y:_}}:{isDigit:!0,id:h,node:(i=B,o=m,c=f,u=h,s=(d=(d=l.append.call(i,"g"),l.attr).call(d,"id","digit-"+u),l.style).call(d,"filter","url(#motionFilter-"+u+")"),[0,1,2,3,4,5,6,7,8,9,0].forEach(function(t,e){var r;(r=(r=l.append.call(s,"text"),l.attr).call(r,"y",-e*o*c),l.text).call(r,t)}),s),filter:(r=F,a=h,(n=(n=(n=(n=(n=(n=(n=l.append.call(r,"filter"),l.attr).call(n,"id","motionFilter-"+a),l.attr).call(n,"width","300%"),l.attr).call(n,"x","-100%"),l.append).call(n,"feGaussianBlur"),l.attr).call(n,"class","blurValues"),l.attr).call(n,"in","SourceGraphic"),l.attr).call(n,"stdDeviation","0 0")),value:Number(t),initial:Number(G[e]),offset:{x:0,y:_+Number(G[e])*(m*f)}}}),H=[],V=q.filter(function(t){return t.isDigit});V.forEach(function(t,e){var r=t.initial*(m*f),a=(30+t.value)*(m*f),n=(0,i.default)({from:r,to:a,delay:(V.length-1-e)*h+v,step:function(e){var n;t.offset.y=_+e%(m*f*10),(n=t.node,l.attr).call(n,"transform","translate("+t.offset.x+", "+t.offset.y+")");var i=(r+a)/2,o=Number(Math.abs(Math.abs(Math.abs(e-i)-i)-r)/100).toFixed(1);(n=t.filter,l.attr).call(n,"stdDeviation","0 "+o)},end:0===e?function(){return z()}:function(t){return t}});H.push(n)});var z=(0,n.default)(function(t){var e,r,a;j=0,q.forEach(function(t){try{var e=t.node.getBBox().width;t.offset.x=j,t.isDigit&&[].concat(function(t){if(Array.isArray(t)){for(var e=0,r=Array(t.length);e<t.length;e++)r[e]=t[e];return r}return Array.from(t)}(t.node.childNodes)).forEach(function(t){var r=t.getBBox().width,a=(e-r)/2;t.setAttribute("x",a)}),j+=e+s}catch(t){}}),j-=s,q.forEach(function(t){var e;(e=t.node,l.attr).call(e,"transform","translate("+t.offset.x+", "+t.offset.y+")")}),e=E,r=j,a=w,l.attr.call(e,"width",r),l.attr.call(e,"height",a),l.attr.call(e,"viewBox","0 0 "+r+" "+a),l.style.call(e,"overflow","visible"),H.forEach(function(e){return e.update(t)})});return z}},function(t,e){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(t){var e=void 0;return function r(a){e=requestAnimationFrame(r),t(a)}(0),function(){return cancelAnimationFrame(e)}}},function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(t){var e=document.createElementNS(l.default.svg,t);return this.appendChild(e),e};var a,n=r(6),l=(a=n)&&a.__esModule?a:{default:a}},function(t,e){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(t,e){return this.setAttribute(t,e),this}},function(t,e,r){"use strict";function a(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var n=r(7);Object.defineProperty(e,"select",{enumerable:!0,get:function(){return a(n).default}});var l=r(3);Object.defineProperty(e,"append",{enumerable:!0,get:function(){return a(l).default}});var i=r(4);Object.defineProperty(e,"attr",{enumerable:!0,get:function(){return a(i).default}});var o=r(8);Object.defineProperty(e,"style",{enumerable:!0,get:function(){return a(o).default}});var c=r(9);Object.defineProperty(e,"text",{enumerable:!0,get:function(){return a(c).default}})},function(t,e){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={svg:"http://www.w3.org/2000/svg"}},function(t,e){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(t){return t===String(t)?document.querySelector(t):t}},function(t,e){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(t,e){var r=arguments.length>2&&void 0!==arguments[2]?arguments[2]:"";return this.style.setProperty(t,e,r),this}},function(t,e){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(t){return this.textContent=t,this}},function(t,e){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var r=function(t){return((t*=2)<=1?t*t*t:(t-=2)*t*t+2)/2};e.default=function(t){var e=t.from,a=t.to,n=t.duration,l=void 0===n?3e3:n,i=t.delay,o=void 0===i?0:i,c=t.easing,u=void 0===c?r:c,f=t.start,d=void 0===f?function(t){return t}:f,s=t.step,p=void 0===s?function(t){return t}:s,v=t.end,y=void 0===v?function(t){return t}:v,h=e,g=0,b=!1;return{update:function(t){if(!b){g||(g=t,d(h));var r=Math.min(Math.max(t-g-o,0),l)/l;h=u(r)*(a-e)+e,p(h),1===r&&(b=!0,y(h))}}}}}])});

depp.define({
    'mcw-chartjs': [mcw.url + 'assets/public/js/Chart.min-3.3.2.js'],
    'mcw-echarts': [mcw.url + 'assets/public/js/Charts.min.js'],
    'mcw-datatable': [mcw.url + 'assets/public/js/jquery.dataTables.min.js', mcw.url + 'assets/public/js/dataTables.responsive.min.js']
});

(function($) {

    // JS equivalent for PHP's number_format
    mcw.number_format = function(number, decimals, dec_point, thousands_point) {
        if (number == null || !isFinite(number)) {
            return '';
        }
    
        if (!decimals) {
            var len = number.toString().split('.').length;
            decimals = len > 1 ? len : 0;
        }
    
        if (!dec_point) {
            dec_point = '.';
        }
    
        if (!thousands_point) {
            thousands_point = ',';
        }
    
        number = parseFloat(number).toFixed(decimals);
    
        number = number.replace(".", dec_point);
    
        var splitNum = number.split(dec_point);
        splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
        number = splitNum.join(dec_point);
    
        return number;
    }

    // Format number based on fiat currency
    mcw.numberFormat = function(num, iso, shorten = false, decimals = 'auto') {
        num = parseFloat(num);
        var format = (mcw.currency_format[iso] !== undefined) ? mcw.currency_format[iso] : mcw.default_currency_format;

        if (shorten) {
            decimals = format.decimals;
        } else if (decimals == 'auto') {
            decimals = (num >= 1) ? format.decimals : (num < 0.000001 ? 14 : 6);
        } else {
            decimals = parseInt(decimals);
        }

        num = num.toFixed(decimals);

        var index = 0;
        var suffix = '';
        var suffixes = ["", " K", " M", " B", " T"];

        if (shorten) {
            while (num > 1000) {
                num = num / 1000;
                index++;
            }
            suffix = suffixes[index];
        }
        
        return mcw.number_format(num, decimals, format.decimals_sep, format.thousands_sep) + suffix;
    }

    // Format price with symbols
    mcw.priceFormat = function(price, iso, shorten = false, decimals = 'auto') {
        price = parseFloat(price);
        var format = (mcw.currency_format[iso] !== undefined) ? mcw.currency_format[iso] : mcw.default_currency_format;

        price = mcw.numberFormat(price, iso, shorten, decimals);

        var out = format.position;
        out = out.replace('{symbol}', '<b class="fiat-symbol">' + format.symbol + '</b>');
        out = out.replace('{space}', ' ');
        out = out.replace('{price}', '<span>' + price + '</span>');

        return out;
    }

    $.fn.extend({
      animateCss: function(animationName, callback) {
        var animationEnd = (function(el) {
          var animations = {
            animation: 'animationend',
            OAnimation: 'oAnimationEnd',
            MozAnimation: 'mozAnimationEnd',
            WebkitAnimation: 'webkitAnimationEnd',
          };

          for (var t in animations) {
            if (el.style[t] !== undefined) {
              return animations[t];
            }
          }
        })(document.createElement('div'));

        this.addClass('mcw-animated ' + animationName).one(animationEnd, function() {
          $(this).removeClass('mcw-animated ' + animationName);

          if (typeof callback === 'function') callback();
        });

        return this;
      },
    });

    var realtimes = $('[data-realtime="on"]');

    if (realtimes.length > 0) {

        var socket = new WebSocket('wss://ws.coincap.io/prices?assets=ALL');

        socket.addEventListener('message', function(msg) {
            var prices = JSON.parse(msg.data);

            for (var coin in prices) {
                realtimes.find('[data-live-price="' + coin + '"]').each(function(){
                    $(this).realTime(coin, prices[coin]);
                });
            }
        });

    }

    $.fn.realTime = function(coin, priceusd) {

        var self = $(this);

        var rate = self.data('rate');
        var currency = self.data('currency');
        var timeout = parseInt($(this).attr('data-timeout')) || 0;
        var difference = Math.floor(Date.now()) - timeout;

        if (difference > 10000) {
            var price = mcw.priceFormat(priceusd * rate, currency);

            self.html(price);

            if (priceusd > parseFloat(self.attr('data-price'))) {
                self.animateCss('liveup');
            }
            if (priceusd < parseFloat(self.attr('data-price'))) {
                self.animateCss('livedown');
            }

            self.attr('data-price', priceusd);
            self.attr('data-timeout', Math.floor(Date.now()));
        }

    }

    function rgb2hex(rgb){
		rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
		return (rgb && rgb.length === 4) ? "#" +
		("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
		("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
		("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
	}
    
    function isBrightness($that){
		var c = rgb2hex($that.css('background-color'));
		var rgb = parseInt(c.substring(1), 16);
		var r = (rgb >> 16) & 0xff;
		var g = (rgb >>  8) & 0xff;
		var b = (rgb >>  0) & 0xff;

		var luma = 0.2126 * r + 0.7152 * g + 0.0722 * b;

		if (luma < 50) {
			$that.addClass('invert-act');
		}
	}

	$.fn.invertable = function() {
        isBrightness($(this));

		var invertList = ['ethereum','ripple','iota','eos','0x','bancor','dentacoin','bibox-token','medishares','santiment','quantstamp','raiden-network-token','pillar','republic-protocol','metal','eidoo','credo','blackmoon','covesting','shivom','suncontract','numeraire','daostack','bitdegree','matryx','faceter','internxt','cryptoping','invacio','chainium','creativecoin','trezarcoin','elcoin-el','jesus-coin','mojocoin','gapcoin','prime-xi','speedcash','veltor','loopring-neo','francs'];

		$(this).find('img').each(function(){
			if(invertList.join('-').toLowerCase().indexOf($(this).attr('alt').toLowerCase()) > -1) {
				$(this).addClass('invertable');
			}

		});
    }
    
    $('.mcw-dark-theme,.mcw-midnight-theme,.mcw-custom-theme,.mcw-table.dark').each(function() {
        $(this).invertable();
    });

    $.fn.inView = function() {
        var win = $(window);
        obj = $(this);
        var scrollPosition = win.scrollTop();
        var visibleArea = win.scrollTop() + win.height();
        var objEndPos = (obj.offset().top + obj.outerHeight());
        return (visibleArea >= objEndPos && scrollPosition <= objEndPos ? true : false);
    };

    $.fn.drawChart = function() {

        var self = $(this);

        depp.require('mcw-chartjs', function() {

            var rate = (self.data('rate')) ? self.data('rate') : 1;
            var currency = (self.data('currency')) ? self.data('currency') : 'USD';
            var color = self.data('color');
            var gradient = parseInt(self.data('gradient'));
            var border = parseInt(self.data('border'));
            var opacity = parseFloat(self.data('opacity'));
            var values = self.data('points').split(',').slice(0, 24);

            values = values.map(function(value) {
                value = parseFloat(value) * rate;
                var decimals = value >= 1 ? 2 : (value < 0.000001 ? 14 : 6);
                return value.toFixed(decimals);
            });

            background = (background) ? background : color;

            if (gradient === 0) {
                var background = 'rgba(' + color + ',' + opacity + ')';
            } else {
                var background = self[0].getContext('2d').createLinearGradient(0, 0, 0, gradient);
                background.addColorStop(0, 'rgba(' + color + ',0.3)');
                background.addColorStop(1, 'rgba(' + color + ',0)');
            }
        
            var data = {
                labels: values,
                datasets: [{
                    data: values,
                    fill: true,
                    backgroundColor: background,
                    borderColor: 'rgb(' + color + ')',
                    pointBorderColor: 'rgb(' + color + ')',
                    lineTension: 0.25,
                    pointRadius: 0,
                    borderWidth: border
                }]
            };
            var options = {
                interaction: {
                    intersect: false,
                    mode: 'nearest',
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return mcw.priceFormat(tooltipItem.parsed.y, currency).replace( /(<([^>]+)>)/ig, '');
                            },
                            title: function(tooltipItem, data) { return ''; }
                        }
                    },
                },
                scales: { x: { display: false }, y: { display: false } },
                maintainAspectRatio: false,
            };
            var chart = new Chart(self[0].getContext("2d"), { type: "line", data: data, options: options });

        });

    }

    $.fn.mcwTable = function() {

        var self = this;
        var breakpoint = 480;
        var table = self.find('.mcw-datatable');

        depp.require('mcw-datatable', function() {

            $.fn.dataTableExt.oPagination.info_buttons = {fnInit:function(e,a,n){var t=e._iDisplayStart+1+" - "+e.fnDisplayEnd()+" of "+e.fnRecordsDisplay(),i=document.createElement("span"),s=document.createElement("span"),o=document.createElement("span");i.appendChild(document.createTextNode(e.oLanguage.oPaginate.sPrevious)),o.appendChild(document.createTextNode(e.oLanguage.oPaginate.sNext)),s.appendChild(document.createTextNode(t)),i.className="paginate_button previous",o.className="paginate_button next",s.className="paginate_button info",a.appendChild(i),a.appendChild(s),a.appendChild(o),$(i).click(function(){e.oApi._fnPageChange(e,"previous"),n(e)}),$(o).click(function(){e.oApi._fnPageChange(e,"next"),n(e)}),$(i).bind("selectstart",function(){return!1}),$(o).bind("selectstart",function(){return!1})},fnUpdate:function(e,a){if(e.aanFeatures.p)for(var n=e.aanFeatures.p,t=0,i=n.length;t<i;t++){var s=n[t].getElementsByTagName("span");s[1].innerText=e._iDisplayStart+1+" - "+e.fnDisplayEnd()+" of "+e.fnRecordsDisplay(),0===e._iDisplayStart?s[0].className="paginate_button previous disabled":s[0].className="paginate_button previous enabled",e.fnDisplayEnd()==e.fnRecordsDisplay()?s[2].className="paginate_button next disabled":s[2].className="paginate_button next enabled"}}};

            var coins = {}, columns = [], fields = [];

            table.find('thead th').each(function(index) {

                var column = $(this).data('col');

                fields.push(column);

                columns.push({
                    data: column,
                    name: column,
                });

            });

            var tabledt = table.DataTable({
                dom: 'r<"loader"><"datatable-scroll"t><"loader show"><"dataTables-footer"lp><"clear">',
                order: [],
                scrollCollapse: true,
                pagingType: 'info_buttons',
                responsive: {
                    details: {
                        type: (self.width() < breakpoint) ? 'column' : 'inline',
                        target: 'tr'
                    }
                },
                pageLength: parseInt(table.data('length')),
                lengthMenu: [parseInt(table.data('length')),10,25,50,100].sort(function (a, b) {  return a - b;  }).filter(function(value, index, self) { return self.indexOf(value) === index; }),
                columns: columns,
                processing: true,
                serverSide: true,
                columnDefs: [
                    { targets: 'col-name', className: 'ctrl text-left all' },
                    { targets: 'col-rank', className: 'text-left min-tablet-p', width: '20px' },
                    { targets: 'col-price_usd', className: 'all' },
                    { targets: 'col-weekly', width: '190px', 'max-width': '190px', className: 'chart-wrapper' },
                ],
                drawCallback: function(data) {

                    table.find('canvas').each(function() {
                        $(this).drawChart();
                    });

                    var realtime = table.parents('.cryptoboxes').data('realtime');

                    if (realtime === 'on') {

                        socket.addEventListener('message', function(msg) {
                            var prices = JSON.parse(msg.data);

                            for (var coin in prices) {
                                table.find('[data-live-price="' + coin + '"]').each(function(){
                                    $(this).realTime(coin, prices[coin]);
                                });
                            }
                        });

                    }

                    $('.mcw-table.dark').each(function() {
                        $(this).invertable();
                    });

                },
                ajax: {
                    url: mcw.ajax_url,
                    type: 'POST',
                    data: {
                        action : "mcw_table",
                        mcw_id : self.attr('id').split('-')[1]
                    }
                },
                language: {
                    processing: '',
                    lengthMenu: mcw.text.lengthmenu,
                    paginate: {
                        next: mcw.text.next,
                        previous: mcw.text.previous
                    }
                }
            });

            tabledt.on('responsive-resize', function ( e, datatable, columns ) {
                var index = columns[0] ? 0 : 1;
                var dtr = ['dtr-inline', 'dtr-column'];
                table.find('tr td').removeClass('ctrl');
                table.find('tbody tr').each(function() {
                    $(this).find('td').eq(index).addClass('ctrl');
                });
                table.removeClass('dtr-column dtr-inline');
                table.addClass(dtr[index]);
            });

            tabledt.on('processing.dt', function ( e, settings, processing ) {
                if (processing) {
                    var loaderpos = self.find('thead').inView() ? 0 : 1;
                    self.find('.loader').eq(loaderpos).addClass('show');
                } else {
                    self.find('.loader').removeClass('show');
                }
            });

            tabledt.on('responsive-display', function (e) {
                $(e.currentTarget).find('td.child canvas').parent().addClass('chart-wrapper');
                $(e.currentTarget).find('td.child canvas').each(function() {
                    $(this).drawChart();
                });
            });

        });

    }

    $.fn.mcwChart = function() {

        if (this.length === 0) { return; }

        var self = $(this);

        depp.require('mcw-echarts', function() {

            //session storage destroy every 30 minutes
            setInterval(function(){
                if(sessionStorage.length > 0){
                    for (var j = 0; j < sessionStorage.length; j++){
                        if (sessionStorage.key(j).indexOf('mcw-') > -1) {
                            sessionStorage.setItem(sessionStorage.key(j),'');
                        }
                    }
                }
            },1000*60*30);
            // Set default values
            var options = {
                type: self.attr('data-type'),
                coin: self.attr('data-coin'),
                symbol: self.attr('data-symbol'),
                currency: self.attr('data-currency'),
                rate: parseFloat(self.attr('data-rate')),
                period: self.attr('data-view'),
                theme: self.attr('data-theme'),
                smooth: (self.attr('data-smooth') == 'true'),
                textColor: self.attr('data-areacolor'),
                areaColor: self.attr('data-bgcolor'),
                font: self.attr('data-font')
            };
            var opts = $.extend({ type: 'chart', coin: 'bitcoin', symbol: 'BTC', currency: 'USD', rate: 1, period: 'day', theme: 'dark', smooth:true, areaColor: 'rgba(112,147,254,0.8)', textColor: '#202328', font: 'inherit' }, options);

            var themes = {
                light: {
                    backgroundColor: '#fff',
                    color: (opts.type == 'chart') ? (opts.textColor) != '' ? [opts.textColor] : '#202328' : '#202328',
                    fontFamily: (opts.font == 'default') ? self.css('font-family') : opts.font,
                    chartColors: (opts.type == 'chart') ? (opts.areaColor) != '' ? [opts.areaColor] : 'rgba(112,147,254,0.8)' : ['rgba(108,130,145,0.2)'],
                    titleColor: (opts.type == 'chart') ? (opts.areaColor) != '' ? [opts.areaColor] : 'rgba(112,147,254,0.8)' : '#656565',
                    xAxis: '#333333',
                    yAxis: '#333333',
                    axisLine: 'rgba(54,60,78,0.1)',
                    border: '#eee'
                },
                dark: {
                    backgroundColor: '#202328',
                    color: (opts.type == 'chart') ? (opts.textColor) != '' ? [opts.textColor] : '#fff'  : '#fff',
                    fontFamily: (opts.font == 'default') ? self.css('font-family') : opts.font,
                    chartColors: (opts.type == 'chart') ? (opts.areaColor) != '' ? [opts.areaColor] : 'rgba(112,147,254,0.8)' : ['rgba(108,130,145,0.2)'],
                    titleColor: (opts.type == 'chart') ? (opts.areaColor) != '' ? [opts.areaColor] : 'rgba(112,147,254,0.8)' : '#fff',
                    xAxis: '#dddddd',
                    yAxis: '#dddddd',
                    axisLine: '#363c4e',
                    border: '#202328'
                }
            };

            var theme = themes[opts.theme];

            var periods = {
                chart: { day: 1, week: 7, month: 30, threemonths: 90, sixmonths: 180, year: 365, all: 'max' },
                candlestick: { day: 24, week: 168, month: 30, threemonths: 90, sixmonths: 180, year: 365, all: 2000 }
            };
            var periodnames = { day: 'Day', week: 'Week', month: 'Month', threemonths: '3 Months', sixmonths: '6 Months', year: 'Year', all: 'All Time' }
            var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            var breakpoint = 320;
            var chart = echarts.init(self.get(0));
            var options = {
                animation: false,
                backgroundColor: theme.backgroundColor,
                color: theme.chartColors,
                textStyle: { color: theme.color, fontFamily: theme.fontFamily },
                title : {
                    text: opts.symbol +'/'+ opts.currency,
                    subtext: periodnames[opts.period],
                    textStyle: { color: theme.titleColor }
                },
                grid: {  left: 15, right:15, top: (self.width() > breakpoint) ? 80 : 110, bottom: 10, containLabel: true },
                tooltip : {
                    trigger: 'axis',
                    formatter: function (params) {

                        var tooltip = params[0].name;

                        if (opts.type == 'chart') {
                            tooltip += '<br/>';
                            tooltip += params[0].marker + ' Price: <b style="color: #fff;">' + mcw.priceFormat(params[0].value, opts.currency);
                            tooltip += '</b>';
                        } else {
                            tooltip += '<br/>';
                            tooltip += params[0].marker + ' H: <b style="color: #fff;">' + mcw.priceFormat(params[0].value[4], opts.currency) + '</b>';
                            tooltip += '  L: <b style="color: #fff;">' + mcw.priceFormat(params[0].value[3], opts.currency) + '</b>';
                            tooltip += '<br/>';
                            tooltip += params[0].marker + ' O: <b style="color: #fff;">' + mcw.priceFormat(params[0].value[2], opts.currency) + '</b>';
                            tooltip += '  C: <b style="color: #fff;">' + mcw.priceFormat(params[0].value[1], opts.currency) + '</b>';
                            tooltip += '<br/>';
                            tooltip += params[1].marker + ' V: <b style="color: #fff;">' + mcw.numberFormat(params[1].value, opts.currency, false, 0) + "</b> " + opts.coin;
                        }

                        return tooltip;
                    }
                },
                dataZoom: { show : false, realtime: true, start : 0, end : 100, textStyle: { color: theme.color } },
                toolbox: {
                    show : true,
                    itemSize: 22,
                    left: (self.width() > breakpoint) ? 'right' : 'left',
                    top: (self.width() > breakpoint) ? 'top' : 50,
                    feature : {
                        myTool1: {
                            show: true,
                            title: periodnames['day'],
                            icon: 'image://'+mcw.url+'assets/public/charts/images/1d-dark-01.svg',
                            onclick: function () { changeData('day'); }
                        },
                        myTool2: {
                            show: true,
                            title: periodnames['week'],
                            icon: 'image://'+mcw.url+'assets/public/charts/images/1w-dark-01.svg',
                            onclick: function () { changeData('week'); }
                        },
                        myTool3: {
                            show: true,
                            title: periodnames['month'],
                            icon: 'image://'+mcw.url+'assets/public/charts/images/1m-dark-01.svg',
                            onclick: function () { changeData('month'); }
                        },
                        myTool4: {
                            show: true,
                            title: periodnames['threemonths'],
                            icon: 'image://'+mcw.url+'assets/public/charts/images/3m-dark-01.svg',
                            onclick: function () { changeData('threemonths'); }
                        },
                        myTool5: {
                            show: true,
                            title: periodnames['sixmonths'],
                            icon: 'image://'+mcw.url+'assets/public/charts/images/6m-dark-01.svg',
                            onclick: function () { changeData('sixmonths'); }
                        },
                        myTool6: {
                            show: true,
                            title: periodnames['year'],
                            icon: 'image://'+mcw.url+'assets/public/charts/images/1y-dark-01.svg',
                            onclick: function () { changeData('year'); }
                        },
                        myTool7: {
                            show: true,
                            title: periodnames['all'],
                            icon: 'image://'+mcw.url+'assets/public/charts/images/all-dark-01.svg',
                            onclick: function () { changeData('all'); }
                        },
                        mark : { show: false },
                        dataView : { show: false },
                        magicType : { show: false },
                        restore : { show: false },
                        saveAsImage : { show: false }
                    }
                },
                xAxis : [],
                yAxis : [],
                series : []
            };

            if (opts.type == 'chart') {

                options.xAxis.push({
                    type : 'category',
                    boundaryGap : false,
                    axisLabel: {
                        textStyle: {
                            color: theme.xAxis
                        },
                    }
                });

                options.yAxis.push({
                    type : 'value',
                    scale: true,
                    axisLabel: {
                        textStyle: {
                            color: theme.yAxis
                        },
                        formatter: function(value) {
                            return value == 0 ? 0 : mcw.numberFormat(value, opts.currency);
                        }
                    },
                    splitLine: {
                        show: true,
                        lineStyle: { color: theme.axisLine, width: 1, type: 'solid' }
                    },
                    boundaryGap: false
                });

                options.series.push({
                    name: 'Price',
                    type: 'line',
                    smooth: opts.smooth,
                    itemStyle: { normal: { areaStyle: { type: 'default' } } },
                });

            } else {

                options.xAxis.push({
                    type: 'category',
                    boundaryGap: true,
                    axisTick: { onGap:false },
                    splitLine: {
                        show: false
                    },
                    axisLabel: {
                        textStyle: {
                            color: theme.xAxis
                        },
                    }
                });

                options.yAxis.push({
                    type : 'value',
                    scale: false,
                    axisLabel: {
                        textStyle: {
                            color: theme.yAxis
                        },
                        formatter: function(value) {
                            return value == 0 ? 0 : mcw.numberFormat(value, opts.currency);
                        }
                    },
                    splitLine: {
                        show: false
                    },
                    boundaryGap: ['0%', '0%']
                }, {
                    type : 'value',
                    scale: true,
                    axisLabel: {
                        textStyle: {
                            color: theme.yAxis
                        },
                        formatter: function(value) {
                            return value == 0 ? 0 : mcw.numberFormat(value, opts.currency);
                        }
                    },
                    splitLine: {
                        show: true,
                        lineStyle: { color: theme.axisLine, width: 1, type: 'solid' }
                    },
                    boundaryGap: ['0%', '0%']
                });

                options.series.push({
                    name:'OHLC',
                    type:'k',
                    itemStyle: {
                        normal: { color: '#dc3545', color0: '#23BF08' }
                    },
                    yAxisIndex: 1
                });

                options.series.push({
                    name: 'Volume',
                    type: 'bar'
                });

            }

            self.css('background', theme.backgroundColor);
            self.css('border-color', theme.border);

            function changeData(period) {
                opts.period = period;
                options.title.subtext = periodnames[opts.period];
                drawChart();
            }

            function getData(callback) {

                if (opts.type === 'chart') {

                    var slugReplace = {'truefeedback': 'truefeedbackchain', 'avalanche': 'avalanche-2', 'bnb': 'binancecoin', 'multi-collateral-dai': 'dai', 'polkadot-new': 'polkadot', 'polygon': 'matic-network', 'unus-sed-leo': 'leo-token', 'xrp': 'ripple', 'apecoin-ape': 'apecoin', 'hedera': 'hedera-hashgraph', 'near-protocol': 'near', 'bitcoin-sv': 'bitcoin-cash-sv', 'bittorrent-new': 'bittorrent', 'elrond-egld': 'elrond-erd-2', 'kucoin-token': 'kucoin-shares', 'quant': 'quant-network', 'synthetix': 'havven', 'theta-network': 'theta-token', 'trueusd': 'true-usd', 'enjin-coin': 'enjincoin', 'klaytn': 'klay-token', 'mina': 'mina-protocol', 'neutrino-usd': 'neutrino', 'pancakeswap': 'pancakeswap-token', 'stacks': 'blockstack', 'compound': 'compound-governance-token', 'gatetoken': 'gatechain-token', 'gnosis-gno': 'gnosis', 'green-metaverse-token': 'stepn', 'holo': 'holotoken', 'amp': 'amp-token', 'kyber-network-crystal-v2': 'kyber-network-crystal', 'omg': 'omisego', 'optimism-ethereum': 'optimism', 'reserve-rights': 'reserve-rights-token', 'xinfin': 'xdce-crowd-sale', 'zel': 'zelcash', 'golem-network-tokens': 'golem', 'hive-blockchain': 'hive', 'horizen': 'zencash', 'skale-network': 'skale', 'sxp': 'swipe', 'abbc-coin': 'alibabacoin', 'casper': 'casper-network', 'ceek-vr': 'ceek', 'dogelon': 'dogelon-mars', 'polymath-network': 'polymath', 'sushiswap': 'sushi', 'voyager-token': 'ethos', 'wootrade': 'woo-network', 'conflux-network': 'conflux-token', 'ontology-gas': 'ong', 'pundix-new': 'pundi-x-2', 'ren': 'republic-protocol', 'request': 'request-network', 'xyo': 'xyo-network', 'chromia': 'chromaway', 'constellation': 'constellation-labs', 'function-x': 'fx-coin', 'rlc': 'iexec-rlc', 'bittorrent': 'bittorrent-old', 'counos-x': 'counosx', 'fruits-eco': 'fruits', 'steth': 'staked-ether', 'toncoin': 'the-open-network', 'metisdao': 'metis-token', 'mvl': 'mass-vehicle-ledger', 'safe': 'safe-anwang', 'terra-luna-v2': 'terra-luna-2', 'threshold': 'threshold-network-token', 'wemix': 'wemix-token', 'nest-protocol': 'nest', 'stasis-euro': 'stasis-eurs', 'usdx-kava': 'usdx', 'wrapped-everscale': 'everscale', 'funtoken': 'funfair', 'prom': 'prometeus', 'susd': 'nusd', 'lukso': 'lukso-token', 'shentu': 'certik', 'standard-tokenization-protocol': 'stp-network', 'stormx': 'storm', 'vulcan-forged-pyr': 'vulcan-forged', 'orchid': 'orchid-protocol', 'quarkchain': 'quark-chain', 'aleph-im': 'aleph', 'fetch': 'fetch-ai', 'freeway-token': 'freeway', 'mrweb-finance-v2': 'mrweb-finance', 'myneighboralice': 'my-neighbor-alice', 'rsk-infrastructure-framework': 'rif-token', 'rsk-smart-bitcoin': 'rootstock', 'alpha-finance-lab': 'alpha-finance', 'sologenic': 'solo-coin', 'wirex-token': 'wirex', 'bitmax-token': 'asd', 'defi-pulse-index': 'defipulse-index', 'deso': 'bitclout', 'thundercore': 'thunder-token', 'efforce': 'wozx', 'enzyme': 'melon', 'hunt': 'hunt-token', 'idex': 'aurora-dao', 'jasmy': 'jasmycoin', 'splintershards': 'splinterlands', 'travala': 'concierge-io', 'chrono-tech': 'chronobank', 'flamingo': 'flamingo-finance', 'mstable-usd': 'musd', 'richquack-com': 'richquack', 'star-link': 'starlink', 'super-zero-protocol': 'super-zero', 'syntropy': 'noia-network', 'xenioscoin': 'xenios', 'yearn-finance-ii': 'yearn-finance', 'chimpion': 'banana-token', 'kiltprotocol': 'kilt-protocol', 'truefi-token': 'truefi', 'vegaprotocol': 'vega-protocol', 'wrapped-kardiachain': 'kardiachain', 'dxchain-token': 'dxchain', 'keystone-of-opportunity-knowledge': 'kok', 'moss-coin': 'mossland', 'terra-virtua-kolect': 'the-virtua-kolect', 'wing': 'wing-finance', 'burger-cities': 'burger-swap', 'dia': 'dia-data', 'nash': 'neon-exchange', 'rizon-blockchain': 'rizon'};

                    if (opts.coin in slugReplace) {
                        opts.coin = slugReplace[opts.coin];
                    }

                    var url = 'https://api.coingecko.com/api/v3/coins/';
                    url += opts.coin;
                    url += '/market_chart?vs_currency=usd&days=' + periods.chart[opts.period];

                    var stname = "mcw-" + opts.coin.toLowerCase() + "-usd-chart" +  "-" + opts.period;

                } else {

                    var url = 'https://min-api.cryptocompare.com/data/';
                    url += (opts.period === 'day' || opts.period === 'week') ? 'histohour' : 'histoday';
                    url += '?fsym=' + opts.symbol + '&tsym=USDT';
                    url += (opts.period === 'all') ? '&allData=true' : '&limit=' + periods.candlestick[opts.period];
                    url += '&aggregate=1&extraParams=massivecrypto';

                    var stname = "mcw-" + opts.symbol.toLowerCase() + "-usd-candlestick" +  "-" + opts.period;

                }

                if ((sessionStorage.getItem(stname) === null) || sessionStorage.getItem(stname) == '') {

                    $.get(url, function(data) {
                        sessionStorage.setItem(stname, JSON.stringify(data));
                        return callback(data);
                    }, "json");

                } else {

                    var json = JSON.parse(sessionStorage.getItem(stname));
                    return callback(json);

                }

            }

            function formatAMPM(date) {
                var hours = date.getHours();
                var minutes = date.getMinutes();
                var ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                minutes = minutes < 10 ? '0'+minutes : minutes;
                var strTime = hours + ':' + minutes + ' ' + ampm;
                return strTime;
            }

            function drawChart() {

                chart.showLoading('default', { text: '', color: theme.titleColor, maskColor: theme.backgroundColor  });

                getData(function(data) {

                    var labels = [], values = [], volumes = [];

                    if (opts.type === 'chart') {

                        for (var i = 0; i < data.prices.length; i++) {

                            var date = new Date(data.prices[i][0]);
                            date = (opts.period == 'day') ? formatAMPM(date) : months[date.getMonth()] + " " + date.getDate() + ", " + date.getFullYear();
                            var value = data.prices[i][1] * opts.rate;

                            labels.push(date);
                            values.push(value);
                        }

                    } else {

                        for (var i = 0; i < data.Data.length; i++) {

                            var date = new Date(data.Data[i].time * 1000);
                            date = (opts.period == 'day') ? formatAMPM(date) : months[date.getMonth()] + " " + date.getDate() + ", " + date.getFullYear();
    
                            var value = (opts.type == 'chart') ? data.Data[i].close * opts.rate : [data.Data[i].close * opts.rate, data.Data[i].open * opts.rate, data.Data[i].low * opts.rate, data.Data[i].high * opts.rate];
    
                            labels.push(date);
                            values.push(value);
                            volumes.push(data.Data[i].volumefrom);
    
                        }

                    }

                    options.xAxis[0].data = labels;
                    options.series[0].data = values;

                    if (opts.type == 'candlestick') {
                        var zoomstart = Math.round((periods.candlestick[opts.period] / 60) * 10);
                        var zoomshow = (opts.type == 'candlestick' && zoomstart > 10) ? true : false;

                        options.dataZoom.show = zoomshow;
                        options.dataZoom.start = (opts.period == 'all') ? 0 : zoomstart;
                        options.grid.bottom = (zoomshow) ? 50 : 10;
                        options.series[1].data = volumes;
                    }

                    options.yAxis[0].min = (opts.period == 'all') ? 0 : null;

                    chart.setOption(options);
                    chart.hideLoading();
                });

            };

            drawChart();

            $(window).on('resize', function(){
                options.grid.top = (self.width() > breakpoint) ? 80 : 110;
                options.toolbox.left = (self.width() > breakpoint) ? 'right' : 'left';
                options.toolbox.top = (self.width() > breakpoint) ? 'top' : 50;
                chart.setOption(options);
                chart.resize();
            });

            self.visibilityChanged({
                callback: function(element, visible, initialLoad) {
                    if (visible) {
                        $(window).trigger('resize');
                    }
                },
                runOnLoad: true,
                frequency: 100
            });

        });

    }

    $.fn.mcwConverter = function() {

        var self = this;

        if (this.length === 0) { return; }

        this.find('select').selectize({
            onInitialize: function () {
                var s = this;
                this.revertSettings.$children.each(function () {
                    $.extend(s.options[this.value], $(this).data());
                });
                s.$dropdown.addClass('mcw-conv-style');
            },
            dropdownParent: "body"
        });
        this.find('.mcw-convert-swap').click(function() {
            self.find('.mcw-form-group').eq(0).toggleClass('mcw-form-group-swap');
        });
		var from = this.attr('data-from');
		var to = this.attr('data-to');
		var auto = (this.attr('data-auto') == 'true');
        var optionone = this.find('select').first();
        var optiontwo = this.find('select').last();
        var fieldone = this.find('input.mcw-field').first();
        var fieldtwo = this.find('input.mcw-field').last();
        var direction = 'down';

        var curfiat = $(this).closest('.cryptoboxes').data('fiat');
        var curcrypto = $(this).closest('.cryptoboxes').data('crypto');

        var fromdefault = (from == 'fiat') ? curfiat : '';
        var todefault = (from == 'crypto') ? ((to == 'fiat') ? curfiat : ((optionone[0].value == 'bitcoin') ? 'ethereum' : '')) : ((to == 'fiat') ? ((curfiat == 'USD') ? 'EUR' : 'USD') : ((curcrypto != '') ? curcrypto : ''));

        if(fromdefault != ''){ optionone[0].selectize.setValue(fromdefault); }
        if(todefault != '') { optiontwo[0].selectize.setValue(todefault); }

        // Calculate initial
        fieldone.val(1); calcdown();

        if (auto) {

            fieldone.on('input', function() {
                calcdown();
            });

            fieldtwo.on('input', function() {
                calcup();
            });

            optionone.change(function() {
                calcup(); direction = 'up';
            });

            optiontwo.change(function() {
                calcdown(); direction = 'down';
            });

        } else {

            fieldone.on('input', function() {
                direction = 'down';
            });

            fieldtwo.on('input', function() {
                direction = 'up';
            });

            var button = this.find('.mcw-button');

            button.click(function(e) {
				e.preventDefault();
                var calc = (direction == 'down') ? calcdown() : calcup();
            });
        }

        function calcdown() {

            var optiononeval = optionone[0].selectize.options[optionone.val()].val;
            var optiontwoval = optiontwo[0].selectize.options[optiontwo.val()].val;

            var out = '', val = parseFloat(fieldone.val().replace(/,/g, ''));

            if (from === 'crypto' && to === 'crypto') {
                out = (val) ? val * (optiononeval / optiontwoval) : '';
            } else if (from === 'fiat' && to === 'fiat') {
                out = (val) ? val * (optiontwoval / optiononeval) : '';
            } else if (from === 'fiat' && to === 'crypto') {
				out = (val) ? val / (optiononeval*optiontwoval) : '';
			} else {
                out = (val) ? val * optiononeval * optiontwoval : '';
            }

            var out = parseFloat(out);
            fieldtwo.val(mcw.numberFormat(out));
        }

        function calcup() {

            var optiononeval = optionone[0].selectize.options[optionone.val()].val;
            var optiontwoval = optiontwo[0].selectize.options[optiontwo.val()].val;

            var out = '', val = parseFloat(fieldtwo.val().replace(/,/g, ''));

            if (from === 'crypto' && to === 'crypto') {
                out = (val) ? val * (optiontwoval / optiononeval) : '';
            } else if (from === 'fiat' && to === 'fiat') {
                out = (val) ? val * (optiononeval / optiontwoval) : '';
            } else if (from === 'fiat' && to === 'crypto') {
				out = (val) ? val * optiononeval * optiontwoval : '';
			} else {
                out = (val) ? (val * (1 / optiontwoval)) / optiononeval : '';
            }

            var out = parseFloat(out);
            fieldone.val(mcw.numberFormat(out));
        }
    }

    $.fn.mcwHeadCard = function() {

        var self = this;
        var el = self.find('.mcw-price');
        var realtime = self.parents('.cryptoboxes').data('realtime');
        var curprice = el.text();
        var rate = el.data('rate');
        var currency = el.data('currency');
        bounty.default({ el: el[0], initialValue: curprice, value: curprice });

        if (realtime == 'on') {

            socket.addEventListener('message', function(msg) {

                var prices = JSON.parse(msg.data);
                var timeout = parseInt(el.attr('data-timeout')) || 0;
                var difference = Math.floor(Date.now()) - timeout;
    
                for (var coin in prices) {
                    if (el.data('live') == coin && difference > 10000) {
                        if (prices[coin] !== parseFloat(el.attr('data-price'))) {
                            var newprice = $(mcw.priceFormat(parseFloat(prices[coin] * rate), currency)).text();
                            bounty.default({ el: el[0], initialValue: curprice, value: newprice });
                            curprice = newprice;
                            el.attr('data-price', prices[coin]);
                            el.attr('data-timeout', Math.floor(Date.now()));
                        }
                    }
                }
    
            });

        }

        var toggleswitch = self.find('.mcw-toggle-switch');

        toggleswitch.click(function(e) {
            e.preventDefault();
            rate = $(this).data('rate');
            currency = $(this).data('currency');
            self.find('.mcw-toggle-switch').removeClass('active');
            $(this).addClass('active');

            var newprice = $(mcw.priceFormat(parseFloat(el.attr('data-price') * rate), currency)).text();

            bounty.default({ el: el[0], initialValue: curprice, value: newprice });
            curprice = newprice;
            return false;
        });

    };

    $.fn.mcwAccordion = function() {

        var self = this;

        self.find('.mcw-list-item:eq(0)').addClass('active').find('.mcw-list-body').slideDown();

        self.find('.mcw-list-header').click(function() {
            $(this).parents('.mcw-list').find('.mcw-list-item').not($(this).parent()).removeClass('active').find('.mcw-list-body').slideUp();
            $(this).parent().toggleClass('active');
            $(this).next('.mcw-list-body').slideToggle();
        });

    }
    
    $('.mcw-card-7').each(function() {
        $(this).mcwHeadCard();
    });

    $('.mcw-table').each(function(){
        $(this).mcwTable();
    });

    $('.mcw-chart').each(function(){
        $(this).mcwChart();
    });

    $.fn.mcwTabs = function() {

        var self = this;
        var tabs = self.find('.mcw-tab');
        var items = self.find('.mcw-tab-content');

        tabs.click(function() {
            var index = $(this).index();

            tabs.removeClass('active');
            $(this).addClass('active');

            items.not(':eq(' + index + ')').removeClass('active');
            items.eq(index).addClass('active');
        });
    }

    $('.mcw-converter').each(function(){
        $(this).mcwConverter();
    });

    $('.mcw-list-2').each(function() {
        $(this).mcwAccordion();
        $(this).find('canvas').each(function() {
            $(this).drawChart();
        });
    });

    $('.mcw-list-3').each(function() {
        $(this).find('canvas').each(function() {
            $(this).drawChart();
        });
    });

    $('.mcw-multi-tabs').each(function() {
        $(this).mcwTabs();
    });

    $('.mcw-box').each(function() {
        $(this).find('canvas').each(function() {
            $(this).drawChart();
        });

        $(this).find('.chart-offset').show();

        if ($(this).hasClass('mcw-box-2')) {

            var self = $(this);
            var crypto = self.find('.mcw-crypto-convert');
            var fiat = self.find('.mcw-fiat-convert');

            self.find('select').change(function() {
                var total = parseFloat(crypto.val()) * parseFloat(fiat.val());
                var currency = fiat.find('option:selected').text();
                price = mcw.priceFormat(total, currency);
                self.find('.mcw-price').html(price);

                var percent = crypto.find(':selected').data('change');
                self.find('.mcw-list-change').html(Math.abs(percent) + '%');
                if (percent >= 0) { self.find('.mcw-list-change').toggleClass('down up'); } else { self.find('.mcw-list-change').toggleClass('up down'); }
            });
        }
    });
    
    $.fn.multiply = function(numCopies) {
		var newElements = this.clone();
		for(var i = 1; i < numCopies; i++) {
			newElements = newElements.add(this.clone());
		}
		return newElements;
    };
    
    $(window).on('load', function() {

        $('.cc-stats').each(function() {

            var listWidth = 0;

            $(this).find('.cc-coin').each(function() {
                listWidth += $(this).innerWidth();
            });

            var clonedElem = $(this).find('.cc-coin');
            var mult = $(this).innerWidth() / listWidth;

            $(this).append('<div class="cc-dup"></div>');

            if(mult > 0.5){
                $(this).find('.cc-dup').append(clonedElem.multiply(Math.ceil(mult)));
            } else {
                $(this).find('.cc-dup').append(clonedElem.multiply(1));
            }

            $(this).css('width',listWidth);

            $(this).find('canvas').each(function() {
                $(this).drawChart();
            });

            var itemcount = $(this).find('.cc-coin').length;
            var itemsize = listWidth / itemcount;

            var speed = $(this).closest('.mcw-ticker').data('speed');
            var duration = itemsize * 10;
            
            if (speed === 200) {
                duration = 10;
            } else if (speed == 0) {
                duration = 0;
            } else if (speed > 100) {
                speed = speed - 100;
                speed = (speed / 10) * itemsize;
                duration = duration - speed;
            } else if (speed < 100) {
                speed = 100 - speed;
                speed = (speed / 10) * (itemsize * 8);
                duration = duration + speed;
            }

            var speed = (itemcount * duration) / 1000;
            $(this).css('animation-duration',  speed + 's');

            $(this).closest('.mcw-ticker').css('visibility', 'visible');
            
            if ($(this).closest('.mcw-ticker').hasClass('mcw-header')) {
                $('body').css('padding-top', $(this).closest('.mcw-ticker').height() + 'px');
                $('#wpadminbar').css('margin-top', $(this).closest('.mcw-ticker').height() + 'px');
            }

        });

    });

    $.fn.coinmcResize = function() {
        var breakpoint = 'xs';
        var width = (this.find('.cmc-row').length > 0) ? this.find('.cmc-row').eq(0).width() : this.width();

        if (width >= 992) {
            breakpoint = 'lg';
        } else if (width >= 768) {
            breakpoint = 'md';
        } else if (width >= 576) {
            breakpoint = 'sm';
        }

        this.removeClass('cmcl-xs cmcl-sm cmcl-md cmcl-lg').addClass('cmcl-' + breakpoint);
        this.trigger('view');
    }

    $('.mcwgrid').each(function() {
        var self = this;
        $(this).coinmcResize();

        $(window).resize(function() {
            $(self).coinmcResize();
        });
    });

})( jQuery );