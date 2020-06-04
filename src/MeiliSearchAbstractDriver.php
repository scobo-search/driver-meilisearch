<?php
/*
 * This file is part of Scobo.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */
namespace Scobo\Drivers;

use MeiliSearch\Client;
use Scobo\Drivers\AbstractDriver;

class MeiliSearchAbstractDriver extends AbstractDriver {

    public $client;

    public function __construct(string $url, string $masterKey)
    {
        $this->client = new Client($url, $masterKey);
    }

    /**
     * Search functionality
     *
     * @param string $index
     * @param string $query
     * @param array $options
     * @return mixed
     */
    public function search(string $index, string $query, array $options)
    {
        $meiliIndex = $this->client->getIndex($index);
        $results = $meiliIndex->search($query);

        $searchObject = new SearchObject();
        $searchObject->data = $results["hits"];
        $searchObject->offset = $results["offset"];
        $searchObject->limit = $results["limit"];
        $searchObject->query = $results["query"];

        return $searchObject->outputArray();
    }

    /**
     * Add item to index
     *
     * @param string $index
     * @param array $item
     * @return string
     */
    public function addItem(string $index, array $item): string
    {
        $meiliIndex = $this->client->getIndex($index);

        if (!$meiliIndex) {
            $meiliIndex = $this->client->createIndex($index);
        }

        return $meiliIndex->addDocuments([$item]);
    }

    /**
     * Add items to index
     *
     * @param string $index
     * @param array $items
     * @return string
     */
    public function addItems(string $index, array $items) : string {
        $meiliIndex = $this->client->getIndex($index);

        if (!$meiliIndex) {
            $meiliIndex = $this->client->createIndex($index);
        }

        return $meiliIndex->addDocuments($items);
    }

    /**
     * Delete object from index
     *
     * @param string $objectId
     * @return mixed
     */
    public function delete(string $objectId)
    {
        // TODO: Implement delete() method.
    }

    public function filter(array $filter)
    {
        // TODO: Implement filter() method.
    }

    /**
     * Flush index of all objects
     *
     * @param string $index
     * @return mixed
     */
    public function flush(string $index)
    {
        $meiliIndex = $this->client->getIndex($index);
        return $meiliIndex->deleteAllDocuments();
    }
}