<?php

namespace AppBundle\Entity;

/**
 * Settings
 */
class Settings
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $configKey;

    /**
     * @var string
     */
    private $configValue;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set configKey.
     *
     * @param string $configKey
     *
     * @return Settings
     */
    public function setConfigKey($configKey)
    {
        $this->configKey = $configKey;

        return $this;
    }

    /**
     * Get configKey.
     *
     * @return string
     */
    public function getConfigKey()
    {
        return $this->configKey;
    }

    /**
     * Set configValue.
     *
     * @param string $configValue
     *
     * @return Settings
     */
    public function setConfigValue($configValue)
    {
        $this->configValue = $configValue;

        return $this;
    }

    /**
     * Get configValue.
     *
     * @return string
     */
    public function getConfigValue()
    {
        return $this->configValue;
    }
}
