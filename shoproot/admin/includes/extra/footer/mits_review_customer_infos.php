<?php
/**
 * --------------------------------------------------------------
 * File: mits_review_customer_infos.php
 * Date: 20.04.2022
 * Time: 11:42
 *
 * Author: Hetfield
 * Copyright: (c) 2022 - MerZ IT-SerVice
 * Web: https://www.merz-it-service.de
 * Contact: info@merz-it-service.de
 * --------------------------------------------------------------
 */

$mits_show_max_reviews = 0; // Wieviele Bewertungen sollen beim Kunden max. angezeigt werden? 0 bedeutet keine Limitierung!

if (defined('FILENAME_REVIEWS') && basename($PHP_SELF) == FILENAME_REVIEWS) {
  if (isset($rInfo) && is_object($rInfo) && isset($rInfo->customers_id) && !empty($rInfo->customers_id) && $rInfo->customers_id > 0) {
    $email_query = xtc_db_query("SELECT customers_email_address, customers_firstname, customers_lastname FROM " . TABLE_CUSTOMERS . " WHERE customers_id = " . $rInfo->customers_id);
    $email = xtc_db_fetch_array($email_query);
    $email_address = ($email['customers_email_address'] != '') ? $email['customers_email_address'] : '';
    $mits_customer_info_button = $gift_button = '';
    if ($email_address != '') {
      $mits_customer_info_button = '<p class="">' . TABLE_HEADING_CUSTOMER . ': ' . $email['customers_firstname'] . ' ' . $email['customers_lastname'] . '</p>
      <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_CUSTOMERS, xtc_get_all_get_params(array('cID', 'action', 'oID', 'page', 'status', 'asb', 'search', 'rID')) . 'cID=' . $rInfo->customers_id . '&search=' . $email_address . '&asb=asb') . '">' . BUTTON_SEARCH_CUSTOMER . '</a> 
      <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_ORDERS, 'cID=' . $rInfo->customers_id) . '">' . BUTTON_ORDERS . '</a> 
      <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_MAIL, xtc_get_all_get_params(array('customer')) . 'customer=' . $email_address) . '">' . BUTTON_EMAIL . '</a>';
      if (defined('ACTIVATE_GIFT_SYSTEM') && ACTIVATE_GIFT_SYSTEM == 'true') {
        $gift_button = '<a class="button" href="'.xtc_href_link(FILENAME_GV_MAIL, 'cID='.$rInfo->customers_id).'">'.BUTTON_SEND_COUPON.'</a>';
      }
      $MITS_LastCustomerReview = $mits_list_last_reviews = '';
      $reviews_query = xtc_db_query("SELECT r.*,
                                            rd.reviews_text,
                                            length(rd.reviews_text) as reviews_text_size,
                                            p.products_image,
                                            pd.products_name
                                       FROM ".TABLE_REVIEWS." r
                                       JOIN ".TABLE_REVIEWS_DESCRIPTION." rd 
                                            ON r.reviews_id = rd.reviews_id
                                  LEFT JOIN ".TABLE_PRODUCTS." p
                                            ON r.products_id = p.products_id
                                  LEFT JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd
                                            ON r.products_id = pd.products_id
                                              AND language_id = " . (int)$_SESSION['languages_id'] . "
                                      WHERE r.customers_id = " . $rInfo->customers_id . "
                                        AND r.reviews_id != " . $rInfo->reviews_id . "
                                   ORDER BY r.date_added DESC");
      if (xtc_db_num_rows($reviews_query) > 0) {
        $mits_list_last_reviews = '<table class="tableBoxCenter collapse">
        <tr class="dataTableHeadingRow">
          <td class="dataTableHeadingContent txta-l">' . TABLE_HEADING_PRODUCTS . '</td>
          <td class="dataTableHeadingContent txta-r">' .  TABLE_HEADING_RATING . '</td>
        </tr>';
        while ($reviews = xtc_db_fetch_array($reviews_query)) {
          $mits_list_last_reviews .= '
          <tr>
            <td class="dataTableContent txta-l">
              <a onclick="this.blur();" href="' . xtc_href_link(FILENAME_REVIEWS, 'page=' . $page . '&rID=' . $reviews['reviews_id'] . '&action=edit') . '">' . $reviews['products_name'] . '</a>
            </td>
            <td class="dataTableContent txta-r" align="right">' . xtc_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.png') . '</td>
          </tr>';
        }
        $mits_list_last_reviews .= '</table>';
        $mits_list_last_reviews = preg_replace("%(\r\n)|(\r)%", "", $mits_list_last_reviews);
        $MITS_LastCustomerReview = '<table class="contentTable"><tbody><tr class="infoBoxHeading"><td class="infoBoxHeading"><div id="mits_list_last_reviews">' . HEADING_MORE_RATINGS . $mits_list_last_reviews . '</div></td></tr></tbody></table>';
      }
      $mits_customer_info_button = preg_replace("%(\r\n)|(\r)%", "", $mits_customer_info_button);
      $MITS_ReviewCustomerInfo = '<table class="contentTable"><tbody><tr class="infoBoxHeading"><td class="infoBoxHeading"><div id="mits_review_customer_infos_button">' . $mits_customer_info_button . $gift_button . '</div></td></tr></tbody></table>';
      ?>
      <script>
        $(document).ready(function () {
          $('.boxRight .contentTable:first').after('<?php echo $MITS_ReviewCustomerInfo;?>');
          <?php if ($MITS_LastCustomerReview != '') { ?>$('.boxRight .contentTable:last').after('<?php echo $MITS_LastCustomerReview;?>'); <?php } ?>
        });
      </script>
      <style>
        #mits_review_customer_infos_button, #mits_list_last_reviews {
          text-align: center;
          padding: 6px;
          background: #ffe;
          box-shadow: 0 0 0.4em #6a9;
          -moz-box-shadow: 0 0 0.4em #6a9;
          -webkit-box-shadow: 0 0 0.4em #6a9;
          border: 0.125em solid #ffe;
        }
        #mits_review_customer_infos_button p {
          font-size: 13px;
          margin: 0;
          padding: 4px;
        }
        #mits_list_last_reviews p {
          font-size: 10px;
          margin: 0;
          padding: 4px;
        }
      </style>
      <?php
    }
  }
}

if (defined('FILENAME_CUSTOMERS') && basename($PHP_SELF) == FILENAME_CUSTOMERS) {
  if (isset($cInfo) && is_object($cInfo) && isset($cInfo->customers_id) && !empty($cInfo->customers_id)) {
    $MITS_LastCustomerReview = $mits_list_last_reviews = '';
    if ($cInfo->number_of_reviews > 0) {
      $mits_list_last_reviews = '<table class="tableBoxCenter collapse">
        <tr class="dataTableHeadingRow">
          <td class="dataTableHeadingContent txta-l">' . TABLE_HEADING_PRODUCTS . '</td>
          <td class="dataTableHeadingContent txta-r">' .  TABLE_HEADING_RATING . '</td>
        </tr>';
      $mits_reviews_limit = ($mits_show_max_reviews > 0) ? " LIMIT " . $mits_show_max_reviews : "";
      $reviews_query = xtc_db_query("SELECT r.*,
                                            rd.reviews_text,
                                            length(rd.reviews_text) as reviews_text_size,
                                            p.products_image,
                                            pd.products_name
                                       FROM ".TABLE_REVIEWS." r
                                       JOIN ".TABLE_REVIEWS_DESCRIPTION." rd 
                                            ON r.reviews_id = rd.reviews_id
                                  LEFT JOIN ".TABLE_PRODUCTS." p
                                            ON r.products_id = p.products_id
                                  LEFT JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd
                                            ON r.products_id = pd.products_id
                                              AND language_id = " . (int)$_SESSION['languages_id'] . "
                                      WHERE r.customers_id = " . $cInfo->customers_id . "
                                   ORDER BY r.date_added DESC" . $mits_reviews_limit);
      while ($reviews = xtc_db_fetch_array($reviews_query)) {
        $mits_list_last_reviews .= '
          <tr>
            <td class="dataTableContent txta-l">
              <a onclick="this.blur();" href="' . xtc_href_link(FILENAME_REVIEWS, 'page=' . $page . '&rID=' . $reviews['reviews_id'] . '&action=edit') . '">' . $reviews['products_name'] . '</a>
            </td>
            <td class="dataTableContent txta-r" align="right">' . xtc_image(DIR_WS_IMAGES.'stars_' . $reviews['reviews_rating'] . '.png') . '</td>
          </tr>';
      }
      $mits_list_last_reviews .= '</table>';
      $mits_list_last_reviews = preg_replace("%(\r\n)|(\r)%", "", $mits_list_last_reviews);
      $mits_last_review_heading = ($mits_show_max_reviews > 0) ? sprintf(HEADING_LAST_RATINGS_LIMIT, $mits_show_max_reviews) : HEADING_LAST_RATINGS;
      $MITS_LastCustomerReview = '<table class="contentTable"><tbody><tr class="infoBoxHeading"><td class="infoBoxHeading"><div id="mits_list_last_reviews">' . $mits_last_review_heading . $mits_list_last_reviews . '</div></td></tr></tbody></table>';
      ?>
      <script>
        $(document).ready(function () {
          $('.boxRight .contentTable:last').after('<?php echo $MITS_LastCustomerReview;?>');
        });
      </script>
      <style>
        #mits_list_last_reviews {
          text-align:center;
          padding:6px;
          background: #ffe;
          box-shadow: 0 0 0.4em #6a9;
          -moz-box-shadow: 0 0 0.4em #6a9;
          -webkit-box-shadow: 0 0 0.4em #6a9;
          border: 0.125em solid #ffe;
        }
        #mits_list_last_reviews p {
          font-size: 10px;
          margin: 0;
          padding: 4px;
        }
      </style>
      <?php
    }
  }
}