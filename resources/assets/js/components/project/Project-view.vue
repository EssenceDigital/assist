<template>
	<v-container fluid>

		<!-- Main layout container -->
		<v-layout v-if="!loading" row class="mt-5">
			<!-- Main flex container -->
			<v-flex xs12 xl10 offset-xl1>
				<!-- Top most card -->
				<v-card class="grey lighten-5" flat>
				  <!-- Main card container -->
				  <v-layout row>
				    <v-flex xs12 lg10 offset-lg1>
				      <v-card class="card--flex-toolbar">	
								<v-container>
					      	<!-- Card toolbar -->
					        <v-toolbar card class="white" prominent>
					          <v-toolbar-title class="display-1">				         
					          	Project Details ({{ $store.getters.currentProject.id }})		          	
					          </v-toolbar-title>				          				          
					          <v-spacer></v-spacer>
					        </v-toolbar><!-- /Card toolbar -->	
					      </v-container>	
					      <v-container>
					      	<v-layout row>
					        	<p class="subheading pl-4">
						          This is where you can edit and track the project you've selected.				        		
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
				        <v-card-text>				 
									<v-layout row>
										<v-flex xs12>
											<!-- Client details -->
											<v-container>
												<div class="headline">
													Client
												</div>
												<!-- Row one -->
												<v-layout row>
													<v-flex xs4>
														<field-input-toggle
															:type="'select'"
															:select_options="clients"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Client Company'"
															:field="'client_company_name'"
															:value="$store.getters.currentProject.client_company_name"
														></field-input-toggle>
													</v-flex>													
												</v-layout>
												<!-- Row two -->
												<v-layout row class="mt-5">
													<v-flex xs4>
														<field-input-toggle
															:type="'text'"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Company Contact'"
															:field="'client_contact_name'"
															:value="$store.getters.currentProject.client_contact_name"
														></field-input-toggle>
													</v-flex>
													<v-flex xs4>
														<field-input-toggle
															:type="'text'"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Contact\'s Phone No.'"
															:icon="'phone'"
															:field="'client_contact_phone'"
															:value="$store.getters.currentProject.client_contact_phone"
														></field-input-toggle>
													</v-flex>
													<v-flex xs4>
														<field-input-toggle
															:type="'text'"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Contact\'s Email'"
															:field="'client_contact_email'"
															:value="$store.getters.currentProject.client_contact_email"
														></field-input-toggle>
													</v-flex>
												</v-layout>	
											</v-container><!-- /Client details -->

											<v-divider class="mt-5"></v-divider>

											<!-- Location details -->
											<v-container class="mt-5">
												<div class="headline">
													Location
												</div>
												<v-layout row>
													<v-flex xs4>
														<field-input-toggle
															:type="'select'"
															:select_options="[
											          { text: 'Province...', value: '' },
											          { text: 'Alberta', value: 'Alberta' },
											          { text: 'British Columbia', value: 'British Columbia' },
											          { text: 'Saskatchewan', value: 'Saskatchewan' }
															]"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Province'"
															:field="'province'"
															:value="$store.getters.currentProject.province"
														></field-input-toggle>														
													</v-flex>
													<v-flex xs8>
														<field-input-toggle
															:type="'textarea'"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Specific Location'"
															:field="'location'"
															:value="$store.getters.currentProject.location"
															:char_count="100"
														></field-input-toggle>														
													</v-flex>
												</v-layout>
											</v-container><!-- /Location details -->

											<v-divider class="mt-5"></v-divider>

											<!-- Basic details -->
											<v-container class="mt-5">
												<div class="headline">
													Basics
												</div>
												<v-layout row>
													<v-flex xs12>
														<field-input-toggle
															:type="'textarea'"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Basic Details'"
															:field="'details'"
															:value="$store.getters.currentProject.details"
															:char_count="750"
														></field-input-toggle>														
													</v-flex>
												</v-layout>
											</v-container><!-- /Basic details -->		

											<v-divider class="mt-5"></v-divider>

											<!-- Work details -->
											<v-container class="mt-5">
												<div class="headline">
													Work Details
												</div>
												<!-- Row one -->
												<v-layout row>
													<v-flex xs4>
														<field-input-toggle
															:type="'select'"
															:select_options="[
											          { text: 'Work type...', value: '' },
											          { text: 'HRIA', value: 'HRIA' },
											          { text: 'Archaeology', value: 'Archaeology' },
											          { text: 'Paleaontology', value: 'Paleaontology' }
															]"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Work Type'"
															:field="'work_type'"
															:value="$store.getters.currentProject.work_type"
														></field-input-toggle>														
													</v-flex>
													<v-flex xs4>
														<field-input-toggle
															:type="'date'"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Respond By'"
															:field="'response_by'"
															:value="$store.getters.currentProject.response_by"
														></field-input-toggle>														
													</v-flex>
													<v-flex xs4>
														<field-input-toggle
															:type="'text'"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Estimate'"
															:field="'estimate'"
															:prefix="'$'"
															:value="$store.getters.currentProject.estimate"
														></field-input-toggle>														
													</v-flex>													
												</v-layout>
												<!-- Row two -->
												<v-layout row>
													<v-flex xs12>
														<field-input-toggle
															:type="'textarea'"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Work Overview'"
															:field="'work_overview'"
															:value="$store.getters.currentProject.work_overview"
															:char_count="750"
														></field-input-toggle>														
													</v-flex>
												</v-layout>
												<!-- Row three -->
												<v-layout row>
													<v-flex xs12>
														<field-input-toggle
															:type="'textarea'"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Work Plans'"
															:field="'plans'"
															:value="$store.getters.currentProject.plans"
															:char_count="750"
														></field-input-toggle>														
													</v-flex>
												</v-layout>												
											</v-container><!-- /Basic details -->		

											<v-divider class="mt-5"></v-divider>

											<!-- Land details -->
											<v-container class="mt-5">
												<div class="headline">
													Land
												</div>
												<!-- Row one -->
												<v-layout row>
													<v-flex xs4>
														<field-input-toggle
															:type="'select'"
															:select_options="[
											          { text: 'Land ownership...', value: '' },
											          { text: 'Crown', value: 'Crown' },
											          { text: 'Freehold', value: 'Freehold' }
															]"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Land Ownership'"
															:field="'land_ownership'"
															:value="$store.getters.currentProject.land_ownership"
														></field-input-toggle>														
													</v-flex>
													<v-flex xs4>
														<field-input-toggle
															:type="'select'"
															:select_options="[
											          { text: 'Land access granted...', value: '' },
											          { text: 'No', value: 0 },
											          { text: 'Yes', value: 1 }
															]"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Access Granted'"
															:field="'land_access_granted'"
															:value="$store.getters.currentProject.land_access_granted"
															:bool_field="true"
														></field-input-toggle>														
													</v-flex>													
												</v-layout>
												<!-- Row two -->
												<v-layout row>
													<v-flex xs4>
														<field-input-toggle
															:type="'text'"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Access Granted By'"
															:field="'land_access_granted_by'"
															:value="$store.getters.currentProject.land_access_granted_by"
														></field-input-toggle>														
													</v-flex>													
													<v-flex xs4>
														<field-input-toggle
															:type="'text'"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Access Contact'"
															:field="'land_access_contact'"
															:value="$store.getters.currentProject.land_access_contact"
														></field-input-toggle>														
													</v-flex>	
													<v-flex xs4>
														<field-input-toggle
															:type="'text'"
															:icon="'phone'"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Contact\'s Phone'"
															:field="'land_access_phone'"
															:value="$store.getters.currentProject.land_access_phone"
														></field-input-toggle>														
													</v-flex>																									
												</v-layout>
											</v-container><!-- /Land details -->

											<v-divider class="mt-5"></v-divider>

											<!-- Approval details -->
											<v-container class="mt-5">
												<div class="headline">
													Approval
												</div>										
												<v-layout row>
													<v-flex xs4>
														<field-input-toggle
															:type="'date'"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Approval Date'"
															:field="'approval_date'"
															:value="$store.getters.currentProject.approval_date"
														></field-input-toggle>														
													</v-flex>													
												</v-layout>
											</v-container><!-- / Approval details -->

											<v-divider class="mt-5"></v-divider>

											<!-- Invoicing details -->
											<v-container class="mt-5">
												<div class="headline">
													Invoicing
												</div>												
												<v-layout row>
													<v-flex xs4>
														<field-input-toggle
															:type="'date'"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Invoiced Date'"
															:field="'invoiced_date'"
															:value="$store.getters.currentProject.invoiced_date"
														></field-input-toggle>														
													</v-flex>	
													<v-flex xs4>
														<field-input-toggle
															:type="'date'"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Invoice Paid Date'"
															:field="'invoice_paid_date'"
															:value="$store.getters.currentProject.invoice_paid_date"
														></field-input-toggle>														
													</v-flex>
													<v-flex xs4>
														<field-input-toggle
															:type="'text'"
															:icon="'attach_money'"
															:action="'updateProjectField'"
															:id="$store.getters.currentProject.id"
															:label="'Invoice Amount'"
															:field="'invoice_amount'"
															:prefix="'$'"
															:value="$store.getters.currentProject.invoice_amount"
														></field-input-toggle>														
													</v-flex>																						
												</v-layout>
											</v-container><!-- /Invoicing details -->		
											<v-divider class="mt-5"></v-divider>
										</v-flex>
									</v-layout><!-- Project view layout -->		

									<!-- Project notes -->
									<v-layout row>
										<v-flex xs12>
											<!-- Notes-->
											<v-container class="mt-5">
												<div class="headline">
													Notes
												</div>
												<project-notes></project-notes>
											</v-container><!-- /Notes -->
										</v-flex>
									</v-layout><!-- /Project notes -->

									<!-- Project crew -->
									<v-layout row>
										<v-flex xs12>
											
										</v-flex>
									</v-layout><!-- /Project crew -->

				        </v-card-text><!-- /Card body -->
				      </v-card>
				    </v-flex>
				  </v-layout><!-- / Main card container -->
				</v-card><!-- / Top most card -->
			</v-flex><!-- / Flex container -->
		</v-layout><!-- /Layout container -->
	</v-container>
</template>

<script>
	import FieldInputToggle from './../form/Field-input-toggle';
	import ProjectNotes from './../form/Project-notes.vue';

	export default {
		props: ['id'],

		components: {
			'field-input-toggle': FieldInputToggle,
			'project-notes': ProjectNotes
		},

		data () {
			return {
				loading: false
			}
		},

		computed: {
      // Watch for uniqueCLients state to update
      clients () {
        return this.$store.getters.clientsSelectList;
      }
		},

		created () {
			// Toggle loader
			this.loading = true;
      // Update the requested project
      this.$store.dispatch('getProject', this.id).then( () => {
    		// Get unique clients
    		this.$store.dispatch('getClients');
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
</style>