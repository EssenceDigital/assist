<template>

	<v-container fluid>
		<v-layout row class="mt-5">
			<v-flex xs12 xl10 offset-xl1>
				<!-- Top most card -->
				<v-card class="grey lighten-5" flat>
					<!-- Red top toolbar -->
				  <v-toolbar dark class="primary elevation-0" extended>
				  	<!-- Back button -->
				    <v-btn 
				    	icon 
				    	v-tooltip:top="{ html: 'Go Back' }"
				    	@click="$router.go(-1)"
				    >
				    	<v-icon dark>arrow_back</v-icon>
				    </v-btn>
				  </v-toolbar><!-- / Red top toolbar -->
				  <!-- Main card container -->
				  <v-layout row>
				    <v-flex xs12 lg10 offset-lg1>
				      <v-card class="card--flex-toolbar">
				      	<v-container>
					      	<!-- Card toolbar -->
					        <v-toolbar card class="white" prominent>
					          <v-toolbar-title class="display-1">				         
					          	Your Info		          	
					          </v-toolbar-title>				          				          
					        </v-toolbar><!-- /Card toolbar -->	
					      </v-container>			        
					      <v-container>
					      	<v-layout row>
				      			<p class="subheading pl-4">
						          This is where you can edit your personal information      		
					        	</p>
					      	</v-layout>
					      </v-container>
				        <v-divider></v-divider>
				        <!-- Container for helpful tips -->
				        <v-container class="mt-4">				        	
					        <v-layout row>
					        	<v-flex xs12>	
			        				<p class="subheading info--text pl-4">
												<v-icon left class="info--text">help_outline</v-icon>
							          To edit a field just click the gear icon next to that field.       			
					        		</p>					        						        							        							        				        		
					        	</v-flex>				        	
					        </v-layout>		        	
				        </v-container><!-- / Container for helpful tips -->	
				        <!-- Card body -->
				        <v-card-text v-if="!loading">				 

									<v-layout row>
										<v-flex xs12>
											<!-- User details -->
											<v-container>
												<div class="headline">
													Details
												</div>
												<!-- Row one -->
												<v-layout row>
													<v-flex xs4>
														<field-input-toggle
															:type="'text'"
															:action="'updateUserField'"
															:id="parseInt($store.getters.user.id)"
															:label="'Email'"
															:field="'email'"
															:value="$store.getters.user.email"
														></field-input-toggle>
													</v-flex>	
													<v-flex xs4>
														<field-input-toggle
															:type="'text'"
															:action="'updateUserField'"
															:id="parseInt($store.getters.user.id)"
															:label="'Company'"
															:field="'company_name'"
															:value="$store.getters.user.company"
														></field-input-toggle>
													</v-flex>
													<v-flex xs4>
														<field-input-toggle
															:type="'text'"
															:action="'updateUserField'"
															:id="parseInt($store.getters.user.id)"
															:label="'GST Number'"
															:field="'gst_number'"
															:value="$store.getters.user.gst_number"
														></field-input-toggle>
													</v-flex>																																						
												</v-layout>														
											</v-container><!-- /basic details -->

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
																		v-model="passwordForm.current_password.val"
																		type="password"
																	  label="Current Password"
																	  :error="passwordForm.current_password.err"
																	></v-text-field>	
																	<!-- Error msg -->
												      		<v-layout row>
																		<p v-if="passwordForm.current_password.err" class="caption error--text">
																			{{ passwordForm.current_password.errMsg }}
																		</p>       			
												      		</v-layout>																													
																</v-flex>																
															</v-layout>
															<!-- Row two -->
															<v-layout row class="mt-3">
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
									</v-layout><!-- user settings layout -->		
	
				        </v-card-text><!-- /Card body -->
				      </v-card>
				    </v-flex>
				  </v-layout><!-- / Main card container -->
				</v-card><!-- / Top most card -->
			</v-flex>
		</v-layout>
	</v-container>

</template>

<script>
	import FieldInputToggle from './../form/Field-input-toggle';
  import Helpers from './../../store/helpers'; 

	export default {
		components: {
			'field-input-toggle': FieldInputToggle,
		},

		data () {
			return {
				loading: false,
				passwordChanging: false,				
				passwordForm: {
					id: {val: this.id, err: false, errMsg: '', dflt: this.id},	
					current_password: {val: '', err: false, errMsg: '',  dflt: ''},
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
						this.$store.dispatch('changePersonalPassword', data)
							.then( () => {
								// Toggle loader
								this.passwordChanging = false;
								// Clear form
								Helpers.resetForm(this.passwordForm);
								Helpers.clearFormErrors(this.passwordForm);
							})
							.catch( (errors) => {
								// Clear form
								Helpers.clearFormErrors(this.passwordForm);
								// Show errors								
								Helpers.populateFormErrors(this.passwordForm, errors.response.data).then( () => this.passwordChanging = false);
							});				
					});
			}
		}		
	}
</script>