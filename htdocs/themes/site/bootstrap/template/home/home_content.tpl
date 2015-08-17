<!-- START OF 'CONTENT' SECTION -->
<div class="page-header">
    <h2>{{= $site_summary_title }}</h2>
</div>
<h3>
    <small>{{= $site_summary_text }}</small>
</h3>

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

<!-- START OF CONDITIONAL 'LATEST' SECTION -->
{{ if ($latest): }}
<div class="panel panel-default featured_panel">
    <div class="panel-heading">
        <h3 class="panel-title">
            <h4>{{= lang('home_latest_reviews') }}</h4>
        </h3>
    </div>
    <ul class="list-group">
        <li class="list-group-item">
            {{= $pagination }}
        </li>
        {{ foreach ($latest as $index => $latest_result): }}

        <!-- START OF CONDITIONAL 'ADS' SECTION -->
        {{ if ($list_ads): }}
        <!-- CHANGE THE NUMBERS IN THE NEXT LINE TO CHANGE THE POSITIONS IN THE LIST WHERE ADS APPEAR -->
        {{ if (in_array($index, array(
        2,
        6
        ))
        ) : }}
        <li class="list-group-item">
            {{ foreach ($list_ads as $ad) : }}
            {{ if ($ad->visible_in_lists > 0): }}
            <div class="lists_ad_block">
                {{ if ($ad->image != ''): }}
                <div class="ad_image">
                    {{= anchor($ad->link, $ad->image) }}
                </div>
                {{ endif }}
                {{ if ($ad->text != ''): }}
                <div class="ad_text">
                    {{= character_limiter($ad->text) }}
                </div>
                {{ endif }}
            </div>
            {{ endif }}
            {{ break }}
            {{ endforeach }}
        </li>
        {{ endif }}
        {{ endif }}                            <!-- END OF 'ADS' SECTION -->
        <!-- START OF 'REVIEW ITEM' SECTION -->
        <li class="list-group-item">
            <h3 class="title">
                <a href="{{= base_url('review/show/' . $latest_result->seo_title) }}">
                    <img src="{{= $latest_result->list_thumb_url }}" class="img-polaroid">
                </a>{{= anchor('review/show/' . $latest_result->seo_title, highlight_keywords($latest_result->title, $keywords)) }}
                <img src="{{= template_path() . 'design/images/' . $latest_result->rating_image }}" alt=""/>
            </h3>

            <p>{{= character_limiter($latest_result->description, 300) }}</p>

            <p>{{= anchor('review/show/' . $latest_result->seo_title, lang('lists_read_more'), 'class="btn btn-success"') }}</p>
        </li>
        <!-- END OF REVIEW ITEM SECTION -->
        {{ endforeach }}

        <li class="list-group-item">
            {{= $pagination }}
        </li>
    </ul>
</div>
{{ else: }}                                    <!-- IF THERE ARE NO REVIEWS DISPLAY A MESSAGE -->
<div class="alert alert-info">
    <h4 class="alert-heading">{{= lang('home_no_reviews') }}</h4>
</div>
{{ endif }}                                        <!-- END OF 'LATEST' SECTION -->

<!-- END OF 'CONTENT' SECTION -->
