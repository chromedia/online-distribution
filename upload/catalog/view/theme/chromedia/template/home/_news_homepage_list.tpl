<div id="latest-news">
    <?php if(!empty($news)): ?>
        <section class="row news-section" style="padding:10px;">
            <div class="small-12 columns" style="margin:-20px auto -30px auto">
                <h1 style="float:left;" name="latest_news">Latest News</h1>
                <!-- <a href="<?php //echo $this->url->link('information/news', '', 'SSL'); ?>" class="view-all-news">View All Updates</a> -->
                <a href="/news" class="view-all-news">View All News</a>
            </div>

            <?php foreach(array_slice($news, 0, 2) as $newsItem): ?>
                <div class="small-12 medium-6 columns blog-box-left news-item">
                    <a href="<?php echo $newsItem['url']; ?>">
                        <?php if($newsItem['featured_image']): ?>
                            <img src="<?php echo $newsItem['featured_image'];?>" alt="">
                        <?php endif;?>
                    </a>

                    <h3><a href="<?php echo $newsItem['url']; ?>"><?php echo $string_util->truncateString(strip_tags($newsItem['title']), 65); ?></a></h3>
                    
                    <p> 
                        <?php $truncatedContent = $string_util->truncateString(strip_tags($newsItem['content']), 250);?>
                        <?php echo $truncatedContent; ?>
                        
                        <a href="<?php echo $newsItem['url']; ?>">(read more)</a>
                    </p>

                    <ul class="inline-list blog-box-social">
                        <?php $shortenedUrl = $url_util->shortenUrl($newsItem['url']); ?>

                        <li>
                            <a href="http://twitter.com/home?status=<?php echo urlencode($newsItem['title'].' '.$shortenedUrl);?>" TARGET="_blank"><i class="fi-social-twitter"></i></a>
                        </li>
                        <li>
                            <a href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]=<?php echo $shortenedUrl;?>" TARGET="_blank"><i class="fi-social-facebook"></i></a>
                        </li>
                    </ul>
                </div>
            <?php endforeach;?>
        </section>  
    <?php endif;?>
</div>