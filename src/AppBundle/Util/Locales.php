<?php

namespace AppBundle\Util;

use Symfony\Component\Intl\Intl;
use Symfony\Component\HttpFoundation\Session\Session;

class Locales
{
    /**
     * @var string
     */
    private $locale_active;

    /**
     * @var array
     */
    private $locales;

    /**
     * @var Session
     */
    private $session;

    public function __construct($locale_active, $locales, Session $session)
    {
        $this->locale_active = $locale_active;
        $this->locales = $locales;
        $this->session = $session;
    }

    /**
     * @return array
     */
    public function getLocales()
    {
        $localeCodes = explode('|', $this->locales);
        $locales = array();

        foreach ($localeCodes as $localeCode) {
            $locales[] = [
                'code' => $localeCode,
                'name' => ucfirst(
                    Intl::getLocaleBundle()->getLocaleName($localeCode, $this->session->get('_locale'))
                ),
                'active' => ($this->locale_active == $localeCode ? true : false),
            ];
        }

        return $locales;
    }

    /**
     * @return string
     */
    public function getLocaleActive()
    {
        return $this->locale_active;
    }
}