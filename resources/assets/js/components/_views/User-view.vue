<template>
	<card-layout
		:tips="tips"
	>
		<div slot="title">
			User Details ({{ currentUser.id }})
		</div>
		<div slot="description">
			This is where you can edit the user you've selected.				
		</div>
		<div slot="content">
			<v-layout row>
				<v-flex xs12>
					<!-- User details -->
					<v-container>
						<div class="headline">
							Basics
						</div>
						<!-- Row one -->
						<v-layout row>
							<v-flex xs4>
								<field-input-toggle
									:type="'text'"
									:action="'updateUserField'"
									:id="currentUser.id"
									:label="'First'"
									:field="'first'"
									:value="currentUser.first"
								></field-input-toggle>
							</v-flex>
							<v-flex xs4>
								<field-input-toggle
									:type="'text'"
									:action="'updateUserField'"
									:id="currentUser.id"
									:label="'Last'"
									:field="'last'"
									:value="currentUser.last"
								></field-input-toggle>
							</v-flex>
							<v-flex xs4>
								<field-input-toggle
									:type="'text'"
									:action="'updateUserField'"
									:id="currentUser.id"
									:label="'Email'"
									:field="'email'"
									:value="currentUser.email"
								></field-input-toggle>
							</v-flex>																										
						</v-layout>	
						<!-- Row two -->
						<v-layout row>
							<v-flex xs4>
								<field-input-toggle
									:type="'select'"
									:select_options="[
					          { text: 'Permissions...', value: '' },
					          { text: 'Admin', value: 'admin' },
					          { text: 'User', value: 'user' }
									]"
									:action="'updateUserField'"
									:id="currentUser.id"
									:label="'Permissions'"
									:field="'permissions'"
									:value="currentUser.permissions"
								></field-input-toggle>																
							</v-flex>																									
						</v-layout>													
					</v-container><!-- /basic details -->

					<!-- Business details -->
					<v-container>
						<div class="headline">
							Business
						</div>
						<!-- Row one -->
						<v-layout row>
							<v-flex xs4>
								<field-input-toggle
									:type="'text'"
									:action="'updateUserField'"
									:id="currentUser.id"
									:label="'Company'"
									:field="'company_name'"
									:value="currentUser.company_name"
								></field-input-toggle>
							</v-flex>
							<v-flex xs4>
								<field-input-toggle
									:type="'text'"
									:action="'updateUserField'"
									:id="currentUser.id"
									:label="'Hourly Rate'"
									:field="'hourly_rate_one'"
									:prefix="'$'"
									:value="currentUser.hourly_rate_one"
								></field-input-toggle>
							</v-flex>
							<v-flex xs4>
								<field-input-toggle
									:type="'text'"
									:action="'updateUserField'"
									:id="currentUser.id"
									:label="'GST Number'"
									:field="'gst_number'"
									:value="currentUser.gst_number"
								></field-input-toggle>
							</v-flex>																										
						</v-layout>													
					</v-container><!-- /user company -->

					<v-container>
						<v-divider class="mt-4 mb-4"></v-divider>												
					</v-container>

					<v-container>
						<v-card class="grey lighten-2 elevation-3">
						  <v-card-text>
								<!-- Change password -->
				        <v-container>
									<div class="headline">
										Change Password
									</div>
									<!-- Row one -->
									<v-layout row>
										<v-flex xs5>
											<v-text-field
												class="pb-0"
												v-model="passwordForm.password.val"
												type="password"
											  label="New Password"
											  :error="passwordForm.password.err"
											></v-text-field>	
											<!-- Error msg -->
						      		<v-layout row>
												<p v-if="passwordForm.password.err" class="caption error--text">
													{{ passwordForm.password.errMsg }}
												</p>       			
						      		</v-layout>																													
										</v-flex>
										<v-spacer></v-spacer>
										<v-flex xs5>
											<v-text-field
												class="pb-0"
												v-model="passwordForm.password_confirmation.val"
												type="password"
											  label="Confirm New Password"
											  :error="passwordForm.password.err"
											></v-text-field>	
											<!-- Error msg -->
						      		<v-layout row>
												<p v-if="passwordForm.password.err" class="caption error--text">
													{{ passwordForm.password.errMsg }}
												</p>       			
						      		</v-layout>
										</v-flex>													
									</v-layout>
									<v-layout row>
										<v-flex xs4 offset-xs4>
											<v-btn block flat outline
												:loading="passwordChanging"
												:disabled="passwordChanging"
												@click.native="changePassword"
											>
								      	Change					
											</v-btn>																	
										</v-flex>
									</v-layout>							        	
				        </v-container>
						  </v-card-text>
						</v-card>												
					</v-container>
				</v-flex>
			</v-layout><!-- Project view layout -->		     		
		</div>
	</card-layout>
</template>

<script>
	import CardLayout from './_Card-layout';
	import FieldInputToggle from './../form/Field-input-toggle';
  import Helpers from './../../store/helpers'; 

	export default {
		props: ['id'],

		components: {
			'card-layout': CardLayout,
			'field-input-toggle': FieldInputToggle,
		},

		computed: {
			currentUser () {
				return this.$store.getters.currentUser;
			}
		},

		data () {
			return {
				// Tips for card layout
				tips: [
					{ text: 'To edit a field just click the gear icon next to that field.' }
				],
				loading: false,
				passwordChanging: false,
				passwordForm: {
					id: {val: this.id, err: false, errMsg: '', dflt: this.id},	
					password: {val: '', err: false, errMsg: '',  dflt: ''},
					password_confirmation: {val: '', err: false, errMsg: '', dflt: ''}	
				}
			}
		},

		methods: {
			changePassword () {
				// Toggle loader
				this.passwordChanging = true;
				// Populate data then dispatch action
				Helpers.populatePostData(this.passwordForm)
					.then( (data) => {
						// Dispatch action to change password
						this.$store.dispatch('changeUserPassword', data)
							.then( () => {
								// Toggle loader
								this.passwordChanging = false;
								// Clear form
								Helpers.resetForm(this.passwordForm);
							})
							.catch( (errors) => {
								Helpers.populateFormErrors(this.passwordForm, errors.response.data).then( () => this.passwordChanging = false);
							});				
					});
			}
		},

		created () {
			// Toggle loader
			this.loading = true;
      // Update the requested project
      this.$store.dispatch('getUser', this.id).then( () => {
    		// Toggle loader
    		this.loading = false;
    	});			
		}
	}

</script>

<style scoped>
	.center{
		margin-left: auto;
		margin-right: auto;	
	}
  .card--flex-toolbar {
    margin-top: -64px;
  }	
</style>