<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
function themeConfig($form) {
    $logoUrl = new Typecho_Widget_Helper_Form_Element_Text('logoUrl', NULL, NULL, _t('站点 LOGO 地址'));
    $form->addInput($logoUrl);
    $icoUrl = new Typecho_Widget_Helper_Form_Element_Text('icoUrl', NULL, NULL, _t('站点 Favicon 地址'));
    $form->addInput($icoUrl);
    $thumbUrl = new Typecho_Widget_Helper_Form_Element_Text('thumbUrl', NULL, NULL, _t('默认缩略图地址'));
    $form->addInput($thumbUrl);
    $sticky = new Typecho_Widget_Helper_Form_Element_Text('sticky', NULL, NULL, _t('置顶文章cid'), _t('多篇文章以`|`符号隔开'), _t('首页置顶文章。'));
    $form->addInput($sticky);
    $cnavatar = new Typecho_Widget_Helper_Form_Element_Text('cnavatar', NULL, NULL, _t('Gravatar镜像'), _t('默认https://cravatar.cn/avatar/,可自定义'));
    $form->addInput($cnavatar);
    $telegramurl = new Typecho_Widget_Helper_Form_Element_Text('telegramurl', NULL, 'https://t.me/', _t('Telegram'), _t('电报Telegram链接'));
    $form->addInput($telegramurl);
    $githuburl = new Typecho_Widget_Helper_Form_Element_Text('githuburl', NULL, 'https://github.com/', _t('Github'), _t('个人github仓库链接'));
    $form->addInput($githuburl);
    $mastodonurl = new Typecho_Widget_Helper_Form_Element_Text('mastodonurl', NULL,'https://jiong.us/', _t('Mastodon'), _t('Mastodon联邦宇宙链接'));
    $form->addInput($mastodonurl);
    $SNS = new Typecho_Widget_Helper_Form_Element_Textarea('SNS', NULL, '<a href="/" target="_blank" class="socialIcon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M20.1945 14.1973C20.1945 17.5586 15.6658 20.6206 10.2693 20.6206C5.31907 20.6206 1.00098 18.1885 1.00098 14.5338C1.00098 12.5873 2.1808 10.447 4.24042 8.44571C7.0727 5.69957 10.4695 4.41277 12.0994 5.99781C12.5966 6.48041 12.8225 7.11988 12.8178 7.85577C14.7934 7.28031 16.4689 7.45174 17.3006 8.60768C17.7497 9.2307 17.8332 9.98744 17.6268 10.8152C19.1382 11.4259 20.1945 12.5847 20.1945 14.1973ZM15.755 12.1279C15.3691 11.7166 15.3549 11.2078 15.5572 10.7169C15.7655 10.2087 15.7701 9.90467 15.6776 9.77646C15.4134 9.40925 14.1443 9.41428 12.4833 10.0883C12.4306 10.1106 12.3609 10.1367 12.2765 10.1623C12.1789 10.1917 12.0794 10.2147 11.9744 10.2286C11.6296 10.274 11.3028 10.2293 10.9913 9.96287C10.572 9.60419 10.5175 9.10776 10.6694 8.64655C10.8835 7.97702 10.8482 7.57054 10.7057 7.43232C10.5197 7.25137 9.92883 7.24087 9.04745 7.57477C7.97823 7.97981 6.74875 8.79941 5.63339 9.88084C3.92586 11.54 3.00098 13.2178 3.00098 14.5338C3.00098 16.7755 6.27688 18.6206 10.2693 18.6206C14.6892 18.6206 18.1945 16.2506 18.1945 14.1973C18.1945 13.4588 17.558 12.8585 16.5218 12.5446C16.1276 12.4316 15.9855 12.3736 15.755 12.1279ZM22.8091 10.5099C22.6705 11.0445 22.1247 11.3655 21.5901 11.2268C21.0555 11.0882 20.7345 10.5424 20.8732 10.0078C20.9577 9.68188 21.001 9.34424 21.001 9C21.001 6.79086 19.2101 5 17.001 5C16.7223 5 16.448 5.02834 16.1806 5.08401C15.6399 5.19657 15.1103 4.84949 14.9977 4.3088C14.8852 3.76811 15.2323 3.23854 15.773 3.12599C16.1743 3.04244 16.5852 3 17.001 3C20.3147 3 23.001 5.68629 23.001 9C23.001 9.514 22.9361 10.0203 22.8091 10.5099Z"></path></svg></a>', _t('更多SNS'), _t('更多SNS方式,支持HTML'));
    $form->addInput($SNS);
    $addhead = new Typecho_Widget_Helper_Form_Element_Textarea('addhead', NULL, NULL, _t('添加head'), _t('用于网站验证,支持HTML'));
    $form->addInput($addhead);
    $tongji = new Typecho_Widget_Helper_Form_Element_Textarea('tongji', NULL, NULL, _t('统计代码'), _t('支持HTML'));
    $form->addInput($tongji);
} 

function themeFields($layout) {
    $summary= new Typecho_Widget_Helper_Form_Element_Textarea('summary', NULL, NULL, _t('文章摘要'), _t('自定义摘要'));
    $layout->addItem($summary);
}

/**
 * 获取文章浏览量
 */
function get_post_view($archive) {
    $cid = $archive->cid;
    $db = Typecho_Db::get();
    $prefix = $db->getPrefix();
    if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))) {
        $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `views` INT(10) DEFAULT 0;');
        echo 0;
        return;
    }
    $row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid));
    if ($archive->is('single')) {
        $views = Typecho_Cookie::get('extend_contents_views');
        if (empty($views)) {
            $views = array();
        } else {
            $views = explode(',', $views);
        }
        if (!in_array($cid, $views)) {
            $db->query($db->update('table.contents')->rows(array('views' => (int)$row['views'] + 1))->where('cid = ?', $cid));
            array_push($views, $cid);
            $views = implode(',', $views);
            Typecho_Cookie::set('extend_contents_views', $views); //记录查看cookie
            
        }
    }
    echo $row['views'];
}

/**
 * 获取Gravatar前缀地址
 */
$options = Typecho_Widget::widget('Widget_Options');
$gravatarPrefix = empty($options->cnavatar) ? 'https://cravatar.cn/avatar/' : $options->cnavatar;
define('__TYPECHO_GRAVATAR_PREFIX__', $gravatarPrefix);

/**
* 页面加载时间
*/
function timer_start() {
    global $timestart;
    $mtime = explode( ' ', microtime() );
    $timestart = $mtime[1] + $mtime[0];
    return true;
    }
    timer_start();
    function timer_stop( $display = 0, $precision = 3 ) {
    global $timestart, $timeend;
    $mtime = explode( ' ', microtime() );
    $timeend = $mtime[1] + $mtime[0];
    $timetotal = number_format( $timeend - $timestart, $precision );
    $r = $timetotal < 1 ? $timetotal * 1000 . " ms" : $timetotal . " s";
    if ( $display ) {
    echo $r;
    }
    return $r;
    }

//回复加上@
function getPermalinkFromCoid($coid) {
	$db = Typecho_Db::get();
	$row = $db->fetchRow($db->select('author')->from('table.comments')->where('coid = ? AND status = ?', $coid, 'approved'));
	if (empty($row)) return '';
	return '<a href="#comment-'.$coid.'" style="text-decoration: none;">@'.$row['author'].'</a>';
}

/**
 * 获取文章缩略图和所有图片
 * 
 * @param object|array $post 文章对象或数组
 * @return array 包含缩略图URL、所有图片数组(最多9张)和实际图片总数
 */
function get_post_thumbnail($post) {
    if (is_array($post)) $post = (object)$post;
    $result = array(
        'images' => array(),
        'cropped_images' => array(),
        'count' => 0,
        'total_count' => 0 
    );  
    if (!$post) return $result;  
    $theme_dir = basename(dirname(__FILE__));
    $content = '';
    if (!empty($post->text)) $content = $post->text;
    else if (!empty($post->content)) $content = $post->content;
    else if (method_exists($post, 'content') && is_callable([$post, 'content'])) $content = $post->content();    
    $all_images = array();
    if (!empty($content)) {
        // HTML img 标签
        preg_match_all('/<img[^>]*src=["|\']([^"\']+)["|\'][^>]*>/i', $content, $html_matches);
        if (!empty($html_matches[1])) {
            foreach ($html_matches[1] as $img_url) {
                if (strpos($img_url, 'http') !== 0 && strpos($img_url, '//') !== 0) {
                    $img_url = Helper::options()->siteUrl . ltrim($img_url, '/');
                }
                $all_images[] = $img_url;
            }
        }            
        // URL直链
        preg_match_all('/(https?:\/\/[^\s<>"\']*?\.(?:jpg|jpeg|png|gif|webp))(\?[^\s<>"\']*)?/i', $content, $url_matches);
        if (!empty($url_matches[1])) {
            $all_images = array_merge($all_images, $url_matches[1]);
        }       
        // 去重
        $all_images = array_unique($all_images);
        $all_images = array_values($all_images);        
        // 获取总数
        $total_count = count($all_images);       
        // 只取前三张图片用于显示
        $display_images = array_slice($all_images, 0, 3);        
        // 处理缩略图
        $cropped_images = array();
        foreach ($display_images as $img) {
            $cropped_images[] = get_thumb($img, $theme_dir);
        }        
        // 设置返回结果
        $result['images'] = $display_images;
        $result['cropped_images'] = $cropped_images;
        $result['count'] = count($display_images);
        $result['total_count'] = $total_count;  
        // 设置缩略图（使用第一张图片）
        if (!empty($all_images)) {
            $result['thumbnail'] = $all_images[0];
        }
    }
    return $result;
}

/**
 * 生成缩略图
 * 
 * @param string $imgUrl 原始图片URL
 * @param array $options 配置选项
 * @return string 缩略图URL
 */
function get_thumb($imgUrl, $options) {
    $theme_dir = basename(dirname(__FILE__));
    $upload_dir = __DIR__ . '/thumbnails/';
    $custom_thumbnail = Helper::options()->thumbUrl ?? '';
    if (!empty($custom_thumbnail)) {
        $default_thumbnail = $custom_thumbnail;
    }
    // 确保缓存目录存在
    if (!is_dir($upload_dir)) {
        if (!@mkdir($upload_dir, 0755, true)) {
            return $default_thumbnail; // 如果无法创建目录，返回默认图片
        }
    }
    // 生成唯一文件名
    $hash = md5($imgUrl);
    $thumbnail_path = $upload_dir . $hash . '.webp';
    $thumbnail_url = Helper::options()->themeUrl . '/thumbnails/' . $hash . '.webp';
    // 如果缩略图已存在，直接返回
    if (file_exists($thumbnail_path)) {
        return $thumbnail_url;
    }
    // 获取原始图片
    $img_data = @file_get_contents($imgUrl);
    if ($img_data === false) {
        return $default_thumbnail; // 图片404或无法获取时，返回默认图片
    }
    // 创建图片资源
    $src = @imagecreatefromstring($img_data);
    if (!$src) {
        return $default_thumbnail; // 图片格式无效或无法创建资源时，返回默认图片
    }
    try {
        $width = imagesx($src);
        $height = imagesy($src);
        // 计算缩略图尺寸
        $target_ratio = 3 / 2;
        $src_ratio = $width / $height; 
        if ($src_ratio > $target_ratio) {
            $new_height = $height;
            $new_width = $height * $target_ratio;
            $src_x = ($width - $new_width) / 2;
            $src_y = 0;
        } else {
            $new_width = $width;
            $new_height = $width / $target_ratio;
            $src_x = 0;
            $src_y = ($height - $new_height) / 2;
        }
        // 计算最终尺寸
        $scale = min(600/$new_width, 400/$new_height);
        $dst_width = (int)($new_width * $scale);
        $dst_height = (int)($new_height * $scale);
        // 创建目标图像
        $thumb = imagecreatetruecolor($dst_width, $dst_height);
        // 处理透明背景
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
        // 重采样
        imagecopyresampled(
            $thumb, $src,
            0, 0, $src_x, $src_y,
            $dst_width, $dst_height,
            $new_width, $new_height
        );
        // 保存缩略图
        imagewebp($thumb, $thumbnail_path, 85);
        return $thumbnail_url;
    } catch (Exception $e) {
        // 发生异常时返回默认图片
        return $default_thumbnail;
    } finally {
        // 释放资源
        if (isset($src)) {
            imagedestroy($src);
        }
        if (isset($thumb)) {
            imagedestroy($thumb);
        }
    }
}

/**
 * 获取上一篇文章
 * 
 * @param Widget_Archive $archive 当前文章归档对象
 * @return object|null 上一篇文章对象，如果没有则返回null
 */
function get_previous_post($archive) {
    if (!$archive->is('single')) {
        return null;
    }
    $db = Typecho_Db::get();
    $prefix = $db->getPrefix();  
    // 获取上一篇文章（按创建时间排序）
    $post = $db->fetchRow($db->select()
        ->from('table.contents')
        ->where('table.contents.status = ?', 'publish')
        ->where('table.contents.created < ?', $archive->created)
        ->where('table.contents.type = ?', 'post')
        ->order('table.contents.created', Typecho_Db::SORT_DESC)
        ->limit(1));
    
    if (!$post) {
        return null;
    }  
    // 构建标准化的文章对象
    $result = new stdClass();
    $result->cid = $post['cid'];
    $result->title = $post['title'];
    $result->slug = $post['slug'];
    $result->created = $post['created'];
    $result->content = isset($post['text']) ? $post['text'] : '';
    $result->text = isset($post['text']) ? $post['text'] : '';
    $result->permalink = get_permalink($post['cid']);    
    // 获取文章自定义字段
    $fields = $db->fetchAll($db->select()->from('table.fields')
        ->where('cid = ?', $post['cid']));
    // 添加自定义字段到文章对象
    if ($fields) {
        $result->fields = new stdClass();
        foreach ($fields as $field) {
            $result->fields->{$field['name']} = $field['str_value'] ? $field['str_value'] : $field['int_value'];
        }
    } 
    return $result;
}

/**
 * 获取下一篇文章
 * 
 * @param Widget_Archive $archive 当前文章归档对象
 * @return object|null 下一篇文章对象，如果没有则返回null
 */
function get_next_post($archive) {
    if (!$archive->is('single')) {
        return null;
    }
    $db = Typecho_Db::get();
    $prefix = $db->getPrefix();
    // 获取下一篇文章（按创建时间排序）
    $post = $db->fetchRow($db->select()
        ->from('table.contents')
        ->where('table.contents.status = ?', 'publish')
        ->where('table.contents.created > ?', $archive->created)
        ->where('table.contents.type = ?', 'post')
        ->order('table.contents.created', Typecho_Db::SORT_ASC)
        ->limit(1));
    
    if (!$post) {
        return null;
    }
    // 构建标准化的文章对象
    $result = new stdClass();
    $result->cid = $post['cid'];
    $result->title = $post['title'];
    $result->slug = $post['slug'];
    $result->created = $post['created'];
    $result->content = isset($post['text']) ? $post['text'] : '';
    $result->text = isset($post['text']) ? $post['text'] : '';
    $result->permalink = get_permalink($post['cid']);
    // 获取文章自定义字段
    $fields = $db->fetchAll($db->select()->from('table.fields')
        ->where('cid = ?', $post['cid']));
    // 添加自定义字段到文章对象
    if ($fields) {
        $result->fields = new stdClass();
        foreach ($fields as $field) {
            $result->fields->{$field['name']} = $field['str_value'] ? $field['str_value'] : $field['int_value'];
        }
    }
    
    return $result;
}

/**
 * 获取文章永久链接
 * 
 * @param int $cid 文章ID
 * @return string 文章链接
 */
function get_permalink($cid) {
    try {
        // 获取文章对象
        $db = Typecho_Db::get();
        $post = $db->fetchRow($db->select()
            ->from('table.contents')
            ->where('cid = ?', $cid)
            ->where('status = ?', 'publish'));   
        if (!$post) {
            return '';
        }
        // 构造文章对象
        $post['type'] = 'post'; // 确保类型为文章
        $post = Typecho_Widget::widget('Widget_Abstract_Contents')->filter($post);   
        // 使用文章对象的 permalink 方法生成链接
        return $post['permalink'];
    } catch (Exception $e) {
        // 出现异常时使用最简单的方式
        $options = Helper::options();
        return $options->siteUrl . '?cid=' . $cid;
    }
}

/**
 * Typecho后台附件增强：图片预览、批量插入、保留官方删除按钮与逻辑
 * @author jkjoy
 * @date 2025-04-25
 */
Typecho_Plugin::factory('admin/write-post.php')->bottom = array('AttachmentHelper', 'addEnhancedFeatures');
Typecho_Plugin::factory('admin/write-page.php')->bottom = array('AttachmentHelper', 'addEnhancedFeatures');

class AttachmentHelper {
    public static function addEnhancedFeatures() {
        ?>
        <style>
        #file-list{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:15px;padding:15px;list-style:none;margin:0;}
        #file-list li{position:relative;border:1px solid #e0e0e0;border-radius:4px;padding:10px;background:#fff;transition:all 0.3s ease;list-style:none;margin:0;}
        #file-list li:hover{box-shadow:0 2px 8px rgba(0,0,0,0.1);}
        #file-list li.loading{opacity:0.7;pointer-events:none;}
        .att-enhanced-thumb{position:relative;width:100%;height:150px;margin-bottom:8px;background:#f5f5f5;overflow:hidden;border-radius:3px;display:flex;align-items:center;justify-content:center;}
        .att-enhanced-thumb img{width:100%;height:100%;object-fit:contain;display:block;}
        .att-enhanced-thumb .file-icon{display:flex;align-items:center;justify-content:center;width:100%;height:100%;font-size:40px;color:#999;}
        .att-enhanced-finfo{padding:5px 0;}
        .att-enhanced-fname{font-size:13px;margin-bottom:5px;word-break:break-all;color:#333;}
        .att-enhanced-fsize{font-size:12px;color:#999;}
        .att-enhanced-factions{display:flex;justify-content:space-between;align-items:center;margin-top:8px;gap:8px;}
        .att-enhanced-factions button{flex:1;padding:4px 8px;border:none;border-radius:3px;background:#e0e0e0;color:#333;cursor:pointer;font-size:12px;transition:all 0.2s ease;}
        .att-enhanced-factions button:hover{background:#d0d0d0;}
        .att-enhanced-factions .btn-insert{background:#467B96;color:white;}
        .att-enhanced-factions .btn-insert:hover{background:#3c6a81;}
        .att-enhanced-checkbox{position:absolute;top:5px;right:5px;z-index:2;width:18px;height:18px;cursor:pointer;}
        .batch-actions{margin:15px;display:flex;gap:10px;align-items:center;}
        .btn-batch{padding:8px 15px;border-radius:4px;border:none;cursor:pointer;transition:all 0.3s ease;font-size:10px;display:inline-flex;align-items:center;justify-content:center;}
        .btn-batch.primary{background:#467B96;color:white;}
        .btn-batch.primary:hover{background:#3c6a81;}
        .btn-batch.secondary{background:#e0e0e0;color:#333;}
        .btn-batch.secondary:hover{background:#d0d0d0;}
        .upload-progress{position:absolute;bottom:0;left:0;width:100%;height:2px;background:#467B96;transition:width 0.3s ease;}
        </style>
        <script>
        $(document).ready(function() {
            // 批量操作UI按钮
            var $batchActions = $('<div class="batch-actions"></div>')
                .append('<button type="button" class="btn-batch primary" id="batch-insert">批量插入</button>')
                .append('<button type="button" class="btn-batch secondary" id="select-all">全选</button>')
                .append('<button type="button" class="btn-batch secondary" id="unselect-all">取消全选</button>');
            $('#file-list').before($batchActions);

            // 插入格式
            Typecho.insertFileToEditor = function(title, url, isImage) {
                var textarea = $('#text'), 
                    sel = textarea.getSelection(),
                    insertContent = isImage ? '![' + title + '](' + url + ')' : 
                                            '[' + title + '](' + url + ')';
                textarea.replaceSelection(insertContent + '\n');
                textarea.focus();
            };
            // 批量插入
            $('#batch-insert').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var content = '';
                $('#file-list li').each(function() {
                    if ($(this).find('.att-enhanced-checkbox').is(':checked')) {
                        var $li = $(this);
                        var title = $li.find('.att-enhanced-fname').text();
                        var url = $li.data('url');
                        var isImage = $li.data('image') == 1;
                        content += isImage ? '![' + title + '](' + url + ')\n' : '[' + title + '](' + url + ')\n';
                    }
                });
                if (content) {
                    var textarea = $('#text');
                    var pos = textarea.getSelection();
                    var newContent = textarea.val();
                    newContent = newContent.substring(0, pos.start) + content + newContent.substring(pos.end);
                    textarea.val(newContent);
                    textarea.focus();
                }
            });
            $('#select-all').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#file-list .att-enhanced-checkbox').prop('checked', true);
                return false;
            });
            $('#unselect-all').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#file-list .att-enhanced-checkbox').prop('checked', false);
                return false;
            });
            // 防止复选框冒泡
            $(document).on('click', '.att-enhanced-checkbox', function(e) {e.stopPropagation();});
            // 增强文件列表样式，但不破坏li原结构和官方按钮
            function enhanceFileList() {
                $('#file-list li').each(function() {
                    var $li = $(this);
                    if ($li.hasClass('att-enhanced')) return;
                    $li.addClass('att-enhanced');
                    // 只增强，不清空li
                    // 增加批量选择框
                    if ($li.find('.att-enhanced-checkbox').length === 0) {
                        $li.prepend('<input type="checkbox" class="att-enhanced-checkbox" />');
                    }
                    // 增加图片预览（如已有则不重复加）
                    if ($li.find('.att-enhanced-thumb').length === 0) {
                        var url = $li.data('url');
                        var isImage = $li.data('image') == 1;
                        var fileName = $li.find('.insert').text();
                        var $thumbContainer = $('<div class="att-enhanced-thumb"></div>');
                        if (isImage) {
                            var $img = $('<img src="' + url + '" alt="' + fileName + '" />');
                            $img.on('error', function() {
                                $(this).replaceWith('<div class="file-icon">🖼️</div>');
                            });
                            $thumbContainer.append($img);
                        } else {
                            $thumbContainer.append('<div class="file-icon">📄</div>');
                        }
                        // 插到插入按钮之前
                        $li.find('.insert').before($thumbContainer);
                    }
                });
            }
            // 插入按钮事件
            $(document).on('click', '.btn-insert', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $li = $(this).closest('li');
                var title = $li.find('.att-enhanced-fname').text();
                Typecho.insertFileToEditor(title, $li.data('url'), $li.data('image') == 1);
            });
            // 上传完成后增强新项
            var originalUploadComplete = Typecho.uploadComplete;
            Typecho.uploadComplete = function(attachment) {
                setTimeout(function() {
                    enhanceFileList();
                }, 200);
                if (typeof originalUploadComplete === 'function') {
                    originalUploadComplete(attachment);
                }
            };
            // 首次增强
            enhanceFileList();
        });
        </script>
        <?php
    }
}
?>