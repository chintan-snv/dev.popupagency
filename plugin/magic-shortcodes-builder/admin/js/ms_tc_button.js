(function() {
  
    
    tinymce.PluginManager.add('ms_tc_button', function( editor, url ) {
        var filepath = url.replace("js", "");       
        editor.addButton( 'ms_tc_button', {
            text: 'Magic Shortcodes',
            icon: 'icon ms_icon',
            onclick: function() {
                editor.windowManager.open( {
                    title: 'Insert Magic Shortcodes',
                    width: 650,
					// minus head and foot of dialog box
					height: (jQuery( window ).height() - 36 - 50) * 1,
                    inline: 1,
                    //id:'display_advertising_shortcodes_aaa',
                    file: filepath + 'views/magic-shortcodes-admin-options-display.php',
                    buttons: [
                                {
                                    text: 'Insert',
                                    id: 'plugin-slug-button-insert',
                                    class: 'insert',
                                    onclick: function( e ) {                      
                                        var shortcode = getshortcode(filepath);
                                        editor.insertContent(shortcode);
                                        editor.windowManager.close();
                                    },
                                },
                                {
                                    text: 'Cancel',
                                    id: 'ms-tc-button-cancel',
                                    onclick: 'close'
                                }
                            ]
        
    }, {
    path: ABSURL
});
            }
        });
    });
    
    function getshortcode(filepath)
    {  
        var shortcode = "";            
        jQuery.ajax({
            url: filepath+'views/shortcode.txt',
            type: 'get',
            async: false,
            success: function(html) {              
                shortcode = html;              
            }
        });        
        
        
        var domain = filepath.split("wp-content");
         jQuery.post(
			domain[0]+'wp-admin/admin-ajax.php', 
			{
				action:'removeshortcode'
			}, 
            function(jsontext){					
            });
        
     return shortcode;     
    }
   
})();