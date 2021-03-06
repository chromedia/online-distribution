
</div>

<!-- BEG FOOTER SECTION -->

<footer class="small-12 columns" id="home-footer">
    <div class="row" style="margin: 2em auto;">
        <div class="large-8 small-12 columns">
          <h4 style="font-weight: 800;">Open Tech Collaborative</h4>
          <!--<p>Manufacture Locally, Collaborate Globally</p>--><br>
          <ul class="inline-list footer-social">
            <li><a href="https://github.com/OpenTechCollaborative"><i class="fi-social-github" style="color: #69D2E7;"></i></a></li>
            <li><a href="https://twitter.com/OpenTechCo"><i class="fi-social-twitter" style="color: #A7DBD8;"></i></a></li>
            <li><a href="https://www.facebook.com/opentechforever"><i class="fi-social-facebook" style="color: #E0E4CC;"></i></a></li>
            <li><a href="http://vimeo.com/opentechforever"><i class="fi-social-vimeo" style="color: #F38630;"></i></a></li>
            <li><a href="mailto:info@opentechcollaborative.cc"><i class="fi-mail" style="color: #FA6900;"></i></a></li>
          </ul>
        </div>
        <div class="large-4 small-12 columns">
            <h4>Email Sign Up</h4>
            <p>Sign up for news updates.</p>
              <!-- Begin MailChimp Signup Form -->
            <div id="mc_embed_signup">
            <form action="http://opentechcollaborative.us7.list-manage.com/subscribe/post?u=a2a2b02c4c16296211f9e5a11&amp;id=58a59d4727" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
              <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="your@email.com" required>
                <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                <div style="position: absolute; left: -5000px;"><input type="text" name="b_a2a2b02c4c16296211f9e5a11_58a59d4727" value=""></div>
              <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="postfix small button expand"></div>
            </form>
        </div>

        <!--End mc_embed_signup-->
        </div>
        <div class="mt20 small-12 columns">
            <div class="row">
                <div class="large-6 columns">
                    <a style="color: #FFF;" href="/index.php?route=information/terms_of_service">Terms Of Service</a>
                </div>
                <div class="large-6 columns">
                    <a class="back-to-top"><i class="fi-arrow-up right" style="color: #fff;"> Back to Top</i></a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- END FOOTER SECTION -->

	<?php wp_footer(); ?>


<!-- <script type="text/javascript" src="<?php //bloginfo('template_directory') ?>/js/vendor/jquery.js"></script> -->
<script type="text/javascript">
  $(function() {
        $.ajax({
            url: '<?php echo DIR_HOME;?>/index.php?route=api/cart/countProducts',
            type: 'post',
            dataType: 'json',
            success: function(json) {
              var count = json.productsCount;
              
              if (count) {
                if ($('.items-in-cart').length == 0) {
                  $('.cart-link').prepend('<span class="items-in-cart">'+count+'</span>');
                }
              }
            },
            error: function(error) {
              console.log(error);
            }
        });
    });

    $(document).ready(function() {
        $('.back-to-top').click(function() {
            var offset = $('.page-wrap').offset();

            if(offset) {
                $('body').stop();
                $('html, body').animate({
                  scrollTop: offset.top - 50
                }, 1000); 
            }
        });
    });
</script>
	
	<!-- Don't forget analytics -->
	
</body>

</html>
