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

<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">
        <h4>{{= lang('results_you_searched_for') }}&#8220;{{= $keywords }}
            &#8221;...&nbsp;({{= $result_count }}{{= $result_singular_plural }})</h4>
    </div>

    <!-- List group -->
    <ul class="list-group">
        <li class="list-group-item"> {{= $pagination }} </li>
        {{ foreach ($results as $index => $result): }}
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
        {{ endif }}
        <!-- END OF 'ADS' SECTION -->

        <!-- START OF 'REVIEW ITEM' SECTION -->
        <li class="list-group-item">
            <h3 class="title">
                <a href="{{= base_url('review/show/' . $result->seo_title) }}">
                    <img src="{{= $result->list_thumb_url }}" class="img-polaroid">
                </a>{{= anchor('review/show/' . $result->seo_title, $result->title) }}
            </h3>

            <p>{{= character_limiter(strip_tags($result->description), 180) }}</p>

            <div class="tags">
                <span class="tag_title">{{= lang('results_tags') }}</span>
                {{ foreach ($result->tags as $i => $tag): }}
                {{ $search_index++ }}
                <form class=" tagformList" method="post" name="searchform{{= $search_index }}"
                      action="{{= site_url("results/search") }}">
                    <input id="keyword" type="hidden" name="keyword" value="{{= $tag }}"/>
                    <a onclick="javascript:dosearch('searchform{{= $search_index }}');"
                       class="badge badge-info">{{= $tag }}</a>
                </form>
                {{ endforeach }}
            </div>
            {{= anchor('review/show/' . $result->seo_title, lang('lists_read_more'), 'class="btn btn-success read_more_submit"') }}
        </li>

        <!-- END OF 'REVIEW ITEM' SECTION -->

        {{ endforeach }}

        <li class="list-group-item"> {{= $pagination }} </li>
    </ul>
</div>
