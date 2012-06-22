<?php
/*
 * Google+ Json Feed.
 *
 * Copyright (c) 2011,2012 Satoshi Fukutomi <info@fuktommy.com>.
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
namespace Fuktommy\GooglePlusFeed\Model;
use Fuktommy\WebIo\Resource;

/**
 * Google+ Json Feed Fetcher.
 *
 * This class save date to cache, and so on.
 *
 * @package Fuktommy\GooglePlusFeed
 * @subpackage Model
 */
class JsonFeedFetcher
{
    /**
     * @var string
     */
    private $_cacheDir;

    /**
     * @var int
     */
    private $_cacheTime = 600;

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
        $this->_cacheDir = $resource->config['gplus_cache_dir'];
    }

    /**
     * Fetch feed.
     * @param string $userId
     * @return Fuktommy\GooglePlusFeed\Model\JsonFeed
     * @throws InvalidArgumentException
     */
    public function fetchFeed($userId)
    {
        if (! ctype_digit($userId)) {
            throw new InvalidArgumentException("{$userId} is not numeric");
        }
        $oldJson = $this->_readCache($userId);
        $json = $this->_getJsonUsingCache($userId);
        return new JsonFeed(json_decode($json, true), $oldJson !== $json);
    }

    private function _cacheFileOf($userId)
    {
        return "{$this->_cacheDir}/{$userId}.txt";
    }

    private function _readCache($userId)
    {
        $cacheFile = $this->_cacheFileOf($userId);
        if (is_file($cacheFile)) {
            return file_get_contents($cacheFile);
        } else {
            return '';
        }
    }

    private function _getJsonUsingCache($userId)
    {
        $cacheFile = $this->_cacheFileOf($userId);
        $readFromCache = is_file($cacheFile)
                      && (time() < filemtime($cacheFile) + $this->_cacheTime);
        if ($readFromCache) {
            return file_get_contents($cacheFile);
        }

        $log = $this->_resource->getLog('gplusfeed');
        $lock = fopen("{$this->_cacheDir}/lock", 'w');
        $lockSuccess = flock($lock, LOCK_EX|LOCK_NB);
        if (! $lockSuccess) {
            fclose($lock);
            $log->info("lock failed for {$userId}");
            return $this->_readCache($userId);
        }

        touch($cacheFile);
        $apiKey = $this->_resource->config['google_api_key'];
        $jsonUrl = "https://www.googleapis.com/plus/v1/people/{$userId}/activities/public?key={$apiKey}";
        $log->info("accessing json for {$userId}");
        try {
            $json = file_get_contents($jsonUrl);
        } catch (ErrorException $e) {
            // I can not catch exceptions here...
            $log->warning("{$e->getMessage()} for {$userId}");
            return $this->_readCache($cacheFile);
        }
        if (empty($json)) {
            $log->warning("empty json for {$userId}");
            return $this->_readCache($cacheFile);
        }
        file_put_contents($cacheFile, $json);
        flock($lock, LOCK_UN);
        fclose($lock);
        return $json;
    }
}
