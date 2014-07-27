<?php echo $header;?>

<!--<div data-magellan-expedition="fixed">
  <dl class="sub-nav">
    <dd data-magellan-arrival="home"><a href="/">Home</a></dd>
    <dd data-magellan-arrival="vision"><a href="#vision">Vision</a></dd>
    <dd data-magellan-arrival="open-source-hardware"><a href="#open-source-hardware">Open Source Hardware</a></dd>
    <dd data-magellan-arrival="do"><a href="#do">What We Do</a></dd>
    <dd data-magellan-arrival="team"><a href="#team">Team</a></dd>
    <dd data-magellan-arrival="contact-us"><a href="#contact-us">Contact Us</a></dd>
  </dl>
</div>-->

<!-- <img src="catalog/view/theme/chromedia/image///about01.jpg" alt="" class="general-large-image"> -->

<div class="row">
<?php include_once(DIR_APPLICATION . 'view/theme/chromedia/template/information/_what_we_do.tpl'); ?>
</div>

<!-- BEG TEAM -->
<?php include_once(DIR_APPLICATION . 'view/theme/chromedia/template/information/_team.tpl'); ?>
<!-- END TEAM -->

<div class="row">
    <?php include_once(DIR_APPLICATION . 'view/theme/chromedia/template/information/_vision.tpl'); ?>

    <?php include_once(DIR_APPLICATION . 'view/theme/chromedia/template/information/_open_source.tpl'); ?>


</div>

<?php for($i = 1; $i < 10; $i++){ echo "<br>"; } ?>

<script type="text/javascript">

</script>

<?php echo $footer;?>
