<?php declare(strict_types=1);

namespace App\Domain\Administration\Action\RegisterChampion;

use App\Domain\Administration\Entity\Champion;
use App\Domain\Administration\Exception\ChampionIdentityAlreadyUsed;
use App\Domain\Administration\Repository\ChampionRepository;

final class RegisterChampionCommandHandler
{
    private $championRepository;

    public function __construct(ChampionRepository $championRepository)
    {
        $this->championRepository = $championRepository;
    }

    /**
     * @throws ChampionIdentityAlreadyUsed
     */
    public function __invoke(RegisterChampionCommand $command): void
    {
        if ($this->championRepository->isIdAlreadyUsed($command->championId())) {
            throw new ChampionIdentityAlreadyUsed();
        }

        $champion = Champion::register(
            $command->championId(),
            $command->versionNumber(),
            $command->championName(),
            $command->championImageUrl()
        );

        $this->championRepository->add($champion);
    }
}
