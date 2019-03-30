<?php

// no direct access
defined('_JEXEC') or die;


if($work_type_require == 'and'){
    
    
            #1 Require for web site address
            $domen_is = $params->get('domen_is', '');//off, only , without
            if($domen_is):
                $domen_sites = $params->get('domen_site', $_SERVER['HTTP_HOST']);
                $domen_sites = self::replace('/','',$domen_sites); 
                $domen_sites = self::replace('www.','',$domen_sites);
            //echo '<pre style"color: green;">'.print_r($web_sites,true).'</pre>';
            //echo '<pre style"color: green;">'.print_r($_SERVER['HTTP_HOST'],true).'</pre>';
                if($domen_is == 'only' && !self::inArray ($_SERVER['HTTP_HOST'],$domen_sites,'www.')){
                    //throw new Exception('only');
                    return FALSE;
                }
                else if($domen_is == 'without' && self::inArray ($_SERVER['HTTP_HOST'],$domen_sites,'www.')){
                    //throw new Exception('without');
                    return FALSE;
                }
            endif;
            
            #2 Require for ip user address
            $ip_user_is = $params->get('ip_user_is', '');//off, only , without
            if($ip_user_is):
                $ip_user = $params->get('ip_user');
                $ip_user = self::replace(',','.',$ip_user);
                $ips_user = self::replace('00.','0.',$ip_user); 
                if($ip_user_is == 'only' && !self::inArray($_SERVER['REMOTE_ADDR'], $ips_user)){
                    return FALSE;
                }
                else if($ip_user_is == 'without' && self::inArray($_SERVER['REMOTE_ADDR'], $ips_user)){
                    return FALSE;
                }
            endif;
            
             
            #3 Require for debug url task "index.php?deb=1"
            $is_debug = $params->get('is_debug', '');//off, only , without
            if($is_debug):
                $is_deb = JFactory::getApplication()->input->getBool('deb');
                if($is_debug == 'only' && !$is_deb){
                    return FALSE;
                }
                else if($is_debug == 'without' && $is_deb){
                    return FALSE;
                }
            endif;
            
           
            #4 Require for items menu
            $menu_is = $params->get('menu_is', '');//off, only , without
            if($menu_is):
                $menu_assigment = (array)$params->get('menu_assigment',[]); 
                $menu_id = JFactory::getApplication()->getMenu()->getActive()->id; 
                if(in_array(0, $menu_assigment) || empty($menu_assigment)){ 
                }
                elseif($menu_is == 'only' && !in_array($menu_id, $menu_assigment)){ 
                    return FALSE; 
                }
                else if($menu_is == 'without' && in_array($menu_id, $menu_assigment)){ 
                    return FALSE;
                }
            endif;
            
            #5 Require for type component
            $component_is = $params->get('component_is', '');//off, only , without
            if($component_is):
                $component_site = (array)$params->get('component_site',[]); 
                $component_id = JFactory::getApplication()->getMenu()->getActive()->component_id; 
                if(empty($component_site)){ 
                }
                elseif($component_is == 'only' &&  !in_array($component_id, $component_site)){ 
                    return FALSE; 
                }
                else if($component_is == 'without' &&  in_array($component_id, $component_site)){ 
                    return FALSE;
                }
            endif;
            
            #6 Require for view component
            $view_is = $params->get('view_is', '');//off, only , without
            if($view_is):
                $views =  $params->get('view_site','');  
                $view_site = JFactory::getApplication()->input->getWord('view'); 
                if(empty($views)){ 
                }
                elseif($view_is == 'only' &&  !modMultiModuleHelper::inArray($view_site, $views)){ 
                    return FALSE; 
                }
                else if($view_is == 'without' &&  modMultiModuleHelper::inArray($view_site, $views)){ 
                    return FALSE;
                }
            endif;
            
    
    return TRUE; 
}else{
    
    
            #1 Require for web site address
            $domen_is = $params->get('domen_is', '');//off, only , without
            if($domen_is):
                $domen_sites = $params->get('domen_site', $_SERVER['HTTP_HOST']); 
                $domen_sites = self::replace('/','',$domen_sites);  
                $domen_sites = self::replace('www.','',$domen_sites);
                if($domen_is == 'only' && self::inArray ($_SERVER['HTTP_HOST'],$domen_sites,'www.')){ 
                    return TRUE;
                }
                else if($domen_is == 'without' && !self::inArray ($_SERVER['HTTP_HOST'],$domen_sites,'www.')){ 
                    return TRUE;
                }
            endif;
            
            #2 Require for ip user address
            $ip_user_is = $params->get('ip_user_is', '');//off, only , without
            if($ip_user_is):
                $ip_user = $params->get('ip_user');
                $ip_user = self::replace(',','.',$ip_user);
                $ips_user = self::replace('00.','0.',$ip_user);  
                if($ip_user_is == 'only' && self::inArray ($_SERVER['REMOTE_ADDR'],$ips_user)){ 
                    return TRUE;
                }
                else if($ip_user_is == 'without' && !self::inArray ($_SERVER['REMOTE_ADDR'],$ips_user)){ 
                    return TRUE;
                }
            endif;
            
             
            #3 Require for debug url task "index.php?deb=1"
            $is_debug = $params->get('is_debug', '');//off, only , without
            if($is_debug):
                $is_deb = JFactory::getApplication()->input->getBool('deb');
                if($is_debug == 'only' && $is_deb){
                    return TRUE;
                }
                else if($is_debug == 'without' && !$is_deb){
                    return TRUE;
                }
            endif;
            
            
            #4 Require for items menu
            $menu_is = $params->get('menu_is', '');//off, only , without
            if($menu_is):
                $menu_assigment = (array)$params->get('menu_assigment',[]);
                $menu_id = JFactory::getApplication()->getMenu()->getActive()->id; 
            
                if(in_array(0, $menu_assigment) || empty($menu_assigment)){
                    
                }
                elseif($menu_is == 'only' && in_array($menu_id, $menu_assigment)){
                    return TRUE;
                }
                elseif($menu_is == 'without' && !in_array($menu_id, $menu_assigment)){
                    return TRUE;
                }
            endif;
    
            
            #5 Require for type component
            $component_is = $params->get('component_is', '');//off, only , without
            if($component_is):
                $component_site = (array)$params->get('component_site',[]);  
                $component_id = JFactory::getApplication()->getMenu()->getActive()->component_id; 
                if( empty($component_site)){
                    
                }
                elseif($component_is == 'only' && in_array($component_id, $component_site)){
                    return TRUE;
                }
                elseif($component_is == 'without' && !in_array($component_id, $component_site)){
                    return TRUE;
                }
            endif;
            
            
            
            #6 Require for view component
            $view_is = $params->get('view_is', '');//off, only , without
            if($view_is):
                $views =  $params->get('view_site','');  
                $view_site = JFactory::getApplication()->input->getWord('view'); 
                if(empty($views)){ 
                }
                elseif($view_is == 'only' &&  modMultiModuleHelper::inArray($view_site, $views)){ 
                    return TRUE; 
                }
                else if($view_is == 'without' &&  !modMultiModuleHelper::inArray($view_site, $views)){ 
                    return TRUE;
                }
            endif;
            
    return FALSE; 
}