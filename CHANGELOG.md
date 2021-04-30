# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.1.0] - 2021-04-20

## Changed

- Rewrited DataProvider for Dynamic Price Aggregation to provide min/max price values not affected by current price filter

## [2.0.0] - 2021-04-20

### Added

- Possibility to specify custom FE Block to render specific Filter type (by Filter request var).

### Changed
- Changed the way Block is added to filter list on frontend. 
  Before - it was added as a separate block unrelated to filter blocks. 
  Now - it will replace the default Price filter Block in the Filters List.
- Changed the way min/max price values received.
  Before - it was creating new Layout object which lead to recreating new Product Collection and calling getMinimalPrice functions on it.
  Now - it will use Collection Faceted Data to get min/max values.
- Rewrote 'Dynamic' Aggregations Builder to return min/max price instead of Price ranges list.

### Backward compatibility breaks

Should be mostly backward compatible. Tested with 'Mysql' and 'Elastcisearch7' search engines.

#### Possible incompatibilities:

- Slider Block on FE now rendered in place of default Price Filter Block. 
  You should unhide default block if it was hidden to fit previous solution.
- Slider Block moved to default Filters list now. 
  This can cause some styling breaking because of wrapping html elements changed.
  The slider itself on frontend now rendered not always at the bottom of leftnav block, but in place of the default Price filter.


## [1.2.14] - 2020-11-30

### Changed

- Added support for PHP 7.4

## [1.2.13] - 2020-11-27

### Changed

- Added support for PHP 7.4

## [1.2.12] - 2020-11-27

### Changed

- Update supported PHP version

## [1.2.11] - 2020-02-11

### Fixed

- Fixed jQuery touch punch not loading on 2.3.3-p1

## [1.2.10] - 2019-06-03

### Changed

- Added support for PHP 7.2-7.3
- Added support for Magento 2.3

## [1.2.9] - 2018-06-06

### Fixed

- Fixed IE11 support by converting ES6 JavaScript code to ES5

## [1.2.8] - 2018-06-05

### Changed

- Converted slider to a UI component to make it extendable

## [1.2.7] - 2018-05-31

## [1.2.6] - 2017-12-05

### Changed

- Added support for PHP 7.1

## [1.2.5] - 2017-10-31

### Changed

- Added support for Magento 2.2

## [1.2.4] - 2017-05-26

### Fixed

- Fixed null configuration values for low/high

## [1.2.3] - 2017-05-25

### Changed

- Allow null values in price slider configuration

## [1.2.2] - 2017-05-19

### Changed

- Changed to touch punch JS library as dependency for better mobile support

## [1.2.1] - 2017-05-11

## [1.2.0] - 2017-05-08

## [1.1.0] - 2017-03-02

### Added

- Added dynamic values to min and max prices

## [0.1.0] - 2017-02-21

Initial alpha release

