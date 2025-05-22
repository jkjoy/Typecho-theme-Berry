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
<?php  
/** 文章置顶 */
$sticky = $this->options->sticky; // 置顶的文章id，多个用|隔开
$db = Typecho_Db::get();
$pageSize = $this->options->pageSize;
if ($sticky && !empty(trim($sticky))) {
    $sticky_cids = array_filter(explode('|', $sticky)); // 分割文本并过滤空值
    if (!empty($sticky_cids)) {
        $sticky_html = " <span class='sticky--post'><svg xmlns='http://www.w3.org/2000/svg' width='16px' height='16px' fill='none' viewBox='0 0 24 24' class='bk'>
<path fill='#242424' fill-rule='evenodd' d='M12.333 16.993a7.4 7.4 0 0 1-1.686-.12 7.25 7.25 0 1 1 8.047-4.334v.001a7.2 7.2 0 0 1-.632 1.188 7.26 7.26 0 0 1-4.708 3.146l-.07.013q-.466.083-.951.105m.356.979a8.4 8.4 0 0 1-1.377 0l-2.075 5.7a.375.375 0 0 1-.625.13l-2.465-2.604-3.563.41a.375.375 0 0 1-.395-.501l2.645-7.267a8.25 8.25 0 1 1 14.333 0l2.645 7.267a.375.375 0 0 1-.396.5l-3.562-.41-2.465 2.604a.375.375 0 0 1-.625-.13zm5.786-3.109a8.25 8.25 0 0 1-4.775 2.962l1.658 4.554 1.77-1.87.344-.362.496.057 2.558.294zm-12.95 0L3.476 20.5l2.557-.295.497-.057.344.363 1.77 1.87 1.658-4.555a8.25 8.25 0 0 1-4.775-2.961' clip-rule='evenodd'></path>
</svg>
置顶 </span> "; // 置顶标题的 html
        // 清空原有文章的队列
        $this->row = [];
        $this->stack = [];
        $this->length = 0;
        // 获取总数，并排除置顶文章数量
        if (isset($this->currentPage) && $this->currentPage == 1) {
            $totalOriginal = $this->getTotal();
            $stickyCount = count($sticky_cids);
            $this->setTotal(max($totalOriginal - $stickyCount, 0));
            // 构建置顶文章的查询
            $selectSticky = $this->select()->where('type = ?', 'post');
            foreach ($sticky_cids as $i => $cid) {
                if ($i == 0) 
                    $selectSticky->where('cid = ?', $cid);
                else 
                    $selectSticky->orWhere('cid = ?', $cid);
            }
            // 获取置顶文章
            $stickyPosts = $db->fetchAll($selectSticky);
            // 压入置顶文章到文章队列
            foreach ($stickyPosts as &$stickyPost) {
                $stickyPost['isSticky'] = true;            // 添加置顶标记
                $stickyPost['stickyHtml'] = $sticky_html;  // 添加置顶HTML
                $this->push($stickyPost);
            }
            $standardPageSize = $pageSize - count($stickyPosts);
        } else {
            $standardPageSize = $pageSize;
        }
        // 构建正常文章查询，排除置顶文章
        $selectNormal = $this->select()
            ->where('type = ?', 'post')
            ->where('status = ?', 'publish')
            ->where('created < ?', time())
            ->order('created', Typecho_Db::SORT_DESC)
            ->page(isset($this->currentPage) ? $this->currentPage : 1, $standardPageSize);
        foreach ($sticky_cids as $cid) {
            $selectNormal->where('table.contents.cid != ?', $cid);
        }
    } else {
        // 如果sticky_cids为空，使用默认查询
        $selectNormal = $this->select()
            ->where('type = ?', 'post')
            ->where('status = ?', 'publish')
            ->where('created < ?', time())
            ->order('created', Typecho_Db::SORT_DESC)
            ->page(isset($this->currentPage) ? $this->currentPage : 1, $pageSize);
    }
} else {
    // 如果没有置顶文章，使用默认查询
    $selectNormal = $this->select()
        ->where('type = ?', 'post')
        ->where('status = ?', 'publish')
        ->where('created < ?', time())
        ->order('created', Typecho_Db::SORT_DESC)
        ->page(isset($this->currentPage) ? $this->currentPage : 1, $pageSize);
}
// 登录用户显示私密文章
if ($this->user->hasLogin()) {
    $uid = $this->user->uid;
    if ($uid) {
        $selectNormal->orWhere('authorId = ? AND status = ?', $uid, 'private');
    }
}
$normalPosts = $db->fetchAll($selectNormal);
// 如果之前没有清空队列（没有置顶文章的情况），现在清空
if (empty($sticky) || empty(trim($sticky)) || empty($sticky_cids)) {
    $this->row = [];
    $this->stack = [];
    $this->length = 0;
}
// 压入正常文章到文章队列
foreach ($normalPosts as $normalPost) {
    $this->push($normalPost);
}
?>
<section class="container">
<?php while($this->next()): ?>
<article class="block--list">
<div class="block-postMetaWrap">
<?php if (isset($this->isSticky) && $this->isSticky): ?>
<?php echo $this->stickyHtml; ?>
<?php endif; ?>
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