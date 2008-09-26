<?php

//
// SOFTWARE NAME: Google Sitemap generator
// COPYRIGHT NOTICE: Copyright (C) 2008 Grenland Web AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//

include_once( "kernel/common/template.php" );
$tpl =& templateInit();

eZExpiryHandler::registerShutdownFunction();

define( 'MAX_AGE', 86400 );

while ( @ob_end_clean() );

if ( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) )
{
    header( $_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified' );

    header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + MAX_AGE ) . ' GMT' );
    header( 'Cache-Control: max-age=' . MAX_AGE );
    header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', strtotime( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) . ' GMT' );
    header( 'Pragma: ' );

    eZExecution::cleanExit();
}

$siteINI = eZINI::instance();
$sitemapINI = eZINI::instance( 'sitemap.ini' );
$siteURL = $sitemapINI->variable( 'SitemapSettings', 'SiteURL' );
$maxDepth = $sitemapINI->variable( 'SitemapSettings', 'MaxDepth' );
$classArray = $sitemapINI->variable( 'SitemapSettings', 'ClassArray' );
$rootNodeID = $sitemapINI->variable( 'SitemapSettings', 'RootNode' );
if ( $searchText = $Params['RootNode'] )
{
    $rootNodeID = $searchText = $Params['RootNode'];
}   

$node = eZContentObjectTreeNode::fetch( $rootNodeID );

if ( !$node )
{
    header( $_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found' );
}
else if ( !$node->canRead() )
{
    header( $_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found' );
}
else
{
    $conditions= array( 'Depth' => $maxDepth );
    $conditions['ClassFilterType'] = 'include';
    $conditions['ClassFilterArray'] = $classArray;

    $children = $node->subTree( $conditions );

    $tpl->setVariable( 'children', $children );
    $tpl->setVariable( 'siteURL', $siteURL );

    $list = $tpl->fetch( "design:sitemap/xml.tpl" );

    header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + MAX_AGE ) . ' GMT' );
    header( 'Cache-Control: cache, max-age=' . MAX_AGE . ', post-check=' . MAX_AGE . ', pre-check=' . MAX_AGE );
    header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $node->ModifiedSubNode ) . ' GMT' );
    header( 'Pragma: cache' );
    header( 'Content-Type: text/xml' );
    header( 'Content-Length: '.strlen( $list ) );
 
    echo $list;
}

eZExecution::cleanExit();

?>