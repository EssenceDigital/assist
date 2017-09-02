<template>
<v-app standalone>
	<v-layout row justify-center style="position: relative;">
	  <v-dialog v-model="login" persistent absolute width="425">
	    <v-card>
	      <v-card-title class="primary">
	        <div class="headline white--text">Arrow Assist</div>
	      </v-card-title>
	      <v-card-text>
	      	<v-container fluid>
		      	<v-layout row>
			      	<v-text-field
			      		v-model="form.email.val"
			      		:error="form.email.err"
			      		label="Email"
			      		prepend-icon="person"
			      	></v-text-field>		      		
		      	</v-layout>
	      		<!-- Error msg -->
	      		<v-layout row>
							<p v-if="form.email.err" class="caption error--text">
								{{ form.email.errMsg }}
							</p>       			
	      		</v-layout>		      	
		      	<v-layout row>
			      	<v-text-field
			      		v-model="form.password.val"
			      		:error="form.password.err"
			      		type="password"
			      		label="Password"
			      		prepend-icon="security"
			      	></v-text-field>		      		
		      	</v-layout>	
	      		<!-- Error msg -->
	      		<v-layout row>
							<p v-if="form.password.err" class="caption error--text">
								{{ form.password.errMsg }}
							</p>       			
	      		</v-layout>		      	
		      	<v-layout row>
		      		<v-btn outline block
		      			:loading="loggingIn"
		      			:disabled="loggingIn"
		      			@click="submitLogin"
		      		>
		      			Login
		      		</v-btn>
		      	</v-layout>	      		
	      	</v-container>
	      </v-card-text>
	    </v-card>
	  </v-dialog>
	</v-layout>
</v-app>	
</template>

<script>
	import Helpers from './store/helpers';

	export default {
		props: ['token'],

		data () {
			return {
				login : true,
				loggingIn: false,
				form: {
					email: {val: '', err: false, errMsg: '', dflt: ''},
					password: {val: '', err: false, errMsg: '',  dflt: ''}					
				}
			}			
		},

		methods: {
			submitLogin () {
				// Toggle loader
				this.loggingIn = true;
				// Clear any form erros
				Helpers.clearFormErrors(this.form);
				// Send login request
				axios.post('/login', {
					email: this.form.email.val,
					password: this.form.password.val
				})
				// Login success
				.then( (response) => {
					// Toggle loader
					this.loggingIn = false;					
					console.log(response);
					this.$router.go('/app')
					
				})
				// Login errors
				.catch( (error) => {
					// Cache errors
					var errors = error.response.data;
					// Set possible email errors
					if(errors.email) {
						this.form.email.err = true;
						if(Array.isArray(errors.email)) this.form.email.errMsg = errors.email[0];
							else this.form.email.errMsg = errors.email
					}
					// Set possible password errors
					if(errors.password) {
						this.form.password.err = true;
						if(Array.isArray(errors.password)) this.form.password.errMsg = errors.password[0];
							else this.form.password.errMsg = errors.password
					} 
					// Toggle loader
					this.loggingIn = false;					
				});
			}
		}
	}
</script>