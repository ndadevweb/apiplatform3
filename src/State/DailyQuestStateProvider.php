<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\DailyQuest;
use App\ApiResource\QuestTreasure;
use App\Enum\DailyQuestStatusEnum;
use App\Repository\DragonTreasureRepository;
use ArrayIterator;

class DailyQuestStateProvider implements ProviderInterface
{
    public function __construct(
        private DragonTreasureRepository $treasureRepository,
        private Pagination $pagination
    )
    {

    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        // Retrieve the state from somewhere
        // return [
        //     new DailyQuest(new \DateTime('now')),
        //     new DailyQuest(new \DateTime('yesterday'))
        // ];

        if($operation instanceof CollectionOperationInterface) {
            $currentPage = $this->pagination->getPage($context);
            $itemsPerPage = $this->pagination->getLimit($operation, $context);
            $offset = $this->pagination->getOffset($operation, $context);
            $totalItems = $this->countTotalQuests();
            $quests = $this->createQuests($offset, $itemsPerPage);

            return new TraversablePaginator(
                new ArrayIterator($quests),
                $currentPage,
                $itemsPerPage,
                $totalItems
            );
        }

        $quests = $this->createQuests(0, $this->countTotalQuests());

        return $quests[$uriVariables['dayString']] ?? null;
    }

    private function createQuests(int $offset, int $limit = 50): array
    {
        $treasures = $this->treasureRepository->findBy([], [], 10);


        $quests = [];
        for ($i = $offset; $i < ($offset + $limit); $i++) {
            $quest = new DailyQuest(new \DateTimeImmutable(sprintf('- %d days', $i)));
            $quest->questName = sprintf('Quest %d', $i);
            $quest->description = sprintf('Description %d', $i);
            $quest->difficultyLevel = $i % 10;
            $quest->status = $i % 2 === 0 ? DailyQuestStatusEnum::ACTIVE : DailyQuestStatusEnum::COMPLETED;
            $quest->lastUpdated = new \DateTimeImmutable(sprintf('-%d days', rand(10, 100)));
            $randomTreasures = $treasures[array_rand($treasures)];
            $quest->treasures = new QuestTreasure(
                $randomTreasures->getName(),
                $randomTreasures->getValue(),
                $randomTreasures->getCoolFactor()
            );

            $quests[$quest->getDayString()] = $quest;
        }
    
        return $quests;
    }

    private function countTotalQuests(): int
    {
        return 50;
    }
}
