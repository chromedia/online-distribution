<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Open Tech Collaborative</title>
        <link rel="shortcut icon" href="img/favicon.ico">

        <!-- <link rel="stylesheet" href="catalog/view/theme/chromedia/css/foundation/foundation.min.css" /> -->
        <link rel="stylesheet" href="catalog/view/theme/chromedia/css/app.css" />
        <link rel="stylesheet" href="catalog/view/theme/chromedia/css/style.css" />


        <script src="catalog/view/theme/chromedia/javascripts/foundation/modernizr.foundation.js"></script>
        <script src="catalog/view/theme/chromedia/javascripts/jquery/jquery.min.js"></script>
        <script>
        /*(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-46786512-1', 'opentechcollaborative.cc');
        ga('send', 'pageview');*/

        </script>

    </head>
    
    <body style="height:100%;">

        <!-- <div class="wrapper" style="position:relative; min-height:100%;"> -->

            <!-- <div class="main-content" style="padding-bottom: 295px;"> -->
                <!-- BEG TOP BAR -->
            <?php if(isset($sticky_header) && $sticky_header): ?>
                <div class="fixed" id="top">
                    <?php include_once(DIR_APPLICATION . 'view/theme/chromedia/template/common/_header_nav.tpl'); ?> 
                </div>
            <?php else: ?>
                <?php include_once(DIR_APPLICATION . 'view/theme/chromedia/template/common/_header_nav.tpl'); ?> 
            <?php endif;?>
            <!-- END TOP BAR -->

            <?php include_once(DIR_APPLICATION . 'view/theme/chromedia/template/modal/common_modal.tpl'); ?> 

            <div class="page-wrap">

            
