<?php 
/**
 * 友情链接
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<main class="container">
<article class="page--single">
<header class="page-archive-header">
<h2 class="page-archive-title"><?php $this->title(); ?></h2>
</header>
<h3 class="link-title"></h3>
<div class="link-items">
<?php Links_Plugin::output('<a class="link-item" href="{url}" target="_blank" title="{title}"><img alt="" src="{image}" class="avatar avatar-64 photo" height=64 width=64 decoding="async"/><strong>{name}</strong><span class="sitename">{title}</span></a>');?>
</div>          
</article>
</main>
<?php $this->need('footer.php'); ?>