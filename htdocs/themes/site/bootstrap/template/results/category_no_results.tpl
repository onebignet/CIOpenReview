<!-- START OF 'CONTENT' SECTION -->
<h2 class="heading no_items">{{= lang('results_no_reviews_in_cat_1') }}{{= $category_name }}{{= lang('results_no_reviews_in_cat_2') }}</h2>
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
<!-- END OF 'CONTENT' SECTION -->