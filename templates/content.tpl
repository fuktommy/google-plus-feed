{* -*- coding: utf-8 -*- *}
{* Copyright (c) 2011 Satoshi Fukutomi <info@fuktommy.com>. *}
{$entry[47]|default:$entry[4]}
{if $entry[47] && $entry[4] && ($entry[4] != $entry[47])}
    <blockquote><div>{$entry[4]}</div></blockquote>
{/if}
{foreach from=$entry[11] item="link"}
    <br /><a href="{$link[24][1]|escape}">{$link[3]|default:$link[21]}</a>
    <blockquote cite="{$link[24][1]|escape}"><div>{$link[21]}</div></blockquote>
{/foreach}
{if $entry[27]}
    <br /><a href="{$entry[27][9]|default:$entry[27][8]|escape}">{$entry[27][2]|default:$entry[27][3]|escape}</a>
{/if}
{foreach from=$entry[66][0][6] item="img"}
    {if preg_match('|^image/|', $img[1])}
        <br />
        {if $img[0]}<a href="{$img[0]|escape}">{/if}
            <img src="http:{$img[2]|escape}" alt="" />
        {if $img[0]}</a>{/if}
    {/if}
{/foreach}
