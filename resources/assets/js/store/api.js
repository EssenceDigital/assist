import axios from 'axios';

export default {
	get (url) {
		return axios.get(url)
			.then( (response) => Promise.resolve(response.data.payload) )
			.catch( (error) => Promise.reject(error) );
	},

	getAction (context, payload, url, mutation) {
		// Return a promise
		return new Promise((resolve, reject) => {
			// Use helper method to send GET request
			this.get(url)
				.then( (response) => {
					// Commit change to state
					context.commit(mutation, response);					
					// Resolve promise
					resolve();
				})
				.catch( (error) => reject(error) );				
		});				
	},

	post (url, payload) {
		return axios.post(url, payload)
			.then( (response) => Promise.resolve(response.data.payload) )
			.catch( (error) => Promise.reject(error) );
	},

	postAction (context, payload, url, mutation) {
		// Return a promise
		return new Promise((resolve, reject) => {
			// Use api to send POST request
			this.post(url, payload)
				.then( (response) => {
					// For debug
					if(context.state.debug) console.log(response);
					// Change state					
					context.commit(mutation, response);
					// Resolve promise
					resolve();
				})
				.catch( (error) => reject(error) );				
		});		
	},

	delete (url) {
		return axios.delete(url)
			.then( (response) => Promise.resolve(response.data.payload) )
			.catch( (error) => Promise.reject(error) );
	},

	deleteAction (context, payload, url, mutation) {
		// Return a promise
		return new Promise((resolve, reject) => {
			// Use api to send DELETE request
			this.delete(url)
				.then( (response) => {
					// Change state
					context.commit(mutation, response);
					// Resolve promise
					resolve();
				});
		});
	}
}