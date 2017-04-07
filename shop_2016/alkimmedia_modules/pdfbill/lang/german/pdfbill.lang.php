<?php
define('IBN_BILLNR_TITLE', '[ibillnr] N&auml;chste Rechnungsnummer');       //ibillnr
define('IBN_BILLNR_DESC', 'Beim fakturieren einer Bestellung wird diese Nummer als n&auml;chstes vergeben.'); 
define('IBN_BILLNR_FORMAT_TITLE', '[ibillnr] Rechnungsnummer Format');       //ibillnr
define('IBN_BILLNR_FORMAT_DESC', 'Aufbauschema Rechn.Nr.: {n}=laufende Nummer, {d}=Tag, {m}=Monat, {y}=Jahr, <br>z.B. "100{n}-{d}-{m}-{y}" ergibt "10099-28-02-2007"'); 
define('IBN_DEFAULT_PROFILE_TITLE', '[ibillnr] Default Rechnungsprofil');       //ibillnr
define('IBN_DEFAULT_PROFILE_DESC', 'Jenes Profil welches bei der PDF-Rechnungsgenerierung voreingestellt ist.'); 
define('BOX_PDFBILL_CONFIG', 'PDF-Rechnung Konfig.');                 // pdfbill
define('ENTRY_BILLING', 'Rechnungsnummer:');       // ibillnr    
 define( BUTTON_PDFBILL_CREATE,        'PDF-Rechung generieren');       // pdfbill
    define( BUTTON_PDFDELIVNOTE_CREATE,   'PDF-Lieferschein generieren');       // pdfbill
    define( BUTTON_PDFBILL_RECREATE,      'PDF-Rechung aktualisieren');    // pdfbill
    define( BUTTON_PDFBILL_DISPLAY,  'PDF-Rechung anzeigen');         // pdfbill
    define( BUTTON_PDFBILL_SEND_INVOICE_MAIL, 'PDF Rechnung senden');         // pdfbill
    define( BUTTON_PDFBILL_SEND_INVOICE_MAIL2, 'PDF Rechnung erneut senden');      // pdfbill
    define( BUTTON_BILL, 'Fakturieren');   // ibillnr
    define( PDFBILL_INVOICE_WORD , 'rechnung' );                                 // used for pdf e-mail 
    define( PDFBILL_MSG_INVOICEMAIL_SENT , 'E-Mail wurde übermittelt' );      
    define( PDFBILL_MSG_DELINFO_PDF , '<br /><br />Eine PDF-Rechnung wurde bereits erstellt und wird ebenfalls gelöscht.' );      
    define( PDFBILL_TXT_DELIVERYDATE , 'Lieferdatum:' );      
    define( PDFBILL_TXT_BILLPROFILE , 'Rechnung:' );      
    define( PDFBILL_TXT_DELIVNOTEPROFILE , 'Lieferschein:' );  
    define( PDFBILL_DOWNLOAD_INVOICE, 'PDF-Rechnung Download' );   // ipdfbill#
    define( 'ALKIM_billnumber', 'Rechnungsnummer:');
define( 'ALKIM_billdate', 'Rechnungsdatum:');

?>
