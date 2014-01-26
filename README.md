Fooman Google Analytics+
===================

Magento Extension to add extra features to the default GoogleAnalytics module.

### Extra features compared to the version available on Magento Connect

- Works on Magento 1.8.1
- Dropped Support for Magento pre 1.4.2
- Google Universal Support with Ecommerce Tracking (we recommend using this as an additional profile while still using Classic Analytics)
- Dynamic Remarketing (add your conversion id and label as provided from Google, products are identified by their product id)
- Tag Manager (this provides an input field for the tag manager script snippet - please note that the population of the data layer would be up to you)
- Category for transaction items is now populated (based on custom attribute, otherwise defaults to first encountered product category)
- more templates

To provide feedback please create an issue on Github.

There is a change in tracking behaviour - instead of passing order grand totals order subtotals are now being tracked (this matches Google's definition but breaks with Magento's default behaviour and previous behaviour of this extension)
