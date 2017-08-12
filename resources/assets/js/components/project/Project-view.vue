<template>
	<v-container fluid>
		<v-layout row class="mt-5">
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
									<v-layout row class="pl-4">
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
														></field-input-toggle>														
													</v-flex>
												</v-layout>
											</v-container><!-- /Location details -->

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
														></field-input-toggle>														
													</v-flex>
												</v-layout>
											</v-container><!-- /Basic details -->		

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
															:icon="'attach_money'"
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
														></field-input-toggle>														
													</v-flex>
												</v-layout>												
											</v-container><!-- /Basic details -->																					

										</v-flex>
									</v-layout>					        	
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

	export default {
		props: ['id'],

		components: {
			'field-input-toggle': FieldInputToggle
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
      // Update the requested project
      this.$store.dispatch('getProject', this.id);
      this.$store.dispatch('getClients');			
		}
	}

</script>

<style scoped>

</style>