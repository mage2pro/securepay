The module integrates a Magento 2 based webstore with the **[SecurePay](https://www.securepay.com.au)** Australian payment service.  
The module is **free** and **open source**.

## Screenshots
### 1. Frontend. The payment form
![](https://mage2.pro/uploads/default/original/2X/1/17d2a0c186e05ac55cf2921e5397759d62da942c.png)
### 2. Backend. The extension's settings
#### 2.1. Live Mode
![](https://mage2.pro/uploads/default/original/2X/e/e465222e35c496d4cc2da4c437deb736c0c96ac7.png)
#### 2.2. Test Mode
![](https://mage2.pro/uploads/default/original/2X/c/c2d8199329b2184aef3c24e8fe143aefb2239bbb.png)

## Demo videos
1. [**Capture** a bank card payment](https://www.youtube.com/watch?v=xMr-_RnlvWM).
2. [**Refund** a bank card payment](https://www.youtube.com/watch?v=UyRmVVfEYdA).

## How to install
[Hire me in Upwork](https://upwork.com/fl/mage2pro), and I will: 
- install and configure the module properly on your website
- answer your questions
- solve compatiblity problems with third-party checkout, shipping, marketing modules
- implement new features you need 

### 2. Self-installation
```
bin/magento maintenance:enable
rm -f composer.lock
composer clear-cache
composer require mage2pro/securepay:*
bin/magento setup:upgrade
rm -rf var/di var/generation generated/code
bin/magento setup:di:compile
rm -rf pub/static/*
bin/magento setup:static-content:deploy -f en_US <additional locales, e.g.: en_AU>
bin/magento maintenance:disable
bin/magento cache:enable
```

## How to upgrade
```
bin/magento maintenance:enable
composer remove mage2pro/securepay
rm -f composer.lock
composer clear-cache
composer require mage2pro/securepay:*
bin/magento setup:upgrade
rm -rf var/di var/generation generated/code
bin/magento setup:di:compile
rm -rf pub/static/*
bin/magento setup:static-content:deploy -f en_US <additional locales, e.g.: en_AU>
bin/magento maintenance:disable
bin/magento cache:enable
```
