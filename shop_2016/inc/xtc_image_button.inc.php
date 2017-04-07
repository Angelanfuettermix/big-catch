<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_image_button.inc.php 899 2005-04-29 02:40:57Z hhgag $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(html_output.php,v 1.52 2003/03/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (xtc_image_button.inc.php,v 1.3 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
// Output a function button in the selected language
  function xtc_image_button($image, $alt = '', $parameters = '') {
      // BOF - Alkim Media - 2015-03-XX - CSS Button
    if (CURRENT_TEMPLATE =='alkim_tpl_responsive') {
        require_once dirname(__FILE__) . '/xtc_image_submit.inc.php';
        $class = '';
        if (preg_match('#class="([^"]+)"#', $parameters, $m)) {
            $class = $m[1];
            $parameters = str_replace($m[0], ' ', $parameters);
        }
        $class = 'class="button submit' . ('' != $class ? ' ' . $class : '') . '"';
        $parameters = $class . ('' != $parameters ? ' ' . $parameters : '');
        $text = $alt;
        $image = basename($image);
        $image = substr($image, 0, strrpos($image, '.'));
        $const = 'BUTTON_TEXT_' . strtoupper($image);
        if (defined($const)) {
            $text = constant($const);
        }
        $text = xtc_parse_input_field_data($text, array('"' => '&quot;'));
        return '<div ' . $parameters . '>' . $text . '</div>';
    }
    // EOF - Alkim Media - 2015-03-XX - CSS Button

    return xtc_image('templates/'.CURRENT_TEMPLATE.'/buttons/' . $_SESSION['language'] . '/'. $image, $alt, '', '', $parameters);
  }
 ?>
