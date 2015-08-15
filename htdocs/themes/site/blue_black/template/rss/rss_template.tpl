{{= '{{ xml version="1.0" encoding="utf-8" }}'."\n" }}
<rss version="2.0"
     xmlns:atom="http://www.w3.org/2005/Atom"
        >
    <channel>
        <atom:link href="{{= $link }}" rel="self"/>
        {{= $rss_content }}
    </channel>
</rss>