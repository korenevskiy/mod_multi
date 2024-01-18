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
                if($param->domen_is == 'only' && !self::inArray($_SERVER['HTTP_HOST'],$domen_sites,'www.')){
                    return FALSE;
                }
                else if($param->domen_is == 'without' && self::inArray($_SERVER['HTTP_HOST'],$domen_sites,'www.')){

                    return FALSE;
                }
            endif;
//echo $param->ip_user_is;
            #2 Require for ip user address
            if($param->ip_user_is):
                $ip_param = $param->ip_user;
                $ip_param = modMultiHelper::replace('.000.','.0.',$ip_param);
                $ip_param = modMultiHelper::replace('.00.','.0.',$ip_param);
				$ip_param = modMultiHelper::replace(' ',',',$ip_param);
				$ip_param = modMultiHelper::replace(';',',',$ip_param);
				$ip_user = ip2long($_SERVER['REMOTE_ADDR']);
				$ip_exist = false;
				foreach (explode(',', $ip_param) as $ip){
					if(ip2long($ip) == $ip_user){
						$ip_exist = true;
						break;
					}
				}
//                
//echo $param->ip_user_is;
//echo ' <br>';
//echo $ips_user;
//echo ' <br>';
//echo $_SERVER['REMOTE_ADDR'];
//echo ' :'. modMultiHelper::inArray($_SERVER['REMOTE_ADDR'], $ips_user);
				
                if($param->ip_user_is == 'only' && !$ip_exist){
                    return FALSE;
                }
                else if($param->ip_user_is == 'without' && $ip_exist){

echo $_SERVER['REMOTE_ADDR'];
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
			
            #8 Require for url
            if($param->url_is ?? FALSE):
				
				$url_sites = explode("\n", ($param->url_site ?? ''));
			
				$url_string = ($_SERVER['QUERY_STRING']?:' ');
				
				$url_scheme = (string)($_SERVER['REQUEST_SCHEME']??'');
				$url_host = (string)($_SERVER['HTTP_HOST']??'');
				$url_uri = (string)($_SERVER['REQUEST_URI']??'');	
			
				foreach ($url_sites as $url){
					
					$url = trim($url," ");
					
					if($url == '')
						continue;
					
					if($url == '/' && $_SERVER['REQUEST_URI'] != '/')
						return FALSE;
					
					switch($url){
						case ($url_scheme):
						
						case $url_scheme.'://'.$url_host:
						case '//'.$url_host:
							
						case $url_scheme.'://'.$url_host.'/':
						case '//'.$url_host.'/':
							
						case $url_scheme.'://'.$url_host.'?':
						case '//'.$url_host.'?':
							
						case $url_scheme.'://'.$url_host.$url_uri:
						case '//'.$url_host.$url_uri:
						case $url_uri: 
						
						case trim($url_uri, "/"):
						case $url_string:
							
						case $url_scheme.'://'.$url_host.'?'.$url_string:
						case '//'.$url_host.'?'.$url_string:
						case '?'.$url_string:
							
						case $url_scheme.'://'.$url_host.'/?'.$url_string:
						case '//'.$url_host.'/?'.$url_string:
						case '/?'.$url_string:
							break;
						
						default:
							return FALSE;
					}
					
					if($_SERVER['QUERY_STRING'] && strpos($url, '=')){
						
						$q_strings = [];
						foreach (explode('&', $_SERVER['QUERY_STRING']) as $qs){
							$q = explode('=', $qs);
							$q_strings[($u[0] ?? '')] = $q[1] ?? '';
							if(isset($q_strings['']))
								return FALSE;
						}
						
						foreach (explode('&', $url) as $us){
							$u = explode('=', $us);
							
							$url_all_exist =  isset($q_strings[($u[0] ?? '')]) && $q_strings[($u[0] ?? '')] == ($u[1] ?? '');
							if($url_all_exist == FALSE)
								return FALSE;
						}
					}
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
                $ip_param = $param->ip_user;
                $ip_param = modMultiHelper::replace('.000.','.0.',$ip_param);
                $ip_param = modMultiHelper::replace('.00.','.0.',$ip_param);
				$ip_param = modMultiHelper::replace(' ',',',$ip_param);
				$ip_param = modMultiHelper::replace(';',',',$ip_param);
				$ip_user = ip2long($_SERVER['REMOTE_ADDR']);
				$ip_exist = false;
				foreach (explode(',', $ip_param) as $ip){
					if(ip2long($ip) == $ip_user){
						$ip_exist = true;
						break;
					}
				}
                if($param->ip_user_is == 'only' && $ip_exist){
                    return TRUE;
                }
                else if($param->ip_user_is == 'without' && !$ip_exist){
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
				
            #8 Require for url
            if($param->url_is):
				$url_sites = explode("\n", ($param->url_site ?? ''));
				
				$url_scheme = (string)($_SERVER['REQUEST_SCHEME']??'');
				$url_host = (string)($_SERVER['HTTP_HOST']??'');
				$url_uri = (string)($_SERVER['REQUEST_URI']??'');	
				
				foreach ($url_sites as $url){
					
					$url = trim($url," ");
					
					if($url == '')
						continue;
					
					if($url == '/' && $_SERVER['REQUEST_URI'] == '/')
						return TRUE;
					
					$url_request = $_SERVER['REQUEST_URI'];
					$url_string = ($_SERVER['QUERY_STRING']?:' ');
					
//					$url = trim($url,"/");
					
					switch($url){
						case $url_scheme:
							
						case $url_scheme.'://'.$url_host:
						case '//'.$url_host:
							
						case $url_scheme.'://'.$url_host.'/':
						case '//'.$url_host.'/':
							
						case $url_scheme.'://'.$url_host.'?':
						case '//'.$url_host.'?':
							
						case $url_scheme.'://'.$url_host.$url_uri:
						case '//'.$url_host.$url_uri:
						case $url_uri: 
						
						case trim($url_uri, "/"):
						case $url_string:
							
						case $url_scheme.'://'.$url_host.'?'.$url_string:
						case '//'.$url_host.'?'.$url_string:
						case '?'.$url_string:
							
						case $url_scheme.'://'.$url_host.'/?'.$url_string:
						case '//'.$url_host.'/?'.$url_string:
						case '/?'.$url_string: 
							return TRUE;
							break;						
					}
					
					if($_SERVER['QUERY_STRING'] && strpos($url, '=')){
						
						$q_strings = [];
						foreach (explode('&', $_SERVER['QUERY_STRING']) as $qs){
							$q = explode('=', $qs);
							$q_strings[($u[0] ?? '')] = $q[1] ?? '';
						}
						
						$url_all_exist = TRUE;
						foreach (explode('&', $url) as $us){
							$u = explode('=', $us);
							
							$url_all_exist =  isset($q_strings[($u[0] ?? '')]) && $q_strings[($u[0] ?? '')] == ($u[1] ?? '');
							if(empty($url_all_exist) || isset($q_strings['']))
								break;
						}
						if($url_all_exist)
							return TRUE;
					}
				}
            endif;
            
			
    return FALSE; 
endif;