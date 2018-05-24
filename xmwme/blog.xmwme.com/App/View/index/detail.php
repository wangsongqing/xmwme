<?php include View . '/public/header.php'; ?>
<div class="container">
    <div class="article-list">
        <article class="article">
            <h1><a href="http://blog.xmwme.com/index/detail/?id=<?=$data['id']?>" title="<?=$data['title']?>" alt="<?=$data['title']?>"><?=$data['title']?></a></h1>

            <div class="content">
             <?=$data['content']?>
                <div style="clear:both; margin-top:5px; margin-bottom:5px;"></div>
                <div style="clear:both; margin-top:5px; margin-bottom:5px;"></div>					
                <div class="article-copyright">
                    <i class="fa fa-share-alt"></i> 码字很辛苦，转载请注明来自<b><a href="http://blog.xmwme.com/">松松的技术博客</a></b>的<a href="http://blog.xmwme.com/">《<?=$data['title']?>》</a>
                </div>
            </div>
            <div class="article-info">
                <i class="fa fa-calendar"></i> <?=date('Y-m-d',$data['created'])?> &nbsp; <i class="fa fa-map-marker"></i> 
                <a href="http://blog.xmwme.com/" rel="category tag"><?=$class[$data['class']]?></a>                
            </div>
        </article>        <section class="comments">
            <h1>评论</h1>
            <div class="content">

                <div id="comments">
                    <ol class="commentlist">
                        <li class="comment even thread-even depth-1" id="li-comment-2701">
                            <div id="comment-2701" class="comment-body">
                                <div class="comment-author"><img src="https://secure.gravatar.com/avatar/7b8a375634bca045cc96cd72de913c19?s=64" class="avatar avatar-64" height="64" width="64"></div>
                                <div class="comment-head">
                                    <span class="name">木偶</span>
                                    <span class="num"> #1</span>
                                    <p> <p>博主，如果是在项目中直接设置session 是不是更好一些<br />
                                        ini_set(&#8216;session.save_handler&#8217;, &#8216;redis&#8217;);<br />
                                        ini_set(&#8216;session.save_path&#8217;, &#8216;tcp://127.0.0.1:6379&#8217;);</p>
                                    </p>
                                    <div class="post-reply"><a rel='nofollow' class='comment-reply-link' href='http://blog.star7th.com/2016/04/1996.html?replytocom=2701#respond' onclick='return addComment.moveForm("comment-2701", "2701", "respond", "1996")' aria-label='回复给木偶'>回复</a></div>
                                    <div class="date">2017-08-22</div>
                                </div>

                            </div>
                            <ul class="children">
                                <li class="comment byuser comment-author-chenxingqi bypostauthor odd alt depth-2" id="li-comment-2702">
                                    <div id="comment-2702" class="comment-body">
                                        <div class="comment-author"><img src="https://secure.gravatar.com/avatar/28688ae0c93f06c9f73a3e6ba79e0508?s=64" class="avatar avatar-64" height="64" width="64"></div>
                                        <div class="comment-head">
                                            <span class="name">第七星尘</span>
                                            <span class="num"> </span>
                                            <p> <p>都可以的。写在项目中也行。但为了省得每个项目都配置一下，干脆统一修改配置文件了。</p>
                                            </p>
                                            <div class="post-reply"><a rel='nofollow' class='comment-reply-link' href='http://blog.star7th.com/2016/04/1996.html?replytocom=2702#respond' onclick='return addComment.moveForm("comment-2702", "2702", "respond", "1996")' aria-label='回复给第七星尘'>回复</a></div>
                                            <div class="date">2017-08-23</div>
                                        </div>

                                    </div>
                                </li><!-- #comment-## -->
                            </ul><!-- .children -->
                        </li><!-- #comment-## -->
                        <li class="comment even thread-odd thread-alt depth-1" id="li-comment-2700">
                            <div id="comment-2700" class="comment-body">
                                <div class="comment-author"><img src="https://secure.gravatar.com/avatar/7b8a375634bca045cc96cd72de913c19?s=64" class="avatar avatar-64" height="64" width="64"></div>
                                <div class="comment-head">
                                    <span class="name">木偶</span>
                                    <span class="num"> #2</span>
                                    <p> <p>棒</p>
                                    </p>
                                    <div class="post-reply"><a rel='nofollow' class='comment-reply-link' href='http://blog.star7th.com/2016/04/1996.html?replytocom=2700#respond' onclick='return addComment.moveForm("comment-2700", "2700", "respond", "1996")' aria-label='回复给木偶'>回复</a></div>
                                    <div class="date">2017-08-22</div>
                                </div>

                            </div>
                        </li><!-- #comment-## -->
                        <li class="comment odd alt thread-even depth-1" id="li-comment-1922">
                            <div id="comment-1922" class="comment-body">
                                <div class="comment-author"><img src="https://secure.gravatar.com/avatar/?s=64" class="avatar avatar-64" height="64" width="64"></div>
                                <div class="comment-head">
                                    <span class="name"><a href='http://t.qq.com/maoyuping9819' rel='external nofollow' class='url'>毛雨平</a></span>
                                    <span class="num"> #3</span>
                                    <p> <p>不错</p>
                                    </p>
                                    <div class="post-reply"><a rel='nofollow' class='comment-reply-link' href='http://blog.star7th.com/2016/04/1996.html?replytocom=1922#respond' onclick='return addComment.moveForm("comment-1922", "1922", "respond", "1996")' aria-label='回复给毛雨平'>回复</a></div>
                                    <div class="date">2017-03-28</div>
                                </div>

                            </div>
                        </li><!-- #comment-## -->
                        <li class="comment even thread-odd thread-alt depth-1" id="li-comment-1634">
                            <div id="comment-1634" class="comment-body">
                                <div class="comment-author"><img src="https://secure.gravatar.com/avatar/?s=64" class="avatar avatar-64" height="64" width="64"></div>
                                <div class="comment-head">
                                    <span class="name"><a href='http://t.qq.com/forgaoqiang' rel='external nofollow' class='url'>高强</a></span>
                                    <span class="num"> #4</span>
                                    <p> <p>nice</p>
                                    </p>
                                    <div class="post-reply"><a rel='nofollow' class='comment-reply-link' href='http://blog.star7th.com/2016/04/1996.html?replytocom=1634#respond' onclick='return addComment.moveForm("comment-1634", "1634", "respond", "1996")' aria-label='回复给高强'>回复</a></div>
                                    <div class="date">2016-09-1</div>
                                </div>

                            </div>
                        </li><!-- #comment-## -->
                    </ol>
                    <div id="respond" class="respond">
                        <form action="" method="post" id="commentform" class="comment-form">
                            <h3 class="clearfix"><span id="cancel-comment-reply"><a rel="nofollow" id="cancel-comment-reply-link" href="/2016/04/1996.html#respond" style="display:none;">点击这里取消回复。</a></span></h3>
                            <div id="author_info">
                                <p>
                                    <input type="text" name="author" id="author" class="text" size="15" value="" />
                                    <label for="author"><small>姓名</small></label>
                                </p>
                                <p>
                                    <input type="text" name="email" id="mail" class="text" size="15" value="" />
                                    <label for="mail"><small>邮箱</small></label>
                                </p>
                                <p>
                                    <input type="text" name="url" id="url" class="text" size="15" value="" />
                                    <label for="url"><small>网站</small></label>
                                </p>
                            </div>
                            <div id="author_textarea">

                                <a href="javascript:grin(':?:')"      ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_question.gif"  alt="" /></a>
                                <a href="javascript:grin(':razz:')"   ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_razz.gif"      alt="" /></a>
                                <a href="javascript:grin(':sad:')"    ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_sad.gif"       alt="" /></a>
                                <a href="javascript:grin(':evil:')"   ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_evil.gif"      alt="" /></a>
                                <a href="javascript:grin(':!:')"      ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_exclaim.gif"   alt="" /></a>
                                <a href="javascript:grin(':smile:')"  ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_smile.gif"     alt="" /></a>
                                <a href="javascript:grin(':oops:')"   ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_redface.gif"   alt="" /></a>
                                <a href="javascript:grin(':grin:')"   ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_biggrin.gif"   alt="" /></a>
                                <a href="javascript:grin(':eek:')"    ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_surprised.gif" alt="" /></a>
                                <a href="javascript:grin(':shock:')"  ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_eek.gif"       alt="" /></a>
                                <a href="javascript:grin(':???:')"    ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_confused.gif"  alt="" /></a>
                                <a href="javascript:grin(':cool:')"   ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_cool.gif"      alt="" /></a>
                                <a href="javascript:grin(':lol:')"    ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_lol.gif"       alt="" /></a>
                                <a href="javascript:grin(':mad:')"    ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_mad.gif"       alt="" /></a>
                                <a href="javascript:grin(':twisted:')"><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_twisted.gif"   alt="" /></a>
                                <a href="javascript:grin(':roll:')"   ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_rolleyes.gif"  alt="" /></a>
                                <a href="javascript:grin(':wink:')"   ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_wink.gif"      alt="" /></a>
                                <a href="javascript:grin(':idea:')"   ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_idea.gif"      alt="" /></a>
                                <a href="javascript:grin(':arrow:')"  ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_arrow.gif"     alt="" /></a>
                                <a href="javascript:grin(':neutral:')"><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_neutral.gif"   alt="" /></a>
                                <a href="javascript:grin(':cry:')"    ><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_cry.gif"       alt="" /></a>
                                <a href="javascript:grin(':mrgreen:')"><img src="http://blog.star7th.com/wp-content/themes/SimpleHome/smilies/icon_mrgreen.gif"   alt="" /></a>
                                <textarea name="comment" id="comment" class="textarea" cols="105" rows="5" tabindex="4" onkeydown="if (event.ctrlKey && event.keyCode == 13) {
                                            document.getElementById('submit').click();
                                            return false
                                        }
                                        ;"></textarea>
                            </div>
                            <p><input id="submit" type="submit" name="submit" value="确认提交 / Ctrl+Enter" class="submit" /></p>
                            <input type='hidden' name='comment_post_ID' value='1996' id='comment_post_ID' />
                            <input type='hidden' name='comment_parent' id='comment_parent' value='0' />

                            <p style="display: none;"><input type="hidden" id="akismet_comment_nonce" name="akismet_comment_nonce" value="3af898e204" /></p><p style="display: none;"><input type="hidden" id="ak_js" name="ak_js" value="18"/></p>		
                        </form>
                    </div>
                </div>
                <script type="text/javascript">
                    /* <![CDATA[ */
                    function grin(tag) {
                        if (document.getElementById('comment') && document.getElementById('comment').type == 'textarea') {
                            myField = document.getElementById('comment');
                        } else {
                            return false;
                        }
                        tag = ' ' + tag + ' ';
                        if (document.selection) {
                            myField.focus();
                            sel = document.selection.createRange();
                            sel.text = tag;
                            myField.focus();
                        } else if (myField.selectionStart || myField.selectionStart == '0') {
                            startPos = myField.selectionStart
                            endPos = myField.selectionEnd;
                            cursorPos = startPos;
                            myField.value = myField.value.substring(0, startPos)
                                    + tag
                                    + myField.value.substring(endPos, myField.value.length);
                            cursorPos += tag.length;
                            myField.focus();
                            myField.selectionStart = cursorPos;
                            myField.selectionEnd = cursorPos;
                        } else {
                            myField.value += tag;
                            myField.focus();
                        }
                    }
                    /* ]]> */
                </script>
            </div>			
    </div>

    <?php include View . '/public/footer.php'; ?>
