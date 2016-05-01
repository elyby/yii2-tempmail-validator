<?php
namespace Ely\Yii2;

use Ely\TempMailBuster\StorageInterface;
use Ely\TempMailBuster\Validator as TempmailBuster;
use Yii;
use yii\validators\Validator;

class TempmailValidator extends Validator
{
    /**
     * @var string class name for used tempmail loader
     */
    public $loader = '\Ely\TempMailBuster\Loader\AntiTempmailRepo';
    /**
     * @var string class name for used storage object
     */
    public $storage = '\Ely\TempMailBuster\Storage';
    /**
     * @var bool switcher for white/blacklist validation
     */
    public $whitelistMode = false;
    /**
     * @var null|array|StorageInterface additional list to invert current mode validation
     * @see \Ely\TempMailBuster\Validator::validate() implementation for additional info
     */
    public $secondaryStorage;

    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('yii', '{attribute} is not allowed email address.');
        }
    }

    protected function validateValue($value)
    {
        $validator = $this->buildValidator();
        if ($validator->validate($value)) {
            return null;
        }

        return [$this->message, []];
    }

    /**
     * @return TempmailBuster
     */
    protected function buildValidator()
    {
        /** @var \Ely\TempMailBuster\LoaderInterface $loader */
        $loader = new $this->loader;
        /** @var StorageInterface $primaryStorage */
        $primaryStorage = new $this->storage($loader->load());
        $secondaryStorage = $this->secondaryStorage;
        if (is_array($this->secondaryStorage)) {
            $secondaryStorage = new $this->storage($this->secondaryStorage);
        }

        $validator = new TempmailBuster($primaryStorage, $secondaryStorage);
        $validator->whitelistMode($this->whitelistMode);

        return $validator;
    }
}
