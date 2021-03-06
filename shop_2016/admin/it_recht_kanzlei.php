<?php
/* -----------------------------------------------------------------------------------------
   $Id: it-recht-kanzlei.php 2011-11-24 modified-shop $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(configuration.php,v 1.40 2002/12/29); www.oscommerce.com
   (c) 2003   nextcommerce (configuration.php,v 1.16 2003/08/19); www.nextcommerce.org
   (c) 2003 XT-Commerce - community made shopping http://www.xt-commerce.com ($Id: configuration.php 1125 2005-07-28 09:59:44Z novalis $)
   (c) 2008 Gambio OHG (gm_trusted_info.php 2008-08-10 gambio)

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

require('includes/application_top.php');
require (DIR_WS_INCLUDES.'head.php');
?>
  </head>
  <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
    <!-- header //-->
    <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
    <!-- header_eof //-->
    <!-- body //-->
    <table border="0" width="100%" cellspacing="2" cellpadding="2">
      <tr>
        <td class="columnLeft2" width="<?php echo BOX_WIDTH; ?>" valign="top">
          <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
            <!-- left_navigation //-->
            <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
            <!-- left_navigation_eof //-->
          </table>
        </td>
        <!-- body_text //-->
        <td class="boxCenter" width="100%" valign="top">
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td width="100%">
                <table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="80" rowspan="2"><?php echo xtc_image(DIR_WS_ICONS.'heading_modules.gif'); ?></td>
                    <td class="pageHeading">IT-Recht Kanzlei</td>
                  </tr>
                  <tr>
                    <td class="main" valign="top">Modules</td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="250" valign="middle" class="dataTableHeadingContent">
                      Update-Service
                    </td>
                    <td valign="middle" class="dataTableHeadingContent"  style="border-right: 0px;">
                      <a href="<?php echo xtc_href_link('module_export.php', 'set=&module=it_recht_kanzlei'); ?>"><u>Einstellungen</u></a>  
                    </td>
                  </tr>
                </table>
                <table style="border: 1px solid #dddddd" border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tr style="background-color: #FFFFFF;">
                    <td style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; padding: 0px 10px 11px 10px; text-align: justify">
                      <br />
                      <font color="#2B7AC4"><strong>Schnittstellenmodul der IT-Recht Kanzlei M&uuml;nchen: Entspannter e-Commerce.</strong></font><br />
                      <img src="images/it_recht_kanzlei/it_recht_kanzlei.png" /><img src="images/it_recht_kanzlei/pruefzeichen-partner3.png" align="right" />
                      <br />
                      <br />
                      <img src="images/it_recht_kanzlei/schutzpaket_grafik.png" align="right" />
                      Das modified eCommerce Schnittstellen-Modul f&uuml;r den e-Commerce in Deutschland aktualisiert automatisch ihre Rechtstexte &ndash; so bleiben Sie 
                      dauerhaft vor Abmahnungen gesch&uuml;tzt und k&ouml;nnen sich ganz entspannt ihrem operativen Gesch&auml;ft widmen.<br />
                      <br />
                      <br />
                      Das Modul stellt Ihnen die folgenden Rechtstexte zur Verf&uuml;gung:
                      <ul>
                        <li style="list-style-type: circle !important;"><strong>AGB</strong></li>
                        <li style="list-style-type: circle !important;"><strong>Widerrufsbelehrung</strong></li>
                        <li style="list-style-type: circle !important;"><strong>Datenschutzerkl&auml;rung</strong></li>
                        <li style="list-style-type: circle !important;"><strong>Impressum</strong></li>
                      </ul>
                      <br />
                      Die Einbindung funktioniert ganz einfach: Sie beantworten online ein paar Fragen zu Ihren Unternehmen. Anhand ihrer Angaben werden f&uuml;r 
                      Ihren Shop optimierte Rechtstexte erstellt und mittels des Schnittstellenmoduls der IT-Recht Kanzlei automatisch an den richtigen Positionen 
                      dargestellt. Sobald die Rechtslage sich &auml;ndert, aktualisieren die Anw&auml;lte der IT-Recht Kanzlei alle betroffenen Texte; die &Auml;nderungen 
                      werden dann automatisch in ihrem Webshop und ihren eMails eingepflegt.<br />Und wie f&uuml;r Anwaltskanzleien &uuml;blich, haftet die IT-Recht Kanzlei 
                      auch f&uuml;r die Richtigkeit ihrer Texte. So unterst&uuml;tzen wir Sie nicht nur mit dauerhaft aktuellen Texten, sondern auch durch ein minimales 
                      finanzielles Risiko. Das gibt ihnen die Sicherheit, optimal vor Abmahnungen gesch&uuml;tzt zu sein und sich in Ruhe dem operativen Gesch&auml;ft widmen 
                      zu k&ouml;nnen.
                      <br /><br />
                      Ihre Vorteile auf einen Blick:
                      <br />
                      <ul>
                        <li style="list-style-type: circle !important;"><strong>Anwaltlich erstellte Dokumente f&uuml;r Ihren Webshop</strong></li>
                        <li style="list-style-type: circle !important;"><strong>St&auml;ndige und automatisierte Aktualisierung f&uuml;r dauerhafte Rechtssicherheit</strong></li>
                        <li style="list-style-type: circle !important;"><strong>Bequeme Integration durch das Schnittstellen-Modul</strong></li>
                        <li style="list-style-type: circle !important;"><strong>Selbstverst&auml;ndlich: Haftung f&uuml;r die Richtigkeit der Texte</strong></li>
                      </ul>
                      <p align="left">
                        <br />
                        <a href="http://www.it-recht-kanzlei.de/Service/agb-online-shop.php?partner_id=21" target="_blank"><font size="3" color="#893769"><u><strong>Jetzt den Update-Service der IT-Recht Kanzlei buchen.</strong></u></font></a> 
                      </p>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
        <!-- body_text_eof //-->
      </tr>
    </table>
    <!-- body_eof //-->
    <!-- footer //-->
    <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
    <!-- footer_eof //-->
    <br />
  </body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>