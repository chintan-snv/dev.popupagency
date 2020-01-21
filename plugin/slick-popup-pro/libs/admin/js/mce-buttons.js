(function() {
    tinymce.PluginManager.add('sppro', function( editor, url ) {
        var sh_tag = 'sppro';
 
        //helper functions
        function getAttr(s, n) {
            n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
            return n ?  window.decodeURIComponent(n[1]) : '';
        };
 
        function html( cls, data, con) {
			
			id = getAttr(data,'id');
			htmlTag = getAttr(data,'htmltag');
			htmlId = getAttr(data,'htmlid');	
			linkText = getAttr(data,'text');	
			console.log('Hello: '+htmlTag+' : '+linkText+' '+htmlId);
			
			//var placeholder = url + '/img/' + getAttr(data,'type') + '.png';
            var placeholder = url + '/img/default-all.png';
            data = window.encodeURIComponent( data );
            content = window.encodeURIComponent( con );
			
			var placeholder = url + '/img/default-'+htmlTag+'.png';
			var placeholder = url + '/img/default-sppro.png';
			toReturn = '<img src="' + placeholder + '" class="mceItem ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con+'" data-mce-resize="false" data-mce-placeholder="1" />';
			
			//img = '<img src="' + placeholder + '" class="mceItem ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con+'" data-mce-resize="false" data-mce-placeholder="1" />';
            shortcode = '['+ sh_tag +']' + con + '[/'+sh_tag+']';
			
			return shortcode; 
			//return toReturn; 
			
        }
 
        function replaceShortcodes( content ) {
		      return content;            
		   //match [sppro(attr)](con)[/sppro]
            return content.replace( /\[sppro([^\]]*)\]([^\]]*)\[\/sppro\]/g, function( all,attr,con) {
                return html( 'wp-sppro', attr , con);
            });
        }
 
        function restoreShortcodes( content ) {
            //match any image tag with our class and replace it with the shortcode's content and attributes
            return content.replace( /(?:<p(?: [^>]+)?>)*(<span [^>]+>)(?:<\/p>)*/g, function( match, image ) {
                var data = getAttr( image, 'data-sh-attr' );
                var con = getAttr( image, 'data-sh-content' );
				
				//alert('Data: '+data);
 
                if ( data ) {
                    return '[' + sh_tag + data + ']' + con + '[/'+sh_tag+']';
                }
                return match;
            });
        }
 
		var multiplePopups = get_popup_list();
 
        //add popup
        editor.addCommand('sppro_popit', function(ui, v) {
            //setup defaults
            var id = '';
            var linkText = 'click here';
            var linkImage = '';
            var htmlTag = 'span';
            var content = '';
            
			if (v.id) id = v.id;
            if (v.linkText) linkText = v.linkText;
            if (v.linkImage) linkImage = v.linkImage;
            if (v.htmlTag) htmlTag = v.htmlTag;
            if (v.htmlId) htmlId = v.htmlId;
            if (v.content) content = v.content;
            
			//open the popup
            editor.windowManager.open( {
                title: 'Add Slick Popup Shortcode',
                body: [
                    {//add id input
                        type: 'listbox',
                        name: 'id',
                        label: 'Multiple Popup',
						value: id,
                        'values': multiplePopups,
                        tooltip: 'Choose the popup'
                    },
                    {//add htmlTag select
                        type: 'listbox',
                        name: 'htmlTag',
                        label: 'Popup Handle',
                        value: htmlTag,
                        'values': [
                            {text: 'Plain Text', value: 'span'},
                            {text: 'Hyperlink', value: 'a'},
							{text: 'Button', value: 'button'},
							//{text: 'DIV', value: 'div'},
                            //{text: 'Table', value: 'table'},
                            //{text: 'Paragraph', value: 'p'},                            
                        ],
                        tooltip: 'Default: span'
                    },	
                    {//add linkText input
                        type: 'textbox',
                        name: 'linkText',
                        label: 'Text',
                        value: linkText,
                        tooltip: 'Default: Click Here'
                    },
                    {//add linkImage input
                        type: 'textbox',
                        name: 'linkImage',
                        label: 'Link Image',
                        value: linkImage,
                        tooltip: 'Leave blank for none'
                    },				
                    {//add htmlId input
                        type: 'textbox',
                        name: 'htmlId',
                        label: 'HTML ID',
                        //value: htmlId,
                        value: '',
                        tooltip: 'HTML ID for the element'
                    },
                    /*{//add content textarea
                        type: 'textbox',
                        name: 'content',
                        label: 'Popup Content (not in use)',
                        value: content,
                        multiline: true,
                        minWidth: 300,
                        minHeight: 100,
						tooltip: 'It can be used if you want to add more content'
                    }*/
                ],
                onsubmit: function( e ) { //when the ok button is clicked
                    //console.log(e);
					//start the shortcode tag
                    var shortcode_str = '[' + sh_tag;
 
                    //check for id
                    if (typeof e.data.id != 'undefined' && e.data.id.length) 
                        shortcode_str += ' id="' + e.data.id + '"';
						
                    //check for linkText
                    if (typeof e.data.linkText != 'undefined' && e.data.linkText.length)
                        shortcode_str += ' text="' + e.data.linkText + '"';
 
                    //check for linkImage
                    if (typeof e.data.linkImage != 'undefined' && e.data.linkImage.length)
                        shortcode_str += ' image="' + e.data.linkImage + '"';
					
					//check for htmlTag
                    if (typeof e.data.htmlTag != 'undefined' && e.data.htmlTag.length)
                        shortcode_str += ' htmltag="'+e.data.htmlTag+'"';
                    
					//check for htmlId
                    if (typeof e.data.htmlId != 'undefined' && e.data.htmlId.length)
                        shortcode_str += ' htmlid="'+e.data.htmlId+'"';
					
					shortcode_str += ']';
                    
					//check for content
                    if (typeof e.data.content != 'undefined' && e.data.content.length)
                        shortcode_str += e.data.content;
 
                    //add panel content
                    shortcode_str += '[/' + sh_tag + ']';
 
                    //insert shortcode to tinymce
                    editor.insertContent(shortcode_str);
                }
            });
        });
 
        //add button
        editor.addButton('sppro', {
            icon: 'sppro',
            tooltip: 'Slick Popup Pro',
            onclick: function() {
                editor.execCommand('sppro_popit','',{
                    id : '',
                    linkText : '',
                    linkImage : '',
                    type   : 'default',
                    content: ''
                });
            }
        });
 
        //replace from shortcode to an image placeholder
        editor.on('BeforeSetcontent', function(event){
            event.content = replaceShortcodes( event.content );
        });
 
        //replace from image placeholder to shortcode
        editor.on('GetContent', function(event){
            event.content = restoreShortcodes(event.content);
        });
 
        //open popup on placeholder double click
        editor.on('DblClick',function(e) {
            var cls  = e.target.className.indexOf('wp-sppro');
            if ( e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-sppro') > -1 ) {
                var title = e.target.attributes['data-sh-attr'].value;
                title = window.decodeURIComponent(title);
                //console.log(title);
                var content = e.target.attributes['data-sh-content'].value;
                editor.execCommand('sppro_popit','',{
                    id : getAttr(title,'id'),
                    linkText : getAttr(title,'text'),
                    linkImage : getAttr(title,'image'),
                    htmlTag   : getAttr(title,'htmltag'),
                    htmlId   : getAttr(title,'htmlid'),
                    content: content
                });
            }
        });
    });
})();


function get_popup_list() {
	
	var result = [];	
	jQuery.ajax({
		type: "POST",
		url: ajaxurl,
		data: { action: 'get_popup_list' }
	}).done(function(msg) {
		process = jQuery.parseJSON(msg.response);
		jQuery.each(process, function(key, val){
			//console.log("Data Saved: " + key + ':' + val);
			var tmp = { text: val.text, value: val.value};
			result.push(tmp);
		});
	});
    return result;
}