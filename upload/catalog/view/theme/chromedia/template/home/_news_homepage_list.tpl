<span id="latest-news" style="margin:50px;"></span>
<div>
    <?php if(!empty($news)): ?>
        <section class="row news-section">
            <div class="small-12 columns" style="margin:-20px auto -30px auto">
                <h1 style="float:left;" name="latest_news">Latest News</h1>
                <!-- <a href="<?php //echo $this->url->link('information/news', '', 'SSL'); ?>" class="view-all-news">View All Updates</a> -->
                <a href="/news" class="view-all-news">View All News</a>
            </div>

            <?php foreach($news as $newsItem): ?>
                <div class="small-12 medium-6 columns blog-box-left news-item">
                    <a href="<?php echo $newsItem['url']; ?>">
                        <?php if($newsItem['featured_image']): ?>
                            <img src="<?php echo $newsItem['featured_image'];?>" alt="">
                        <?php endif;?>
                    </a>

                    <h3><a href="<?php echo $newsItem['url']; ?>"><?php echo $newsItem['title']; ?></a></h3>
                    
                    <p> 
                        <?php $truncatedContent = $string_util->truncateString(strip_tags($newsItem['content']), 310);?>
                        <?php echo $truncatedContent; ?>
                        
                        <a href="<?php echo $newsItem['url']; ?>">(read more)</a>
                    </p>

                    <ul class="inline-list blog-box-social">
                        <?php $shortenedUrl = $url_util->shortenUrl($newsItem['url']); ?>

                        <li>
                            <a href="http://twitter.com/home?status=<?php echo urlencode($newsItem['title'].' '.$shortenedUrl);?>" TARGET="_blank"><i class="fi-social-twitter"></i></a>
                        </li>
                        <li>
                            <a href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]=<?php echo $shortenedUrl;?>&p[images][0]=<?php echo $url_util->shortenUrl($newsItem['featured_image']); ?>&p[title]=<?php echo urlencode($newsItem['title']);?>&p[summary]=<?php echo $truncatedContent;?>" TARGET="_blank"><i class="fi-social-facebook"></i></a>
                        </li>
                    </ul>
                </div>
            <?php endforeach;?>
        </section>  
    <?php endif;?>
</div>