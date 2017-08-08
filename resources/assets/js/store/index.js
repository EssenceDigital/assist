import Vue from 'vue';
import Vuex from 'vuex';
import api from './api';

Vue.use(Vuex);

export const store = new Vuex.Store({
	state: {
		debug: true,
		projects: [],
		clients: []
	},

	mutations: {
		// Update projects
		updateProjects (state, payload) {
			state.projects = payload;
		},

		// Update clients
		updateClients (state, payload) {
			state.clients = payload;
		}
	},

	actions: {
		// Use api to retrieve all projects
		getProjects (context, payload) {
			var url = '/projects';

			if(payload){
				// Add client to string
				if(payload.client != '') url += '/' + payload.client;
					else url += '/' + 0;
				// Add province to string
				if(payload.province != '') url += '/' + payload.province;
					else url += '/' + 0;
				// Add invoice status to filter
				if(payload.invoice != '') url += '/' + payload.invoice;
					else url += '/' + 0;
			}

			return api.get(url)
				.then( (response) => { 
					// For debug
					if(context.state.debug) console.log(response);
					// Change state
					context.commit('updateProjects', response)
				})
				.catch( (error) => console.log(error));
		},

		// Use api to retrieve all the unique clients (from projects)
		getClients (context) {
			return api.get('/projects/clients')
				.then( (response) => {
					// For debug
					if(context.state.debug) console.log(response);
					// Change state
					context.commit('updateClients', response);
				})
				.catch( (error) => console.log(error));
		}
	},

	getters: {
		// Return the debug state
		debug (state) {
			return state.debug;
		},

		// Return the loaded projects
		projects (state) {
			return state.projects;
		},

		// Return the clients (from projects table)
		clients (state) {
			return state.clients;
		}
	}
});