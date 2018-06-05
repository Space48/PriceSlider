define([
    'uiComponent',
    'jquery',
    'jquery/ui',
    'jquery/ui/touch-punch',
    'domReady!'
], function (Component, $) {

    return Component.extend({
        initialize: function (config, node) {
            /* Cache DOM lookups for performance and brevity. */
            this.$el = $(node);
            this.$min = this.$('.js-min');
            this.$max = this.$('.js-max');

            this.timer = null;
            this.config = config;

            /* Set inital values display low/high, default to min/max */
            this.setVal(this.config.low || this.config.min, this.config.high || this.config.max);

            this.build();
        },

        constructUrl(min, max) {
            console.log(this.config);
            var url = window.location.href;
            var query = url.split('?');
            var prefix = 'price=';
            var params;

            /*  If min/max are not present, or the same as low/high,
                use empty string when constructing URL. */
            min = (min && min !== this.config.min ? min : '');
            max = (max && max !== this.config.max ? max : '');

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
                params.push('price=' + min + '-' + max);

                url = query[0] + (params.length > 0 ? '?' + params.join('&') : '');

                return url;
            } else {
                /* No URL parameter, create one */
                return url + '?price=' + min + '-' + max;
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
                step: Math.floor((this.config.max - this.config.min) / 100),
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
    });
});
