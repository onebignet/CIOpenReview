<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="content-language" content="en"/>
    <meta name="keywords" content="{{= $meta_keywords }}"/>
    <meta name="description" content="{{= $meta_description }}"/>
    <title>{{= $page_title }}</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
    <link href="{{= template_path() }}assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{= template_path() }}assets/css/{{= $this->setting['template_color_theme'] }}.css">
    {{ if ($lightbox): }}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">
    {{ endif }}

    <link rel="alternate" type="application/rss+xml" title="Latest Reviews (RSS2.0)" href="{{= base_url() . 'rss' }}"/>
    <script language="JavaScript" type="text/javascript">
        <!--
        function dosearch(formname) {
            theform = document.forms[formname];
            theform.submit();
            return false;
        }
        -->
    </script>

    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/icons/114x114.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/icons/72x72.png">
    <link rel="apple-touch-icon-precomposed" href="img/icons/default.png">
    <link href='http://fonts.googleapis.com/css?family=Monda:400,700' rel='stylesheet' type='text/css'>
</head>

<body class="has-navbar-fixed-top page-index">
<!-- START OF NAVIGATION -->
<nav class="navbar navbar-inverse navbar-static-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">{{=  $page_title }}</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav pull-right">
                <li>{{= anchor('/', lang('top_nav_home')) }}</li>
                <li>{{= anchor('articles', lang('top_nav_articles')) }}</li>
                <li>{{= anchor('page/show/privacy_policy_terms_and_conditions', lang('top_nav_privacy')) }}</li>
                <li>{{= anchor('page/show/about_us', lang('top_nav_about_us')) }}</li>
                <li>{{= anchor('contact', lang('top_nav_contact_us')) }}</li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
<!-- END OF NAVIGATION -->
<!-- START OF 'MAIN' SECTION -->
<div id="highlighted">
    <div class="inner"></div>
</div>
<!-- START OF 'CONTAINER' SECTION -->
<div id="content">
    <div class="container">
        <div class="inner">
            <div class="row">
                <div class="col-sm-9">
                    {{= $content }}
                </div>
                <div class="col-sm-3 sidebar sidebar-right">
                    <div class="inner">
                        {{= $sidebar }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="content-below" class="wrapper">
    <div class="container"></div>
</div>

<!-- END OF 'CONTAINER' SECTION -->

<!-- FOOTER -->
<nav class="navbar navbar-default navbar-bottom">
    <div class="container">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col text-center">
                    <h3>More...</h3>
                </div>
                <div class="col-sm-4 col">
                    <div class="media row">
                        <div class="media-body span6">
                            <h5 class="title media-heading">
                                {{= anchor(base_url(), 'Latest Reviews') }}
                            </h5>
                        </div>
                    </div>
                    <div class="media row">
                        <div class="media-body span6">
                            <h5 class="title media-heading">
                                {{= anchor('articles', lang('top_nav_articles')) }}
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col">
                    <div class="media row">
                        <div class="media-body span6">
                            <h5 class="title media-heading">
                                {{= anchor('page/show/about_us', lang('top_nav_about_us')) }}
                            </h5>
                        </div>
                    </div>
                    <div class="media row">
                        <div class="media-body span6">
                            <h5 class="title media-heading">
                                {{= anchor('contact', lang('top_nav_contact_us')) }}
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col">
                    <div class="media row">
                        <div class="media-body span6">
                            <h5 class="title media-heading">
                                {{= anchor('page/show/privacy_policy_terms_and_conditions', lang('top_nav_privacy')) }}
                            </h5>
                        </div>
                    </div>
                    <div class="media row">
                        <div class="media-body span6">
                            <h5 class="title media-heading">
                                <a href="{{= base_url() . 'rss' }}">RSS</a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 text-center">
                    <p?>Copyright &copy;2015 - {{= date("Y"); }} Powered by <a
                            href="http://ciopenreview.com">CIOpenReview</a></p>
                </div>
            </div>
        </div>
    </div>
</nav>
<!-- END OF 'MAIN' SECTION -->

<!-- Bootstrap Javascript -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"
        type="text/javascript"></script>
<script src="{{= template_path() }}assets/js/script.js"
        type="text/javascript"></script>
<script src='{{= base_url() }}libs/raty/jquery.raty.js' type="text/javascript"
        language="javascript"></script>
<script language="JavaScript">
    $(document).ready(function () {

        $('.star_rating').raty({
            path: '{{= base_url() }}libs/raty/images',
            readOnly: true,
            half: true,
            score: function () {
                return $(this).attr('data-score');
            }
        });
        // Handler for .ready() called.
    });
</script>
{{ if ($lightbox): }}
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.9.0/js/lightbox.min.js" type="text/javascript"></script>
{{ endif }}
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })
    (window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-382047-15', 'auto');
    ga('send', 'pageview');

</script>
</body>
</html>
