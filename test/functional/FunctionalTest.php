<?php

use \RemoteWebDriver;
use \WebDriverCapabilityType;
use \WebDriverBy;

class FunctionalTest extends \PHPUnit_Framework_TestCase
{
    protected $url;
    protected $webDriver;

    public function setUp()
    {
        $this->url = TEST_WEB_ROOT;
        $capabilities = array(WebDriverCapabilityType::BROWSER_NAME => 'firefox');
        $this->webDriver = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);
    }

    public function tearDown()
    {
        $this->webDriver->close();
    }

    public function testUploadHistoryAndGetSomeResults()
    {
        $this->webDriver->get($this->url);

        $this->assertContains('Shazam to Spotify', $this->webDriver->getTitle());

        $element = $this->webDriver->findElement(WebDriverBy::id("status_text"));
        $this->assertEquals('Upload your file.', $element->getText());

        $element = $this->webDriver->findElement(WebDriverBy::id("file_input"));
        $element->sendKeys(array(__DIR__ . '/fixtures/myshazam-history_small.html'));

        $this->webDriver->wait(30, 500)->until(function ($driver) {
            $element = $driver->findElement(WebDriverBy::id("status_text"));
            if($element->getText() == 'Finished.') {

                $element = $driver->findElement(WebDriverBy::id("copy"));
                $text = $element->getText();
                $this->assertTrue(!is_null($text));
                $this->assertContains('spotify:', $text);

                $element = $driver->findElement(WebDriverBy::cssSelector("table > tbody"));
                $this->assertContains('Tracy Johnson', $element->getText());

                return true;
            }
            return false;
        });

    }

}
