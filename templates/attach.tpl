{* -*- coding: utf-8 -*- *}
{* Copyright (c) 2012 Satoshi Fukutomi <info@fuktommy.com>. *}
{strip}
{if $attach.objectType === "photo"}
    {if ! empty($attach.url)}    
        <a href="{$attach.url|escape}">
    {/if}
    <img src="{$attach.image.url|escape}" alt="{$attach.content|escape}"
         {**} height="{$attach.image.height|escape}"
         {**} width="{$attach.image.width|escape}" />
    {if ! empty($attach.url)}    
        </a>
    {/if}
{else}
    {if ! empty($attach.url)}
        <a href="{$attach.url|escape}">
        {$attach.displayName|default:"link"}</a>
    {/if}
    {if ! empty($attach.content)}
        <blockquote><div>{$attach.content}</div></blockquote>
    {/if}
    {if ! empty($attach.image)}
        <div>
        <img src="{$attach.image.url|escape}" alt=""
            {if ! empty($attach.image.height)} height="{$attach.image.height|escape}"{/if}
            {if ! empty($attach.image.width)} width="{$attach.image.width|escape}"{/if}
        />
        </div>
    {/if}
{/if}
{/strip}
