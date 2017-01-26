<?php
namespace Grav\Plugin;

use Grav\Common\Language\LanguageCodes;
use Grav\Common\Page\Page;
use Grav\Common\Plugin;
use Grav\Common\Uri;

class LangHelperPlugin extends Plugin
{
    /** @var Uri $uri */
    protected $uri;


    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize configuration
     */
    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            $this->active = false;
            return;
        }

        $this->uri = $this->grav['uri'];

        $this->enable([
            'onTwigInitialized'   => ['onTwigInitialized', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0]
        ]);
    }

    /** Add the native_name function */
    public function onTwigInitialized()
    {
        $this->grav['twig']->twig()->addFunction(
            new \Twig_SimpleFunction('langhelper_current', function() {
                return $this->getCurrentNativeLang();
            })
        );
        $this->grav['twig']->twig()->addFunction(
            new \Twig_SimpleFunction('langhelper_native_name', function($key) {
                return LanguageCodes::getNativeName($key);
            })
        );
        $this->grav['twig']->twig()->addFunction(
            new \Twig_SimpleFunction('langhelper_pageurl', function(Page $page, $code) {
                return $this->getPageUrl($page, $code);
            })
        );
        $this->grav['twig']->twig()->addFunction(
            new \Twig_SimpleFunction('langhelper_flagimg', function($code) {
                return $this->getFlagImg($code);
            })
        );
    }

    private function getCurrentNativeLang()
    {
        $current = $this->grav['language']->getLanguage();
        return LanguageCodes::getNativeName($current);
    }


    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }


    private function getFlagImg($code = 'en')
    {
        return $this->uri->base().'/user/plugins/langhelper/assets/flag-'. $code .'.png';
    }


    private function getPageUrl(Page $page, $code = 'en')
    {
        $urls = $this->getTranslatedPageUrls($page);

        if (isset($urls[$code]))
            return $urls[$code];

        return '';
    }


    private function getTranslatedPageUrls(Page $page)
    {
        $translated = $page->translatedLanguages();

        $rootUrl = $this->uri->base();

        $urls = array();
        foreach ($translated as $lang => $slug) {
            $urls[$lang] = array(
                'root' => $rootUrl.'/'.$lang,
                'params' => array()
            );
        }

        $isHome = false;
        while ($isHome == false) {
            $translations = $page->translatedLanguages();

            foreach ($translations as $lang => $slug) {
                if (isset($urls[$lang]) && !$page->home()) {
                    array_unshift($urls[$lang]['params'], $slug);
                }
            }
            $page = $page->parent();
            if ($page instanceof Page) {
                $isHome = $page->home();
            } else {
                $isHome = true;
            }
        }

        foreach ($urls as $language => $u) {
            $url = $u['root'];
            $params = join('/', $u['params']);
            if (strlen($params)) {
                $url .= '/'.$params;
            }

            $urls[$language] = $url;
        }

        return $urls;
    }

    /**
     * Set needed variables to display Langshelper.
     */
    public function onTwigSiteVariables()
    {
        $data = new \stdClass;
        $data->page_route = $this->grav['page']->rawRoute();
        /** @var Page $page */
        $page = $this->grav['page'];
        if ($page->home()) {
            $data->page_route = '';
        }
        $data->current = $this->grav['language']->getLanguage();
        $data->languages = $this->grav['language']->getLanguages();
        $this->grav['twig']->twig_vars['langhelper'] = $data;
    }



}
