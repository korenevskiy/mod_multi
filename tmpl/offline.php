<?php
/*------------------------------------------------------------------------
# mod_multi - Modules Conatinier
# ------------------------------------------------------------------------
# author    Sergei Borisovich Korenevskiy
# Copyright (C) 2010 www./explorer-office.ru. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: //explorer-office.ru/download/joomla/category/view/1
# Technical Support:  Forum - //fb.com/groups/multimodule
# Technical Support:  Forum - //vk.com/multimodule
-------------------------------------------------------------------------*/
defined('_JEXEC') || TRUE || die;
if(defined('_JEXEC')){

    $app = JFactory::getApplication();
    $doc = JFactory::getDocument();

$app->redirect(JRoute::_('/modules/mod_multi/tmpl/offline.php',false));

    return;
}

$param = $params;//*** new \Reg($params)->toObject()

    defined('JPATH_SITE') or define('JPATH_SITE', realpath(__DIR__.'/../../..'));
    defined('JPATH_PLATFORM') or define('JPATH_PLATFORM', JPATH_SITE.'/libraries');
    $config = (JPATH_SITE.'/configuration.php');
    if(file_exists($config)){
        require_once $config;
        $config = new JConfig;
    }else{
        $config = FALSE;
    }
    $session_name = '';
    $opt = [];
    if($config){
        $session_name = md5($config->secret . '');
        $opt = [ 'name' => $session_name,'expire' => 900,'force_ssl' => $config->force_ssl];
    }

$FormToken = function ($length = 8, $secret = '' ){
		$salt = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$base = strlen($salt);
		$makepass = '';
		$random = random_bytes($length + 1);
		$shift = ord($random[0]);

		for ($i = 1; $i <= $length; ++$i)
		{
			$makepass .= $salt[($shift + ord($random[$i])) % $base];
			$shift += ord($random[$i]);
		}

		return md5($secret . 0 .$makepass);
};

$token = '<input type="hidden" name="' . $FormToken(32,$config->secret) . '" value="1"' . $attributes . ' />';

 $offline_image = $config->offline_image;

 $images = [];
 $images[] = '/'.$offline_image;
 $images[] = '/images/site.jpg';
 $images[] = '/images/site.png';
 $images[] = '/images/site.webp';
 $images[] = '/images/site.gif';
 $images[] = '/images/site.swf';
 $images[] = '/images/site.psd';
 $images[] = '/images/site.tiff';

 foreach ($images as $i => $im){
    if(file_exists(JPATH_SITE.'/'.$im)){
         $offline_image = $im;
         break;
}   }

$im = getimagesize(JPATH_SITE.'/'.$offline_image);
$image_width = $im[0];
$image_height = $im[1];
$image_type = $im[2];
$image_width = $im[3];

$lang = 'en-GB';
$langs = [];
foreach(glob(JPATH_SITE."/language/*-*") as $dir){
    $dir = substr($dir, strlen(JPATH_SITE) + 10 );
    list($l1,$l2) = explode('-', $dir);
    if(file_exists(JPATH_SITE."/language/$dir/$dir.ini") && $dir != 'en-GB')
        $langs[] = $dir;
}
$langs[] = 'en-GB';
foreach (explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $lan){
    list($l1) = explode(';', $lan);
    foreach ($langs as $dir){
        $pos = strpos($dir, $l1);
        if($pos === 0 || $pos){
            $lang = $dir;
            break 2;
        }
    }
}
$langs = parse_ini_file(JPATH_SITE."/language/$lang/$lang.ini");

    $title = $sitename = $config->sitename;

    $fromname = $config->fromname;
    $mailfrom = $config->mailfrom;

    $replyto  = $config->replyto;
    $replytoname  = $config->replytoname;

    $offline_message = $message = $description = $config->offline_message;
    $display_offline_message = $config->$display_offline_message;

$JText_ = function ($str = '') use ($langs){
    return $langs[$str] ?: $str;
};
?>
<html>
    <style>
    </style>
<body style="background:url(<?= $offline_image ?>) center top no-repeat,white ; height:<?= $image_height?>">
    <meta charset="utf-8">

<?php

?>
<img src="<?= $image ?>" alt="<?= $sitename ?>"   style="width:auto; height: auto; margin: 0 -2000px; left:-5000px; position: relative; display:block;" />
<div id="frame" class="outline" style="position: absolute;position: fixed; top: 20px; left: 20px; right: 20px; width: min-content; margin: auto; border-radius: 20px; box-shadow: 0 5px 20px #000; background-color: rgba(255,255,255,0.9);display:table;  ">
            <img src="/modules/mod_multi/media/img/animated_girl.gif" alt="<?= $sitename ?>"  style="border-radius: 10px;  margin: 10px;"/>
		<?php if ($image) : ?>
			<img src="<?= $image; ?>" alt="<?= $sitename ?>" />
		<?php endif; ?>
		<h1 style="border-radius: 10px;  margin: 10px;">
			<?= $sitename ?> <a  style="font-size: small;" href="mailto:<?= $mailfrom ?>" target="__blank"><?= $mailfrom ?> </a>
		</h1>
	<?php if ($display_offline_message == 1 && str_replace(' ', '', $offline_message) != '') : ?>
		<p style="border-radius: 10px;  margin: 10px;">
			<?= $offline_message ?>
		</p>
	<?php elseif ($display_offline_message == 2 && str_replace(' ', '', $JText_('JOFFLINE_MESSAGE')) != '') : ?>
		<p style="border-radius: 10px;  margin: 10px;">
			<?php echo $JText_('JOFFLINE_MESSAGE'); ?>
		</p>
	<?php endif; ?>
	<form action="<?= '/administrator/index.php' ?>" method="post" id="form-login">
            <fieldset class="input" style="border-radius: 10px; border: 1px solid gray; margin: 10px;">
		<p id="form-login-username"  style="display:flex; justify-content: space-between;">
			<label for="username" style="flex: 1 ;"><?php echo $JText_('JGLOBAL_USERNAME'); ?></label>
			<input name="username" id="username" type="text" class="inputbox"   style="border-radius:5px;" alt="<?php echo $JText_('JGLOBAL_USERNAME'); ?>" size="18" />
		</p>
		<p id="form-login-password"  style="display:flex; justify-content: space-between;">
			<label for="passwd" style="flex: 1 ;"><?php echo $JText_('JGLOBAL_PASSWORD'); ?></label>
			<input name="passwd"  id="passwd"type="password" class="inputbox" size="18"  style="border-radius:5px;" alt="<?php echo $JText_('JGLOBAL_PASSWORD'); ?>" />
		</p>
		<?php if (count($twofactormethods) > 1) : ?>
			<p id="form-login-secretkey">
				<label for="secretkey"><?php echo $JText_('JGLOBAL_SECRETKEY'); ?></label>
				<input type="text" name="secretkey" class="inputbox" size="18" alt="<?php echo $JText_('JGLOBAL_SECRETKEY'); ?>" id="secretkey" />
			</p>
		<?php endif; ?>
		<p id="submit-buton"  style="display:flex; justify-content: center;">
			<label>&nbsp;</label>
			<input type="submit" name="Submit" style="border-radius:5px;"  class="button login" value="<?php echo $JText_('JLOGIN'); ?>" />
		</p>
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.login" />
		<input type="hidden" name="return" value="<?= $return ?>" />
		<?php $token;/*echo JHtml::_('form.token');*/ ?>
	</fieldset>
	</form>
	</div>
</body>
</html>
