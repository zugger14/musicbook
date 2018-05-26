import Vuex from 'vuex'

import Vue from 'vue'

Vue.use(Vuex);

export default new Vuex.Store({// export const store = what does that mean

	state: { 

		notifications: [],
		messagesR: [],
		messagesS: [],
		message: {
			subject: '',
			message: '',
			sender: '',
			receiver: ''


		},
		usersList: []

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

		}
	}

});