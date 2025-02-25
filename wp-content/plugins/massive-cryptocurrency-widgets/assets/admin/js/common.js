jQuery(document).ready(function(){
    $ = jQuery.noConflict();

    if ($('.crypto-preview').innerHeight() < $('.crypto-options').innerHeight() && $('.mcw-ticker.mcw-header').length == 0 && $('.mcw-ticker.mcw-footer').length == 0) {
        $('.crypto-preview').css('position', 'sticky');
    }

    var selectOptions = {
        labelField: 'label',
        valueField: 'value',
        searchField: ['label','value'],
        plugins: ['drag_drop', 'remove_button'],
        delimiter: ',',
        persist: false,
        create: false
    };

    var coinSelectOptions = {
        dataAttr: 'data-extra',
        searchField: ['symbol', 'text'],
        score: function (search) {
            return function (option) {
                search = search.toLowerCase();
                return (option.symbol == search) ? 2 : (option.text.toLowerCase().indexOf(search) !== -1 || option.symbol.indexOf(search) !== -1) ? 1 : 0;
            }
        },
        plugins: ['drag_drop', 'remove_button'],
        delimiter: ',',
        persist: false,
        create: false
    }

    var wtype = $('input[type=radio][name=type]:checked').val();
    var currencyselect = $('#currency-select').selectize(selectOptions);
    var selectcols = $('#mcw_tablecols').selectize(selectOptions);
    var changellysend = $('#changelly-send').selectize(selectOptions);
    var changellyreceive = $('#changelly-receive').selectize(selectOptions);
    var coinselect = $('#select-beast').selectize($.extend(coinSelectOptions, {
        maxItems: (wtype === 'chart' || wtype === 'box') ? 1 : null
    }));

    $('.removecoins').click(function() {
        coinselect[0].selectize.clear();
    });

    $('.removecols').click(function() {
        selectcols[0].selectize.clear();
    });

    $('.removecur').click(function() {
        currencyselect[0].selectize.clear();
    });

    $('.remove-changelly-send').click(function() {
        changellysend[0].selectize.clear();
    });

    $('.remove-changelly-receive').click(function() {
        changellyreceive[0].selectize.clear();
    });

    $('input[type=radio][name=type]').change(function() {
        $('.crypto-toggle').addClass('cc-hide');
        $('.' + this.value + '-position').removeClass('cc-hide');
        $('.all-position').removeClass('cc-hide');
        $('.' + this.value + '-not-position').addClass('cc-hide');

        coinSelectOptions['maxItems'] = (this.value === 'chart' || this.value === 'box') ? 1 : null;
        coinselect[0].selectize.destroy();
        coinselect = $('#select-beast').selectize(coinSelectOptions);
        $('.widgetname').text(this.value.toUpperCase());
    });

    $('input[type=radio][name=ticker_position]').change(function() {
        $('.ticker-position-label').removeClass('selected');
        $(this).parent().addClass('selected');
    });

    $('input[type=radio][name=theme]').change(function() {
        $('input[type=radio][name=theme]').parent().removeClass('cc-active');
        $(this).parent().addClass('cc-active');
    });

    $('input[type=radio][name=table_style]').change(function() {
        $('input[type=radio][name=table_style]').parent().removeClass('cc-active');
        $(this).parent().addClass('cc-active');
    });

    $('input[type=radio][name=chart_theme]').change(function() {
        $('input[type=radio][name=chart_theme]').parent().removeClass('cc-active');
        $(this).parent().addClass('cc-active');
    });

    $('input[type=radio][name=changelly_theme]').change(function() {
        $('input[type=radio][name=changelly_theme]').parent().removeClass('cc-active');
        $(this).parent().addClass('cc-active');
    });

    $('#mcwshortcode').on('click', function () {
        $('.shortcode-hint').show().delay(1000).fadeOut();
    });
    var cp = new ClipboardJS('#mcwshortcode');

    $('.color-field').spectrum({
        type: "component",
        hideAfterPaletteSelect: "true",
        showInput: "true"
    });

    $.fn.rangeSlider = function() {
        var range = this.find('.range-slider__range');
        var value = this.find('.range-slider__value');
        range.on('input', function() {
            value.html(this.value + value.data('suffix'));
        });
    }

    $('.range-slider').each(function() {
        $(this).rangeSlider();
    });

    if ($('.mcw-ticker.mcw-header').length > 0) {
        var tickerHeader = $('.cryptoboxes').detach();
        $('body').append(tickerHeader);
    }

    $('.crypto-collapse').click(function() {
        $('.crypto-edit').toggleClass('collapsed');
        setTimeout(function() {
            $(window).trigger('resize');
        }, 250);
    });    

    $(document).on('change', 'input[name="linkto"]', function(){
        if($(this).val() == 'custom'){
            $('.link').addClass('active');
        } else {
            $('.link').removeClass('active');
        }
    });

    function mcwBreakpoint(width) {
        var breakpoint = 'xs';

        if (width >= 992) {
            breakpoint = 'lg';
        } else if (width >= 768) {
            breakpoint = 'md';
        } else if (width >= 576) {
            breakpoint = 'sm';
        }

        return breakpoint;
    }

    $('.mcw-extensions').removeClass('cmcl-xs cmcl-sm cmcl-md cmcl-lg').addClass('cmcl-' + mcwBreakpoint($('.wrap').width()));

    $(window).resize(function() {
        $('.mcw-extensions').removeClass('cmcl-xs cmcl-sm cmcl-md cmcl-lg').addClass('cmcl-' + mcwBreakpoint($('.wrap').width()));
    });

    Vue.component('mcw-settings', {
        template: '#mcw-settings-template',
        props: ['options'],
        data: function() {
            var data = {
                menu: '',
                opts: this.options,
            };
            return data;
        },
        mounted() {
            var self = this;
            self.$data.menu = $('ul.page-menu').find('li').eq(0).data('page');

            var fontselect = $('#font-select').selectize(selectOptions);

            var editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
            editorSettings.codemirror = _.extend({}, editorSettings.codemirror, {
                indentUnit: 4,
                tabSize: 2,
                mode: 'css',
                autoRefresh: true
            });
            var editor = wp.codeEditor.initialize($('#mcw-css-editor'), editorSettings);
        },
        methods: {
            toggleMenu: function(menu) {
                this.$data.menu = menu;
            },
            license: function(action) {
                this.$data.opts.license_action = action;
                setTimeout(function() {
                    $('#mcw-settings-form').submit();
                }, 0);
            },
            addFormat: function() {
                this.$data.opts.currency_format.push(this.$data.opts.default_currency_format);
            },
            removeFormat: function(index) {
                this.$data.opts.currency_format.splice(index, 1);
            }
        }
    });

    var app = new Vue({
        el: '.vue-component',
        props: {
            options: Object
        }
    });
 
});