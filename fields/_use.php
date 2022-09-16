<?php defined('_JEXEC') or die;
/**------------------------------------------------------------------------
# mod_multi - Modules Conatinier 
# ------------------------------------------------------------------------
# author    Sergei Borisovich Korenevskiy
# Copyright (C) 2010 www./explorer-office.ru. All Rights Reserved.
# @package  mod_multi
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: //explorer-office.ru/download/joomla/category/view/1
# Technical Support:  Forum - //fb.com/groups/multimodule
# Technical Support:  Forum - //vk.com/multimodule
-------------------------------------------------------------------------*/ 

use \Joomla\CMS;
use Joomla\CMS\Factory as JFactory;

if($param->work_type_require == 'and'):
        
            #1 Require for web site address 
            if($param->domen_is):
                $domen_sites = $param->domen_site ?: $_SERVER['HTTP_HOST'];
                $domen_sites = self::replace('/','',$domen_sites); 
                $domen_sites = self::replace('www.','',$domen_sites);
                if($param->domen_is == 'only' && !self::inArray ($_SERVER['HTTP_HOST'],$domen_sites,'www.')){
                    //throw new Exception('only');
                    return FALSE;
                }
                else if($param->domen_is == 'without' && self::inArray ($_SERVER['HTTP_HOST'],$domen_sites,'www.')){
                    //throw new Exception('without');
                    return FALSE;
                }
            endif;
            
            #2 Require for ip user address
            if($param->ip_user_is):
                $ip_user = $param->ip_user;
                $ip_user = self::replace(',','.',$ip_user);
                $ips_user = self::replace('00.','0.',$ip_user); 
                if($param->ip_user_is == 'only' && !self::inArray($_SERVER['REMOTE_ADDR'], $ips_user)){
                    return FALSE;
                }
                else if($param->ip_user_is == 'without' && self::inArray($_SERVER['REMOTE_ADDR'], $ips_user)){
                    return FALSE;
                }
            endif;
                         
            #3 Require for debug url task "index.php?deb=1" 
            if($param->is_debug):
                $is_deb = JFactory::getApplication()->input->getBool('deb');
                if($param->is_debug == 'only' && !$is_deb){
                    return FALSE;
                }
                else if($param->is_debug == 'without' && $is_deb){
                    return FALSE;
                }
            endif;
                       
            #4 Require for items menu 
            if($param->menu_is):
                $menu_assigment = (array) ($param->menu_assigment ?: []); 
                $menu_id = JFactory::getApplication()->getMenu()->getActive()->id; 
                if(in_array(0, $menu_assigment) || empty($menu_assigment)){ 
                }
                elseif($param->menu_is == 'only' && !in_array($menu_id, $menu_assigment)){ 
                    return FALSE; 
                }
                else if($param->menu_is == 'without' && in_array($menu_id, $menu_assigment)){ 
                    return FALSE;
                }
            endif;
            
            #5 Require for type component 
            if($param->component_is):
                $component_site = (array)($param->component_site ?:[]); 
                $component_id = JFactory::getApplication()->getMenu()->getActive()->component_id; 
                if(empty($component_site)){ 
                }
                elseif($param->component_is == 'only' &&  !in_array($component_id, $component_site)){ 
                    return FALSE; 
                }
                else if($param->component_is == 'without' &&  in_array($component_id, $component_site)){ 
                    return FALSE;
                }
            endif;
            
            #6 Require for view component 
            if($param->view_is):
                $views =  $param->view_site;  
                $view_site = JFactory::getApplication()->input->getWord('view'); 
                if(empty($views)){ 
                }
                elseif($param->view_is == 'only' &&  !modMultiHelper::inArray($view_site, $views)){ 
                    return FALSE; 
                }
                else if($param->view_is == 'without' &&  modMultiHelper::inArray($view_site, $views)){ 
                    return FALSE;
                }
            endif;
            
            #7 Require for Date shows 
            if($param->date_is):
                $date_start =  $param->date_start ?: ''; 
                $date_stop  =  $param->date_stop ?: '';  
                $now = JDate::getInstance()->format('Y-m-d'); 
                if(empty($date_start) || empty($date_stop)){ 
                } 
                else if($date_stop < $now &&  $now < $date_start){ 
                    return FALSE;
                } 
                else if($now < $date_start && $date_start <= $date_stop){ 
                    return FALSE;
                }
                else if($date_start <= $date_stop && $date_stop < $now){ 
                    return FALSE;
                }
            endif;
                
    return TRUE; 
else://$param->work_type_require == 'or'
        
            #1 Require for web site address
            if($param->domen_is):
                $domen_sites = $param->domen_site ?: $_SERVER['HTTP_HOST']; 
                $domen_sites = self::replace('/','',$domen_sites);  
                $domen_sites = self::replace('www.','',$domen_sites);
                if($param->domen_is == 'only' && self::inArray ($_SERVER['HTTP_HOST'],$domen_sites,'www.')){ 
                    return TRUE;
                }
                else if($param->domen_is == 'without' && !self::inArray ($_SERVER['HTTP_HOST'],$domen_sites,'www.')){ 
                    return TRUE;
                }
            endif;
            
            #2 Require for ip user address 
            if($param->ip_user_is):
                $ip_user = $param->ip_user;
                $ip_user = self::replace(',','.',$ip_user);
                $ips_user = self::replace('00.','0.',$ip_user);  
                if($param->ip_user_is == 'only' && self::inArray ($_SERVER['REMOTE_ADDR'],$ips_user)){ 
                    return TRUE;
                }
                else if($param->ip_user_is == 'without' && !self::inArray ($_SERVER['REMOTE_ADDR'],$ips_user)){ 
                    return TRUE;
                }
            endif;
                         
            #3 Require for debug url task "index.php?deb=1" 
            if($param->is_debug):
                $is_deb = JFactory::getApplication()->input->getBool('deb');
                if($param->is_debug == 'only' && $is_deb){
                    return TRUE;
                }
                else if($param->is_debug == 'without' && !$is_deb){
                    return TRUE;
                }
            endif;
                        
            #4 Require for items menu 
            if($param->menu_is):
                $menu_assigment = (array) ($param->menu_assigment ?: []);
                $menu_id = JFactory::getApplication()->getMenu()->getActive()->id; 
            
                if(in_array(0, $menu_assigment) || empty($menu_assigment)){
                    
                }
                elseif($param->menu_is == 'only' && in_array($menu_id, $menu_assigment)){
                    return TRUE;
                }
                elseif($param->menu_is == 'without' && !in_array($menu_id, $menu_assigment)){
                    return TRUE;
                }
            endif;
                
            #5 Require for type component 
            if($param->component_is):
                $component_site = (array) ($param->component_site ?: []);  
                $component_id = JFactory::getApplication()->getMenu()->getActive()->component_id; 
                if( empty($component_site)){
                    
                }
                elseif($param->component_is == 'only' && in_array($component_id, $component_site)){
                    return TRUE;
                }
                elseif($param->component_is == 'without' && !in_array($component_id, $component_site)){
                    return TRUE;
                }
            endif;
                                    
            #6 Require for view component 
            if($param->view_is):
                $views =  $param->view_site;  
                $view_site = JFactory::getApplication()->input->getWord('view'); 
                if(empty($views)){ 
                }
                elseif($param->view_is == 'only' &&  modMultiHelper::inArray($view_site, $views)){ 
                    return TRUE; 
                }
                else if($param->view_is == 'without' &&  !modMultiHelper::inArray($view_site, $views)){ 
                    return TRUE;
                }
            endif;
            
            #7 Require for Date shows 
            if($param->date_is):
                $date_start =  $param->date_start; 
                $date_stop  =  $param->date_stop;  
                $now = JDate::getInstance()->format('Y-m-d');  
                if(empty($date_start) || empty($date_stop)){
                }
                else if($date_start == $now || $now == $date_stop){
                    return TRUE;
                } 
                else if($date_start < $now && $now < $date_stop){
                    return TRUE;
                }
                else if($now < $date_stop && $date_stop < $date_start){
                    return TRUE;
                }
                else if($date_stop < $date_start && $date_start < $now){
                    return TRUE;
                }
            endif;
            
    return FALSE; 
endif;