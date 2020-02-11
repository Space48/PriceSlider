var config = {
    paths: {
        'priceSlider': 'Space48_PriceSlider/js/price-slider',
        'priceSlider/touch-punch': 'Space48_PriceSlider/js/lib/jquery.ui.touch-punch.min'
    },
    shim: {
        'priceSlider/touch-punch': {
            deps: ['jquery/ui']
        }
    }
};
