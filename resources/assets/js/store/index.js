import Vue from 'vue';
import Vuex from 'vuex';
import api from './api';
import Helpers from './helpers';

Vue.use(Vuex);

export const store = new Vuex.Store({
	state: {
		debug: true,
		authUser: {

		},
		currentUser: { id: 0 },
		users: [],
		projects: [],
		currentProject: { id: 0 },
		timesheets: [],
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

		// Update a timesheet
		updateTimesheet (state, payload) {
			console.log(payload);
			// The id of the timesheet
			var id = 0;
			// Set depending on which field is the timesheet id
			if(payload.timesheet_id) id = payload.timesheet_id
					else id = payload.id
			// Find timesheet to update
			Helpers.findTimesheet(state.timesheets, id)
				.then( (timesheetIndex) => {
					state.timesheets.splice(timesheetIndex, 1, payload);
				});
		},

		// Update users
		updateUsers (state, payload) {
			return state.users = payload;
		},

		// Add a user to store
		addUser (state, payload) {
			return state.users.push(payload);
		},

		updateCurrentUser (state, payload) {
			return state.currentUser = payload;
		},

		setAuthUser (state, payload) {
			return state.authUser = {
				id: AUTH_ID,
				first: AUTH_FIRST,
				last: AUTH_LAST,
				name: AUTH_FIRST + ' ' + AUTH_LAST,
				permissions: AUTH_PERMISSIONS,
				email: AUTH_EMAIL,
				company: AUTH_COMPANY,
				gst_number: AUTH_GST_NO,
				hourly: AUTH_HOURLY
			};
		},

		clearAuthUser (state, payload) {
			state.authUser = { };
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
			var url = '/projects'
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
			return api.getAction(context, payload, url, 'updateProjects');
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
		// Use api to retrieve all timesheets
		getAllTimesheets (context, payload) {
			var url = '/timesheets';
			
			// Create payload 
			if(payload){
				// Add from date to string
				if(payload.from_date != '') url += '/' + payload.from_date;
					else url += '/' + 0;
				// Add to date to string
				if(payload.to_date != '') url += '/' + payload.to_date;
					else url += '/' + 0;					
				// Add project id to string
				if(payload.project_id != '') url += '/' + payload.project_id;
					else url += '/' + 0;
				// Add user id to string
				if(payload.user_id != '') url += '/' + payload.user_id;
					else url += '/' + 0;					
			}
			// Use api to send request
			return api.getAction(context, payload, url, 'updateTimesheets');
		},

		/* 
			TIMESHEET ACTIONS
		*/
		
		// Use api to add a timesheet to a project
		addTimesheet (context, payload) {
			console.log(payload);
			return api.postAction(context, payload, '/timesheets/add', 'addTimesheet');
		},
		// Use api to update a timesheet on a project
		updateTimesheet (context, payload) {
			return api.postAction(context, payload, '/timesheets/update', 'updateTimesheet');
		},
		// Use api to delete an entire timesheet
		deleteProjectCrew (context, payload) {
			return api.deleteAction(context, payload, '/projects/'+payload.project_id+'/delete-crew/'+ payload.id, 'deleteProjectCrew');
		},		
		// Use api to add hours to a timesheet
		addTimesheetHours (context, payload) {
			return api.postAction(context, payload, '/timesheets/add-hours', 'updateTimesheet');
		},		
		// Use api to add travel to a timesheet
		addTimesheetTravel (context, payload) {
			return api.postAction(context, payload, '/timesheets/add-travel', 'updateTimesheet');
		},
		
		// Use api to add equipment to a timesheet
		addTimesheetEquipment (context, payload) {
			return api.postAction(context, payload, '/timesheets/add-equipment', 'updateTimesheet');
		},
		
		// Use api to add other costs to a timesheet
		addTimesheetOther (context, payload) {
			return api.postAction(context, payload, '/timesheets/add-other', 'updateTimesheet');
		},

		// Use api to update hours on a timesheet
		updateTimesheetHours (context, payload) {
			return api.postAction(context, payload, '/timesheets/update-hours', 'updateTimesheet');
		},			
		// Use api to update travel on a timesheet
		updateTimesheetTravel (context, payload) {
			return api.postAction(context, payload, '/timesheets/update-travel', 'updateTimesheet');
		},
		// Use api to update equipment on a timesheet
		updateTimesheetEquipment (context, payload) {
			return api.postAction(context, payload, '/timesheets/update-equipment', 'updateTimesheet');
		},		
		// Use api to update other cost on a timesheet
		updateTimesheetOther (context, payload) {
			return api.postAction(context, payload, '/timesheets/update-other', 'updateTimesheet');
		},	

		// Use api to delete hours on a timesheet
		deleteTimesheetHours (context, payload) {
			return api.deleteAction(context, payload, '/timesheets/delete-hours/'+payload, 'updateTimesheet');
		},		
		// Use api to delete travel on a timesheet
		deleteTimesheetTravel (context, payload) {
			return api.deleteAction(context, payload, '/timesheets/delete-travel/'+payload, 'updateTimesheet');
		},	
		// Use api to delete equipment on a timesheet
		deleteTimesheetEquipment (context, payload) {
			return api.deleteAction(context, payload, '/timesheets/delete-equipment/'+payload, 'updateTimesheet');
		},	
		// Use api to delete other cost on a timesheet
		deleteTimesheetOther (context, payload) {
			return api.deleteAction(context, payload, '/timesheets/delete-other/'+payload, 'updateTimesheet');
		},	

		/* 
			USER ACTIONS
		*/

		// Use api to retrieve all users and set them in the store
		getUsers (context, payload) {
			return api.getAction(context, payload, '/users', 'updateUsers');
		},

		// Use api to retrieve a user and set it in the state
		getUser (context, payload) {
			// Use api to send request
			return api.getAction(context, payload, '/users/'+payload, 'updateCurrentUser');
		},
		// Use api to add a new user to the db
		addUser (context, payload) {
			// Use api to send request
			return api.postAction(context, payload, '/users/add', 'addUser');
		},		
		// Use api to edit a user field in the db and update the current user in the state
		updateUserField (context, payload) {
			// Use api to send request
			return api.postAction(context, payload, '/users/update-field', 'updateCurrentUser');
		},
		// Use api to change a users password
		changeUserPassword (context, payload) {
			return api.postAction(context, payload, '/users/change-password');
		},
		// Use api to change the logged in users password
		changePersonalPassword (context, payload) {
			return api.postAction(context, payload, '/users/change-personal-password');
		},
		/*
			MISC ACTIONS
		*/

		// Use api to retrieve all the unique clients (from projects)
		getClients (context, payload) {
			// Use api to send request
			return api.getAction(context, payload, '/projects/clients', 'updateClients');
		},



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

		// Return the clients formatted for a vuetify select list
		projectsSelectList (state) {
			// Cache users
			var projects = state.projects,
          projectsSelect = [{ text: "Project...", value: "" }];
      // Create client select array
      projects.forEach(function(project){
        projectsSelect.push({ text: project.id+' | '+project.location, value: project.id });
      });		
      return projectsSelect;	
		},

		timesheets (state) {
			return state.timesheets;
		},

		// Return all users
		users (state) {
			return state.users;
		},

		// Return the logged in user
		user (state) {
			return state.authUser;
		},

		currentUser (state) {
			return state.currentUser;
		},

		// Return the clients formatted for a vuetify select list
		usersSelectList (state) {
			// Cache users
			var users = state.users,
          usersSelect = [{ text: "User...", value: "" }];
      // Create client select array
      users.forEach(function(user){
        usersSelect.push({ text: user.first+' '+user.last, value: user.id });
      });		
      return usersSelect;	
		},

		// Return the usrs formatted for a vuetify select list with user name as value
		usersSelectListNameBased (state) {
			// Cache users
			var users = state.users,
          usersSelect = [{ text: "User...", value: "" }];
      // Create client select array
      users.forEach(function(user){
        usersSelect.push({ text: user.first+' '+user.last, value: user.first+' '+user.last});
      });		
      return usersSelect;	
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