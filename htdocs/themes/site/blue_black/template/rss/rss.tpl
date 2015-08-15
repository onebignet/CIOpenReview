<title>{{= $title }}</title>
<link>{{= $link }}</link>
<description>{{= $description }}</description>
<dc:language>{{= $language }}</dc:language>
<dc:creator>{{= $creator }}</dc:creator>
<manager:generatorAgent rdf:resource="{{= $link }}"/>
{{ foreach ($reviews as $review): }}
<item>
    <title>{{= xml_convert($review->title) }}</title>
    <link>{{= site_url('review/show/' . $review->seo_title) }}</link>
    <guid>{{= site_url('review/show/' . $review->seo_title) }}</guid>
    <description><![CDATA[{{= $review->description }}]]></description>
    <pubDate>{{= date('D, d M Y H:i:s O', strtotime($review->last_modified)) }}</pubDate>
</item>
{{ endforeach }}
{{ foreach ($articles as $article): }}
<item>
    <title>{{= xml_convert($article->title) }}</title>
    <link>{{= site_url('article/show/' . $article->seo_title) }}</link>
    <guid>{{= site_url('article/show/' . $article->seo_title) }}</guid>
    <description><![CDATA[{{= $article->description }}]]></description>
    <pubDate>{{= date('D, d M Y H:i:s O', strtotime($article->last_modified)) }}</pubDate>
</item>
{{ endforeach }}