<?php
/*
 * PubSubHubbub Publisher.
 *
 * Copyright (c) 2012 Satoshi Fukutomi <info@fuktommy.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHORS AND CONTRIBUTORS ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHORS OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 */
namespace Fuktommy\PubSubHubbub;
use Fuktommy\WebIo\Resource;

/**
 * PubSubHubbub Publisher.
 *
 * @package Fuktommy\PubSubHubbub
 */
class Publisher
{
    /**
     * @var Fuktommy\WebIo\Resource
     */
    private $_resource;

    /**
     * Constructor
     * @param Fuktommy\WebIo\Resource
     */
    public function __construct(Resource $resource)
    {
        $this->_resource = $resource;
    }

    /**
     * Publish update.
     * @param string $userId
     * @throws InvalidArgumentException
     */
    public function publish($userId)
    {
        if (! ctype_digit($userId)) {
            throw new InvalidArgumentException("{$userId} is not numeric");
        }
        $config = $this->_resource->config;
        if (empty($config['push_publisher'])) {
            return;
        }

        $feedUrl = ($userId === $config['gplusfeed_default_userid'])
                 ? $config['site_top']
                 : $config['site_top'] . $userId;
        $postData = array(
            'hub.mode' => 'publish',
            'hub.url' => $feedUrl,
        );
        $postString = http_build_query($postData, '', '&');
        $httpOptions = array(
            'method' => 'POST',
            'content' => $postString,
            'header' => implode("\r\n", array(
                'Content-Type: application/x-www-form-urlencoded',
                'Content-Length: ' . strlen($postString),
            )),
        );
        $log = $this->_resource->getLog('gplusfeed');
        $log->info("publishing {$feedUrl}");
        try {
            file_get_contents(
                $config['push_publisher'],
                false,
                stream_context_create(array('http' => $httpOptions)));
        } catch (ErrorException $e) {
            $log->warning("{$e->getMessage()} for publishing {$userId}");
        }
    }
}
