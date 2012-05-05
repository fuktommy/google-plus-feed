<?php
/* Bootstrap.
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
namespace GooglePlusFeed\Config;

/**
 * Bootstrap.
 * @pacpage GooglePlusFeed
 * @subpackage Config
 */
class Bootstrap
{
    public static $config = array();

    public static function autoload($className)
    {
        $path = self::$config['libs_dir'] . DIRECTORY_SEPARATOR
              . strtr($className, array('\\' => DIRECTORY_SEPARATOR))
              . '.php';
        require_once $path;
    }

    public static function handleError($errno, $errstr, $errfile, $errline)
    {
        throw new \RuntimeException("{$errstr} in {$errfile} on line {$errline}", $errno);
    }

    public static function init()
    {
        spl_autoload_register(__NAMESPACE__ . '\\Bootstrap::autoload');
        set_error_handler(__NAMESPACE__ . '\\Bootstrap::handleError',
                          E_ERROR | E_WARNING | E_PARSE | E_RECOVERABLE_ERROR);
        self::$config = require __DIR__ . '/../../conf/siteconfig.php';
    }
}

Bootstrap::init();
