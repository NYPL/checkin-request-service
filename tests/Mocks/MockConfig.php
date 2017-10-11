<?php
namespace NYPL\Services\Test\Mocks;

use Dotenv\Dotenv;
use NYPL\Starter\APIException;
use NYPL\Starter\Config;

class MockConfig extends Config
{
    const ENVIRONMENT_FILE = '.env.sample';
    const CONFIG_FILE = 'mock.env';

    protected static $initialized = false;

    protected static $configDirectory = '';

    protected static $required =
        [
            'SLACK_TOKEN',
            'AWS_ACCESS_KEY_ID',
            'AWS_SECRET_ACCESS_KEY',
            'TIME_ZONE'
        ];

    protected static $addedRequired = [];

    /**
     * @param string $configDirectory
     * @param array  $required
     */
    public static function initialize($configDirectory = '', array $required = [])
    {
        self::setConfigDirectory($configDirectory);

        if ($required) {
            self::setAddedRequired($required);
        }

        self::loadConfiguration();

        self::setInitialized(true);
    }

    /**
     * @param string $name
     * @param null   $defaultValue
     * @param bool   $isEncrypted
     *
     * @return null|string
     * @throws APIException
     */
    public static function get($name = '', $defaultValue = null, $isEncrypted = false)
    {
        if (!self::isInitialized()) {
            throw new APIException('Configuration has not been initialized');
        }

        if (getenv($name) !== false) {
            if ($isEncrypted && self::isEncryptedEnvironment()) {
                return self::decryptEnvironmentVariable($name);
            }

            return (string) getenv($name);
        }

        return $defaultValue;
    }

    /**
     * @throws APIException
     * @return bool
     */
    protected static function isEncryptedEnvironment()
    {
        return true;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected static function decryptEnvironmentVariable($name = '')
    {
        return (string)getenv($name);
    }

    protected static function loadConfiguration()
    {
        $dotEnv = new Dotenv(self::getConfigDirectory(), self::ENVIRONMENT_FILE);
        $dotEnv->load();

        if (file_exists(self::getConfigDirectory() . 'tests/Mocks/' . self::CONFIG_FILE)) {
            $dotEnv = new Dotenv(self::getConfigDirectory() . 'tests/Mocks/', self::CONFIG_FILE);
            $dotEnv->load();
        }

        $dotEnv->required(self::getRequired());

        $dotEnv->required(self::getAddedRequired());

        self::setInitialized(true);
    }
}
