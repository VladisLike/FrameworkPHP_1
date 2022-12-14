<?php

namespace Core\Repository;

use Core\Common\Model;
use Core\Service\RepositoryService\ObjectManager;
use Core\Service\RepositoryService\ObjectManagerInterface;

abstract class AbstractRepository implements RepositoryInterface
{
    private ObjectManagerInterface $objectManager;

    public function __construct()
    {
        $this->objectManager = new ObjectManager($this);
    }

    public function find(int $id): ?Model
    {
        foreach ($this->objectManager->getData() as $item) {
            if ($item['id'] === $id) {
                return $this->objectManager->getObject($item);
            }
        }

        return null;
    }

    public function findAll(): array
    {
        $models = [];

        foreach ($this->objectManager->getData() as $item) {
            $models[] = $this->objectManager->getObject($item);
        }

        return $models;
    }

    public function findBy(array $criteria): array
    {
        $models = [];

        foreach ($this->objectManager->getData() as $item) {
            if ($this->determineItemCompareWith($item, $criteria)) {
                $models[] = $this->objectManager->getObject($item);
            }
        }

        return $models;
    }

    protected function determineItemCompareWith(array $item, array $criteria): bool
    {
        $isCompared = true;
        $diffArray = \array_diff($item, $criteria);

        foreach ($criteria as $key => $value) {
            if (\array_key_exists($key, $diffArray)) {
                $isCompared = false;
            }
        }

        return $isCompared;
    }

    public function getAllModelCount(): int
    {
        return count($this->findAll());
    }

    abstract function getModel(): string;
}