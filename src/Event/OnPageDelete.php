<?php

namespace SlifeC5Events\Event;

use Concrete\Core\User\User;
use Slife\Integration\BasicEvent;
use Slife\Utility\Slack;

class OnPageDelete extends BasicEvent
{
    /**
     * Return the handle of the event.
     *
     * @return string
     */
    public function getEventHandle()
    {
        return 'on_page_delete';
    }

    public function install()
    {
        $this->getOrCreateEvent();
        $this->getOrCreatePlaceholders([
            'page_name',
            'user_name',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultMessage()
    {
        return t("Page '{page_name}' has been deleted by user {user_name}.");
    }

    /**
     * @param \Concrete\Core\Page\DeletePageEvent $event
     * @param string $message
     *
     * @return string
     */
    protected function replacePageName(\Concrete\Core\Page\DeletePageEvent $event, $message)
    {
        $page = $event->getPageObject();

        return str_replace('{page_name}', $page->getCollectionName(), $message);
    }

    /**
     * @param \Concrete\Core\Page\Event $event
     * @param string $message
     *
     * @return string
     */
    protected function replaceUserName(\Concrete\Core\Page\Event $event, $message)
    {
        /**
         * @var User $user
         *
         * If this event is triggered programmatically, the user is null.
         */
        $user = $event->getUserObject();
        if ($user) {
            $link = DIR_BASE . 'index.php/dashboard/users/search/view/' . $user->getUserID();

            $sh = $this->app->make(Slack::class);
            $userName = $sh->makeLink($link, $user->getUserName());
        } else {
            $userName = t('Unknown');
        }

        return str_replace('{user_name}', $userName, $message);
    }
}
