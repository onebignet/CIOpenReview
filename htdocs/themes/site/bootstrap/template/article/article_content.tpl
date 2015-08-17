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

<!-- START OF CONDITIONAL 'ADS' SECTION -->
{{ if ($article_ads): }}
{{ foreach ($article_ads as $ad): }}
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

<!-- START OF 'ARTICLE CONTENT' SECTION -->
<div class="page-header">
    <h2>{{= $article->title }}</h2>
</div>
<p>{{= $article->description }}</p>
{{ if (($article->link_text !== '') && ($article->link_url !== '')): }}
<h3 class=article_link">{{= anchor($article->link_url, $article->link_text) }}</h3>
{{ endif }}
<!-- END OF 'ARTICLE CONTENT' SECTION -->

<!-- START OF CONDITIONAL 'ADS' SECTION -->
{{ if ($article_ads): }}
{{ foreach ($article_ads as $ad): }}
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
<!-- END OF 'ADS' SECTION --><!-- END OF 'CONTENT' SECTION -->
