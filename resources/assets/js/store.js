import Vuex from 'vuex'

import Vue from 'vue'

Vue.use(Vuex);

export const store = new Vuex.Store({// small s le problem ako thyo eta

	state: { //https://www.youtube.com/watch?v=l1cRhxrg5_c&index=31&list=PLZAiN3wmUtzV1eI7mAxERQaE2LkyA5Nh6
		auth: false// idont like to do it this way i am resisting to use this way....
	}
});