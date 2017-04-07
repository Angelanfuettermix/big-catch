<?php

//PLEASE RENAME CLASS NAME
class general_installer{


    public static function install(){
        //SQL MAY BE PHPMYADMIN EXPORT
                $sql = "

INSERT INTO `alkim_module_configuration` (`amc_key`, `amc_value`, `amc_language`) VALUES
('AM_FONT_HIGHLIGHT', '#893769', 0),
('AM_BACKGROUND_HIGHLIGHT_I_1', '#893769', 0),
('AM_BACKGROUND_HIGHLIGHT_I_2', '#AC4977', 0),
('AM_BACKGROUND_HIGHLIGHT_L_1', '#858585', 0),
('AM_BACKGROUND_HIGHLIGHT_L_2', '#EDEDED', 0),
('AM_BACKGROUND_HIGHLIGHT_L_3', '#f4f4f4', 0),
('AM_BORDER_LIGHT', '#838383', 0),
('AM_BORDER_DARK', '#CCCCCC', 0);



";

           
                  
                  $import = $sql;
                  
                  $import = preg_replace ("%/\*(.*)\*/%Us", '', $import);
                  $import = preg_replace ("%^--(.*)\n%mU", '', $import);
                  $import = preg_replace ("%^$\n%mU", '', $import);
                  
                  mysql_real_escape_string($import); 
                  $import = explode (";", $import); 
                  
                  foreach ($import as $imp){
                  
                    if (trim($imp) != '' && $imp != ' '){
                             xtc_db_query($imp); 
                    }
                  } 
                  
                
    
    
    
    
    }






}




 


?>
