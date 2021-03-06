<?php
namespace Geohash;

class Geohash
{
    const ODD = 'odd';
    const EVEN = 'even';
    const DIRECTION_TOP = 'top';
    const DIRECTION_BOTTOM = 'bottom';
    const DIRECTION_LEFT = 'left';
    const DIRECTION_RIGHT = 'right';

    private static $table = "0123456789bcdefghjkmnpqrstuvwxyz";
    private static $bits = array(16, 8, 4, 2, 1);

    private static $neighbors = array(
        self::DIRECTION_RIGHT => array(
            self::EVEN => 'bc01fg45238967deuvhjyznpkmstqrwx',
            self::ODD => 'p0r21436x8zb9dcf5h7kjnmqesgutwvy',
        ),
        self::DIRECTION_LEFT => array(
            self::EVEN =>  '238967debc01fg45kmstqrwxuvhjyznp',
            self::ODD => '14365h7k9dcfesgujnmqp0r2twvyx8zb',
        ),
        self::DIRECTION_TOP => array(
            self::EVEN =>  'p0r21436x8zb9dcf5h7kjnmqesgutwvy',
            self::ODD => 'bc01fg45238967deuvhjyznpkmstqrwx',
        ),
        self::DIRECTION_BOTTOM => array(
            self::EVEN =>  '14365h7k9dcfesgujnmqp0r2twvyx8zb',
            self::ODD => '238967debc01fg45kmstqrwxuvhjyznp',
        ),
    );

    private static $borders = array(
        self::DIRECTION_RIGHT => array(
            self::EVEN => 'bcfguvyz',
            self::ODD => 'prxz',
        ),
        self::DIRECTION_LEFT => array(
            self::EVEN => '0145hjnp',
            self::ODD => '028b',
        ),
        self::DIRECTION_TOP => array(
            self::EVEN => 'prxz',
            self::ODD => 'bcfguvyz',
        ),
        self::DIRECTION_BOTTOM => array(
            self::EVEN => '028b',
            self::ODD => '0145hjnp',
        ),
    );

    public static function encode($lat, $lng, $prec = null)
    {
        $lap = strlen($lat) - strpos($lat, ".");
        $lop = strlen($lng) - strpos($lng, ".");
        $prec = $prec ?: pow(10, -max($lap-1, $lop-1, 0))/2;
        $minlng = -180;
        $maxlng = 180;
        $minlat = -90;
        $maxlat = 90;

        $hash = array();
        $error = 180;
        $isEven = true;
        $chr = 0;
        $b = 0;

        while ($error >= $prec) {
            if ($isEven) {
                $next = ($minlng + $maxlng) / 2;
                if ($lng > $next) {
                    $chr |= self::$bits[$b];
                    $minlng = $next;
                } else {
                    $maxlng = $next;
                }
            } else {
                $next = ($minlat + $maxlat) / 2;
                if ($lat > $next) {
                    $chr |= self::$bits[$b];
                    $minlat = $next;
                } else {
                    $maxlat = $next;
                }
            }
            $isEven = !$isEven;

            if ($b < 4) {
                $b++;
            } else {
                $hash[] = self::$table[$chr];
                $error = max($maxlng - $minlng, $maxlat - $minlat);
                $b = 0;
                $chr = 0;
            }
        }

        return join('', $hash);
    }

    public static function decode($hash)
    {
        $minlng = -180;
        $maxlng = 180;
        $minlat = -90;
        $maxlat = 90;
        $latE = 90;
        $lngE = 180;

        for ($i=0,$c=strlen($hash); $i<$c; $i++) {
            $v = strpos(self::$table, $hash[$i]);
            if (1&$i) {
                if (16&$v) {
                    $minlat = ($minlat + $maxlat) / 2;
                } else {
                    $maxlat = ($minlat + $maxlat) / 2;
                }
                if (8&$v) {
                    $minlng = ($minlng + $maxlng) / 2;
                } else {
                    $maxlng = ($minlng + $maxlng) / 2;
                }
                if (4&$v) {
                    $minlat = ($minlat + $maxlat) / 2;
                } else {
                    $maxlat = ($minlat + $maxlat) / 2;
                }
                if (2&$v) {
                    $minlng = ($minlng + $maxlng) / 2;
                } else {
                    $maxlng = ($minlng + $maxlng) / 2;
                }
                if (1&$v) {
                    $minlat = ($minlat + $maxlat) / 2;
                } else {
                    $maxlat = ($minlat + $maxlat) / 2;
                }
                $latE /= 8;
                $lngE /= 4;
            } else {
                if (16&$v) {
                    $minlng = ($minlng + $maxlng) / 2;
                } else {
                    $maxlng = ($minlng + $maxlng) / 2;
                }
                if (8&$v) {
                    $minlat = ($minlat + $maxlat) / 2;
                } else {
                    $maxlat = ($minlat + $maxlat) / 2;
                }
                if (4&$v) {
                    $minlng = ($minlng + $maxlng) / 2;
                } else {
                    $maxlng = ($minlng + $maxlng) / 2;
                }
                if (2&$v) {
                    $minlat = ($minlat + $maxlat) / 2;
                } else {
                    $maxlat = ($minlat + $maxlat) / 2;
                }
                if (1&$v) {
                    $minlng = ($minlng + $maxlng) / 2;
                } else {
                    $maxlng = ($minlng + $maxlng) / 2;
                }
                $latE /= 4;
                $lngE /= 8;
            }
        }
        $lat = round(($minlat + $maxlat) / 2, max(1, -round(log10($latE))) - 1);
        $lng = round(($minlng + $maxlng) / 2, max(1, -round(log10($lngE))) - 1);

        return array($lat, $lng);
    }

    /**
     * Based on David Troy implementation
     *
     * @link https://github.com/davetroy/geohash-js Original implementation in javascript
     */
    public static function calculateAdjacent($hash, $direction)
    {
        $hash = strtolower($hash);
        $lastChar = substr($hash, -1);
        $type = strlen($hash) % 2 ? self::ODD : self::EVEN;
        $base = substr($hash, 0, -1);

        if (!empty($base) && strpos(self::$borders[$direction][$type], $lastChar) !== false) {
            $base = self::calculateAdjacent($base, $direction);
        }

        return $base . self::$table[strpos(self::$neighbors[$direction][$type], $lastChar)];
    }

    public static function getNeighbors($hash, $layer = 1)
    {
        $neighbors = [];

        $currentHash = $hash;
        // Go Up
        for ($i = 0; $i < $layer; $i++) {
            $currentHash = self::calculateAdjacent($currentHash, self::DIRECTION_TOP);
        }
        $neighbors[] = $currentHash;

        // Go Right
        for ($i = 0; $i < $layer; $i++) {
            $currentHash = self::calculateAdjacent($currentHash, self::DIRECTION_RIGHT);
            $neighbors[] = $currentHash;
        }

        // Go Down
        for ($i = 0; $i < $layer * 2; $i++) {
            $currentHash = self::calculateAdjacent($currentHash, self::DIRECTION_BOTTOM);
            $neighbors[] = $currentHash;
        }

        // Go Left
        for ($i = 0; $i < $layer * 2; $i++) {
            $currentHash = self::calculateAdjacent($currentHash, self::DIRECTION_LEFT);
            $neighbors[] = $currentHash;
        }

        // Go Up Again
        for ($i = 0; $i < $layer * 2; $i++) {
            $currentHash = self::calculateAdjacent($currentHash, self::DIRECTION_TOP);
            $neighbors[] = $currentHash;
        }

        // Go Right Again
        for ($i = 0; $i < $layer - 1; $i++) {
            $currentHash = self::calculateAdjacent($currentHash, self::DIRECTION_RIGHT);
            $neighbors[] = $currentHash;
        }

        return $neighbors;
    }
}
