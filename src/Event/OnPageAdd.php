<?php

namespace SlifeC5Events\Event;

use Slife\Integration\BasicEvent;

class OnPageAdd extends BasicEvent
{
    public function install()
    {
        $this->getOrCreateEvent();
        $this->getOrCreatePlaceholders([
            'username',
            'page_name',
        ]);
    }

    public function getDefaultMessage()
    {
        return t('Page added by {username}: {page_name}.');
    }

    /**
     * Return the handle of the event.
     *
     * E.g. 'on_page_add'.
     *
     * @return string
     */
    public function getEventHandle()
    {
        return 'on_page_add';
    }
}