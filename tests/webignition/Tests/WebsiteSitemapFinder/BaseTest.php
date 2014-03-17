<?php

namespace webignition\Tests\WebsiteSitemapFinder;

use Guzzle\Http\Client as HttpClient;
use webignition\WebsiteSitemapFinder\WebsiteSitemapFinder;

abstract class BaseTest extends \PHPUnit_Framework_TestCase {
        
    /**
     *
     * @var \Guzzle\Http\Client
     */
    private $httpClient = null;   
    
    
    /**
     *
     * @var WebsiteSitemapFinder
     */
    private $sitemapFinder = null;
    
    
    /**
     * 
     * @return \Guzzle\Http\Client
     */
    protected function getHttpClient() {
        if (is_null($this->httpClient)) {
            $this->httpClient = new HttpClient();
        }
        
        return $this->httpClient;
    }  
    
    /**
     * 
     * @return WebsiteSitemapFinder
     */
    protected function getSitemapFinder() {
        if (is_null($this->sitemapFinder)) {
            $this->sitemapFinder = new WebsiteSitemapFinder();
            $this->sitemapFinder->getConfiguration()->setBaseRequest($this->getHttpClient()->get());
        }
        
        return $this->sitemapFinder;
    }
    
    protected function setHttpFixtures($fixtures) {
        $plugin = new \Guzzle\Plugin\Mock\MockPlugin();
        
        foreach ($fixtures as $fixture) {
            $plugin->addResponse($fixture);
        }
         
        $this->getHttpClient()->addSubscriber($plugin);              
    }
    
    
    protected function getHttpFixtures($path) {
        $fixtures = array();        
        $fixturesDirectory = new \DirectoryIterator($path);
        
        $fixturePathnames = array();
        
        foreach ($fixturesDirectory as $directoryItem) {
            if ($directoryItem->isFile()) { 
                $fixturePathnames[] = $directoryItem->getPathname();
            }
        }
        
        sort($fixturePathnames);
        
        foreach ($fixturePathnames as $fixturePathname) {
                $fixtures[] = \Guzzle\Http\Message\Response::fromMessage(file_get_contents($fixturePathname));            
        }
        
        return $fixtures;
    } 
    

    /**
     *
     * @param string $testName
     * @return string
     */
    protected function getFixturesDataPath($className, $testName) {        
        return __DIR__ . '/../../../fixtures/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '/' . $testName;
    }    
    
    
}