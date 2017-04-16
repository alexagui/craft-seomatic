<?php
/**
 * SEOmatic plugin for Craft CMS 3.x
 *
 * A turnkey SEO implementation for Craft CMS that is comprehensive, powerful,
 * and flexible
 *
 * @link      https://nystudio107.com
 * @copyright Copyright (c) 2017 nystudio107
 */

namespace nystudio107\seomatic;

use nystudio107\seomatic\services\Meta as MetaService;
use nystudio107\seomatic\services\Sitemap as SitemapService;
use nystudio107\seomatic\twigextensions\JsonLdTwigExtension;
use nystudio107\seomatic\variables\SeomaticVariable;

use Craft;
use craft\base\Plugin;

/**
 * Class Seomatic
 *
 * @author    nystudio107
 * @package   Seomatic
 * @since     3.0.0
 *
 * @property  MetaService      meta
 * @property  SitemapService   sitemap
 */
class Seomatic extends Plugin
{
    /**
     * @var Seomatic
     */
    public static $plugin;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;
        $this->name = $this->getName();

        // Add in our Twig extensions
        Craft::$app->view->twig->addExtension(new JsonLdTwigExtension());

        $request = Craft::$app->getRequest();
        // Only respond to non-console site requests
        if ($request->getIsSiteRequest() && !$request->getIsConsoleRequest()) {
            // Load the meta containers for this page
            Seomatic::$plugin->meta->loadMetaContainers();
            // Load the sitemap containers
            Seomatic::$plugin->sitemap->loadSitemapContainers();
        }

        // We're loaded
        Craft::info(
            Craft::t(
                'seomatic',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    /**
     * @inheritdoc
     */
    public function defineTemplateComponent()
    {
        return SeomaticVariable::class;
    }

    /**
     * Returns the user-facing name of the plugin, which can override the name in
     * composer.json
     *
     * @return mixed
     */
    public function getName(): string
    {
         return Craft::t('seomatic', 'SEOmatic');
    }
}

/*
        $someSchema = JsonLd::create("Article");
        $someSchema->name = "Andrew";
        $someSchema->url = "https://nystudio107.com";
        $someSchema->description = "This is some description thing";

        $someOtherSchema = JsonLd::create("Person", [
            "name" => "Polly",
            "description" => "wife",
            "url" => "https://nystudio107.com",
            ]);

        $someMoreSchema = JsonLd::create("Person");
        $someMoreSchema->attributes = [
            "name" => "Kumba",
            "description" => "dog",
            "url" => "http://woof.com",
            ];

        $someSchema->author = [$someOtherSchema, $someOtherSchema];
        $someSchema->publisher = $someMoreSchema;
        $someJson = $someSchema->render();
        if ($someSchema->validate())
        {
        }
        else
        {
        //    Craft::dd($someSchema->errors);
        }
        $stuff = (string)$someSchema;
        Craft::dump($stuff);
        Craft::dump($someSchema->getSchemaTypeDescription());
        Craft::dd($someJson);
        */
