import Vue from 'vue';
import Vuex from 'vuex';
import api from './api';

Vue.use(Vuex);

export const store = new Vuex.Store({
	state: {
		debug: true,
		projects: [],
		currentProject: {},
		projectAdded: false,
		clients: []
	},

	mutations: {
		// Update projects
		updateProjects (state, payload) {
			state.projects = payload;
		},
		// Update the current project
		updateCurrentProject (state, payload) {
			state.currentProject = payload;
		},
		// When a project was added
		updateProjectAdded (state, payload) {
			state.projectAdded = payload;
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
				// Add province to string
				if(payload.location != '') url += '/' + payload.location;
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
					context.commit('updateProjects', response);
				})
				.catch( (error) => console.log(error) );
		},

		getProject (context, payload) {
			return api.get('/projects/' + payload)
				.then( (response) => {
					context.commit('updateCurrentProject', response);
				})
				.catch( (error) => console.log(error) );
		},

		// Use api to add a new project to the db
		addProject (context, payload) {
			var url = '/projects/start';

			return new Promise((resolve, reject) => {
				// Use api to send POST request
				api.post(url, payload)
					.then( (response) => {
						// For debug
						if(context.state.debug) console.log(response);
						// Change state					
						context.commit('updateCurrentProject', response);
						// Resolve promise
						resolve();
					})
					.catch( (error) => console.log(error) );				
			});


		},

		// Use api to edit a project field in the db
		updateProjectField (context, payload) {

			return new Promise((resolve, reject) => {
				// Use api to send POST request
				api.post('/projects/update-field', payload)
					.then( (response) => {
						context.commit('updateCurrentProject', response);
						// Resolve promise
						resolve();
					})
					.catch( (error) => { 
						reject(error);
					});
			});

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

		currentProject (state) {
			return state.currentProject;
		},

		projectAdded (state) {
			return state.projectAdded;
		},

		// Return the clients (from projects table)
		clients (state) {
			return state.clients;
		},

		// Return the clients formatted for a vuetify select list
		clientsSelectList (state) {
			// Cache clients
			var clients = state.clients,
          clientsSelect = [{ text: "Client...", value: "" }];
      // Create client select array
      clients.forEach(function(client){
        clientsSelect.push({ text: client, value: client });
      });		
      return clientsSelect;	
		}
	}
});