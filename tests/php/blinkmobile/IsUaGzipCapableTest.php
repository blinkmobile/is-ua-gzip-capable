<?php

namespace blinkmobile;

class IsUaGzipCapableTest extends \PHPUnit_Framework_TestCase
{

    public function testClassExists()
    {
        $this->assertTrue(class_exists('\blinkmobile\IsUaGzipCapable'));
    }

    public function testTestMethodExists()
    {
        $this->assertTrue(method_exists('\blinkmobile\IsUaGzipCapable', 'testRequest'));
    }

    public function testEmptyAcceptEncodingDoesNotGzip()
    {
        $this->assertFalse(IsUaGzipCapable::testRequest(array(
            'HTTP_ACCEPT_ENCODING' => '',
            'HTTP_USER_AGENT' => ''
        )));
    }

    /**
     * http://httpd.apache.org/docs/2.0/en/mod/mod_deflate.html
     */
    public function testNetscape4OnlyGzipsTextHtml()
    {
        // TODO: make the tests sensitive to response MIME type somehow
    }

    /**
     * http://httpd.apache.org/docs/2.0/en/mod/mod_deflate.html
     */
    public function testNetscape406To408DoesNotGzip()
    {
        $this->assertFalse(IsUaGzipCapable::testRequest(array(
            'HTTP_ACCEPT_ENCODING' => 'gzip',
            'HTTP_USER_AGENT' => 'Mozilla/4.06 [de] (WinNT; I)'
        )));
        $this->assertFalse(IsUaGzipCapable::testRequest(array(
            'HTTP_ACCEPT_ENCODING' => 'gzip',
            'HTTP_USER_AGENT' => 'Mozilla/4.07 [de] (WinNT; I)'
        )));
        $this->assertFalse(IsUaGzipCapable::testRequest(array(
            'HTTP_ACCEPT_ENCODING' => 'gzip',
            'HTTP_USER_AGENT' => 'Mozilla/4.08 [de] (WinNT; I)'
        )));
    }

    /**
     * http://httpd.apache.org/docs/2.0/en/mod/mod_deflate.html
     */
    public function testInternetExplorerDoesGzip()
    {
        $this->assertTrue(IsUaGzipCapable::testRequest(array(
            'HTTP_ACCEPT_ENCODING' => 'gzip',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows; U; MSIE 9.0; WIndows NT 9.0; en-US))'
        )));
    }

    /**
     * https://connect.microsoft.com/IE/feedbackdetail/view/950689
     */
    public function testInternetExplorer11DoesNotGzip()
    {
        $this->assertFalse(IsUaGzipCapable::testRequest(array(
            'HTTP_ACCEPT_ENCODING' => 'gzip',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko'
        )));
    }
}
