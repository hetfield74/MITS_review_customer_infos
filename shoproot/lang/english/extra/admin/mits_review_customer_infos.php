<?php
/**
 * --------------------------------------------------------------
 * File: mits_review_customer_infos.php
 * Date: 20.04.2022
 * Time: 17:29
 *
 * Author: Hetfield
 * Copyright: (c) 2022 - MerZ IT-SerVice
 * Web: https://www.merz-it-service.de
 * Contact: info@merz-it-service.de
 * --------------------------------------------------------------
 */

if (defined('FILENAME_REVIEWS') && basename($PHP_SELF) == FILENAME_REVIEWS) {
  defined('BUTTON_SEARCH_CUSTOMER') or define('BUTTON_SEARCH_CUSTOMER', 'To the customer account');
  defined('HEADING_MORE_RATINGS') or define('HEADING_MORE_RATINGS', '<p>Further reviews from this customer</p>');
}

if (defined('FILENAME_CUSTOMERS') && basename($PHP_SELF) == FILENAME_CUSTOMERS) {
  defined('TABLE_HEADING_PRODUCTS') or define('TABLE_HEADING_PRODUCTS', 'Article');
  defined('TABLE_HEADING_RATING') or define('TABLE_HEADING_RATING', 'Review');
  defined('HEADING_LAST_RATINGS_LIMIT') or define('HEADING_LAST_RATINGS_LIMIT', '<p>The last reviews of the customer (max. %s piece)</p>');
  defined('HEADING_LAST_RATINGS') or define('HEADING_LAST_RATINGS', '<p>The last reviews of the customer</p>');
}
