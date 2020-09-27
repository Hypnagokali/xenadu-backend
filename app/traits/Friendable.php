<?php

namespace App\Traits;

use App\Friendship;
use Exception;

trait Friendable
{

    private function findAllPendingRequests()
    {
        $asRequester = Friendship::where('requester_id', $this->id)->get();
        $asAddressee = Friendship::where('addressee_id', $this->id)->get();

        return ['asAddressee' => $asAddressee, 'asRequester' => $asRequester];
    }

    public function findAll()
    {
        $asRequester = Friendship::where(['requester_id' => $this->id, 'state' => Friendship::ACCEPTED])->get();
        $asAddressee = Friendship::where(['addressee_id'=> $this->id, 'state' => Friendship::ACCEPTED])->get();
        $friends = [];
        foreach ($asRequester as $friendship) {
            $friends []= self::find($friendship->addressee_id);
        }
        foreach ($asAddressee as $friendship) {
            $friends []= self::find($friendship->requester_id);
        }
        return $friends;
    }

    public function accept($userId)
    {
        $requestedFriendship = Friendship::where([
            'addressee_id' => $this->id,
            'requester_id' => $userId,
            'state' => Friendship::PENDING
        ])->first();

        if ($requestedFriendship !== null) {
            $requestedFriendship->state = Friendship::ACCEPTED;
            $requestedFriendship->save();
        }
        return $requestedFriendship;
    }

    /**
     * Adding a friend
     *
     * @param Integer
     *
     * @return Friendship
     */
    public function addFriend($addresseeId)
    {
        //is addresseeId a user
        $addressee = self::find($addresseeId);
        if ($addressee === null) {
            throw new Exception('No User with this ID');
        }
        $allPendingStates = $this->findAllPendingRequests();

        // durchsuche alle Anfragen an mich
        foreach ($allPendingStates['asAddressee'] as $pending) {
            if ($pending->requester_id == $addresseeId) {
                return $pending;
            }
        }
        // durchsuche alle Anfragen, die ich losgeschickt habe
        foreach ($allPendingStates['asRequester'] as $pending) {
            if ($pending->addressee_id == $addresseeId) {
                return $pending;
            }
        }

        $friendship = Friendship::create([
            'requester_id' => $this->id,
            'addressee_id' => $addresseeId,
            'state' => Friendship::PENDING
        ]);

        return $friendship;
    }

    private function findFriend($userId)
    {
        $requester = Friendship::where(['requester_id' => $userId, 'addressee_id' => $this->id])->first();
        $addressee = Friendship::where(['addressee_id' => $userId, 'requester_id' => $this->id])->first();
        if ($requester === null && $addressee == null) {
            return null;
        }
        return $requester ?? $addressee;
    }

    public function isFriend($userId)
    {
        $friendship = $this->findFriend($userId);
        if ($friendship !== null && $friendship->state === Friendship::ACCEPTED) {
            return true;
        }
        return false;
    }

    public function hello()
    {
        return "hello from trait user";
    }
}
