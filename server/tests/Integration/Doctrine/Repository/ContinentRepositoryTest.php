<?php

declare(strict_types=1);

namespace App\Tests\Integration\Doctrine\Repository;

use App\Adventure\Entity\Continent;
use App\Adventure\Entity\World;
use App\Adventure\Repository\ContinentRepository;
use App\Adventure\Repository\WorldRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ContinentRepositoryTest extends KernelTestCase
{
    /**
     * @test
     */
    public function getContinentsByWorldShouldReturnFiveContinents(): void
    {
        self::bootKernel();

        /** @var WorldRepository<World> $worldRepository */
        $worldRepository = self::getContainer()->get(WorldRepository::class);

        /** @var World $world */
        $world = $worldRepository->findOneBy(['name' => 'Monde']);

        /** @var ContinentRepository<Continent> $continentRepository */
        $continentRepository = self::getContainer()->get(ContinentRepository::class);

        $continent = $continentRepository->getContinentsByWorld($world);

        self::assertCount(5, $continent);
    }
}