<?php
namespace Geohash\Tests;

use Geohash\Geohash;

class GeohashTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provider
     */
    public function testEncode($lat, $lng, $geohash)
    {
        $this->assertEquals($geohash, Geohash::encode($lat, $lng));
    }

    /**
     * @dataProvider provider
     */
    public function testDecode($lat, $lng, $geohash)
    {
        $this->assertEquals(array($lat, $lng), Geohash::decode($geohash));
    }

    /**
     * All data is come from http://geohash.org
     */
    public function provider()
    {
        return array(
            array(31.283131, 121.500831, 'wtw3uyfjqw61'),
            array(31.28, 121.500831, 'wtw3uy65nwdh'),
            array(31.283131, 121.500, 'wtw3uyct7nq3'),
        );
    }
}
