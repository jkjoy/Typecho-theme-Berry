<?php
/**
 * 一款单栏主题. BERRY 2.0 原作者 bigfa  
 * @package BERRY 2.0
 * @author  老孙 
 * @version 0.1.0
 * @link https://www.imsun.org
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>
<section class="container">
<?php while($this->next()): ?>
<article class="block--list">
<div class="block-postMetaWrap">
<div class="">
<time itemprop="datePublished" datetime="<?php $this->date('c'); ?>" class="humane--time"><?php $this->date('Y-m-d'); ?></time>
<span class="sep"></span>
<?php $this->category(','); ?> 
</div>
<div class="post--meta">
<?php get_post_view($this) ?> Views
</div>
</div>
<h2 class="block-title" itemprop="headline">
<a href="<?php $this->permalink() ?>" title="<?php $this->title() ?>" aria-label="<?php $this->title() ?>"><?php $this->title() ?></a>
</h2>
<div class="block-snippet block-snippet--subtitle grap">
<p itemprop="about">
<?php if($this->fields->summary){ echo $this->fields->summary;} else { $this->excerpt(180);}?>
</p>
<?php $result = get_post_thumbnail($this);$images = $result['images'];$cropped_images = $result['cropped_images'];$total_count = $result['total_count'];if (!empty($images)):$imageCount = count($images);?>
<div class="block--images block--images__withcount" data-count="<?php echo ($total_count > 3) ? ($total_count - 3) : ''; ?>">
<?php foreach ($images as $i => $image): ?>
<img src="<?php echo htmlspecialchars($cropped_images[$i]); ?>" alt="<?php $this->title() ?>" class="block--image" alt="<?php $this->title() ?>" aria-label="<?php $this->title() ?>">            
<?php endforeach; ?>
</div>
<?php endif; ?>
<p>
<a class="more-link" href="<?php $this->permalink() ?>" title="<?php $this->title() ?>" aria-label="<?php $this->title() ?>">read more..</a>
</p>
</div>
</article>
<?php endwhile; ?>
<div class="postsFooterNav">
<nav class="navigation pagination" aria-label="文章分页">
<h2 class="screen-reader-text">文章分页</h2>    
<?php $this->pageNav(' ',' ',1,'...', array('wrapTag' => 'div','wrapClass' => 'nav-links','itemTag' => '','textTag' => 'span','itemClass'   => 'page-numbers', 'currentClass' => 'page-numbers current','prevClass' => 'hidden','nextClass' => 'hidden'));?>
</nav>    
</div>
</section>
<?php $this->need('footer.php'); ?>