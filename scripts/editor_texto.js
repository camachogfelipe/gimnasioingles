// JavaScript Document
$(function(){
	$('.tinymce').tinymce({
		// Location of TinyMCE script
		script_url : '../scripts/tinymce/jscripts/tiny_mce/tiny_mce.js',
		// General options
		theme : "advanced",
		language: "es",
		plugins : "pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		file_browser_callback : "filebrowser",
		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,|,image,media,|,cleanup,help,code,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,advhr",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,|,insertdate,inserttime,preview,|,ltr,rtl,|,fullscreen",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		// Example content CSS (should be your site CSS)
		//content_css : "../fslibertad.css",
		// Drop lists for link/image/media/template dialogs
		// Replace values for the template plugin
	});
});

function filebrowser(field_name, url, type, win) {
    
    fileBrowserURL = "../scripts/tinymce/jscripts/tiny_mce/plugins/pdw_file_browser/index.php?editor=tinymce&filter=" + type;
      
    tinyMCE.activeEditor.windowManager.open({
        title: "PDW File Browser",
        url: fileBrowserURL,
        width: 950,
        height: 650,
        inline: 0,
        maximizable: 1,
        close_previous: 0
      },{
        window : win,
        input : field_name
      }
    );    
  }