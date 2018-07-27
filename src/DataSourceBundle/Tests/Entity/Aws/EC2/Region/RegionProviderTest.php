<?php

namespace DataSourceBundle\Tests\Entity\Aws\EC2\Region;

use DataSourceBundle\Entity\Aws\EC2\Region\RegionProvider;
use PHPUnit\Framework\TestCase;

class RegionProviderTest extends TestCase
{
    /**
     * @var RegionProvider
     */
    protected $regionProvider;

    public function setUp()
    {
        $this->regionProvider = new RegionProvider();
    }

    /**
     * @test
     * @dataProvider getRegions
     */
    public function allRegionsImplementsRegionInterface($region)
    {
        $this->assertInstanceOf(
            'DataSourceBundle\Entity\Aws\Region\RegionInterface',
            $this->regionProvider->factory($region)
        );
    }

    public function getRegions()
    {
        return array_map(
            function ($region) {
                return [$region];
            },
            array_keys(RegionProvider::REGIONS)
        );
    }

    /**
     * @test
     * @dataProvider getInvalidRegions
     * @expectedException \InvalidArgumentException
     */
    public function tryRetrieveAnInvalidRegion($region)
    {
        $this->regionProvider->factory($region);
    }

    public function getInvalidRegions()
    {
        return [
            [""],
            ["Vila Nova"]
        ];
    }

    /**
     * @test
     */
    public function retrieveValidRegions()
    {
        $this->assertEquals(
            array_keys(RegionProvider::REGIONS),
            $this->regionProvider->listAvailableRegions()
        );
    }

    /**
     * @test
     */
    public function retrieveValidRegionsFullData()
    {
        $data = [];
        foreach (RegionProvider::REGIONS as $code => $class) {
            $region = $this->regionProvider->factory($code);
            $data[] = [
                "name"  => $region->getName(),
                "code"    => $region->getCode()
            ];
        }

        $this->assertEquals($data, $this->regionProvider->listAvailableRegions(true));
    }

}