<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<main class="main-content">
<section class="container">
<h2 class="block-title">
<?php $this->title() ?>
</h2>
<div class="grap min-height-100">
<p><?php $this->content(); ?></p>
</div>
<?php if ($this->allow('comment')): ?>
<?php $this->need('comments.php'); ?>
<?php endif; ?> 
</section>
</main>
<?php $this->need('footer.php'); ?>