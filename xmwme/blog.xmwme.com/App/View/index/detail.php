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
                    <ol class="commentlist" id="commentlist">
                        <?php foreach ($data['commit'] as $value){ ?>
                        <li class="comment odd alt thread-odd thread-alt depth-1" id="li-comment-2785">
                            <div id="comment-2785" class="comment-body">
                            <div class="comment-author">
                                <img src="<?=Resource?>images/commit.jpg" class="avatar avatar-64" height="64" width="64">
                            </div>
                            <div class="comment-head">
                                <span class="name"><?=$value['username']?></span>
                                <!--<span class="num"> #2</span>-->
                                <p> </p><p><?=$value['content']?></p>
                                <p></p>
                                <div class="post-reply"><a rel="nofollow" class="comment-reply-link" href="" onclick="return addComment.moveForm( &quot;comment-2785&quot;, &quot;2785&quot;, &quot;respond&quot;, &quot;2007&quot; )" aria-label="回复给问道">回复</a></div>
                                <div class="date"><?=date('Y-m-d H:i:s',$data['created'])?></div>
                            </div>
                            </div>
                        </li>
                        <?php } ?>
                        
                    </ol>
                    <div id="respond" class="respond">
                        <form action="" method="post" onsubmit="return subData()" id="commentform" class="comment-form">
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
                            </div>
                            <div id="author_textarea">
                                <textarea name="comment" id="comment" class="textarea" cols="105" rows="5" tabindex="4" onkeydown="if (event.ctrlKey && event.keyCode == 13) {
                                            document.getElementById('submit').click();
                                            return false
                                        }
                                        ;"></textarea>
                            </div>
                            <p><input id="submit" type="submit" name="submit" id="submit" value="确认提交 / Ctrl+Enter" class="submit" /></p>
                            <input type='hidden' name='comment_post_ID' value='1996' id='comment_post_ID' />
                            <input type='hidden' name='comment_parent' id='comment_parent' value='0' />

                            <p style="display: none;">
                                <input type="hidden" id="akismet_comment_nonce" name="akismet_comment_nonce" value="3af898e204" />
                            </p>
                            <p style="display: none;">
                                <input type="hidden" id="ak_js" name="ak_js" value="18"/>
                            </p>		
                        </form>
                    </div>
                </div>
            </div>	
            </section>
    </div>

    <?php include View . '/public/footer.php'; ?>
    <script>
    function subData(){
         var username = $("#author").val();
         var mail = $("#mail").val();
         var id = '<?=$data['id']?>';
         var comment = $("#comment").val();
        if(username=='' || mail==''){
            alert('姓名或者email不能为空！');return false;
        }
       
        $.post('/index/commit/',{username:username,mail:mail,id:id,comment:comment},function(data){
                $("#commentlist").prepend(data);
        });
    }
    </script>
