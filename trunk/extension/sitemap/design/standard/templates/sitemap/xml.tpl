<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{foreach $children as $child}
   <url>
      <loc>{$siteURL}{$child.url_alias|ezurl(no)}</loc>
      <lastmod>{$child.object.modified|datetime('custom', '%Y-%m-%d')}</lastmod>
   </url>
{/forach}
</urlset> 