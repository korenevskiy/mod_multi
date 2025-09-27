<?php
 /** ----------------------------------------------------------------------
 * plg_PlaceBilet - Plugin Joomshopping Component for CMS Joomla
 * ------------------------------------------------------------------------
 * author    Sergei Borisovich Korenevskiy
 * @copyright (C) 2019 //explorer-office.ru. All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @package plg_PlaceBilet
 * Websites: //explorer-office.ru/download/joomla/category/view/1
 * Technical Support:  Forum - //fb.com/groups/multimodulefb.com/groups/placebilet/
 * Technical Support:  Forum - //vk.com/placebilet
 * -------------------------------------------------------------------------
 **/ 
//defined('_JEXEC') or die;
//return;

sleep(3);

if(! class_exists('\Joomla\CMS\Factory')){
	$files = &HelperMinification::languageMinificationRaw();
	
	
	
	
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($files, JSON_PRETTY_PRINT);
	
	
//	echo "Start minification!<br>";
//	echo '<br>Count:' . count($files);
//	echo '<pre>'.print_r($files,true).'</pre>';
	
//	echo "<br><br>";
	
//	echo __DIR__ . '/' . array_key_first($files);//$files[0];
//	echo "<br>";
//	echo "<br>";
	
//	echo "<pre>";
//	echo str_replace("\n", "<br>", htmlspecialchars(file_get_contents(__DIR__ . array_key_first($files))));
//	echo "</pre>";
	
}





class HelperMinification{


	public static function languageMinificationRaw($pathLanugageDir = ''){
		
		
//		$lang = JFactory::getApplication()->getLanguage();
		
		$default_path = $pathLanugageDir ?: __DIR__;
		
//		$dir = getcwd();
		
		$dir = $default_path;
		
		while(!file_exists($dir . '/language')){
			$dir = dirname($dir);
		}

		$dir .= '/language/*';
		
		$language = realpath(__DIR__ . "/../../../language/");
//		echo "$language <br>";
		
//		$files = Joomla\Filesystem\Folder::files($dir, "raw.ini", true, true);
		
//		$dirs = scandir($dir,  SCANDIR_SORT_NONE);
//		$dirs = glob($dir . '/*');
//		$dirs = array_filter(glob('*'), 'is_dir');
		
		$files = [];
		$length = 0;
		
		foreach (array_filter(glob($dir), 'is_dir') as &$dir){
			foreach (glob($dir.'/*.raw.ini') as $file){
				$text = file_get_contents($file);
				$countLines = count(explode("\n", $text));
				$text = str_replace("\n[", "[{<!>}][", $text);			//	\n[		-
				$text = str_replace("]\n", "][{<!>}]", $text);			//	]\n		-
				$text = str_replace("]\r", "][{<!>}]", $text);			//	]\r		-
				$text = str_replace(";\r\n", ";[{<!>}]", $text);		//	;\r\n	-
				$text = str_replace(";\n", ";[{<!>}]", $text);			//	;\n	-
				$text = str_replace(";\r", ";[{<!>}]", $text);			//	;\r	-
				$text = str_replace(";;", "[{<!>}]", $text);			//	;;		-
				
				$text = str_replace("\"\r\n", "\"[{<!>}]", $text);		//	"\r\n	-
				$text = str_replace("\"\n", "\"[{<!>}]", $text);		//	"\n		-
				$text = str_replace("\"\r", "\"[{<!>}]", $text);		//	"\r		-
				
				$text = str_replace("\r\n", '', $text);					//	\r\n	-
				$text = str_replace("\n", '', $text);					//	\n		-
				$text = str_replace("\r", '', $text);					//	\r		-
				$text = str_replace('[{<!>}]', PHP_EOL, $text);			
				$file = str_replace('.raw.ini', '.ini', $file);
				$f = file_put_contents($file, $text);
				$file = str_replace(__DIR__ . '/../../../../../', '.ini', $file);
				
				$path = str_replace($default_path, '', $file);
				
				$file_work = $language . $path;
//				$files[$file_work] = file_exists($file_work);
				if(file_exists($file_work))
					file_put_contents($file_work, $text);
				
				
						
				
				$countLines2 = count(explode("\n", $text));
//				$files[] = str_replace($default_path, '', $file) ." -- count:$countLines , newCount:$countLines2";
				$files[$path] = "          -- count:$countLines , newCount:$countLines2, exist:".(file_exists($file_work)?:0) ;
				
				if(strlen($path) > $length)
					$length = strlen($path);
			}
			
		}
		foreach ($files as  $f => $desc){
			unset($files[$f]);
			$files[str_pad($f, $length + 1)] = $desc;
		}
		return $files;
	}
}
