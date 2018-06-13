<?php
	namespace App\Traits;

	use App\Friendship;
	use Auth;

	trait Friendable
	{
		public function addFriend($user_requested)
		{	
			if($this->id == $user_requested) {
				return 'are you so alone..';
			}

			if($this->hasPendingRequestTo($user_requested)) {
				return 'already sent a friend request';
			}

			if($this->isFriendWith($user_requested)) {
				return 'you dont need me anymore :( because you guys are already friends.';

			}

			if($this->hasPendingRequestFrom($user_requested)) {
				return "user has just sent you a request..please reload the page and try accepting the request";
			}

			$friendship = Friendship::create([
				'requester' => $this->id,
				'user_requested' => $user_requested

			]);

			if($friendship) {
				return true;
			}
			return 0;
		}

		public function removeFriend($user)
		{

			$friendship = Friendship::where(function ($query) {
					   	$query->where('requester', $this->id)
					          ->orWhere('user_requested', $this->id);
						})
						->Where(function($query) use($user) {
					    	$query->where('requester', $user)
					          ->orWhere('user_requested', $user);
						})
						->where('status', 1)
						->first();		//dont use get and try to delte the collection instead try delte directly without get().

			$friendship->delete();
			return 1;

		}

		public function acceptFriend($requester)
		{
			if(!$this->hasPendingRequestFrom($requester)) {
				return 0;
			}

			$friendship = Friendship::where('requester', $requester)->where('user_requested', $this->id)->first();

			if($friendship) {
				$friendship->update([
					'status' => 1
				]);

				return 1; // must use boolean type true check  in vuejs conditon even string sent from here dont know why so better use 1 and 0 nowon..or use response->json() to send boolean
			}

			return 0;
		}

		public function friends()
		{	
			/*$friendships = Friendship::where(function ($query) {  can also do this way by a little bit of complex query and if condition inside foreahcloop but i am gonna do next method..
					   	$query->where('requester', $this->id)
					          ->orWhere('user_requested', $this->id);
						})
						->Where(function($query) {
					    	$query->where('status', 1);
						})
						->get();
						$friendships = Friendship::where(function ($query) {
					   	$query->where('requester', $this->id)
					          ->orWhere('user_requested', $this->id);
						})
						->Where(function($query) {
					    	$query->where('status', 1);
						})
						->get();
			*/

			$friends1 = array();
			$friendships1 = Friendship::where('requester', $this->id)
							->where('status', 1)
							//->orderBy('created_at','desc')
							->get();

			foreach ($friendships1 as $friendship1) {
				array_push($friends1, \App\User::find($friendship1->user_requested));
			}

			$friends2 = array();

			$friendships2 = Friendship::Where('user_requested', $this->id)
			->where('status', 1)
			//->orderBy('created_at','desc')
			->get();

			foreach ($friendships2 as $friendship2) {
				array_push($friends2, \App\User::find($friendship2->requester));
			}

			return array_merge($friends1,$friends2);

			//return response()->json($friends);
		}


		public function pendingRequest()
		{
			$friendships = Friendship::where(function ($query) {
					   	$query->where('requester', $this->id)
					          ->orWhere('user_requested', $this->id);
						})
						->Where(function($query) {
					    	$query->where('status', 0);
						})
						->get();
			$users = array();
			foreach ($friendships as $friend) {
				if($friend->user_requested == $this->id) {
					array_push($users, \App\User::find($friend->requester));
				}
				else{
					//pending requet sent ....but i am going to make seperate functions for return issues .so clear this mess later..
				}
			}
			return $users;
		}

		public function removePendingRequest($user_id)
		{	
			$friendship = Friendship::where('requester', $user_id)->where('user_requested', $this->id)->where('status', 0)->first();
			$friendship->delete();//dont delete change status only aile lai garde 
			return 1;
		}

		public function pendingRequestIds()
		{
			return collect($this->pendingRequest())->pluck('id')->toArray();
		}

		public function friendsId()
		{
			return collect($this->friends())->pluck('id')->toArray();
		}

		public function isFriendWith($user_id)
		{
			if(in_array($user_id, $this->friendsId())) {
				return true;//jsut retunr 0 or 1 since response json doesnot make sense here it is not sending respose to vuejs
			}

			return false;
		}


		public function pendingRequestSent()
		{
			$friendships = Friendship::where('status', 0)
								->where('requester', $this->id)
								->get();


			$users = array();
			foreach ($friendships as $friendship) {
				
				array_push($users, \App\User::find($friendship->user_requested));
				
			}
			return $users;
		}

		public function removePendingRequestSent($user_id)
		{
			$friendship = Friendship::where('requester', $this->id)->where('user_requested', $user_id)->where('status', 0)->first();
			$friendship->delete();//dont delete change status only aile lai garde 
			return 1;
		}


		public function pendingRequestSentIds()
		{
			return collect($this->pendingRequestSent())->pluck('id')->toArray();
		}

		public function hasPendingRequestFrom($user_id)
		{
			if(in_array($user_id, $this->pendingRequestIds())) {

				return true;

			}
			return false;
		}

		public function hasPendingRequestTo($user_id)
		{
			if(in_array($user_id, $this->pendingRequestSentIds())) {

				return true;

			}
			return false;
		}

	}


?>