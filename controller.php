<?php

namespace Concrete\Package\SlifeC5Events;

use Concrete\Core\Support\Facade\Events;
use Slife\Integration\SlifePackageController;

class Controller extends SlifePackageController
{
    protected $pkgHandle = 'slife_c5_events';
    protected $appVersionRequired = '8.2';
    protected $pkgVersion = '0.9.3';
    protected $pkgAutoloaderRegistries = [
        'src' => '\SlifeC5Events',
    ];

    protected $supportedEvents = [
        'on_page_type_publish',
        'on_page_delete',
        'on_file_delete',
        'on_user_add',
        'on_user_login',
        'on_user_change_password',

        /*
         * 'on_page_add', // cName not available, use on_page_type_publish
         */

        /* @todo these events still need to be implemented */
        //'on_page_update',
        //'on_page_version_approve',
        //'on_page_version_submit_approve',
        //'on_page_version_deny',
        //'on_file_add',
        //'on_file_download',
        //'on_user_update',
        //'on_user_delete',
        //'on_user_validate',
        //'on_user_activate',
        //'on_user_deactivate',
        //'on_job_execute',
    ];

    public function getPackageName()
    {
        return t('Slife C5 Events');
    }

    public function getPackageDescription()
    {
        return t('Slife Extension that adds and handles concrete5 events.');
    }

    public function on_start()
    {
        $th = $this->app->make('helper/text');

        // Register event listeners
        foreach ($this->supportedEvents as $eventHandle) {
            $className = $th->camelcase($eventHandle);
            $listener = $this->app->make('SlifeC5Events\Event\\'.$className, [
                'package' => $this->getPackageEntity(),
            ]);
            Events::addListener($eventHandle, [$listener, 'run']);
        }
    }
}
