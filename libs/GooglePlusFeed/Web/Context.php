<?php
/* Web IO.
 *
 * Copyright (c) 2010,2012 Satoshi Fukutomi <info@fuktommy.com>.
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
namespace GooglePlusFeed\Web;
use GooglePlusFeed\Config\Bootstrap;

/**
 * Web IO.
 * @package GooglePlusFeed
 * @subpackage Web
 */
class Context
{
    /**
     * @var array
     */
    public $config = array();

    /**
     * @var array $_GET
     */
    public $get = array();

    /**
     * @var array $_POST
     */
    public $post = array();

    /**
     * @var array $_COOKIE
     */
    public $cookie = array();

    /**
     * @var array $_REQUEST
     */
    public $request = array();

    /**
     * @var array getallheaders()
     */
    public $header = array();

    /**
     * @var array $_SERVER
     */
    public $server = array();

    /**
     * @var array $_FILES
     */
    public $files = array();

    /**
     * @var array Registory.
     */
    public $vars = array();

    /**
     * Constructor
     */
    private function __construct()
    {
    }

    /**
     * Factory
     * @return GooglePlusFeed\Web\Context
     */
    public static function factory(array $config = array())
    {
        if (empty($config)) {
            $config = Bootstrap::$config;
        }
        $instance = new self();
        $instance->config = $config;
        $instance->get = $_GET;
        $instance->post = $_POST;
        $instance->cookie = $_COOKIE;
        $instance->request = $_REQUEST;
        $instance->header = is_callable('getallheaders')
                          ? getallheaders() : array();
        $instance->server = $_SERVER;
        $instance->files = $_FILES;
        return $instance;
    }

    /**
     * Getter
     * @param string $property 'config', 'get', ...
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($property, $key, $default = null)
    {
        $valueExists = isset($this->$property)
                    && is_array($this->$property)
                    && array_key_exists($key, $this->$property);
        if ($valueExists) {
            return $this->{$property}[$key];
        } else {
            return $default;
        }
    }

    /**
     * Put HTTP header.
     * @param string $key
     * @param string $value
     */
    public function putHeader($key, $value = null)
    {
        if (is_null($value)) {
            header($key);
        } else {
            header("{$key}: {$value}");
        }
    }

    /**
     * Change HTTP body encoding.
     * @param string $encoding
     */
    public function switchEncoding($encoding)
    {
        mb_http_output($encoding);
        mb_internal_encoding('UTF-8');
        ob_start('mb_output_handler');
    }

    /**
     * Factory for Smarty
     * @return Smarty
     */
    public function getSmarty()
    {
        require_once 'Smarty.class.php';
        $smarty = new \Smarty();
        $smarty->template_dir = $this->config['smarty_template_dir'];
        $smarty->plugins_dir = array_merge(
            $smarty->plugins_dir,
            $this->config['smarty_plugins_dir']
        );
        $smarty->compile_dir = $this->config['smarty_compile_dir'];
        $smarty->cache_dir = $this->config['smarty_cache_dir'];
        return $smarty;
    }

    /**
     * Factory for Log
     * @param string $ident
     * @return Log
     */
    public function getLog($ident = '')
    {
        require_once 'Log.php';
        $logfile = $this->config['log_dir'] . strftime('/debug.%Y%m%d.log');
        return \Log::singleton('file', $logfile, $ident);
    }
}
