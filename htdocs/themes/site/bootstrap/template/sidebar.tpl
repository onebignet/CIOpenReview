<!-- START OF SIDEBAR SECTION --><!-- START OF CONDITIONAL 'SEARCH' SECTION -->
{{ if ($show_search): }}

<div class=" block">
    <h3 class="block-title sub-title"><span>{{= lang('sidebar_search') }}</span></h3>

    <form method="post" action="{{= site_url("results/search") }}">
        <input type="text" class="form-control" name="keyword" id="search-text" value=""/>
        <br>
        <input type="submit" id="search-submit" class="btn btn-success" value="GO"/>
    </form>
</div>
{{ endif }}
<!-- END OF 'SEARCH' SECTION -->

<!-- START OF CONDITIONAL 'CATEGORIES' SECTION -->
{{ if ($show_categories): }}

<div class="block">
    <h3 class="block-title sub-title"><span>{{= lang('sidebar_categories') }}</span></h3>
    <ul class="nav nav-list secondary-nav">
        {{ foreach ($categories as $index => $category): }}
        <li>
            <a href="{{= base_url('results/category/' . $category->seo_name) }}">
                <i class="icon-chevron-right"></i>
                {{= $category->name }}
            </a>
        </li>
        {{ if ($index + 1 != count($categories)): }}
        {{ endif }}
        {{ endforeach }}
    </ul>
</div>
{{ endif }}
<!-- END OF 'CATEGORIES' SECTION --><!-- START OF CONDITIONAL 'RECENT REVIEWS' SECTION -->
{{ if (($show_recent) && ($recent)): }}
<div class=" block">
    <h3 class="block-title sub-title"><span>{{= lang('sidebar_recent') }}</span></h3>
    <ul class="nav nav-list secondary-nav">
        {{ foreach ($recent as $index => $review): }}
        <li>
            <a href="{{= base_url('review/show/' . $review->seo_title) }}"><i class="icon-chevron-right"></i>
                {{= character_limiter($review->title, 50) }}
            </a>
        </li>
        <li>
            {{ if ($index + 1 != count($recent)): }}
            {{ endif }}
            {{ endforeach }}
    </ul>
</div>
{{ endif }}
<!-- END OF 'RECENT REVIEWS' SECTION -->

<!-- START OF CONDITIONAL 'SIDEBAR ADS' SECTION -->
{{ if ($sidebar_ads): }}
{{ foreach ($sidebar_ads as $ad) : }}
{{ if ($ad->visible_in_sidebar > 0): }}
{{ if ($ad->image != ''): }}
<p class=" sidebar_ad">
    {{= anchor($ad->link, $ad->image) }}
</p>
{{ endif }}
{{ if ($ad->text != ''): }}

<p class="sidebar_ad">
    {{= character_limiter($ad->text) }}
</p>
{{ endif }}
{{ endif }}
{{ endforeach }}
{{ endif }}
<!-- END OF 'SIDEBAR ADS' SECTION -->

<!-- START OF CONDITIONAL 'TAG CLOUD' SECTION -->
{{ if (isset($tagcloud)): }}

<div class=" block">
    <h3 class="block-title sub-title"><span>{{= lang('sidebar_tag_cloud') }}</span></h3>
    {{ foreach ($tagcloud as $i => $tag): }}

    <form class="tagform" method="post" name="tagsearch{{= $i }}" action="{{= site_url("results/search") }}">
        <input id="keyword" type="hidden" name="keyword" value="{{= $tag[1] }}"/>
	<span style="font-size:{{= get_cloud_size(16, 36, $cloudmin, $cloudmax, $tag[0]) }}px;">
	    <a onclick="javascript:dosearch('tagsearch{{= $i }}');">{{= highlight_keywords($tag[1], $keywords) }}</a>
                                </span>
    </form>
    &nbsp;
    {{ endforeach }}
</div>
{{ endif }}
<!-- END OF 'TAG CLOUD' SECTION --><!-- END OF SIDEBAR SECTION -->
