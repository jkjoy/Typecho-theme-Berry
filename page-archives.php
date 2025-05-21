<?php 
/**
 * 文章归档
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<div class="container">
<header class="page-archive-header">
<h1 class="page-archive-title"><?php $this->title() ?></h1>
</header>
<?php
        $stat = Typecho_Widget::widget('Widget_Stat');
        Typecho_Widget::widget('Widget_Contents_Post_Recent', 'pageSize=' . $stat->publishedPostsNum)->to($archives);
        $year = 0; $mon = 0;
        $output = '<div class="list-archive-wrapper">'; // Start archives container      
        while ($archives->next()) {
            $year_tmp = date('Y', $archives->created);
            $mon_tmp = date('m', $archives->created);         
            // 检查是否需要新的年份标题
            if ($year != $year_tmp) {
                if ($year > 0) {
                    $output .= '</ul></div>'; // 结束上一个年份的月份列表和包裹的div
                }
                $year = $year_tmp; 
                $mon = 0; // 重置月份
                $output .= '<div class="year-wrapper"><h2 class="year-title">' . $year . '年</h2>'; // 开始新的年份div
            }       
            // 检查是否需要新的月份标题
            if ($mon != $mon_tmp) {
                if ($mon > 0) {
                    $output .= '</ul>'; // 结束上一个月份的列表
                }
                $mon = $mon_tmp; 
                $output .= '<h3 class="month-title">'. $mon . '月</h3>';// '. $mon . '
                $output .= '<ul class="month-posts">'; // 开始新的月份列表
            }
            // 输出文章项
            $output .= '<li class="month-post">';
            $output .= '<a href="' . $archives->permalink . '" class="post-title" >' . $archives->title . '</a>';
            $output .= '<span class="post-time">' . date('Y-m-d', $archives->created) . '</span></li>';
        }
        // 循环后，确保所有标签都已经关闭
        if ($mon > 0) {
            $output .= '</ul>'; // 结束最后一个月份的列表
        }
        if ($year > 0) {
            $output .= '</div>'; // 结束最后一个年份的div
        }
        $output .= '</div>'; // End archives container
        echo $output;
    ?>
</div>
<?php $this->need('footer.php'); ?>
 