<?php
/*
  osCommerce Online Merchant $osCommerce-SIG$
  Copyright (c) 2010 osCommerce (http://www.oscommerce.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  namespace osCommerce\OM\Site\Shop\Application\Products\Action;

  use osCommerce\OM\ApplicationAbstract;
  use osCommerce\OM\Registry;
  use osCommerce\OM\OSCOM;
  use osCommerce\OM\Site\Shop\Product;

  class Images {
    public static function execute(ApplicationAbstract $application) {
      $OSCOM_Template = Registry::get('Template');

// HPDL
      $OSCOM_Template->setHasHeader(false);
      $OSCOM_Template->setHasFooter(false);
      $OSCOM_Template->setHasBoxModules(false);
      $OSCOM_Template->setHasContentModules(false);
      $OSCOM_Template->setShowDebugMessages(false);

      $requested_product = null;
      $product_check = false;

      if ( count($_GET) > 2 ) {
        $requested_product = basename(key(array_slice($_GET, 2, 1)));

        if ( $requested_product == OSCOM::getSiteApplication() ) {
          unset($requested_product);

          if ( count($_GET) > 3 ) {
            $requested_product = basename(key(array_slice($_GET, 3, 1)));
          }
        }
      }

      if ( isset($requested_product) ) {
        if ( !$application->siteApplicationSubActionExists(OSCOM::getSiteApplication(), $requested_product) ) {
          if ( Product::checkEntry($requested_product) ) {
            $product_check = true;

            Registry::set('Product', new Product($requested_product));
            $OSCOM_Product = Registry::get('Product');

            $OSCOM_Template->addPageTags('keywords', $OSCOM_Product->getTitle());
            $OSCOM_Template->addPageTags('keywords', $OSCOM_Product->getModel());

            if ( $OSCOM_Product->hasTags() ) {
              $OSCOM_Template->addPageTags('keywords', $OSCOM_Product->getTags());
            }

            $application->setPageTitle($OSCOM_Product->getTitle());
            $application->setPageContent('images.php');
          }
        }
      }

      if ( $product_check === false ) {
        $application->setPageTitle(OSCOM::getDef('product_not_found_heading'));
        $application->setPageContent('not_found.php');
      }
    }
  }
?>