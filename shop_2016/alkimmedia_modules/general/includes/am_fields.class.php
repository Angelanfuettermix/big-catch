<?php


class am_fields{
    
    public static function draw_product_fields($pInfo){
 
    
    $files = glob(DIR_FS_CATALOG.'alkimmedia_modules/*/products_fields.inc.php');
    $lang = xtc_get_languages();
    if(is_array($files) && count($files)>0){
       ?>
        <style>
            .alkimHeading{
                
                background: #e2e2e2; /* Old browsers */
                /* IE9 SVG, needs conditional override of 'filter' to 'none' */
                background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2UyZTJlMiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjUwJSIgc3RvcC1jb2xvcj0iI2RiZGJkYiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjUxJSIgc3RvcC1jb2xvcj0iI2QxZDFkMSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmZWZlZmUiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
                background: -moz-linear-gradient(top,  #e2e2e2 0%, #dbdbdb 50%, #d1d1d1 51%, #fefefe 100%); /* FF3.6+ */
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#e2e2e2), color-stop(50%,#dbdbdb), color-stop(51%,#d1d1d1), color-stop(100%,#fefefe)); /* Chrome,Safari4+ */
                background: -webkit-linear-gradient(top,  #e2e2e2 0%,#dbdbdb 50%,#d1d1d1 51%,#fefefe 100%); /* Chrome10+,Safari5.1+ */
                background: -o-linear-gradient(top,  #e2e2e2 0%,#dbdbdb 50%,#d1d1d1 51%,#fefefe 100%); /* Opera 11.10+ */
                background: -ms-linear-gradient(top,  #e2e2e2 0%,#dbdbdb 50%,#d1d1d1 51%,#fefefe 100%); /* IE10+ */
                background: linear-gradient(to bottom,  #e2e2e2 0%,#dbdbdb 50%,#d1d1d1 51%,#fefefe 100%); /* W3C */
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e2e2e2', endColorstr='#fefefe',GradientType=0 ); /* IE6-8 */
                padding:8px;
                border:1px solid #ddd;
                position:relative;
                font-size:16px;
            
            }
            
       </style>
       <?php echo '
        <div style="clear:both; font-family:Verdana; font-size:11px;">
                
                <h2 class="alkimHeading">
                <a href="http://www.alkim.de" target="_blank">
                  <img src="'.HTTP_SERVER.DIR_WS_CATALOG.'alkimmedia_modules/general/images/AM-Logo.png" style="height:40px; position:absolute;top: -2px; left:0px;" />
                </a><div style="margin-left:40px;">'.BOX_ALKIM_MODULE.'</div></h2>
                <table style="font-size:12px; width:100%;" >';
        foreach($files AS $file){
            include($file);
            
            echo '<tr><td colspan="3"><h3>'.$title.'</h3></td></tr>';
            
            foreach($fields AS $cv){
              if($cv["type"] == 'fullHTML'){
                echo '<tr valign="top">
                        <td colspan="2">
                          '.$cv["key"].'
                        </td>
                      </tr>';
              }else{
                   echo '<tr valign="top">
                            <td style="width:300px;">
                              '.$cv["title"][$_SESSION["language"]].'
                            </td>
                            <td>';
                  
                   switch($cv["type"]){
                        
                        case 'short_text':
                            
                            if($cv["products_description"]==1){
                               foreach($lang AS $l){
                              
                                  echo '<div style="margin-bottom:2px;"><img src="'.HTTP_SERVER.DIR_WS_CATALOG.'lang/'.$l["directory"].'/admin/images/icon.gif" /></div>
                                        <input name="cfg['.$cv["key"].']['.$l['id'].']" value="'.self::get_pd_field_value($cv["key"], $pInfo->products_id, $l['id']).'" type="text" />&nbsp;&nbsp;&nbsp;&nbsp;';
                               }
                            
                            }else{
                                echo '<input name="cfg['.$cv["key"].']" value="'.$pInfo->{$cv["key"]}.'" type="text" />';
                            }
                            break;
                        case 'long_text':
                            if($cv["products_description"]==1){
                               foreach($lang AS $l){
                              
                                  echo '<div><img src="'.HTTP_SERVER.DIR_WS_CATALOG.'lang/'.$l["directory"].'/admin/images/icon.gif" /></div>
                                        <textarea name="cfg['.$cv["key"].']['.$l['id'].']">'.self::get_pd_field_value($cv["key"], $pInfo->products_id, $l['id']).'</textarea>';
                               }
                            
                            }else{
                                echo '<textarea name="cfg['.$cv["key"].']">'.$pInfo->{$cv["key"]}.'</textarea>';
                            }
                            
                            break;
                        case 'bool':
                            echo '<input name="cfg['.$cv["key"].']" type="radio" value="1" '.((int)$pInfo->{$cv["key"]}==1?'checked="checked"':'').' id="'.$cv["key"].'_1"/>
                                  <label for="'.$cv["key"].'_1">'.AC_YES.'</label>
                                  <input name="cfg['.$cv["key"].']" type="radio" value="0" '.((int)$pInfo->{$cv["key"]}==0?'checked="checked"':'').' id="'.$cv["key"].'_0"/>
                                  <label for="'.$cv["key"].'_0">'.AC_NO.'</label>';
                            break;
                        case 'select':
                           echo '<select name="cfg['.$cv["key"].']">';
                           foreach($cv["options"] AS $k=>$name){
                                echo '<option value="'.$k.'" '.($pInfo->{$cv["key"]}==$k?'selected="selected"':'').'>'.$name[$_SESSION["language"]].'</option>';
                                     
                            }
                            echo '</select>';
                            break;
                            
                         case 'HTML':
                            
                          
                                echo $cv["key"];
                           
                            break;
               
               }
               
               echo '</tr>';         
            }
        }
    }
    
    echo '</table><div style="clear:both;height:0px; line-height:0px;">&nbsp;</div></div>';

}

}

      public static function add_product_fields($arr, $post, $language_id=''){
            
            $files = glob(DIR_FS_CATALOG.'alkimmedia_modules/*/products_fields.inc.php');
            
            if(is_array($files) && count($files)>0){
              
                foreach($files AS $file){
                    include($file);
                    
                   
                    
                    foreach($fields AS $f){
                     if($f["type"]!='HTML' && $f["type"]!='fullHTML'){
                          if($f["products_description"]!=1 && $language_id==''){ 
                             
                             $arr[$f["key"]] = xtc_db_prepare_input($post['cfg'][$f["key"]]);
                            
                          }elseif($language_id!='' && $f["products_description"]==1){
                             $arr[$f["key"]] = xtc_db_prepare_input($post['cfg'][$f["key"]][$language_id]);
                          }
                     }  
                    
                    }
                }
            
            
              
            }
            return $arr;
      } 
      
    
      
      public static function get_pd_field_value($field, $pid, $lid){
          $q = "SELECT * FROM products_description WHERE products_id = ".(int)$pid." AND language_id = ".(int)$lid;
          $rs = xtc_db_query($q);
          $r = xtc_db_fetch_array($rs);
          
          return $r[$field]; 
      }
      
      
      public static function draw_cat_fields($cInfo){
 
    
    $files = glob(DIR_FS_CATALOG.'alkimmedia_modules/*/categories_fields.inc.php');
    $lang = xtc_get_languages();
    if(is_array($files) && count($files)>0){
        echo '<div style="width:860px; font-family:Verdana; padding:5px;"><div style="font-family:Verdana; font-size:12px; border: 1px solid; border-color: #aaaaaa; padding:5px;">
                <a href="http://www.alkim.de" target="_blank">
                  <img src="'.HTTP_SERVER.DIR_WS_CATALOG.'alkimmedia_modules/general/images/AM-Logo.png" style="float:right;" />
                </a>
                <h2>'.BOX_ALKIM_MODULE.'</h2>
                <table style="font-size:12px;">';
        foreach($files AS $file){
            include($file);
            
            echo '<tr><td colspan="3"><h3>'.$title.'</h3></td></tr>';
            
            foreach($fields AS $cv){
               echo '<tr valign="top">
                        <td>
                          '.$cv["title"][$_SESSION["language"]].'
                        </td>
                        <td>';
              
               switch($cv["type"]){
                    
                    case 'short_text':
                        
                        if($cv["categories_description"]==1){
                           foreach($lang AS $l){
                          
                              echo '<div style="margin-bottom:2px;"><img src="'.HTTP_SERVER.DIR_WS_CATALOG.'lang/'.$l["directory"].'/admin/images/icon.gif" /></div>
                                    <input name="cfg['.$cv["key"].']['.$l['id'].']" value="'.self::get_cd_field_value($cv["key"], $cInfo->categories_id, $l['id']).'" type="text" />&nbsp;&nbsp;&nbsp;&nbsp;';
                           }
                        
                        }else{
                            echo '<input name="cfg['.$cv["key"].']" value="'.$cInfo->{$cv["key"]}.'" type="text" />';
                        }
                        break;
                    case 'long_text':
                        if($cv["categories_description"]==1){
                           foreach($lang AS $l){
                          
                              echo '<div><img src="'.HTTP_SERVER.DIR_WS_CATALOG.'lang/'.$l["directory"].'/admin/images/icon.gif" /></div>
                                    <textarea name="cfg['.$cv["key"].']['.$l['id'].']">'.self::get_cd_field_value($cv["key"], $cInfo->categories_id, $l['id']).'</textarea>';
                           }
                        
                        }else{
                            echo '<textarea name="cfg['.$cv["key"].']">'.$cInfo->{$cv["key"]}.'</textarea>';
                        }
                        
                        break;
                    case 'bool':
                        echo '<input name="cfg['.$cv["key"].']" type="radio" value="1" '.((int)$cInfo->{$cv["key"]}==1?'checked="checked"':'').' id="'.$cv["key"].'_1"/>
                              <label for="'.$cv["key"].'_1">'.AC_YES.'</label>
                              <input name="cfg['.$cv["key"].']" type="radio" value="0" '.((int)$cInfo->{$cv["key"]}==0?'checked="checked"':'').' id="'.$cv["key"].'_0"/>
                              <label for="'.$cv["key"].'_0">'.AC_NO.'</label>';
                        break;
                    case 'select':
                       foreach($cv["options"] AS $k=>$name){
                            echo '<input name="cfg['.$cv["key"].']" type="radio" value="'.$k.'" '.($cInfo->{$cv["key"]}==$k?'checked="checked"':'').' id="'.$cv["key"].'_'.$k.'"/>
                                  <label for="'.$cv["key"].'_'.$k.'">'.$name[$_SESSION["language"]].'</label>
                                 ';
                        }
                        break;
                    
                     case 'HTML':
                        
                      
                            echo $cv["key"];
                       
                        break;
               
               }
               
               echo '</tr>';         
            
        }
    }
    
    echo '</table><div style="clear:both; height:0px; line-height:0px;">&nbsp;</div></div>';

}

}

      public static function add_cat_fields($arr, $post, $language_id=''){
            
            $files = glob(DIR_FS_CATALOG.'alkimmedia_modules/*/categories_fields.inc.php');
            
            if(is_array($files)){
              
                foreach($files AS $file){
                    include($file);
                    
                   
                    
                    foreach($fields AS $f){
                     
                      if($f["categories_description"]!=1 && $language_id==''){ 
                      
                         $arr[$f["key"]] = xtc_db_prepare_input($post['cfg'][$f["key"]]);
                        
                      }elseif($language_id!='' && $f["categories_description"]==1){
                        
                         $arr[$f["key"]] = xtc_db_prepare_input($post['cfg'][$f["key"]][$language_id]);
                      }
                         
                    
                    }
                }
            
            
              
            }
            return $arr;
      } 
      
    
      
      public static function get_cd_field_value($field, $cid, $lid){
          $q = "SELECT * FROM categories_description WHERE categories_id = ".(int)$cid." AND language_id = ".(int)$lid;
          $rs = xtc_db_query($q);
          $r = xtc_db_fetch_array($rs);
          
          return $r[$field]; 
      }
      
      
}

?>
