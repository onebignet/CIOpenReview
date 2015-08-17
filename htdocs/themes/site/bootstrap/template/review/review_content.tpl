<!-- START OF 'CONTENT' SECTION -->
<!-- START OF CONDITIONAL 'FEATURED' SECTION -->
{{ if ($featured_reviews >= $featured_minimum): }}
<div class="panel panel-danger featured_panel">
    <div class="panel-heading">
        <h3 class="panel-title"><h4>{{= lang('article_page_featured_reviews') }}</h4></h3>
    </div>
    <div class="panel-body">
        {{ foreach ($featured as $index => $featured_item): }}
        <div class="col-sm-6">
            <div class="panel-body">
                <h4>
                    <i class="glyphicon glyphicon-star icon-small icon-inverse-80 _icon-circle"></i>{{= anchor('review/show/' . $featured_item->seo_title, highlight_keywords(character_limiter($featured_item->title, 40), $keywords)) }}
                </h4>

                <p>{{= character_limiter(strip_tags($featured_item->description), 180) }}</p>
                {{= anchor('review/show/' . $featured_item->seo_title, lang('featured_more'), 'class="btn btn-success"') }}
            </div>
        </div>
        {{ endforeach }}
    </div>
</div>
{{ endif }}


<!-- END OF 'FEATURED' SECTION -->
<!-- START OF 'REVIEW' SECTION -->
<div class="review">

    <!-- START OF CONDITIONAL 'ADS' SECTION -->
    {{ if ($review_ads): }}
    {{ foreach ($review_ads as $ad): }}
    {{ if ($ad->visible_on_review_page > 0): }}
    <div class="review_ad_block">
        {{ if ($ad->image !== ''): }}
        <div class="ad_image">
            {{= anchor($ad->link, $ad->image) }}
        </div>
        {{ endif }}
        {{ if ($ad->text !== ''): }}
        <div class="ad_text">
            {{= character_limiter($ad->text) }}
        </div>
        {{ endif }}
    </div>
    {{ endif }}
    {{ break }}
    {{ endforeach }}
    {{ endif }}
    <!-- END OF 'ADS' SECTION -->

    <!-- START OF 'REVIEW CONTENT' SECTION -->
    <div id="review_content">
        <h2 class="heading">{{= anchor('recommends/this/' . $review->seo_title, $review->title) }}</h2>

        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#features_and_rating">
                            <h4>Features and Ratings</h4>
                        </a>
                    </h4>
                </div>
                <div id="features_and_rating" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <!-- START OF 'THUMBNAIL AND FEATURES' SECTION -->
                        <div class="col-sm-6">
                            {{ if ($features_count > 0): }}
                            <div class="review_features">
                                <h2>{{= lang('review_features') }}</h2>
                                {{ foreach ($features as $feature): }}
                                <p><b>{{= $feature->name }}: </b>{{= $feature->value }}</p>
                                {{ endforeach }}
                            </div>
                            {{ endif }}
                        </div>
                        <!-- END OF 'THUMBNAIL AND FEATURES' SECTION -->
                        <!-- START OF 'RATINGS' SECTION -->
                        {{ if ($ratings): }}
                        <div class="col-sm-6">
                            <h2>Scores</h2>
                            {{ foreach ($ratings as $rating): }}
                            <div class="rating_value">
                                <img src="{{= template_path() . 'design/images/' . $rating->rating_image }}" alt=""/>
                            </div>
                            <div class="rating_text">
                                {{= $rating->rating_name }}
                            </div>
                            {{ endforeach }}
                        </div>
                        {{ endif }}
                        <!-- END OF 'RATINGS' SECTION -->

                        <!-- START OF 'BUTTON' SECTION -->
                        <div class="col-sm-12">
                            {{= anchor('recommends/this/' . $review->seo_title, lang('review_button') . " " . $review->seo_title, 'rel="nofollow" class="btn btn-lg btn-success center col-sm-5 offset2"') }}
                        </div>
                        <!-- END OF 'BUTTON' SECTION -->
                        <!-- START OF 'SOCIAL BOOKMARKS' SECTION -->
                        <div class="col-sm-12">
                            <a target="_blank" href="http://www.freescriptsite.com/free-social-bookmark-script/">
                                <img border="0"
                                     src="http://www.freescriptsite.com/free-social-bookmark-script/social1.gif"
                                     alt=""/>
                            </a>
                            <SCRIPT type="text/javaScript"
                                    src="http://www.freescriptsite.com/free-social-bookmark-script/bookmark-script.js"></SCRIPT>
                        </div>
                        <!-- END OF 'SOCIAL BOOKMARKS' SECTION -->
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#full_review">
                            <h4>Full Review</h4>
                        </a>
                    </h4>
                </div>
                <div id="full_review" class="panel-collapse collapse in">
                    <div class="panel-body">
                        {{= auto_typography($review->description) }}

                        <!-- START OF 'BUTTON' SECTION -->
                        <div class="col-sm-12">
                            {{= anchor('recommends/this/' . $review->seo_title, lang('review_button') . " " . $review->seo_title, 'rel="nofollow" class="btn btn-lg btn-success center col-sm-5 offset2"') }}
                        </div>
                        <!-- END OF 'BUTTON' SECTION -->
                        <!-- START OF 'SOCIAL BOOKMARKS' SECTION -->
                        <div class="col-sm-12">
                            <a target="_blank" href="http://www.freescriptsite.com/free-social-bookmark-script/">
                                <img border="0"
                                     src="http://www.freescriptsite.com/free-social-bookmark-script/social1.gif"
                                     alt=""/>
                            </a>
                            <SCRIPT type="text/javaScript"
                                    src="http://www.freescriptsite.com/free-social-bookmark-script/bookmark-script.js"></SCRIPT>
                        </div>
                        <!-- END OF 'SOCIAL BOOKMARKS' SECTION -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END OF 'REVIEW CONTENT' SECTION -->


    <!-- START OF CONDITIONAL 'ADS' SECTION -->
    {{ if ($review_ads): }}
    {{ foreach ($review_ads as $ad) : }}
    {{ if ($ad->visible_on_review_page > 0): }}
    <div class=" review_ad_block">
        {{ if ($ad->image !== ''): }}
        <div class="ad_image">
            {{= anchor($ad->link, $ad->image) }}
        </div>
        {{ endif }}
        {{ if ($ad->text !== ''): }}
        <div class="ad_text">
            {{= character_limiter($ad->text) }}
        </div>
        {{ endif }}
    </div>
    {{ endif }}
    {{ break }}
    {{ endforeach }}
    {{ endif }}
    <!-- END OF 'ADS' SECTION -->
</div>


<!-- END OF 'CONTENT' SECTION -->
