<?php

defined('_JEXEC') or die();
/**
 * ------------------------------------------------------------------------
 * # mod_multi - Modules Conatinier
 * # ------------------------------------------------------------------------
 * # author Sergei Borisovich Korenevskiy
 * # Copyright (C) 2010 www./explorer-office.ru. All Rights Reserved.
 * # @package mod_multi
 * # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * # Websites: //explorer-office.ru/download/joomla/category/view/1
 * # Technical Support: Forum - //fb.com/groups/multimodule
 * # Technical Support: Forum - //vk.com/multimodule
 * -------------------------------------------------------------------------
 */

/*
 * ** ------------------------ Карусель с вращающимся фотками/карточками -------------------------------------------- ***
 */

$param = $params; // *** new \Reg($params)->toObject()

$id = $params->get('id');
$positon = $params->get('position');

$style = $params->get('style');
$mod_show = count($modules);

$module_tag = $params->get('module_tag', 'div');
$moduleclass_sfx = $params->get('moduleclass_sfx');

$showtitle = $params->get('showtitle');

$title = ($params->get('title'));

$header_tag = $params->get('header_tag', 'h3');
$header_class = htmlspecialchars($params->get('header_class', 'module-header'));

$link_show = $params->get('link_show');
$link = $params->get('link');

$modules;
$modules_tag = $params->get('modules_tag');

if ($module_tag2 = $params->get('module_tag2'))
    echo "<$module_tag2 class=\"multimodule" . $params->get('moduleclass_sfx2') . " count$mod_show id$id $style\"  >";

if ($showtitle) :
    $titlea = "";
    if ($link_show == 'ha')
        $titlea = "<$header_tag class=\"$header_class\"><a href=\"$link\" title=\"" . strip_tags($title) . "\" class=\"id$id multiheadera\">$title</a></$header_tag>";
    elseif ($link_show || $link_show == 'ah')
        $titlea = "<a href=\"$link\" title=\"" . strip_tags($title) . "\" class=\"id$id multiheadera\"><$header_tag class=\"$header_class\">$title</$header_tag></a>";
    elseif (empty($link_show))
        $titlea = "<$header_tag class=\"$header_class\">$title</$header_tag>";

    if (in_array($style, [
        'System-none',
        'none',
        'no',
        '0',
        0,
        ''
    ], true))
        echo $titlea;
    else
        ${$mod}->title = $titlea;
endif;

if ($tag = $params->get('modules_tag3')) {
    $tgs = explode('/', $tag);
    $tag_title = $tgs[0] ?? FALSE;
    $tag_block = $tgs[1] ?? FALSE;
    $tag_container = $tgs[2] ?? FALSE;
    $tag_item = $tgs[3] ?? FALSE;
}

$elements = [];
echo "<div id='multislideshowid$param->id'  class='gallery items count$count $moduleclass_sfx  id$param->id Creating3DPerspectiveCarousel' style='height: 80vh;'>";
echo "<canvas id='slideshow$param->id' width='100%' height='800'></canvas>";
echo "</div>";

$images = [];

foreach ($modules as $type => $items) {
    $order = substr($type, 0, 2);
    $type = substr($type, 2);
    if (is_string($items)) {
        echo $items;
        continue;
    }
    $count = count($items);
    foreach ($items as $id => $module) {
        $module->moduleclass_sfx = $module->moduleclass_sfx ?? '';
        $module->moduleclass_sfx .= "  countype$count order$order $type  ";
        $elements[] = $module;
        $images[] = "'" . $module->image . "'";
    }
}

?>
<script type='text/javascript'>

var canvas<?=$param->id?>, ctx<?=$param->id?>;
var aImages<?=$param->id?> = [];
var points<?=$param->id?> = [];
var triangles<?=$param->id?> = [];
var textureWidth<?=$param->id?>, textureHeight<?=$param->id?>;
var lev<?=$param->id?> = 3;
var angle<?=$param->id?> = 0;

/* scene vertices */
var vertices<?=$param->id?> = [
    new Point3D<?=$param->id?>(-2,-1,2),
    new Point3D<?=$param->id?>(2,-1,2),
    new Point3D<?=$param->id?>(2,1,2),
    new Point3D<?=$param->id?>(-2,1,2),
    new Point3D<?=$param->id?>(-2,-1,-2),
    new Point3D<?=$param->id?>(2,-1,-2),
    new Point3D<?=$param->id?>(2,1,-2),
    new Point3D<?=$param->id?>(-2,1,-2)
];

/* scene faces (6 faces) */
var faces<?=$param->id?>  = [[0,1,2,3],[1,5,6,2],[5,4,7,6],[4,0,3,7],[0,4,5,1],[3,2,6,7]];

function Point3D<?=$param->id?>(x,y,z) {
    this.x = x;
    this.y = y;
    this.z = z;

    this.rotateX = function(angle) {
        var rad, cosa, sina, y, z
        rad = angle * Math.PI / 180
        cosa = Math.cos(rad)
        sina = Math.sin(rad)
        y = this.y * cosa - this.z * sina
        z = this.y * sina + this.z * cosa
        return new Point3D<?=$param->id?>(this.x, y, z)
    }
    this.rotateY = function(angle) {
        var rad, cosa, sina, x, z
        rad = angle * Math.PI / 180
        cosa = Math.cos(rad)
        sina = Math.sin(rad)
        z = this.z * cosa - this.x * sina
        x = this.z * sina + this.x * cosa
        return new Point3D<?=$param->id?>(x,this.y, z)
    }
    this.rotateZ = function(angle) {
        var rad, cosa, sina, x, y
        rad = angle * Math.PI / 180
        cosa = Math.cos(rad)
        sina = Math.sin(rad)
        x = this.x * cosa - this.y * sina
        y = this.x * sina + this.y * cosa
        return new Point3D<?=$param->id?>(x, y, this.z)
    }
    this.projection = function(viewWidth, viewHeight, fov, viewDistance) {
        var factor, x, y
        factor = fov / (viewDistance + this.z)
        x = this.x * factor + viewWidth / 2
        y = this.y * factor + viewHeight / 2
        return new Point3D<?=$param->id?>(x, y, this.z)
    }
}
<?= "var aImgs$param->id = [".implode(',', $images)."];" ?>
for (var i = 0; i < aImgs<?=$param->id?>.length; i++) {
    var oImg<?=$param->id?> = new Image();
    oImg<?=$param->id?>.src = aImgs<?=$param->id?>[i];
    aImages<?=$param->id?>.push(oImg<?=$param->id?>);

    oImg<?=$param->id?>.onload = function () {
        textureWidth<?=$param->id?> = oImg<?=$param->id?>.width;
        textureHeight<?=$param->id?> = oImg<?=$param->id?>.height;
    }
}

window.onload = function(){
    width = document.getElementById('multislideshowid<?= $param->id?>').clientWidth;
    height = document.getElementById('multislideshowid<?= $param->id?>').clientHeight;
    // creating canvas<?=$param->id?> objects
    canvas<?=$param->id?> = document.getElementById('slideshow<?= $param->id?>');
    canvas<?=$param->id?>.width = width;
    canvas<?=$param->id?>.height = height;
    ctx<?=$param->id?> = canvas<?=$param->id?>.getContext('2d');

    /* prepare points */
    for (var i = 0; i <= lev<?=$param->id?>; i++) {
        for (var j = 0; j <= lev<?=$param->id?>; j++) {
            var tx = (i * (textureWidth<?=$param->id?> / lev<?=$param->id?>));
            var ty = (j * (textureHeight<?=$param->id?> / lev<?=$param->id?>));
            points<?=$param->id?>.push({
                tx: tx,
                ty: ty,
                nx: tx / textureWidth<?=$param->id?>,
                ny: ty / textureHeight<?=$param->id?>,
                ox: i,
                oy: j
            });
        }
    }

    /* prepare triangles ---- */
    var levT = lev<?=$param->id?> + 1;
    for (var i = 0; i < lev<?=$param->id?>; i++) {
        for (var j = 0; j < lev<?=$param->id?>; j++) {
            triangles<?=$param->id?>.push({
                p0: points<?=$param->id?>[j + i * levT],
                p1: points<?=$param->id?>[j + i * levT + 1],
                p2: points<?=$param->id?>[j + (i + 1) * levT],
                up: true
            });
            triangles<?=$param->id?>.push({
                p0: points<?=$param->id?>[j + (i + 1) * levT + 1],
                p1: points<?=$param->id?>[j + (i + 1) * levT],
                p2: points<?=$param->id?>[j + i * levT + 1],
                up: false
            });
        }
    }

/* MultiModule ModuleID:$param->id - $style_layout */
    drawScene<?=$param->id?>();
};

function drawScene<?=$param->id?>() {
    /* clear context */
    ctx<?=$param->id?>.clearRect(0, 0, ctx<?=$param->id?>.canvas.width, ctx<?=$param->id?>.canvas.height);

    /*  rotate scene */
    var t = new Array();
    for (var iv = 0; iv < vertices<?=$param->id?>.length; iv++) {
        var v = vertices<?=$param->id?>[iv];
        var r = v.rotateY(angle<?=$param->id?>);
        //var r = v.rotateX(angle<?=$param->id?>).rotateY(angle<?=$param->id?>);
        var prj = r.projection(ctx<?=$param->id?>.canvas.width, ctx<?=$param->id?>.canvas.height, 1000, 3);
        t.push(prj)
    }

    var avg_z = new Array();
    for (var i = 0; i < faces<?=$param->id?>.length; i++) {
        var f = faces<?=$param->id?>[i];
        avg_z[i] = {"ind":i, "z":(t[f[0]].z + t[f[1]].z + t[f[2]].z + t[f[3]].z) / 4.0};
    }

    /*  get around through all faces  */
    for (var i = 0; i < faces<?=$param->id?>.length; i++) {
        var f = faces<?=$param->id?>[avg_z[i].ind];

        if (t[f[3]].z+t[f[2]].z+t[f[1]].z+t[f[0]].z > -3) {
            ctx<?=$param->id?>.save();

            /* draw surfaces */
            ctx<?=$param->id?>.fillStyle = "rgb(252,211,164)";
            ctx<?=$param->id?>.beginPath();
            ctx<?=$param->id?>.moveTo(t[f[0]].x,t[f[0]].y);
            ctx<?=$param->id?>.lineTo(t[f[1]].x,t[f[1]].y);
            ctx<?=$param->id?>.lineTo(t[f[2]].x,t[f[2]].y);
            ctx<?=$param->id?>.lineTo(t[f[3]].x,t[f[3]].y);
            ctx<?=$param->id?>.closePath();
            ctx<?=$param->id?>.fill();

            /* draw stretched images */
            if (i < 4) {
                var ip = points<?=$param->id?>.length;
                while (--ip > -1) {
                    var p = points<?=$param->id?>[ip];
                    var mx = t[f[0]].x + p.ny * (t[f[3]].x - t[f[0]].x);
                    var my = t[f[0]].y + p.ny * (t[f[3]].y - t[f[0]].y);
                    p.px = (mx + p.nx * (t[f[1]].x + p.ny * (t[f[2]].x - t[f[1]].x) - mx)) + p.ox;
                    p.py = (my + p.nx * (t[f[1]].y + p.ny * (t[f[2]].y - t[f[1]].y) - my)) + p.oy;
                }

                var n = triangles<?=$param->id?>.length;
                while (--n > -1) {
                    var tri = triangles<?=$param->id?>[n];
                    var p0 = tri.p0;
                    var p1 = tri.p1;
                    var p2 = tri.p2;

                    var xc = (p0.px + p1.px + p2.px) / 3;
                    var yc = (p0.py + p1.py + p2.py) / 3;

                    ctx<?=$param->id?>.save();
                    ctx<?=$param->id?>.beginPath();
                    ctx<?=$param->id?>.moveTo((1.05 * p0.px - xc * 0.05), (1.05 * p0.py - yc * 0.05));
                    ctx<?=$param->id?>.lineTo((1.05 * p1.px - xc * 0.05), (1.05 * p1.py - yc * 0.05));
                    ctx<?=$param->id?>.lineTo((1.05 * p2.px - xc * 0.05), (1.05 * p2.py - yc * 0.05));
                    ctx<?=$param->id?>.closePath();
                    ctx<?=$param->id?>.clip();

                    /* transformation */
                    var d = p0.tx * (p2.ty - p1.ty) - p1.tx * p2.ty + p2.tx * p1.ty + (p1.tx - p2.tx) * p0.ty;
                    ctx<?=$param->id?>.transform(
                        -(p0.ty * (p2.px - p1.px) -  p1.ty * p2.px  + p2.ty *  p1.px + (p1.ty - p2.ty) * p0.px) / d, // m11
                         (p1.ty *  p2.py + p0.ty  * (p1.py - p2.py) - p2.ty *  p1.py + (p2.ty - p1.ty) * p0.py) / d, // m12
                         (p0.tx * (p2.px - p1.px) -  p1.tx * p2.px  + p2.tx *  p1.px + (p1.tx - p2.tx) * p0.px) / d, // m21
                        -(p1.tx *  p2.py + p0.tx  * (p1.py - p2.py) - p2.tx *  p1.py + (p2.tx - p1.tx) * p0.py) / d, // m22
                         (p0.tx * (p2.ty * p1.px  -  p1.ty * p2.px) + p0.ty * (p1.tx *  p2.px - p2.tx  * p1.px) + (p2.tx * p1.ty - p1.tx * p2.ty) * p0.px) / d, // dx
                         (p0.tx * (p2.ty * p1.py  -  p1.ty * p2.py) + p0.ty * (p1.tx *  p2.py - p2.tx  * p1.py) + (p2.tx * p1.ty - p1.tx * p2.ty) * p0.py) / d  // dy
                    );
                    ctx<?=$param->id?>.drawImage(aImages<?=$param->id?>[i], 0, 0);
                    ctx<?=$param->id?>.restore();
                }
            }
        }
    }

    /*  shift angle and redraw scene */
    angle<?=$param->id?> += 0.3;
    setTimeout(drawScene<?=$param->id?>, 40);
}
 </script>
<?php

$count = count($elements);

if ($module_tag2)
    echo "</$module_tag2>";

static $script;

// <editor-fold defaultstate="collapsed" desc="Scrypt Carousel for count 5">
if (empty($script)) {

    $mod_path = Juri::base() . "modules/mod_multi/media/carousel-Animated-3D-Cube-Slideshow/";

    $script = <<< script
    
    /* MultiModule ModuleID:$param->id - $style_layout */
    jQuery(function($){
    
    });
    
    script;

    JFactory::getDocument()->addScriptDeclaration($script);
}
// </editor-fold>
?> 
