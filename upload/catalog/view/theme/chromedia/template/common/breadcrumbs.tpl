<ul class="breadcrumbs">
    <?php foreach ($breadcrumbs as $breadcrumb): ?>
        <li>
            <?php if($breadcrumb['href']): ?>
              <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php else: ?>
              <?php echo $breadcrumb['text']; ?>
            <?php endif;?>
        </li>
        
      <?php endforeach; ?>
</ul>
 