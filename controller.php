<?php

namespace Concrete\Package\SlifeC5Events;

use Concrete\Core\Package\Package;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Events;
use Concrete\Core\Support\Facade\Package as PackageFacade;

class Controller extends Package
{
    protected $pkgHandle = 'slife_c5_events';
    protected $appVersionRequired = '8.1';
    protected $pkgVersion = '0.9.0';
    protected $pkgAutoloaderRegistries = [
        'src' => '\SlifeC5Events',
    ];

    protected $supportedEvents = [
        'on_page_add',
        //'on_page_update',
        //'on_page_delete',
        //'on_page_version_approve',
        //'on_page_version_submit_approve',
        //'on_page_version_deny',
        //'on_file_add',
        //'on_file_download',
        //'on_file_delete',
        //'on_user_add',
        //'on_user_update',
        //'on_user_change_password',
        //'on_user_delete',
        //'on_user_validate',
        //'on_user_activate',
        //'on_user_deactivate',
        //'on_user_login',
        //'on_job_execute',
    ];

    public function getPackageName()
    {
        return t('Slife C5 Events');
    }

    public function getPackageDescription()
    {
        return t('Slife Extension that adds and handles the concrete5 events.');
    }

    public function on_start()
    {
        $th = $this->app->make('helper/text');

        // Register event listeners
        foreach ($this->supportedEvents as $eventHandle) {
            $className = $th->camelcase($eventHandle);
            $listener = $this->app->make('SlifeC5Events\Event\\'.$className, [
                'package' => $this,
            ]);
            Events::addListener($eventHandle, [$listener, 'run']);
        }

        $event = new \Concrete\Core\Page\Event(Page::getByID(1));
        //vents::fire('on_page_add', $event);
    }

    public function install()
    {
        $pkg = parent::install();
        $this->installEverything($pkg);
    }

    public function upgrade()
    {
        $pkg = PackageFacade::getByHandle($this->pkgHandle);
        $this->installEverything($pkg);
    }

    public function installEverything($pkg)
    {
        $this->installEvents($pkg);
    }

    private function installEvents($pkg)
    {
        $th = $this->app->make('helper/text');

        foreach ($this->supportedEvents as $eventHandle) {
            $className = $th->camelcase($eventHandle);
            $eventClass = $this->app->make('SlifeC5Events\Event\\'.$className, [
                'package' => $this,
            ]);

            $eventClass->install();
        }
    }
}
