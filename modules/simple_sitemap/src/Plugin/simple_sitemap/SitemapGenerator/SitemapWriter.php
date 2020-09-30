<?php

namespace Drupal\simple_sitemap\Plugin\simple_sitemap\SitemapGenerator;

use Drupal\Core\Routing\RouteProvider;

/**
 * Class SitemapWriter
 * @package Drupal\simple_sitemap\Plugin\simple_sitemap\SitemapGenerator
 */
class SitemapWriter extends \XMLWriter {

  const GENERATED_BY = 'Generated by the Simple XML Sitemap Drupal module: https://drupal.org/project/simple_sitemap.';
  const XML_VERSION = '1.0';
  const ENCODING = 'UTF-8';

  /**
   * @var \Drupal\Core\Routing\RouteProvider
   */
  protected $routeProvider;

  public function __construct(RouteProvider $route_provider) {
    $this->routeProvider = $route_provider;
  }

  /**
   * Adds the XML stylesheet to the XML page.
   */
  public function writeXsl() {
    // Use this instead of URL::fromRoute() to avoid creating a URL with the
    // subdomain from which creation was triggered which might lead to a CORS
    // problem. See https://www.drupal.org/project/simple_sitemap/issues/3131672.
    $xsl_url = $this->routeProvider
      ->getRouteByName('simple_sitemap.sitemap_xsl')
      ->getPath();

    $this->writePI('xml-stylesheet', 'type="text/xsl" href="' . $xsl_url . '"');
  }

  public function writeGeneratedBy() {
    $this->writeComment(self::GENERATED_BY);
  }

  public function startSitemapDocument() {
    $this->startDocument(self::XML_VERSION, self::ENCODING);
  }

}