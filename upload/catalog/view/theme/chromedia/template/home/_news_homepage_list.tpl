<div id="latest-news">
    <section class="row">
        <div class="small-12 columns">
            <h1 style="margin: 1em auto 1em auto;" name="latest_news">Latest News</h1>
        </div>

        <?php $firstSectionNews = array_slice($news, 0, 2); ?>

        <?php foreach($firstSectionNews as $newsItem): ?>
            <div class="small-12 medium-6 columns blog-box-left">
                <a href="<?php echo $newsItem['url']; ?>">
                    <?php if($newsItem['featured_image']): ?>
                        <img src="<?php echo $newsItem['featured_image'];?>" alt="">
                    <?php endif;?>
                </a>

                <h3><a href="<?php echo $newsItem['url']; ?>"><?php echo $newsItem['title']; ?></a></h3>
                
                <p> 
                    <?php 
                        $truncatedContent = $string_util->truncateString(strip_tags($newsItem['content']), 100);    
                    ?>

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

    <section class="row latest-news">
        <?php $secondSectionNews = array_slice($news, 2, 4); ?>

        <?php foreach($secondSectionNews as $newsItem): ?>
            <div class="small-12 medium-6 columns blog-box-left">
                <a href="<?php echo $newsItem['url']; ?>">
                    <?php if($newsItem['featured_image']): ?>
                        <img src="<?php echo $newsItem['featured_image'];?>" alt="">
                    <?php endif;?>
                </a>

                <h3><a href="<?php echo $newsItem['url']; ?>"><?php echo $newsItem['title']; ?></a></h3>
                
                <p> 
                    <?php 
                        $truncatedContent = $string_util->truncateString(strip_tags($newsItem['content']), 100);    
                    ?>

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
</div>