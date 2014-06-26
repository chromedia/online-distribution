            </div> <!--End .main-content-->

            <!-- BEG FOOTER SECTION -->

            <!-- background-color:#413d3d;color:#ffffff; -->

            <footer class="small-12 columns" id="home-footer" style="width:100%;height:295px;position:absolute;bottom:0;left:0;">
                <div class="row" style="margin: 2em auto;">
                    <div class="large-8 small-12 columns">
                        <h4 style="font-weight: 800;">Open Tech Collaborative</h4>
                        <p>Manufacture Locally, Collaborate Globally</p>
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
                        <p>Sign up for monthly news updates.</p>
                          <!-- Begin MailChimp Signup Form -->
                        <div id="mc_embed_signup">
                            <form action="http://opentechcollaborative.us7.list-manage.com/subscribe/post?u=a2a2b02c4c16296211f9e5a11&amp;id=58a59d4727" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                              <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="your@email.com" required>
                                <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                <div style="position: absolute; left: -5000px;"><input type="text" name="b_a2a2b02c4c16296211f9e5a11_58a59d4727" value=""></div>
                              <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="postfix small button expand"></div>
                            </form>
                        </div>
                    </div>

                    <div class="small-12 columns">
                      <a class="scrollTo" scroll-target="#top"><i class="fi-arrow-up right" style="margin-top: 1em; color: #fff;"> Back to Top</i></a>
                    </div>
                </div>
            </footer>

        </div> <!--End of .content-wrapper-->

<!-- END FOOTER SECTION -->
        <script type="text/javascript" src="catalog/view/theme/chromedia/javascripts/foundation/foundation.min.js"></script>
        <script type="text/javascript" src="catalog/view/theme/chromedia/javascripts/app.js"></script>
        <script type="text/javascript" src="catalog/view/theme/chromedia/javascripts/loader.js"></script>
        <script type="text/javascript" src="catalog/view/theme/chromedia/javascripts/modal.js"></script>

        <script type="text/javascript" src="catalog/view/theme/chromedia/javascripts/jquery/jquery.scrollTo.min.js" ></script>

        <script type="text/javascript">
            $('.scrollTo').off('click').on('click', function(e) {
                e.preventDefault();
                var target = $($(this).attr('scroll-target'));

                if (target.length > 0) {
                    // $('body').stop().scrollTo(target, 1000);
                    $('body').stop().scrollTo(target.position.top - 300, 1000);
                } else {
                    window.location = $(this).attr('href');
                }
            });

            setTimeout(function() {
                var url = document.location.toString();

                if (url.match('#')) {
                    var homepageFocusable = ['latest-news', 'shop'];
                    var anchor = url.split('#')[1];

                    if ($.inArray(anchor, homepageFocusable) != -1) {
                        $('body').stop().scrollTo($('#'+anchor), 1000);
                    }
                } 
            }, 1);
        </script>

    </body>
</html>