/**
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

var $ck = window.$ck || jQuery.noConflict();

$ck(document).ready(function(){
	if ($ck('#ckfolderupload').length) ckAddDndForImageUpload(document.getElementById('ckfolderupload'));
});

if (typeof(ckInitTooltips) != 'function') {
	function ckInitTooltips() {
		// $ck('.hasTip').tooltip({"html": true,"container": "body"});
	}
}

if (typeof(ckAddWaitIcon) != 'function') {
	/**
	 * Add the spinner icon
	 */
	function ckAddWaitIcon(button) {
		$ck(button).addClass('ckwait');
	}
}
if (typeof(ckRemoveWaitIcon) != 'function') {
	/**
	 * Remove the spinner icon
	 */
	function ckRemoveWaitIcon(button) {
		$ck(button).removeClass('ckwait');
	}
}
/*------------------------------------------------------
 * Functions for the image drag and drop upload
 *-----------------------------------------------------*/

function ckReadDndImages(holder, files) {
	// empty the place if there is already an image -> no !!
	// if ($ck(holder).find('img').length) $ck(holder).find('img').remove();
	var formData = !!window.FormData ? new FormData() : null;
    for (var i = 0; i < files.length; i++) {
		if (!files[i].type.match(/^image\//) && !files[i].type.match(/^video\//) && !files[i].type.match(/^audio\//)) {
			alert('The file must be an image : ' + files[i].name) ;
			continue ;
		}
		if (!!window.FormData) formData.append('file', files[i]);
		if ($ck('.ckfoldertree.ckcurrent').length) formData.append('path', $ck('.ckfoldertree.ckcurrent').attr('data-path'));
    
	if (!!window.FormData) {
		$ck(holder).append('<progress max="100" value="0" class="progress"></progress>');
		var holderProgress = $ck(holder).find('.progress');
		var myurl = 'index.php?option=com_slideshowck&task=ajaxAddPicture&' + cktoken + '=1';
		$ck.ajax({
			type: "POST",
			url: myurl,
			// async: false,
			data: formData,
			dataType: 'json',
			processData: false,  // indique � jQuery de ne pas traiter les donn�es
			contentType: false,  // indique � jQuery de ne pas configurer le contentType
			xhr: function () {
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener("progress", function (evt) {
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						holderProgress.val(
							percentComplete * 100
						);
						if (percentComplete === 1) {
							holderProgress.addClass('hide');
						}
					}
				}, false);
				xhr.addEventListener("progress", function (evt) {
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						holderProgress.val(
							percentComplete * 100
						);
					}
				}, false);
				return xhr;
			}
		}).done(function(response) {
			if(typeof response.error === 'undefined')
			{
				// Success
				if(typeof response.img !== 'undefined') {
					holderProgress.remove();
					if ($ck('.ckfoldertree').length) {
					// if the image already exists, return
					if ($ck('.ckfoldertree.ckcurrent').find('> .ckfoldertreefiles').find('[data-filename="' + response.filename + '"]').length) return;

					$ck('.ckfoldertree.ckcurrent').find('> .ckfoldertreename > .ckfoldertreecount').text(parseInt($ck('.ckfoldertree.ckcurrent').find('> .ckfoldertreename > .ckfoldertreecount').text())+1);
					$ck('.ckfoldertree.ckcurrent').find('> .ckfoldertreefiles')
						.append('<div class="ckfoldertreefile" data-filename="' + response.filename + '" data-path="'+ $ck('.ckfoldertree.ckcurrent').attr('data-path') +'" onclick="ckSelectFile(this)" data-type="image">'
						+ '<img title="' + response.filename + '" data-src="' + response.img + '" src="' + URIROOT + response.img+'" />'
						+ '</div>');
					$ck('#ckfileupload').val('');
					} else {
						holderProgress.remove();
						if ($ck(holder).find('img').length) {
							$ck(holder).find('img').attr('src', URIROOT + response.img).attr('data-src', response.img);
						} else {
							$ck(holder).find('.imageck').append('<img data-src="'+response.img+'" src="'+URIROOT + response.img+'" />');
						}
					}
				}
			} else {
				alert('ERROR: ' + response.error);
			}
		}).fail(function() {
			// alert(Joomla.JText._('CK_FAILED', 'Failed'));
		});
    }
	}
}

function ckAddDndForImageUpload(holder) {
	if (typeof FileReader == 'undefined') return;
		if ('draggable' in document.createElement('span')) {
			holder.ondragover = function () { $ck(holder).addClass('ckdndhover'); return false; };
			holder.ondragleave = function () { $ck(holder).removeClass('ckdndhover'); return false; };
			holder.ondragend = function () { $ck(holder).removeClass('ckdndhover'); return false; };
			holder.ondrop = function (e) {
				$ck(holder).removeClass('ckdndhover');
				e.preventDefault();
				ckReadDndImages(holder, e.dataTransfer.files);
			}
		} else {
			alert('Message : Drag and drop for images not supported');
			// fileupload.className = 'hidden';
		}
		$ck('#ckfileupload').on('change', function () {
			ckReadDndImages(holder, this.files);
		});
}

/*------------------------------------------------------
 * END of image drag and drop 
 *-----------------------------------------------------*/


function ckAddFolder() {
	$ck('.ckcurrent .ckfoldertreepathwayaddfolder').hide();
	$ck('.ckcurrent .ckfoldertreepathwayfoldername, .ckcurrent .ckfoldertreepathwaycreatefolder').addClass('ckshow');
}

function ckCreateFolder(btn, path) {
	var folderName = $ck(btn).parent().find('input').val();
	var myurl = SLIDESHOWCK_URL + "&task=ajaxCreateFolder&" + CKTOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			path: path,
			name: folderName
		}
	}).done(function(code) {
		var result = JSON.parse(code);
		if (result.status == '1') {
			alert(result.message);
			var currentpath = $ck('.ckcurrent');
			var code = '';
			if (! currentpath.find('.cksubfolder').length) code += '<div class="cksubfolder">';
			
			code += '<div class="ckfoldertree" data-level="' + (parseInt(currentpath.attr('data-level'))+1) + '" data-path="' + path + '/' + folderName + '">'
			+'<div class="ckfoldertreetoggler" onclick="ckToggleTreeSub(this)"></div>'
			+'<div class="ckfoldertreename" onclick="ckShowFiles(this)"><span class="icon-folder"></span>' + folderName + '<div class="ckfoldertreecount">0</div>'
			+'</div>'
			+'<div class="ckfoldertreefiles">'
				+'<div class="ckfoldertreepathway ckinterface">'
					+'<span>images</span><span class="ckfoldertreepath">'+path+'</span><span class="ckfoldertreepath">' + folderName + '</span>'
									+'<span class="ckfoldertreepathwayactions">'
						+'<span class="ckfoldertreepathwayaddfolder ckbutton" onclick="ckAddFolder()" style="display: none;">Add a subfolder</span>'
						+'<span class="ckfoldertreepathwayfoldername ckshow"><input type="text" class="ckfoldertreepathwayaddfoldername"></span>'
						+'<span class="ckfoldertreepathwaycreatefolder ckbutton ckshow" onclick="ckCreateFolder(this, \'' + path + '/' + folderName + '\')">Create folder</span>'
					+'</span>'
								+'</div>'
					+'</div>'
				+'</div>';

			if (! currentpath.find('.cksubfolder').length) code += '</div>';
			if (! currentpath.find('.cksubfolder').length) {
				$ck('.ckcurrent').append(code);
			} else {
				$ck('.ckcurrent > .cksubfolder').append(code);
			}
		} else if (result.status == '2') {
			alert(result.message);
		} else {
			alert(CKApi.Text._('CK_FAILED', 'Failed'));
		}
	}).fail(function() {
		alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});
}

function ckLoadFiles(btn, type, folder) {
	if ($ck(btn).hasClass('ckdone')) {
		ckFocusFolder(btn);
		return;
	}
	var myurl = SLIDESHOWCK.BASE_URL + '&task=browse.getFiles&' + SLIDESHOWCK.TOKEN;
	$ck.ajax({
	type: "POST",
	url: myurl,
	data: {
		folder: folder,
		type: type
		}
	}).done(function(result) {
		if (result) {
			$ck(btn).find('+ .ckfoldertreefiles').empty().append(result);
			$ck(btn).addClass('ckdone');
		}
		ckFocusFolder(btn);
	}).fail(function() {
		alert('A problem occured when trying to create the module. Please retry.');
	});
}

function ckFocusFolder(btn) {
	// set the current state on the folder
	var item = $ck(btn).parent();
	$ck('.ckcurrent').not(btn).removeClass('ckcurrent');
	if (item.hasClass('ckcurrent')) {
		item.removeClass('ckcurrent');
	} else {
		item.addClass('ckcurrent')
	}
}