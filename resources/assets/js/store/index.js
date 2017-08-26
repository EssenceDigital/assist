import Vue from 'vue';
import Vuex from 'vuex';
import api from './api';
import Helpers from './helpers';

Vue.use(Vuex);

export const store = new Vuex.Store({
	state: {
		debug: true,
		user: {
			id: 1
		},
		users: [],
		projects: [],
		currentProject: { id: 0 },
		timesheets: [],
		currentTimesheet: { },
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
			// Find comment to delete
			state.currentProject.comments.forEach(function(comment){
				if(comment.id == payload) {
					var index = state.currentProject.comments.indexOf(comment); 
					state.currentProject.comments.splice(index, 1);					
				}
			});
		},
		// Push a freshly added crew member into the current project
		addProjectCrew (state, payload) {
			return state.currentProject.users.push(payload);
		},
		// Remove a crew member from the current project
		deleteProjectCrew (state, payload) {
			state.currentProject.users.forEach(function(user){
				if(user.id == payload) {
					var index = state.currentProject.users.indexOf(user); 
					state.currentProject.users.splice(index, 1);					
				}
			});
		},
		// Update the current project timline
		updateCurrentProjectTimeline (state, payload) {
			return state.currentProject.timeline = payload;
		},

		// Update the timesheets
		updateTimesheets (state, payload) {
			return state.timesheets = payload;
		},

		// Add a timesheet
		addTimesheet (state, payload) {
			return state.timesheets.unshift(payload);
		},

		// Add timesheet hours
		addTimesheetHours (state, payload) {
			// Find timesheet
			Helpers.findTimesheet(state.timesheets, payload.timesheet_id)
				.then( (timesheet) => {
					timesheet.work_jobs.push(payload);
				});		
		},

		// Add timesheet travel
		addTimesheetTravel (state, payload) {
			// Find timesheet
			Helpers.findTimesheet(state.timesheets, payload.timesheet_id)
				.then( (timesheet) => {
					timesheet.travel_jobs.push(payload);
				});					
		},

		// Add timesheet equipment
		addTimesheetEquipment (state, payload) {
			// Find timesheet
			Helpers.findTimesheet(state.timesheets, payload.timesheet_id)
				.then( (timesheet) => {
					timesheet.equipment_rentals.push(payload);
				});					
		},

		// Add timesheet other costs
		addTimesheetOther (state, payload) {
			// Find timesheet
			Helpers.findTimesheet(state.timesheets, payload.timesheet_id)
				.then( (timesheet) => {
					timesheet.other_costs.push(payload);
				});						
		},

		// Update timesheet hours asset
		updateTimesheetHours (state, payload) {
			// Find timesheet asset
			return Helpers.findTimesheetAsset(state.timesheets, payload.timesheet_id, 'work_jobs', payload.id)
				.then( (asset) => {
					// Update asset
					asset.job_type = payload.job_type;
					asset.hours_worked = payload.hours_worked;
					asset.comment = payload.comment;
				});
		},
		// Update timesheet travel asset
		updateTimesheetTravel (state, payload) {
			// Find timesheet asset
			return Helpers.findTimesheetAsset(state.timesheets, payload.timesheet_id, 'travel_jobs', payload.id)
				.then( (asset) => {
					// Update asset
					asset.travel_distance = payload.travel_distance;
					asset.travel_time = payload.travel_time;
					asset.comment = payload.comment;
				});
		},
		// Update timesheet equipment asset
		updateTimesheetEquipment (state, payload) {
			// Find timesheet asset
			return Helpers.findTimesheetAsset(state.timesheets, payload.timesheet_id, 'equipment_rentals', payload.id)
				.then( (asset) => {
					// Update asset
					asset.equipment_type = payload.equipment_type;
					asset.rental_fee = payload.rental_fee;
					asset.comment = payload.comment;
				});
		},
		// Update timesheet other cost asset
		updateTimesheetOther (state, payload) {
			// Find timesheet asset
			return Helpers.findTimesheetAsset(state.timesheets, payload.timesheet_id, 'other_costs', payload.id)
				.then( (asset) => {
					// Update asset
					asset.cost_name = payload.cost_name;
					asset.cost = payload.cost;
					asset.comment = payload.comment;
				});
		},

		deleteTimesheetHours (state, payload) {
			return Helpers.deleteTimesheetAsset(state.timesheets, payload.timesheet_id, 'work_jobs', payload.id);
		},
		deleteTimesheetTravel (state, payload) {
			return Helpers.deleteTimesheetAsset(state.timesheets, payload.timesheet_id, 'travel_jobs', payload.id);
		},
		deleteTimesheetEquipment (state, payload) {
			return Helpers.deleteTimesheetAsset(state.timesheets, payload.timesheet_id, 'equipment_rentals', payload.id);
		},
		deleteTimesheetOther (state, payload) {
			return Helpers.deleteTimesheetAsset(state.timesheets, payload.timesheet_id, 'other_costs', payload.id);
		},

		// Update users
		updateUsers (state, payload) {
			return state.users = payload;
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
			return api.getAction(context, payload, '/projects/'+payload, 'updateCurrentProject');
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
			// Use api to send request
			return api.deleteAction(context, payload, '/projects/delete-comment/'+payload.id, 'deleteProjectComment');
		},
		// Use api to add a crew member to a specific project and update the current project users in the state
		addProjectCrew (context, payload) {
			return api.postAction(context, payload, '/projects/add-crew', 'addProjectCrew');
		},
		// Use api to delete a crew member from a specific project and update the current project crew in the state
		deleteProjectCrew (context, payload) {
			return api.deleteAction(context, payload, '/projects/'+payload.project_id+'/delete-crew/'+ payload.id, 'deleteProjectCrew');
		},

		updateTimelineField (context, payload) {
			return api.postAction(context, payload, '/timelines/update-field', 'updateCurrentProjectTimeline');
		},
		// Use api to retrieve all of a users projects and set them in the state
		getUsersProjects (context, payload) {
			 return api.getAction(context, payload, '/users/'+payload.user_id+'/projects', 'updateProjects');
		},
		// Use api to retrieve all of a users timesheets on a project
		getProjectTimesheets (context, payload) {
			return api.getAction(context, payload, '/users/'+payload.user_id+'/projects/'+payload.project_id+'/timesheets', 'updateTimesheets');
		},

		/* 
			TIMESHEET ACTIONS
		*/
		
		// Use api to add a timesheet to a project
		addTimesheet (context, payload) {
			return api.postAction(context, payload, '/timesheets/add', 'addTimesheet');
		},
		// Use api to delete an entire timesheet
		deleteProjectCrew (context, payload) {
			return api.deleteAction(context, payload, '/projects/'+payload.project_id+'/delete-crew/'+ payload.id, 'deleteProjectCrew');
		},		
		// Use api to add hours to a timesheet
		addTimesheetHours (context, payload) {
			return api.postAction(context, payload, '/timesheets/add-hours', 'addTimesheetHours');
		},		
		// Use api to add travel to a timesheet
		addTimesheetTravel (context, payload) {
			return api.postAction(context, payload, '/timesheets/add-travel', 'addTimesheetTravel');
		},
		
		// Use api to add equipment to a timesheet
		addTimesheetEquipment (context, payload) {
			return api.postAction(context, payload, '/timesheets/add-equipment', 'addTimesheetEquipment');
		},
		
		// Use api to add other costs to a timesheet
		addTimesheetOther (context, payload) {
			return api.postAction(context, payload, '/timesheets/add-other', 'addTimesheetOther');
		},

		// Use api to update hours on a timesheet
		updateTimesheetHours (context, payload) {
			return api.postAction(context, payload, '/timesheets/update-hours', 'updateTimesheetHours');
		},			
		// Use api to update travel on a timesheet
		updateTimesheetTravel (context, payload) {
			return api.postAction(context, payload, '/timesheets/update-travel', 'updateTimesheetTravel');
		},
		// Use api to update equipment on a timesheet
		updateTimesheetEquipment (context, payload) {
			return api.postAction(context, payload, '/timesheets/update-equipment', 'updateTimesheetEquipment');
		},		
		// Use api to update other cost on a timesheet
		updateTimesheetOther (context, payload) {
			return api.postAction(context, payload, '/timesheets/update-other', 'updateTimesheetOther');
		},	

		// Use api to delete hours on a timesheet
		deleteTimesheetHours (context, payload) {
			return api.deleteAction(context, payload, '/timesheets/delete-hours/' + payload, 'deleteTimesheetHours');
		},		
		// Use api to delete travel on a timesheet
		deleteTimesheetTravel (context, payload) {
			return api.deleteAction(context, payload, '/timesheets/delete-travel/' + payload, 'deleteTimesheetTravel');
		},	
		// Use api to delete equipment on a timesheet
		deleteTimesheetEquipment (context, payload) {
			return api.deleteAction(context, payload, '/timesheets/delete-equipment/' + payload, 'deleteTimesheetEquipment');
		},	
		// Use api to delete other cost on a timesheet
		deleteTimesheetOther (context, payload) {
			return api.deleteAction(context, payload, '/timesheets/delete-other/' + payload, 'deleteTimesheetOther');
		},	

		/* 
			USER ACTIONS
		*/

		// Use api to retrieve all users and set them in the store
		getUsers (context, payload) {
			return api.getAction(context, payload, '/users', 'updateUsers');
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

		timesheets (state) {
			return state.timesheets;
		},

		// Return all users
		users (state) {
			return state.users;
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