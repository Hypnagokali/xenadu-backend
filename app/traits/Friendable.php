<?php

namespace App\Traits;

use App\Friendship;
use Exception;

trait Friendable
{

    private function findAllPendingRequests()
    {
        $asRequester = Friendship::where(['requester_id' => $this->id, 'state' => Friendship::PENDING])->get();
        $asAddressee = Friendship::where(['addressee_id' => $this->id, 'state' => Friendship::PENDING])->get();

        return ['asAddressee' => $asAddressee, 'asRequester' => $asRequester];
    }

    public function findAllPendingRequestsFromMe()
    {
        $asRequester = Friendship::where(['requester_id' => $this->id, 'state' => Friendship::PENDING])->get();

        return $asRequester;
    }

    public function findAllPendingRequestsToMe()
    {
        $asAddressee = Friendship::where(['addressee_id' => $this->id, 'state' => Friendship::PENDING])->get();

        return $asAddressee;
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

    public function unfriend($userId)
    {
        $friendship = $this->findFriendship($userId);
        if ($friendship !== null) {
            $friendship->delete();
            return true;
        }
        return false;
    }

    public function deny($userId)
    {
        $requestedFriendship = Friendship::where([
            'addressee_id' => $this->id,
            'requester_id' => $userId,
            'state' => Friendship::PENDING
        ])->first();

        if ($requestedFriendship !== null) {
            $requestedFriendship->delete();
            return true;
        }
        return false;
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

    private function findFriendship($userId)
    {
        $requester = Friendship::where(['requester_id' => $userId, 'addressee_id' => $this->id])->first();
        $addressee = Friendship::where(['addressee_id' => $userId, 'requester_id' => $this->id])->first();
        if ($requester === null && $addressee == null) {
            return null;
        }
        return $requester ?? $addressee;
    }

    public function isRequestPendingFromMe()
    {
        $request = Friendship::where(['addressee_id' => $this->id, 'requester_id' => auth()->user()->id, 'state' => Friendship::PENDING])->first();
        if ($request === null) {
            return false;
        }
        return true;
    }

    public function hasRequestedMe()
    {
        $request = Friendship::where(['addressee_id' => auth()->user()->id, 'state' => Friendship::PENDING, 'requester_id' => $this->id])->first();
        if ($request === null) {
            return false;
        }
        return true;
    }

    public function isFriend($userId)
    {
        $friendship = $this->findFriendship($userId);
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
