{* -*- coding: utf-8 -*- *}
{* Copyright (c) 2011,2012 Satoshi Fukutomi <info@fuktommy.com>. *}
{strip}

{if ! empty($entry.annotation)}
    {$entry.annotation}
    <ul>
        <li><a href="{$entry.object.url|escape}">{$entry.object.actor.displayName|escape}</a>
            <blockquote><div>{$entry.object.content}</div></blockquote>
        </li>
    </ul>
{elseif ! empty($entry.object.content)}
    {$entry.object.content}
{/if}

{if ! empty($entry.object.attachments)}
    <ul>
    {foreach from=$entry.object.attachments item=attach}
        <li>
            {include file="attach.tpl" attach=$attach}
        </li>
    {/foreach}
    </ul>
{/if}

{if ! empty($entry.placeName)}
    <a href="http://maps.google.co.jp/maps?q={$entry.geocode|replace:' ':','|escape:'url'}">
    {$entry.placeName|escape} - {$entry.address|escape}</a>
{/if}

{/strip}
