<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<div id="comments" class="comments-area">
    <?php $this->comments()->to($comments); ?>
    <?php $language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';if($this->allow('comment') && stripos($language, 'zh') > -1): ?>
        <h2 class="comments-title">
            <?php $this->commentsNum('0', '1', '%d'); ?> 条评论
        </h2>
        <?php if ($comments->have()): ?>
            <ol class="comment-list commentlist">
                <?php $comments->listComments(array(
                    'before' => '',
                    'after' => '',
                    'callback' => 'threadedComments'
                )); ?>
            </ol>
            <nav class="navigation pagination" aria-label="评论分页">
                <?php
                $comments->pageNav(
                    ' ',
                    ' ',
                    1,
                    '...',
                    array(
                        'wrapTag' => 'div',
                        'wrapClass' => 'nav-links',
                        'itemTag' => '',
                        'textTag' => 'span',
                        'itemClass'   => 'page-numbers', 
                        'currentClass' => 'page-numbers current',
                        'prevClass' => 'hidden',
                        'nextClass' => 'hidden'
                    )
                );
                ?>
            </nav>
        <?php else: ?>   
            <center><h3>暂无评论</h3></center>
        <?php endif; ?>
        <div class="comment-respond" id="respond-post-<?php echo $this->cid; ?>">
            <h3 id="reply-title" class="comment-reply-title">
                发表回复
                <small>
                    <a rel="nofollow" id="cancel-comment-reply-link" href="#comments" style="display:none;" onclick="return TypechoComment.cancelReply();">取消回复</a>
                </small>
            </h3>
            <form action="<?php $this->commentUrl() ?>" method="post" id="comment-form" class="comment-form" novalidate>
                <p class="comment-notes">
                    <span id="email-notes">您的邮箱地址不会被公开。</span>
                    <span class="required-field-message">必填项已用 <span class="required">*</span> 标注</span>
                </p>
                <p class="comment-form-comment">
                    <label for="textarea">评论 <span class="required">*</span></label>
                    <textarea id="textarea" name="text" cols="45" rows="8" maxlength="65525" required onkeydown="if(event.ctrlKey&&event.keyCode==13){document.getElementById('submit').click();return false};"><?php $this->remember('text'); ?></textarea>
                </p>
                <?php if($this->user->hasLogin()): ?>
                    <p>登录身份: 
                        <a href="<?php $this->options->profileUrl(); ?>">
                            <?php $this->user->screenName(); ?>
                        </a>. 
                        <a href="<?php $this->options->logoutUrl(); ?>" title="Logout">退出 &raquo;</a>
                    </p>
                <?php else: ?>
                    <p class="comment-form-author">
                        <label for="author">昵称 <span class="required">*</span></label>
                        <input id="author" name="author" type="text" value="<?php $this->remember('author'); ?>" size="30" maxlength="245" autocomplete="name" required />
                    </p>
                    <p class="comment-form-email">
                        <label for="mail">邮箱 <span class="required">*</span></label>
                        <input id="mail" name="mail" type="email" value="<?php $this->remember('mail'); ?>" size="30" maxlength="100" aria-describedby="email-notes" autocomplete="email" required />
                    </p>
                    <p class="comment-form-url">
                        <label for="url">网站</label>
                        <input id="url" name="url" type="url" value="<?php $this->remember('url'); ?>" size="30" maxlength="200" autocomplete="url" />
                    </p>
                    <p class="comment-form-cookies-consent">
                        <input id="comment-cookies-consent"  type="checkbox" value="yes" checked />
                        <label for="comment-cookies-consent">在此浏览器中保存我的昵称、邮箱地址和网站地址，以便下次评论时使用。</label>
                    </p>
                <?php endif; ?>
                <p class="form-submit">
                    <input name="submit" type="submit" id="submit" class="submit" value="发表评论 (Ctrl+Enter)" />
                    <input type="hidden" name="_" value="" />
                </p>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php
function threadedComments($comments, $options) {
    $commentClass = '';
    if ($comments->authorId) {
        if ($comments->authorId == $comments->ownerId) {
            $commentClass .= ' bypostauthor';
        } else {
            $commentClass .= ' byuser';
        }
    }
    
    $depth = $comments->levels + 1;
    $commentClass .= ' depth-' . $depth;
    if ($depth % 2 == 0) {
        $commentClass .= ' even';
    } else {
        $commentClass .= ' odd alt';
    }
    
    if ($comments->levels == 0) {
        $commentClass .= ' thread-even';
    } else {
        $commentClass .= ' thread-odd thread-alt';
    }
    ?>
    <li class="comment <?php echo $commentClass; ?>" data-id="<?php $comments->theId(); ?>" id="<?php $comments->theId(); ?>">
        <div class="comment-body">
            <div class="comment-meta">
                <div class="comment-author vcard">
                    <?php echo $comments->gravatar('42', ''); ?>
                </div>
                <div class="comment--meta">
                    <div class="comment--author" itemprop="author">
                        <?php if ($comments->url): ?>
                            <a href="<?php echo $comments->url ?>" class="url" rel="ugc external nofollow"><?php echo $comments->author; ?></a>
                        <?php else: ?>
                            <?php echo $comments->author; ?>
                        <?php endif; ?>
                        <div class="comment-metadata"><?php $comments->date('n 月 j,Y'); ?></div>
                    </div>
                    <div class="comment--time humane--time" itemprop="datePublished" datetime="<?php $comments->date('c'); ?>">
                    </div>
                </div>
            </div>
            <div class="comment--content comment-content" itemprop="description">
                <?php if ($comments->parent) { ?>
                    <p>
                        <a href="#comment-<?php echo $comments->parent; ?>" class="comment--parent__link">
                            @<?php echo getAuthorFromCoid($comments->parent); ?>
                        </a>
                    <?php $comments->content(); ?></p>
                <?php } else { ?>
                    <?php $comments->content(); ?>
                <?php } ?>
            </div>
            <div class="reply">
                <?php $comments->reply('<div class="comment-reply-link">回复</div>'); ?> 
            </div>
        </div>
        <?php if ($comments->children) { ?>
            <ol class="children">
                <?php $comments->threadedComments($options); ?>
            </ol>
        <?php } ?>
    </li>
    <?php
}

function getAuthorFromCoid($coid) {
    $db = Typecho_Db::get();
    $comment = $db->fetchRow($db->select()->from('table.comments')->where('coid = ?', $coid));
    return $comment ? $comment['author'] : '';
}
?>

<script type="text/javascript">
// 页面加载时从cookie中获取评论者信息
document.addEventListener('DOMContentLoaded', function() {
    // 从cookie获取保存的评论信息
    var savedAuthor = getCookie('__typecho_remember_author');
    var savedMail = getCookie('__typecho_remember_mail');
    var savedUrl = getCookie('__typecho_remember_url');
    
    // 如果存在保存的信息，填充到表单中
    var authorInput = document.getElementById('author');
    var mailInput = document.getElementById('mail');
    var urlInput = document.getElementById('url');
    var cookieConsent = document.getElementById('comment-cookies-consent');
    
    if (authorInput && savedAuthor) {
        authorInput.value = savedAuthor;
    }
    
    if (mailInput && savedMail) {
        mailInput.value = savedMail;
    }
    
    if (urlInput && savedUrl) {
        urlInput.value = savedUrl;
    }
    
    // 监听表单提交事件
    var commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function() {
            // 只有当复选框被选中时才保存cookie
            if (cookieConsent && cookieConsent.checked) {
                if (authorInput && authorInput.value) {
                    setCookie('__typecho_remember_author', authorInput.value, 30);
                }
                
                if (mailInput && mailInput.value) {
                    setCookie('__typecho_remember_mail', mailInput.value, 30);
                }
                
                if (urlInput && urlInput.value) {
                    setCookie('__typecho_remember_url', urlInput.value, 30);
                }
            } else if (cookieConsent && !cookieConsent.checked) {
                // 如果用户取消了勾选，则删除之前保存的cookie
                deleteCookie('__typecho_remember_author');
                deleteCookie('__typecho_remember_mail');
                deleteCookie('__typecho_remember_url');
            }
        });
    }
});

// 设置cookie
function setCookie(name, value, days) {
    var expires = '';
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = '; expires=' + date.toUTCString();
    }
    document.cookie = name + '=' + encodeURIComponent(value) + expires + '; path=/';
}

// 获取cookie
function getCookie(name) {
    var nameEQ = name + '=';
    var ca = document.cookie.split(';');
    for(var i=0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

// 删除cookie
function deleteCookie(name) {
    setCookie(name, '', -1);
}
</script>