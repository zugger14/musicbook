import Vuex from 'vuex'

import Vue from 'vue'

Vue.use(Vuex);

export default new Vuex.Store({// export const store = what does that mean

	state: { 

		//for private messages
		notifications: [],
		messagesR: [],
		messagesS: [],
		message: {
			subject: '',
			message: '',
			sender: '',
			receiver: ''
		},
		usersList: [],


		//for friend requests
		friendsPending:[],
		friendsSent:[],

	},

	getters: {

		getMessagesR(state) {
			return state.messagesR;
		},

		getMessagesS(state) {
			return state.messagesS;
		},

		getMessageView(state) {
			return state.message
		},

		getUsersList(state) {
			return state.usersList;
		},

		getNotification(state) {
			return state.notifications;
		},




		getFriendsPending(state) {
			return state.friendsPending;
		},

		getFriendsSent(state) {
			return state.friendsSent;
		}


	},

	mutations: {//sync
		setMessageR(state, messages) {
			state.messagesR = messages;

		},

		setMessageS(state, messages) {
			state.messagesS = messages;
		},

		setMessageView(state, message) {
			state.message = message;
		},

		setUsersList(state, users) {
			state.usersList = users;
		},

		sendPrivateMessage(state, message) {
			state.messagesS.push(message);
		},

		newMessageNotification(state, message) {
			state.messagesR.unshift(message);
		//	state.notifications.push(message); no need as we can get notfications for all unread messages

		},


		//for friendsRequests
		setFriendsPending(state, friends) {
			state.friendsPending = friends;
		},

		setFriendsSent(state, friends) {
			state.friendsSent = friends;

		},

		newFriendRequest(state, friend) {
			state.friendsPending.unshift(friend);
		}	

	},

	actions: {//async can set timing intervals

		setUserMessageR(context) {

			let postData = {};
			
			axios.post('/get-private-messages', postData).then(response => {
				context.commit('setMessageR',response.data);
			})

		},

		setUserMessageS(context) {
			let postData = {};			
			axios.post('/get-private-messages-sent', postData).then(response => {
				context.commit('setMessageS',response.data);
			})

		},

		getPrivateMessageById(context, id) {
			axios.get('/get-private-message-by-id/' + id).then(response => {
				context.commit('setMessageView',response.data);
			})
		},

		getUsersList(context) {

			axios.get('/users-list').then(response => {
				context.commit('setUsersList',response.data);
			})
		},

		sendPrivateMessage(context, message) {
			//console.log(message);

			let postData = {

				'receiver_id':message.receiver.id,
				'message':message.message,
				'subject':message.subject
			};

			axios.post('/send-private-message', postData).then(response => {
				context.commit('sendPrivateMessage',response.data);
			})
		},

		newMessageNotification(context, message) {
			//console.log('event is emitted to sent users also');
			context.commit('newMessageNotification', message);

		},

		//for friends Requests
		setFriendsPending(context) {
			axios.get('/getpendingrequests').then((response) => {
                if(response.data != '') {
					context.commit('setFriendsPending', response.data);
                }
            }).catch(error => {
                console.log(error);
            });
		},

		setFriendsSent(context, friends) {
			context.commit('setFriendsSent', friends)

		},

		newFriendRequest(context, user) {
			context.commit('newFriendRequest', user);
		}


	}

});