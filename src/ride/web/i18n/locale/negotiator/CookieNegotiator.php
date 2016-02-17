<?php

namespace ride\web\i18n\locale\negotiator;

use ride\library\i18n\locale\LocaleManager;

/**
 * Negotiator that determines which locale should be used based a cookie
 */
class CookieNegotiator extends AbstractLoggedNegotiator {

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
     * Determines which locale to use, based on a cookie
     * @param \ride\library\i18n\locale\LocaleManager $manager The locale manager
     * @return null| \ride\library\i18n\locale\Locale the locale
     */
    public function getLocale(LocaleManager $manager) {
        $request = $this->getRequest();
        if (!$request) {
            if ($this->log) {
                $this->log->logDebug('Can\'t determine locale from cookie because there is no request', null, self::LOG_SOURCE);
            }

            return null;
        } elseif ($request->getBasePath() != '/') {
            if ($this->log) {
                $this->log->logDebug('Can\'t determine locale from cookie because we are not on the site root', null, self::LOG_SOURCE);
            }

            return null;
        }

        $locale = $request->getCookie($this->getCookieName());
        if (!$locale) {
            if ($this->log) {
                $this->log->logDebug('Can\'t determine locale from cookie because there is none', null, self::LOG_SOURCE);
            }

            return null;
        }

        if ($manager->hasLocale($locale)) {
            if ($this->log) {
                $this->log->logDebug('Loaded locale from cookie', $locale, self::LOG_SOURCE);
            }

            return $manager->getLocale($locale);
        }

        if ($this->log) {
            $this->log->logDebug('No available locale in cookie', $locale, self::LOG_SOURCE);
        }

        return null;
    }

}
