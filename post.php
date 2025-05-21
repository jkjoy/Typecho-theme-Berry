<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<section class="container u-relative" itemscope="itemscope" itemtype="http://schema.org/Article">
<!--
<div class="author--card">
<div class="author--card__avatar">
<img alt='' src='https://secure.gravatar.com/avatar/3caf29a2c6120b65116e07dceb9edafb8a4d24311c90eeb1193900bdc0f7b5dd?s=64&#038;r=g' srcset='https://secure.gravatar.com/avatar/3caf29a2c6120b65116e07dceb9edafb8a4d24311c90eeb1193900bdc0f7b5dd?s=128&#038;r=g 2x' class='avatar avatar-64 photo' height='64' width='64' decoding='async'/>                </div>
<div class="author--card__info">
<h4 class="author--card__name">
<a href="https://www.asbid.cn/archives/author/admin" class="link link--primary">老孙</a>
</h4>
<p class="author--card__desc">
这个人很懒什么也没有留下                    </p>
</div>
</div>
-->
<div class="block-postMetaWrap">
<div class="">
<time itemprop="datePublished" datetime="<?php $this->date('c'); ?>" class="humane--time">
<?php $this->date('Y-m-d'); ?>            
</time>
<span class="sep"></span>
<?php $this->category(','); ?>    
<?php if($this->user->hasLogin() && $this->user->pass('editor', true)): ?>    
<span class="middotDivider"></span>
<a href="<?php $this->options->adminUrl('write-post.php?cid=' . $this->cid); ?>" target="_blank" title="编辑文章">Edit</a>
<?php endif; ?>            
</div>

<div class="post--meta">
<?php get_post_view($this) ?> Views
</div>
</div>
<h2 class="block-title" itemprop="headline">
<?php $this->title() ?>       
</h2>
<div class="grap article-content" itemprop="articleBody">
<p>
<div class="showtoc"></div>
<?php $this->content(); ?>    
</p>
</div>    
<div class="tag-list">
<?php if ($this->tags): ?>
<?php foreach ($this->tags as $tag): ?>
<a href="<?php echo $tag['permalink']; ?>" class="tag-list--item">
<svg viewBox="0 0 24 24" width="24" height="24">
<g fill="none"  stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
<path d="M6.5 7.5a1 1 0 1 0 2 0a1 1 0 1 0-2 0" />
<path d="M3 6v5.172a2 2 0 0 0 .586 1.414l7.71 7.71a2.41 2.41 0 0 0 3.408 0l5.592-5.592a2.41 2.41 0 0 0 0-3.408l-7.71-7.71A2 2 0 0 0 11.172 3H6a3 3 0 0 0-3 3" />
</g>
</svg>
<?php echo $tag['name']; ?>
</a> 
<?php endforeach; ?>
<?php else: ?>
<?php endif; ?>
</div>
<?php $prevPost = get_previous_post($this);?>
<nav class="navigation post-navigation is-active" aria-label="文章">
<div class="nav-previous">
<?php if ($prevPost) { ?>
<a href="<?php echo $prevPost->permalink; ?>" rel="prev">
<span class="meta-nav">下一页</span>
<span class="post-title">
<?php echo $prevPost->title; ?>                
</span>
</a>
<?php } else { ?>
<?php } ?> 
</div>
<?php $nextPost = get_next_post($this);?>
<div class="nav-next">
<?php if ($nextPost) { ?>
<a href="<?php echo $nextPost->permalink; ?>" rel="next">
<span class="meta-nav">
    后一页
</span>
<span class="post-title">
<?php echo $nextPost->title; ?>             
</span>
</a>
<?php } else { ?>
<?php } ?>
</div>
</nav>        
<?php $this->need('comments.php'); ?>
<!-- 相关文章-->
<h3 class="related--posts__title">相关文章</h3>
<div class="articleRelated">
<?php $this->related(6)->to($relatedPosts); ?>   
<?php while ($relatedPosts->next()): ?>
<div class="articleRelated__item" itemscope="itemscope" itemtype="http://schema.org/Article">
<a href="<?php $relatedPosts->permalink(); ?>" aria-label="<?php $relatedPosts->title(25); ?>">
<div class="block--images block--images--1"></div>
<div class="articleRelated__item__title">
<?php $relatedPosts->title(25); ?>           
</div>
<div class="meta">
<time datetime="<?php $relatedPosts->date('c'); ?>" class="humane--time">
<?php $relatedPosts->date('Y-m-d'); ?>             
</time>
</div>
</a>
</div>
<?php endwhile; ?>
</div>    
</section>
<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const targetClassElement = document.querySelector('.showtoc');
    const postContent = document.querySelector('.article-content');
    if (!postContent) return;
    let found = false;
    for (let i = 1; i <= 6 &&!found; i++) {
        if (postContent.querySelector(`h${i}`)) {
            found = true;
        }
    }
    if (!found) return;
    const heads = postContent.querySelectorAll('h1, h2, h3, h4, h5, h6');
    const toc = document.createElement('div');
    toc.id = 'toc';
    toc.innerHTML = '<details class="berry--toc" open><summary>目录</summary><nav id="TableOfContents"><ul></ul></nav></details>';
    // 插入到指定 class 元素之后
    if (targetClassElement) {
        targetClassElement.parentNode.insertBefore(toc, targetClassElement.nextSibling);
    }
    let currentLevel = 0;
    let currentList = toc.querySelector('ul');
    let levelCounts = [0];
    heads.forEach((head, index) => {
        const level = parseInt(head.tagName.substring(1));
        if (levelCounts[level] === undefined) {
            levelCounts[level] = 1;
        } else {
            levelCounts[level]++;
        }
        // 重置下级标题的计数器
        levelCounts = levelCounts.slice(0, level + 1);
        if (currentLevel === 0) {
            currentLevel = level;
        }
        while (level > currentLevel) {
            let newList = document.createElement('ul');
            if (!currentList.lastElementChild) {
                currentList.appendChild(newList);
            } else {
                currentList.lastElementChild.appendChild(newList);
            }
            currentList = newList;
            currentLevel++;
            levelCounts[currentLevel] = 1;
        }
        while (level < currentLevel) {
            currentList = currentList.parentElement;
            if (currentList.tagName.toLowerCase() === 'li') {
                currentList = currentList.parentElement;
            }
            currentLevel--;
        }
        const anchor = head.textContent.trim().replace(/\s+/g, '-');
        head.id = anchor;
        const item = document.createElement('li');
        const link = document.createElement('a');
        link.href = `#${anchor}`;
        link.textContent = `${head.textContent}`;
        link.style.textDecoration = 'none';
        item.appendChild(link);
        currentList.appendChild(item);
    });
});
</script>
<?php $this->need('footer.php'); ?>