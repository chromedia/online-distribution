<?php echo $header;?>

<!-- Breadcrumbs -->
<!-- TODO: put to a partial template -->
<div id="breadcrumbs-product-page" class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb): ?>
          <?php echo $breadcrumb['separator']; ?>

        <?php if($breadcrumb['href']): ?>
          <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php else: ?>
          <?php echo $breadcrumb['text']; ?>
        <?php endif;?>
        
      <?php endforeach; ?>
</div>
 
<!-- DISPLAY PRODUCTS -->
<?php if(!empty($news)): ?>
    <?php $divideByTwo = (count($news) + 1) / 2; ?>

    <?php for ($ctr = 0; $ctr < $divideByTwo; $ctr++): ?>
        <?php $sectionNews = array_slice($news, $ctr, ($ctr == 0 ? ($ctr+1) : $ctr) * 2); ?>

        <section class="row <?php echo (0 == $ctr ? '' : 'latest-news');?>">

            <?php foreach($sectionNews as $newsItem): ?>

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
            <?php endforeach; ?>
        </section>
    <?php endfor;?>
  
<?php else: ?>
    <em>No available products yet.</em>
<?php endif;?>
<!-- ALL PRODUCTS -->


<?php echo $footer;?>




                    