<?php
namespace Xenadu\MyMissions;

class WeeklyGoalsResponse
{
    /**
     * @var array
     */
    public $collection = [];

    public function __construct(GoalCollection $goalCollection)
    {
        $this->collection []= $goalCollection;
        // $this->collectionArray = ['collection' => $goalCollection];
    }

    public function add(GoalCollection $goalCollection)
    {
        $this->collection[]= $goalCollection;
        // $this->collectionArray = array_merge($this->collectionArray, ['collection' => $goalCollection]);
        // $this->collectionArray []= ['collection' => $goalCollection];
    }
}
