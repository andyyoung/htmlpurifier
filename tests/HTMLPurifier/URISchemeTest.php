<?php

require_once 'HTMLPurifier/URIScheme.php';

require_once 'HTMLPurifier/URIScheme/http.php';
require_once 'HTMLPurifier/URIScheme/ftp.php';
require_once 'HTMLPurifier/URIScheme/https.php';
//require_once 'HTMLPurifier/URIScheme/mailto.php';
require_once 'HTMLPurifier/URIScheme/news.php';
require_once 'HTMLPurifier/URIScheme/nntp.php';

class HTMLPurifier_URISchemeTest extends UnitTestCase
{
    
    function test_http() {
        $scheme = new HTMLPurifier_URIScheme_http();
        $config = HTMLPurifier_Config::createDefault();
        
        $this->assertIdentical(
          $scheme->validateComponents(
                null, 'www.example.com', null, '/', 's=foobar', $config),
          array(null, 'www.example.com', null, '/', 's=foobar')
        );
        
        // absorb default port and userinfo
        $this->assertIdentical(
          $scheme->validateComponents(
                'user', 'www.example.com', 80, '/', 's=foobar', $config),
          array(null, 'www.example.com', null, '/', 's=foobar')
        );
        
        // do not absorb non-default port
        $this->assertIdentical(
          $scheme->validateComponents(
                null, 'www.example.com', 8080, '/', 's=foobar', $config),
          array(null, 'www.example.com', 8080, '/', 's=foobar')
        );
        
        // https is basically the same
        
        $scheme = new HTMLPurifier_URIScheme_https();
        $this->assertIdentical(
          $scheme->validateComponents(
                'user', 'www.example.com', 443, '/', 's=foobar', $config),
          array(null, 'www.example.com', null, '/', 's=foobar')
        );
        
    }
    
    function test_ftp() {
        
        $scheme = new HTMLPurifier_URIScheme_ftp();
        $config = HTMLPurifier_Config::createDefault();
        $this->assertIdentical(
          $scheme->validateComponents(
                'user', 'www.example.com', 21, '/', 's=foobar', $config),
          array('user', 'www.example.com', null, '/', null)
        );
        
    }
    
    function test_news() {
        
        $scheme = new HTMLPurifier_URIScheme_news();
        $config = HTMLPurifier_Config::createDefault();
        
        $this->assertIdentical(
          $scheme->validateComponents(
                null, null, null, 'gmane.science.linguistics', null, $config),
          array(null, null, null, 'gmane.science.linguistics', null)
        );
        
        $this->assertIdentical(
          $scheme->validateComponents(
                null, null, null, '642@eagle.ATT.COM', null, $config),
          array(null, null, null, '642@eagle.ATT.COM', null)
        );
        
        // test invalid field removal
        $this->assertIdentical(
          $scheme->validateComponents(
                'user', 'www.google.com', 80, 'rec.music', 'path=foo', $config),
          array(null, null, null, 'rec.music', null)
        );
        
    }
    
    function test_nntp() {
        
        $scheme = new HTMLPurifier_URIScheme_nntp();
        $config = HTMLPurifier_Config::createDefault();
        
        $this->assertIdentical(
          $scheme->validateComponents(
                null, 'news.example.com', null, '/alt.misc/12345', null, $config),
          array(null, 'news.example.com', null, '/alt.misc/12345', null)
        );
        
        
        $this->assertIdentical(
          $scheme->validateComponents(
                'user', 'news.example.com', 119, '/alt.misc/12345', 'foo=asdf', $config),
          array(null, 'news.example.com', null,  '/alt.misc/12345', null)
        );
    }
    
    // mailto currently isn't implemented yet
    function non_test_mailto() {
        
        $scheme = new HTMLPurifier_URIScheme_mailto();
        $config = HTMLPurifier_Config::createDefault();
        
        $this->assertIdentical(
          $scheme->validateComponents(
                null, null, null, 'bob@example.com', null, $config),
          array(null, null, null, 'bob@example.com', null)
        );
        
    }
    
}

?>