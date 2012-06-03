{* -*- coding: utf-8 -*- *}
{* Copyright (c) 2011,2012 Satoshi Fukutomi <info@fuktommy.com>. *}
{if ! empty($entry.object.content)}
    {$entry.object.content}
{/if}

{if ! empty($entry.object.attachments)}
    <ul>
    {foreach from=$entry.object.attachments item=attach}
        <li>
        {if ! empty($attach.url)}
            <a href="{$attach.url|escape}" target="_blank">{$attach.displayName|default:"link"|escape}</a>
        {/if}
        {if ! empty($attach.content)}
            <blockquote><div>{$attach.content|escape}</div></blockquote>
        {/if}
        {if ! empty($attach.image)}
            <div>
            <img src="{$attach.image.url|escape}" alt=""
                 {if ! empty($attach.image.height)}height="{$attach.image.height|escape}"{/if}
                 {if ! empty($attach.image.width)}width="{$attach.image.width|escape}"{/if}
            />
            </div>
        {/if}
        </li>
    {/foreach}
    </ul>
{/if}

{if ! empty($entry.placeName)}
    {strip}
    <a href="http://maps.google.co.jp/maps?q={$entry.geocode|replace:' ':','|escape:'url'}">
    {$entry.placeName|escape} - {$entry.address|escape}</a>
    {/strip}
{/if}
