define(['jquery'], function ($) {

    var priceSlider = {

        init: function (config, node) {
            /* Cache dom lookups for performance and brevity. */
            this.$el = $(node);
            this.$min = this.$('.js-min');
            this.$max = this.$('.js-max');

            this.config = config;

            /* Set inital values display */
            this.setVal(config.low, config.high);

            this.build();
        },

        constructUrl(min, max) {
            var url = window.location.href;
            var query = url.split('?');
            var prefix = encodeURIComponent('price')+'=';
            var params;

            if (query.length > 1) {
                /* Existing URL parameter */
                params = query[1].split(/[&;]/g);

                for (var i = params.length; i-- > 0;) {
                    /* Remove existing price param if exists */
                    if (params[i].lastIndexOf(prefix, 0) !== -1) {
                        params.splice(i, 1);
                    }
                }

                /* Add price parameter */
                params.push('price=' + (min ? min : '') + '-' + (max ? max : ''));

                url = query[0] + (params.length > 0 ? '?' + params.join('&') : '');

                return url;
            } else {
                /* No URL parameter, create one */
                return url + '?price=' + (min ? min : '') + '-' + (max ? max : '');
            }
        },

        $: function (query) {
            /* Helper method. jQuery with context */
            return $(query, this.$el);
        },

        build: function () {
            // Build jQuery-ui component: http://api.jqueryui.com/slider/
            this.$('.js-bar').slider({
                range: true,
                min: this.config.min,
                max: this.config.max,
                step: 10,
                values: [this.config.low, this.config.high],
                slide: $.proxy(this.slide, this),
                change: $.proxy(this.change, this)
            });
        },

        slide: function (event, ui) {
            /* Display updated values on user interaction */
            this.setVal(ui.values[0], ui.values[1]);
        },

        setVal: function (min, max) {
            this.$min.html(min);
            this.$max.html(max);
        },

        change: function (event, ui) {
            /* Contruct new URL and set when values are selected */
            window.location.href = this.constructUrl(ui.values[0], ui.values[1]);
        }
    };

    return function (config, node) {
        priceSlider.init(config, node);
    }
});