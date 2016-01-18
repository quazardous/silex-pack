<?php

namespace Acme\DemoPack\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\DemoPack\Entity\Item;

class AcmeDemoFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $items = [
            'one', 'two', 'three'
        ];
        foreach ($items as $i => $v) {
            $item = new Item();
            $item->setId($i + 1);
            $manager->persist($item);
            $item->setValue($v);
            echo "$v\n";
        }
        $manager->flush();
    }

}