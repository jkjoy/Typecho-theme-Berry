<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<?php
    $categoryImage = '';
    if ($this->categories) {
        $category = $this->categories[0];
        $categoryId = $category['mid'];
        $categoryName = $category['name'];
        $themeUrl = Helper::options()->themeUrl . '/img/';
        $categoryImage = $themeUrl . $categoryId . '.png';
    }
?>
<section class="container">
<?php if ($this->have()): ?>
<header class="term--header">
<?php if ($this->is('category')): ?>
<img src="<?php echo $categoryImage; ?>" alt="<?php echo $categoryName; ?>" class="term--image">
<div class="term--header__content">
<h1 class="term--title"><?php $this->archiveTitle(array(
            'category'  =>  _t('  <span> %s </span> '),
            'search'    =>  _t('包含关键字<span> %s </span>的文章'),
            'date'      =>  _t('在 <span> %s </span>发布的文章'),
            'tag'       =>  _t('标签 <span> %s </span>下的文章'),
            'author'    =>  _t('作者 <span>%s </span>发布的文章')
        ), '', ''); ?></h1>
<div class="term--description"><p><?php echo $this->getDescription(); ?></p>
</div>        
</div>
<?php endif; ?>
<div class="term--header__content">
<h1 class="term--title"><?php $this->archiveTitle(array(
            'category'  =>  _t('  <span> %s </span> '),
            'search'    =>  _t('包含关键字<span> %s </span>的文章'),
            'date'      =>  _t('在 <span> %s </span>发布的文章'),
            'tag'       =>  _t('标签 <span> %s </span>下的文章'),
            'author'    =>  _t('作者 <span>%s </span>发布的文章')
        ), '', ''); ?></h1>       
</div>
</header>
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
<?php else: ?>   
    <header class="archive-header u-textAlignCenter">
        <h3 class="page-title"><span>Sorry</span></h3>
    </header>
    <div class="sandraList">
    <p>很遗憾,未找到您期待的内容</p>
    </div>           
        <?php endif; ?>
<?php $this->need('footer.php'); ?>
