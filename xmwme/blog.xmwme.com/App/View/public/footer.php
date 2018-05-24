<div class="sidebar" style="position: absolute;">
        <div id="archives-2" class="widget sidebox widget_archive" style="display: block;">
            <i class="fa fa-caret-down"></i>
            <h2>博客归档</h2>	
            <ul>
                <?php foreach($blog_date as $date){ ?>
                <li><a href="<?=Root?>index/blogdate/?date=<?=$date['NF']?>"><?=str_replace('-','年',$date['NF'])?>月(<?=$date['num']?>)</a></li>
                <?php } ?>                                      
            </ul>
        </div>
        <div id="meta-2" class="widget sidebox widget_meta" style="display: block;"><i class="fa fa-caret-down"></i><h2>功能</h2>	
            <ul>
                <li><a href="http://blog.xmwme.com" title='松松的博客'>文章<abbr title="松松的博客">JSQ</abbr></a></li>
            </ul>
        </div>	
    
         <div id="nav_menu-3" class="widget sidebox widget_nav_menu"><i class="fa fa-caret-down"></i>
             <h2>友情链接</h2>	
            <ul>
                <li><a href="http://wap.xmwme.com" target='_blank' title='松松的博客'>小游戏<abbr title="松松的博客"></abbr></a></li>
                <li><a href="http://1105235512.lofter.com" target='_blank' title='松松的博客'>历史博客<abbr title="松松的博客"></abbr></a></li>
            </ul>            
        </div>	
    
    </div>
    <script src="<?= Resource ?>js/jquery1.0.js"></script>
    <script src="<?= Resource ?>js/jquery.poshytip.min.js"></script>
    <script src="<?= Resource ?>js/custom.js"></script>
    <script>
        //custom fixed sidebar
        jQuery(function($){
        var topOffset = 50;
        var displayElementId = new Array('hot_tags-2', 'nav_menu-3', 'category-list');
        $(window).scroll(function(){
        if ($(this).scrollTop() >= $(".sidebar").height() + topOffset) {
        if ($(".sidebar").css('position') == 'absolute') {
        $(".sidebar").children().hide().each(function(){
        _this = this;
        $.each(displayElementId, function(i, val){
        if ($(_this).attr('id') == val)
                $(_this).show();
        });
        });
        $(".sidebar").css('position', 'fixed');
        //console.log('fixed');
        }
        } else {
        if ($(".sidebar").css('position') == 'fixed') {
        $(".sidebar").css("position", 'absolute').children().show();
        //console.log('absolute');
        }
        }
        });
        });
    </script>
   
    <script type="text/javascript">
        /* <![CDATA[ */
        var JQLBSettings = {"fitToScreen":"0", "resizeSpeed":"400", "displayDownloadLink":"0", "navbarOnTop":"0", "loopImages":"", "resizeCenter":"", "marginSize":"", "linkTarget":"", "help":"", "prevLinkTitle":"previous image", "nextLinkTitle":"next image", "prevLinkText":"\u00ab Previous", "nextLinkText":"Next \u00bb", "closeTitle":"close image gallery", "image":"Image ", "of":" of ", "download":"Download", "jqlb_overlay_opacity":"80", "jqlb_overlay_color":"#000000", "jqlb_overlay_close":"1", "jqlb_border_width":"10", "jqlb_border_color":"#ffffff", "jqlb_border_radius":"0", "jqlb_image_info_background_transparency":"100", "jqlb_image_info_bg_color":"#ffffff", "jqlb_image_info_text_color":"#000000", "jqlb_image_info_text_fontsize":"10", "jqlb_show_text_for_image":"1", "jqlb_next_image_title":"next image", "jqlb_previous_image_title":"previous image", "jqlb_next_button_image":"http:\/\/blog.star7th.com\/wp-content\/plugins\/wp-lightbox-2\/styles\/images\/next.gif", "jqlb_previous_button_image":"http:\/\/blog.star7th.com\/wp-content\/plugins\/wp-lightbox-2\/styles\/images\/prev.gif", "jqlb_maximum_width":"", "jqlb_maximum_height":"", "jqlb_show_close_button":"1", "jqlb_close_image_title":"close image gallery", "jqlb_close_image_max_heght":"22", "jqlb_image_for_close_lightbox":"http:\/\/blog.star7th.com\/wp-content\/plugins\/wp-lightbox-2\/styles\/images\/closelabel.gif", "jqlb_keyboard_navigation":"1", "jqlb_popup_size_fix":"0"};
        /* ]]> */
    </script>
    <script type="text/javascript" src="<?= Resource ?>js/wp-lightbox-2.min.js"></script>
    <script type="text/javascript">
        /* <![CDATA[ */
        var mejsL10n = {"language":"zh-CN", "strings":{"Close":"\u5173\u95ed", "Fullscreen":"\u5168\u5c4f", "Download File":"\u4e0b\u8f7d\u6587\u4ef6", "Download Video":"\u4e0b\u8f7d\u89c6\u9891", "Play\/Pause":"\u64ad\u653e\/\u6682\u505c", "Mute Toggle":"\u5207\u6362\u9759\u97f3", "None":"\u65e0", "Turn off Fullscreen":"\u5173\u95ed\u5168\u5c4f", "Go Fullscreen":"\u5168\u5c4f", "Unmute":"\u53d6\u6d88\u9759\u97f3", "Mute":"\u9759\u97f3", "Captions\/Subtitles":"\u5b57\u5e55"}};
        var _wpmejsSettings = {"pluginPath":"\/wp-includes\/js\/mediaelement\/"};
        /* ]]> */
    </script>
    <script type="text/javascript" src="<?= Resource ?>js/mediaelement-and-player.min.js"></script>
    <script type="text/javascript" src="<?= Resource ?>js/wp-mediaelement.js"></script>
    <script type="text/javascript" src="<?= Resource ?>js/comments-ajax.js"></script>
</body>
</html>