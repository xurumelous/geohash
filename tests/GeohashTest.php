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
     * @dataProvider adjacentProvider
     */
    public function testCalculateAdjacent($hash, $direction, $adjacentHash)
    {
        $this->assertEquals($adjacentHash, Geohash::calculateAdjacent($hash, $direction));
    }

    /**
     * @dataProvider neighborProvider
     */
    public function testGetNeighbors($hash, $layer, $neighbors)
    {
        $this->assertEquals($neighbors, Geohash::getNeighbors($hash, $layer));
    }

    /**
     * All data come from http://geohash.org
     */
    public function provider()
    {
        return array(
            array(31.283131, 121.500831, 'wtw3uyfjqw61'),
            array(31.28, 121.500831, 'wtw3uy65nwdh'),
            array(31.283131, 121.500, 'wtw3uyct7nq3'),
        );
    }

    public function adjacentProvider()
    {
        return array(
            array('r', Geohash::DIRECTION_TOP, 'x'),
            array('r', Geohash::DIRECTION_BOTTOM, 'p'),
            array('r', Geohash::DIRECTION_LEFT, 'q'),
            array('r', Geohash::DIRECTION_RIGHT, '2'),
            array('r3', Geohash::DIRECTION_TOP, 'r6'),
            array('r3', Geohash::DIRECTION_BOTTOM, 'r2'),
            array('r3', Geohash::DIRECTION_LEFT, 'r1'),
            array('r3', Geohash::DIRECTION_RIGHT, 'r9'),
            array('r3g', Geohash::DIRECTION_TOP, 'r65'),
            array('r3g', Geohash::DIRECTION_BOTTOM, 'r3e'),
            array('r3g', Geohash::DIRECTION_LEFT, 'r3f'),
            array('r3g', Geohash::DIRECTION_RIGHT, 'r3u'),
            array('r3gx', Geohash::DIRECTION_TOP, 'r658'),
            array('r3gx', Geohash::DIRECTION_BOTTOM, 'r3gw'),
            array('r3gx', Geohash::DIRECTION_LEFT, 'r3gr'),
            array('r3gx', Geohash::DIRECTION_RIGHT, 'r3gz'),
            array('r3gx0', Geohash::DIRECTION_TOP, 'r3gx2'),
            array('r3gx0', Geohash::DIRECTION_BOTTOM, 'r3gwb'),
            array('r3gx0', Geohash::DIRECTION_LEFT, 'r3grp'),
            array('r3gx0', Geohash::DIRECTION_RIGHT, 'r3gx1'),
            array('r3gx0w', Geohash::DIRECTION_TOP, 'r3gx0x'),
            array('r3gx0w', Geohash::DIRECTION_BOTTOM, 'r3gx0t'),
            array('r3gx0w', Geohash::DIRECTION_LEFT, 'r3gx0q'),
            array('r3gx0w', Geohash::DIRECTION_RIGHT, 'r3gx0y'),

            array('zzpgxc', Geohash::DIRECTION_BOTTOM, 'zzpgxb'),
            array('zzpgxc', Geohash::DIRECTION_LEFT, 'zzpgx9'),
            array('zzpgxc', Geohash::DIRECTION_RIGHT, 'bp0581'),

            array('bp0581', Geohash::DIRECTION_LEFT, 'zzpgxc'),
        );
    }

    public function neighborProvider()
    {
        return array(
            array('r3gx0', 1, array(
                    'r3gx2', // Top
                    'r3gx3', // Top Right
                    'r3gx1', // Right
                    'r3gwc', // Bottom Right
                    'r3gwb', // Bottom
                    'r3gqz', // Bottom Left
                    'r3grp', // Left
                    'r3grr', // Top Left
                ),
            ),
            array('r3gx0', 2, array(
                    'r3gx8',
                    'r3gx9',
                    'r3gxd',
                    'r3gx6',
                    'r3gx4',
                    'r3gwf',
                    'r3gwd',
                    'r3gw9',
                    'r3gw8',
                    'r3gqx',
                    'r3gqw',
                    'r3gqy',
                    'r3grn',
                    'r3grq',
                    'r3grw',
                    'r3grx',
                ),
            ),
        );
    }
}
