# PriceSlider
A simple price slider extension for Magento 2

## Install
**Manually** 

To install this module copy the code from this repo to `app/code/Space48/PriceSlider` folder of your Magento 2 instance, then you need to run php `bin/magento setup:upgrade`

**Via composer**:

From the terminal execute que following:

`composer config repositories.space48-price-slider vcs git@github.com:Space48/PriceSlider.git`

then

`composer require "space48/priceslider:{module-version}"`

## Known issues

When **Mysql** engine is used for search and catalog - applying Price filter (by dragging slider) 
will reset min/max price values to the filter values.
Using Elasticsearch as search engine does not have this issue.