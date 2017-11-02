<?php

namespace Concrete\Package\FakeData;

use A3020\FakeData\Provider\FakeDataServiceProvider;
use Concrete\Core\Package\Package;

final class Controller extends Package
{
    protected $pkgHandle = 'fake_data';
    protected $appVersionRequired = '8.2.1';
    protected $pkgVersion = '0.9.1';
    protected $pkgAutoloaderRegistries = [
        'src/FakeData' => '\A3020\FakeData',
    ];

    public function getPackageName()
    {
        return t('Fake Data');
    }

    public function getPackageDescription()
    {
        return t('Create fake data in your C5 installation');
    }

    /**
     * Load Composer files.
     *
     * If the C5 installation is Composer based, the vendor directory will
     * be in the root directory. Therefore we first check if the autoloader exists.
     */
    public function on_start()
    {
        $autoloadFile = $this->getPackagePath() . '/vendor/autoload.php';
        if (file_exists($autoloadFile)) {
            require_once $autoloadFile;
        }

        $provider = $this->app->make(FakeDataServiceProvider::class);
        $provider->register();
    }
}
