import Vue from 'vue';
import Vuex from 'vuex';
import api from './api';

Vue.use(Vuex);

export const store = new Vuex.Store({
	state: {
		debug: true,
		user: {
			id: 1
		},
		projects: [],
		currentProject: { id: 0 },
		clients: []
	},

	mutations: {
		// Update projects
		updateProjects (state, payload) {
			return state.projects = payload;
		},
		// Update the current project
		updateCurrentProject (state, payload) {
			return state.currentProject = payload;
		},
		// Push a freshly added comment into the current project
		addProjectComment (state, payload) {
			return state.currentProject.comments.push(payload);
		},
		// Remove a comment from the current project
		deleteProjectComment (state, payload) {
			console.log(payload);
			state.currentProject.comments.forEach(function(comment){
				console.log('itterating');
				if(comment.id == payload) {
					console.log("matching");
					var index = state.currentProject.comments.indexOf(comment); 
					state.currentProject.comments.splice(index, 1);					
				}
			});
		},
		// Update clients
		updateClients (state, payload) {
			return state.clients = payload;
		}
	},

	actions: {

		/* 
			PROJECT ACTIONS
		*/

		// Use api to retrieve all projects and set them in the state
		getProjects (context, payload) {
			// Create payload 
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
					else url += '/' + 'any';
			}
			// Use api to send the request
			return api.getAction(context, payload, '/projects', 'updateProjects');
		},
		// Use api to retrieve a project and set it in the state
		getProject (context, payload) {
			// Use api to send request
			return api.getAction(context, payload, '/projects/', 'updateCurrentProject');
		},

		// Use api to add a new project to the db and update the current project in the state
		addProject (context, payload) {
			// Use api to send request
			return api.postAction(context, payload, '/projects/start', 'updateCurrentProject');
		},

		// Use api to edit a project field in the db and update the current project in the state
		updateProjectField (context, payload) {
			// Use api to send request
			return api.postAction(context, payload, '/projects/update-field', 'updateCurrentProject');
		},
		// Use api to add a comment to specific project and update the current project comments in the state
		addProjectComment (context, payload) {
			// Use api to send request
			return api.postAction(context, payload, '/projects/add-comment', 'addProjectComment');
		},
		// Use api to delete a comment and update the current project comments in the state
		deleteProjectComment (context, payload) {
			console.log(payload);
			return api.deleteAction(context, payload, '/projects/delete-comment/', 'deleteProjectComment');
		},
		/*
			MISC ACTIONS
		*/

		// Use api to retrieve all the unique clients (from projects)
		getClients (context, payload) {
			// Use api to send request
			return api.getAction(context, payload, '/projects/clients', 'updateClients');
		}

	},

	getters: {
		// Return the debug state
		debug (state) {
			return state.debug;
		},

		// Return the logged in user
		user (state) {
			return state.user;
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