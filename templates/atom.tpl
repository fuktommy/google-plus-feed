{* -*- coding: utf-8 -*- *}
{* Copyright (c) 2011,2012 Satoshi Fukutomi <info@fuktommy.com>. *}
<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet href="/atomfeed.xsl" type="text/xsl"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title>{$feed.items[0].actor.displayName|default:$feed.title|escape} - g+</title>
  {* <subtitle>{$feed.title|escape}</subtitle> *}
  <link rel="self" href="{$config.site_top}{$userId|escape}" />
  <link rel="alternate" href="https://plus.google.com/{$userId|escape}/posts" type="text/html"/>
  <updated>{$feed.updated|escape}</updated>
  <generator>https://github.com/fuktommy/google-plus-feed</generator>
  <id>tag:fuktommy.com,2011:google/plusfeed</id>
  <author><name>{$feed.items[0].actor.displayName|escape}</name></author>
  <icon>https://ssl.gstatic.com/s2/oz/images/favicon.ico</icon>
{foreach from=$feed.items item="entry"}
  {include assign="content" file="content.tpl" entry=$entry}
  <entry>
    {if $entry.title}
        <title>{$entry.title|trim|escape}</title>
    {else}
        <title>{$content|strip_tags|regex_replace:'/\s+/':' '|htmlspecialchars_decode:$smarty.const.ENT_QUOTES|trim|mbtruncate:60|escape|default:"untitled"}</title>
    {/if}
    <link rel="alternate" href="{$entry.url|escape}"/>
    <summary type="text">{$content|strip_tags|regex_replace:'/\s+/':' '}</summary>
    <content type="html"><![CDATA[
        {$content|replace:"]]>":""}
    ]]></content>
    <published>{$entry.published|escape}</published>
    <updated>{$entry.updated|escape}</updated>
    <author><name>{$entry.actor.displayName|escape}</name></author>
    <id>tag:fuktommy.com,2011:google/plusfeed/{$entry.id|escape}</id>
    {if $entry.actor.id === $config.gplusfeed_default_userid}
        <rights>{$config.rights|escape}</rights>
    {/if}
  </entry>
{/foreach}
</feed>
