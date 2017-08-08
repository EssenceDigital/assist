import axios from 'axios';

export default {
	get (url) {
		return axios.get(url)
			.then((response) => Promise.resolve(response.data.payload))
			.catch((error) => Promise.reject(error));
	}
}