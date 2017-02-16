define(['jquery'], function ($) {

    var priceSlider = {

        init: function (config, node) {
            /* Cache DOM lookups for performance and brevity. */
            this.$el = $(node);
            this.$min = this.$('.js-min');
            this.$max = this.$('.js-max');

            this.timer = null;
            this.config = config;

            /* Set inital values display */
            this.setVal(this.config.low, this.config.high);

            this.build();
        },

        constructUrl(min, max) {
            var url = window.location.href;
            var query = url.split('?');
            var prefix = 'price=';
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
            /* Helper method: jQuery with context */
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
            window.clearTimeout(this.timer);
            this.setVal(ui.values[0], ui.values[1]);
        },

        setVal: function (min, max) {
            /* Set values on frontend */
            this.$min.html(this.config.currency + min);
            this.$max.html(this.config.currency + max);
        },

        change: function (event, ui) {
            /*  Contruct new URL and set window.location when values are selected.
                Allow delay, to allow time for second slider to be used. */
            this.timer = window.setTimeout($.proxy(function () {
                window.location.href = this.constructUrl(ui.values[0], ui.values[1]);
            }, this), this.config.waitTimeout);
        }
    };

    return function (config, node) {
        priceSlider.init(config, node);
    }
});
