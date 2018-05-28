Fooman Google Analytics+
===================

Magento Extension to add extra features to the default GoogleAnalytics module.

### Extra features compared to the version available on Magento Connect

- Works on Magento 1.8.1
- Dropped Support for Magento pre 1.4.2
- Google Universal Support with Ecommerce Tracking
- Dynamic Remarketing (add your conversion id and label as provided from Google, products are identified by their product id)
- Tag Manager (this provides an input field for the tag manager script snippet - please note that the population of the data layer would be up to you)
- Category for transaction items is now populated (based on custom attribute, otherwise defaults to first encountered product category)
- more templates

To provide feedback please create an issue on Github.

### Installation Instructions and User Manual
To install and configure the extension, start [here](http://magento1-support.fooman.co.nz/category/969-install-set-up-user-manual).

### Installation Options

**via composer**  
Fooman extension are included in the packages.firegento.com repository so you can install them easily via adding the extension to the require section and then running `composer install` or `composer update`

    "require":{
      "fooman/googleanalyticsplus":"*"
    },

Please note that packages.firegento.com is not always up-to-date - in this case please add the following in the repositories section

    "repositories":[
      {
        "type":"composer",
        "url":"http://packages.fooman.co.nz"
      }
    ],

**via modman**    
`modman clone https://github.com/fooman/common.git`   
`modman clone https://github.com/fooman/googleanalyticsplus.git`   

**via file transfer (zip download)**  
    please see the releases tab for https://github.com/fooman/googleanalyticsplus/releases  
    and https://github.com/fooman/common/releases
    
