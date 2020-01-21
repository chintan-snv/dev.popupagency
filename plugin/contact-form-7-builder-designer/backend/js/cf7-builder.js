(function( $ ) {
    $(function() {
        //var cft_builder_ctrl = '<div class="cf7-ctrl cf7-ctrl-inner"><div class="cf7-move"></div><div class="cf7-edit"></div><div class="cf7-remove"></div></div>';
        



        $('#cf7-builder-id-icon').iconpicker();
        var cf7_id_container ="";
        $( ".cf7-tag-generator-list li" ).draggable({
          helper: "clone",
          revert: "invalid",
        zIndex: "99999",

    	});
        

        $(".cf7-builder-container-row").droppable({
            over: function(event, ui) {
                if(  ui.draggable.attr("id") == "cf7-builder-row") {
                    $(this).addClass('cf7_builder_hover');
                }    
               
            },
            out: function(event, ui) {
                $("div").removeClass('cf7_builder_hover');
            },
	       	drop: function( event, ui ) {
                $("div").removeClass('cf7_builder_hover');
                if(!$(ui.draggable).hasClass("dropped")) {
    	       		var parent_clas = ui.draggable.context.parentNode.className ;
    	       		var id =  ui.draggable.attr("id");
    	       		if( id == "cf7-builder-row") {     
                        var d = new Date();           
                        var $contrl = '<div class="cf7-ctrl"><div class="cf7-move"></div><div class="cf7-colunm"></div><div class="cf7-dub cf7-dub-row"></div><div class="cf7-remove"></div></div>';
                        var $abc = $('<div class="cf7-builder-container-row1 cf7-builder-container-row-inner cf7-main-builder dropped" id="cf7-container-builder-'+d.getTime()+'">'+$contrl+'<div class="cf7-row"><div class="col-md-12"><div class="cf7-builder-container-row-inner-drop"></div></div></div></div>');
    	       			$( this ).append($abc);
    	       		}else{
                        

    	       		}
		        }else{

                }
                droppable_inner();
                cf7_get_data();
      		}
    	}).sortable();
        var droppable_inner = function() {
            $(".cf7-builder-container-row-inner-drop").droppable({
            over: function(event, ui) {
                if($(this)[0]!=$(ui.draggable).parent()[0] && !$(ui.draggable).hasClass("cf7-builder-container-row-inner") &&   ui.draggable.attr("id") != "cf7-builder-row" ) {
                     $(this).addClass('cf7_builder_hover');
                    }
                 
              
            },
            out: function(event, ui) {
                $("div").removeClass('cf7_builder_hover');
            },
                    drop: function( event, ui ) {
                        $("div").removeClass('cf7_builder_hover');
                        var $orig = $(ui.draggable);
                        if(!$(ui.draggable).hasClass("dropped") ) {
                            var parent_clas = ui.draggable.context.parentNode.className ;
                            var id =  ui.draggable.attr("id");
                            var type =  ui.draggable.find('a').attr('href');
                            var hide_head = false;
                            if( type.search("heading") >0 || type.search("submit") > 0 || type.search("text_content") >0 ) {
                                    hide_head = true;
                            }
                            if( id == "cf7-builder-row") { 
                               
                            }else{
                                var d = new Date(); 
                                cf7_id_container = "cf7-container-builder-"+d.getTime();
                                var font= '<div class="cf7_font"></div>';
                                var $contrl = '<div class="cf7-ctrl cf7-ctrl-inner"><div class="cf7-move"></div><div class="cf7-edit"></div><div class="cf7-dub cf7-dub-text"></div><div class="cf7-remove"></div></div>';
                                if( hide_head ) {
                                    var $text ='<div class="cf7-text cf7-main-builder dropped" id="'+cf7_id_container+'">'+$contrl+font+'<div class="cf7_text_shortcode">[text* your-name]</div></div>';
                                }else{
                                    var $text ='<div class="cf7-text cf7-main-builder dropped" id="'+cf7_id_container+'">'+$contrl+'<div class="cf7_text_label">Text Label</div>'+font+'<div class="cf7_text_shortcode">[text* your-name]</div></div>';
                                }
                                
                                $( this ).append($text);
                                ui.draggable.find('a').click();
                            }
                        }else{
                            if($(this)[0]!=$orig.parent()[0] && !$(ui.draggable).hasClass("cf7-builder-container-row-inner") ) {
                                    console.log($orig);
                                    var $el = $orig
                                        .clone().css({"position": "relative", "left": "auto", "top": "auto","width": "auto"});
                                        $( this ).append($el);
                                $orig.remove(); 
                            }else{
                                
                            }
                        }
                        cf7_get_data(); 
                    }
                }).sortable();
        }
        droppable_inner();
         $( 'input.insert-tag' ).click( function(e) {
                e.preventDefault();
                var $form = $( this ).closest( 'form.tag-generator-panel' );
                var tag = $form.find( 'input.tag' ).val();
                $("#"+cf7_id_container).find(".cf7_text_shortcode").html(tag);
                tb_remove();
                cf7_get_data();
                return false;
            } );
        $(document).on("click", ".cf7-colunm", function(e) {
            $("#cf7_pu_type").val(1);
            var id = $(this).closest(".cf7-main-builder").attr("id");
            $("#cf7-builder-id-col").val(id);
            $("#cf7-builder-id-col").show();
            //$("#cf7-builder-id-label,#cf7-builder-id-value-content").hide();
            $(".cf7_col_data").removeClass('hidden');
            $(".cf7_col_text").addClass('hidden');
            tb_show("Contact Form 7", "#TB_inline?width=600&height=200&inlineId=cf7_add_thickbox", "");
           return false;
        });
        $(document).on("click", ".cf7-edit", function(e) {
            $("#cf7_pu_type").val(0);
            var id = $(this).closest(".cf7-text").attr("id");
            var content = $(this).closest(".cf7-text").find('.cf7_text_shortcode').html();
            var label = $(this).closest(".cf7-text").find('.cf7_text_label').html();
            var font = $(this).closest(".cf7-text").find('.cf7_font i').attr("class");
            console.log(font);
            if( font !== undefined ) {
               font = font.replace("fa ", "");
            }
            $("#cf7-builder-id-icon").val(font);
            $("#cf7-builder-id-col").val(id);
            $("#cf7-builder-id-value-content").val(content);
            $("#cf7-builder-id-label").val(label);
            $(".cf7_col_data").addClass('hidden');
            $(".cf7_col_text").removeClass('hidden');
            tb_show("Contact Form 7", "#TB_inline?width=600&height=200&inlineId=cf7_add_thickbox", "");
           return false;
        });
        $(document).on("click", ".cf7-dub-row", function(e) {
            var d = new Date(); 
            var id = d.getTime();
            var html = '<div class="cf7-builder-container-row1 cf7-builder-container-row-inner cf7-main-builder dropped" id="cf7-container-builder-'+id+'">' + $(this).closest('.cf7-builder-container-row-inner').html() + "</div>";
            html = html.replace(/id=".*?"/g,function myFunction(x){
                var inner_id = Math.floor(Math.random() * 10000000);
                return 'id="cf7-container-builder-'+inner_id + '"';
            });
            $(this).closest('.cf7-builder-container-row-inner').parent().append(html);
             droppable_inner();
             cf7_get_data();
        })
         $(document).on("click", ".cf7-dub-text", function(e) {
            var d = new Date(); 
            var id = d.getTime();
            var html = '<div class="cf7-text cf7-main-builder dropped ui-sortable-handle" id="cf7-container-builder-'+id+'">' + $(this).closest('.cf7-text').html() + "</div>";
            html = html.replace(/id=".*?"/g,function myFunction(x){
                var inner_id = Math.floor(Math.random() * 10000000);
                return 'id="cf7-container-builder-'+inner_id + '"';
            });
            $(this).closest('.cf7-text').parent().append(html);
             droppable_inner();
            cf7_get_data();
        })
        $("body").on("click",".update-cf7",function(e){
            var id = $("#cf7-builder-id-col").val();
            var type = $("#cf7_pu_type").val();
            if( type == 1 ) {
                      var data = $("#cf7-builder-id-value").val();
                        data = data.split("+");
                        var data_row = $("#"+id+ " .cf7-row>div");
                     
                        
                        var html ="";
                        for (i = 0; i < data.length; i++) { 
                          
                            if( typeof data_row[i] !== 'undefined' ) {
                                 
                                html += '<div class="col-md-'+data[i]+'"><div class="cf7-builder-container-row-inner-drop">'+data_row[i].childNodes[0].innerHTML + "</div></div>";
                            }else{
                                html += '<div class="col-md-'+data[i]+'"><div class="cf7-builder-container-row-inner-drop"></div></div>';
                            }  
                           
                        }
                        var $contrl = '<div class="cf7-ctrl"><div class="cf7-move"></div><div class="cf7-colunm"></div><div class="cf7-dub cf7-dub-row"></div><div class="cf7-remove"></div></div>';
                        $("#"+id).html( $contrl + '<div class="cf7-row">'+ html +"</div>");
                        
            }else{
                var data = $("#cf7-builder-id-value-content").val();
                var label = $("#cf7-builder-id-label").val();
                var font = $("#cf7-builder-id-icon").val();
                if( font !== ""){
                     $("#"+id).addClass('cf7_input_icon');
                    $("#"+id).find(".cf7_font").html('<i class="fa '+font+'" aria-hidden="true"></i>');
                }else{
                    $("#"+id).removeClass('cf7_input_icon');
                    $("#"+id).find(".cf7_font").html('');
                }
                
                $("#"+id).find(".cf7_text_shortcode").html(data);
                $("#"+id).find(".cf7_text_label").html(label);
                
            }
          droppable_inner();
          cf7_get_data();
          tb_remove();
        })
        $("body").on("click",".cf7-remove",function(e){
            $(this).closest('.cf7-main-builder').remove();
            cf7_get_data();
            return false;
        })
        $(".cf7-switch-html").click(function(event) {
            /* Act on the event */
            event.preventDefault();
            $(".wp-editor-tabs-cf7 button").removeClass('active');
            $(this).addClass('active');
            $("#wpcf7-form").removeClass('hidden');
            $("#cf7-builder-container-row").addClass('hidden');
            $("#cf7_builder_type").val(0);
            cf7_get_data();
        });
         $(".cf7-switch-builder").click(function(event) {
            /* Act on the event */
            event.preventDefault();
            $(".wp-editor-tabs-cf7 button").removeClass('active');
            $(this).addClass('active');

            $("#wpcf7-form").addClass('hidden');
            $("#cf7-builder-container-row").removeClass('hidden');
            $("#cf7_builder_type").val(1);
            var value = $("#wpcf7-form").val();
            let searchParams = new URLSearchParams(window.location.search);
            if( searchParams.has('post') ) {
                    if( value.search("cf7-builder-container-row-inner") > 0 ) {
                        value = value.replace('<div class="cf7-text cf7-main-builder dropped ui-sortable-handle ui-sortable-placeholder" ></div>',"");
                        $("#cf7-builder-container-row").html( value );
                    }else{
                        var custom = '<div class="cf7-builder-container-row1 cf7-builder-container-row-inner cf7-main-builder dropped" id="cf7-container-builder-1511678423660"><div class="cf7-ctrl"><div class="cf7-move"></div><div class="cf7-colunm"></div><div class="cf7-dub cf7-dub-row"></div><div class="cf7-remove"></div></div><div class="cf7-row"><div class="col-md-12"><div class="cf7-builder-container-row-inner-drop ui-droppable ui-sortable"><div class="cf7-text cf7-main-builder dropped" id="cf7-container-builder-1511678425644"><div class="cf7-ctrl cf7-ctrl-inner"><div class="cf7-move"></div><div class="cf7-edit"></div><div class="cf7-dub cf7-dub-text"></div><div class="cf7-remove"></div></div><div class="cf7_text_shortcode">';
                            custom += value + '</div></div></div></div></div></div>';
                        $("#cf7-builder-container-row").html( custom );
                    }
            }else{
               
                     
                var text_defaul = '<div class="cf7-builder-container-row1 cf7-builder-container-row-inner cf7-main-builder dropped" id="cf7-container-builder-1511681290919"><div class="cf7-ctrl"><div class="cf7-move"></div><div class="cf7-colunm"></div><div class="cf7-dub cf7-dub-row"></div><div class="cf7-remove"></div></div><div class="cf7-row"><div class="col-md-12"><div class="cf7-builder-container-row-inner-drop ui-droppable ui-sortable"><div class="cf7-text cf7-main-builder dropped" id="cf7-container-builder-1511783505381"><div class="cf7-ctrl cf7-ctrl-inner"><div class="cf7-move"></div><div class="cf7-edit"></div><div class="cf7-dub cf7-dub-text"></div><div class="cf7-remove"></div></div><div class="cf7_text_label">Your Name (required)</div><div class="cf7_font"></div><div class="cf7_text_shortcode">[text* your-name]</div></div><div class="cf7-text cf7-main-builder dropped ui-sortable-handle" id="cf7-container-builder-4060317"><div class="cf7-ctrl cf7-ctrl-inner"><div class="cf7-move"></div><div class="cf7-edit"></div><div class="cf7-dub cf7-dub-text"></div><div class="cf7-remove"></div></div><div class="cf7_text_label">Your Email (required)</div><div class="cf7_font"></div><div class="cf7_text_shortcode">[email* your-email]</div></div><div class="cf7-text cf7-main-builder dropped ui-sortable-handle" id="cf7-container-builder-4528487"><div class="cf7-ctrl cf7-ctrl-inner"><div class="cf7-move"></div><div class="cf7-edit"></div><div class="cf7-dub cf7-dub-text"></div><div class="cf7-remove"></div></div><div class="cf7_text_label">Subject</div><div class="cf7_font"></div><div class="cf7_text_shortcode">[text your-subject]</div></div><div class="cf7-text cf7-main-builder dropped ui-sortable-handle" id="cf7-container-builder-4936959"><div class="cf7-ctrl cf7-ctrl-inner"><div class="cf7-move"></div><div class="cf7-edit"></div><div class="cf7-dub cf7-dub-text"></div><div class="cf7-remove"></div></div><div class="cf7_text_label">Your Message</div><div class="cf7_font"></div><div class="cf7_text_shortcode">[textarea your-message]</div></div><div class="cf7-text cf7-main-builder dropped ui-sortable-handle" id="cf7-container-builder-4191720"><div class="cf7-ctrl cf7-ctrl-inner"><div class="cf7-move"></div><div class="cf7-edit"></div><div class="cf7-dub cf7-dub-text"></div><div class="cf7-remove"></div></div><div class="cf7_text_label"></div><div class="cf7_font"></div><div class="cf7_text_shortcode">';
text_defaul += '[submit "Send"]</div></div></div></div></div></div>';

                $("#cf7-builder-container-row").html( text_defaul );
            }
            
            droppable_inner();
        });

        $(".cf7-row-cl li").click(function(){
            var col = $(this).data("col");
            $("#cf7-builder-id-value").val(col);
            return false;
        })
        var cf7_get_data = function(content) {
                var content = $("#cf7-builder-container-row").html();
                content = content.replace(/style=".*?"/g,"");
                  content = content.replace('<div class="cf7-text cf7-main-builder dropped ui-sortable-handle ui-sortable-placeholder" ></div>',"");
                  content = content.replace('<div class="cf7-builder-container-row1 cf7-builder-container-row-inner cf7-main-builder dropped ui-sortable-handle ui-sortable-placeholder" ></div>',"");
                $("#wpcf7-form").val(content.trim());

            };
        if( $('.cf7-tag-generator-list').length > 0) {
        var width_cf7_div = $(".cf7-tag-generator-list");
        var width_cf7_height = $(".cf7-tag-generator-list").offset().top - 100;;
            $(window).scroll(function(){ 
                var sticky = $('.cf7-tag-generator-list');
                var scroll = $(window).scrollTop();

                  if (scroll >= width_cf7_height ){
                    sticky.addClass('cf7_fixed');
                    sticky.css('width', width_cf7_div+'px');
                  }else{
                    sticky.css('width', 'auto');
                    sticky.removeClass('cf7_fixed');
                  }
                })
        }

    })
})( jQuery );