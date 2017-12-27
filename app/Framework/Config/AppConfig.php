<?php
namespace Framework\Config;

use Framework\Http\Request;
use Lib\Config;

class AppConfig
{
    const ENV_DEV = 'dev';
    const ENV_LIVE = 'live';
    const ENV_TEST = 'test';

    protected static $config = [];

    protected static $env;

    private static function setConfig()
    {
        if (empty(self::$config)) {
            self::$config = require config_dir . 'app.php';
        }
    }

    public static function get($param, $default = null)
    {
        static::setConfig();

        if (! empty(static::$config[$param])) {
            return static::$config[$param];
        } else {
            return $default;
        }
    }

    public static function resolveEnv()
    {
        if (empty(self::$env)) {
            $env = self::ENV_LIVE;

            if (self::get('env', false)) {
                $env = self::get('env');
            } else {
                $urls = [
                    self::ENV_DEV => self::get('devUrls', []),
                    self::ENV_TEST => self::get('testUrls', []),
                    self::ENV_LIVE => self::get('liveUrls', []),
                ];
                $host = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

                foreach ($urls as $envName => $envUrls) {
                    foreach ($envUrls as $url) {
                        if (preg_match($url, $host)) {
                            if ($envName == self::ENV_DEV) {
                                $env = self::ENV_DEV;
                            } else if ($envName == self::ENV_TEST) {
                                $env = self::ENV_TEST;
                            }
                            break(2);
                        }
                    }
                }
            }
            self::$env = $env;

        }

        return self::$env;
    }

    public static function getBuildVersion()
    {
        $buildVersion = "";

        if (in_array(self::$env, [self::ENV_TEST, self::ENV_DEV])) {
            $buildConfig = Config::load('buildVersion', 'buildVersion');

            $buildVersion = "{$buildConfig['env']}" .
                            "{$buildConfig['version']['major']}" .
                            ".{$buildConfig['version']['release']}" .
                            ".{$buildConfig['version']['hotfix']}" .
                            ".{$buildConfig['version']['build']}";
        }

        return $buildVersion;
    }
}
