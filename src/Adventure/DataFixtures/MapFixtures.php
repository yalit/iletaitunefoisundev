<?php

declare(strict_types=1);

namespace App\Adventure\DataFixtures;

use App\Adventure\Entity\Level;
use App\Adventure\Entity\Map;
use App\Core\Uid\UlidGeneratorInterface;
use App\Node\DataFixtures\CourseFixtures;
use App\Node\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class MapFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UlidGeneratorInterface $ulidGenerator)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var array<int, Course> $courses */
        $courses = $manager->getRepository(Course::class)->findAll();

        $previousMap = null;

        for ($i = 1; $i <= 5; ++$i) {
            $map = $this->createMap($i, $previousMap);

            $manager->persist($map);

            $mapCourses = array_slice($courses, ($i - 1) * 10, 10);

            $previousLevel = null;

            foreach ($mapCourses as $order => $course) {
                $level = $this->createLevel($order + 1, $course, $map, $previousLevel);

                if (0 === $order) {
                    $map->setStart($level);
                }

                $manager->persist($level);

                $previousLevel = $level;
            }

            $previousMap = $map;
        }

        $manager->flush();
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [CourseFixtures::class];
    }

    private function createMap(int $index, ?Map $previous): Map
    {
        $map = new Map();
        $map->setId($this->ulidGenerator->generate());
        $map->setPrevious($previous);
        $map->setName(sprintf('Map %d', $index));

        return $map;
    }

    private function createLevel(int $order, Course $course, Map $map, ?Level $previous): Level
    {
        $level = new Level();
        $level->setId($this->ulidGenerator->generate());
        $level->setPrevious($previous);
        $level->setMap($map);
        $level->setCourse($course);
        $level->setOrder($order);

        return $level;
    }
}