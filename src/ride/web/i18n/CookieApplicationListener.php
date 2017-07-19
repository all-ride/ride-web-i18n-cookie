<?php

namespace ride\web\i18n;

use ride\library\event\Event;
use ride\library\http\Cookie;
use ride\library\http\Header;
use ride\library\i18n\I18n;
use ride\library\mvc\view\HtmlView;

/**
 * Application listener to set a cookie for the locale
 */
class CookieApplicationListener {

    /**
     * Name of the cookie
     * @var string
     */
    private $cookieName;

    /**
     * Sets the name for the cookie
     * @param string $cookieName
     * @return null
     */
    public function setCookieName($cookieName) {
        $this->cookieName = $cookieName;
    }

    /**
     * Gets the name for the cookie
     * @return string
     */
    public function getCookieName() {
        return $this->cookieName ? $this->cookieName : '_locale';
    }

    /**
     * Sets a cookie for the current locale when offering a HTML view
     * @param \ride\library\event\Event $event
     * @param \ride\library\i18n\I18n $i18n
     * @return null
     */
    public function handleLocaleCookie(Event $event, I18n $i18n) {
        $web = $event->getArgument('web');
        $request = $web->getRequest();
        $response = $web->getResponse();

        $view = $response->getView();
        if (!($view instanceof HtmlView)) {
            return;
        }

        $cookieName = $this->getCookieName();
        $locale = $i18n->getLocale()->getCode();
        $domain = $request->getHeader(Header::HEADER_HOST);
        $expires = time() + (365 * 24 * 60 * 60);

        $cookie = new Cookie($cookieName, $locale, $expires, $domain, '/', $request->isSecure());

        $response->setCookie($cookie);
    }

}
