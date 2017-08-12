/**
 * Main projects page. Shows the add project button and projects table.
*/
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
					          	Find &amp; Add Projects				          	
					          </v-toolbar-title>				          				          
					          <v-spacer></v-spacer>

					          <!-- Add project dialog -->
										<v-layout row justify-center style="position: relative;">
									    <v-dialog v-model="addProjectDialog" width="765" lazy absolute>
								        <!-- Add project button -->
								        <v-btn slot="activator" icon v-tooltip:top="{ html: 'Add Project' }">
								          <v-icon>add_circle</v-icon>
								        </v-btn>	    
									      <v-card>
									        <v-card-title>
									          <div class="headline grey--text">Start a project</div>									          
									        </v-card-title>
									        <v-divider></v-divider>
									        <!-- Container for tips -->
									        <v-container>
						        				<p class="subheading info--text pl-2">
															<v-icon left class="info--text">help_outline</v-icon>
										          After starting the project you will be able to add more information.        			
								        		</p>									        	
									        </v-container><!-- /Container for tips -->
									        <v-card-text>
									        	<v-layout row>
												      <v-flex xs5>
												        <v-select
												          v-model="client_company_name_select"
												          :items="clients"
												          label="Client..."
												          single-line
												          bottom
												        ></v-select>        
												      </v-flex>	
												      <v-spacer></v-spacer>
												      <v-flex xs1 class="mt-4">
												      	- OR -
												      </v-flex>	
												      <v-spacer></v-spacer>
												      <v-flex xs5>
											          <v-text-field
											            label="Add a new client"
											            v-model="client_company_name.val"
											            :disabled="disableClientCompanyInput"
											          ></v-text-field>												      	
												      </v-flex>							        		
									        	</v-layout>
 									        	
									        </v-card-text>
									        <v-card-actions>
									          <v-spacer></v-spacer>
									          <v-btn outline class="red--text darken-1" flat="flat" @click.native="addProjectDialog = false">Cancel</v-btn>
									          <v-btn outline class="green--text darken-1" flat="flat" @click.native="startProject">Start Project</v-btn>
									        </v-card-actions>
									      </v-card>
									    </v-dialog>
									  </v-layout><!-- / Add project dialog -->
					        </v-toolbar><!-- /Card toolbar -->	
					      </v-container>			        
					      <v-container>
					      	<v-layout row>
				      			<p class="subheading pl-4">
						          This is where you can find and filter all of the projects within the system.       		
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
							          Add a new project with the plus icon in the upper right corner of this card.        			
					        		</p>					        	
			        				<p class="subheading info--text pl-4">
												<v-icon left class="info--text">help_outline</v-icon>
							          You can use all of the filters at once, or simply one at a time. In addition, clicking on a heading will sort the projects accordingly.		        			
					        		</p>	
			        				<p class="subheading info--text pl-4">
												<v-icon left class="info--text">help_outline</v-icon>
							          Click on the view projects arrow in the action column to see the entire project.		        			
					        		</p>					        							        							        				        		
					        	</v-flex>				        	
					        </v-layout>		        	
				        </v-container><!-- / Container for helpful tips -->	
				        <!-- Card body -->
				        <v-card-text>				 
									<v-layout row>
										<v-flex xs12>
											<!-- Projects table component -->
											<projects-table 
												:table_state="'admin'"
												class="mt-2 mb-5"
											></projects-table>
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
	import ProjectsTable from './Projects-table';

  export default {
  	components: {
  		'projects-table': ProjectsTable
  	},

  	data () {
  		return {
  			// For add project dialog
  			addProjectDialog: false,
  			// For project form inputs
  			client_company_name: { val: '', err: false, default: '' },
  			// For dual company name input
  			client_company_name_select: '',
  			disableClientCompanyInput: false
  		}
  	},

  	computed: {
  		// The unique clients for the select inputs (Uses the store)
  		clients () {
  			return this.$store.getters.clientsSelectList;
  		},

  		// If a project has just been added (Uses the store)
  		projectAdded () {
  			return this.$store.getters.projectAdded;
  		}
  	},

		watch: {
			/* For the dual client company input setup. If a select option is selected then
			 * set the selected value to the form property and disable the text input. 
			*/
			client_company_name_select (value) {
				// If a value is present
				if(value != ''){
					this.client_company_name.val = value;
					this.disableClientCompanyInput = true;
				} else{
					// If empty string then enable text input again
					this.disableClientCompanyInput = false;
				}
			}
		},	

  	methods: {
  		// Add a project to the db via a store action
  		startProject () {
  			// Dispatch action
  			this.$store.dispatch('addProject', {
  				client_company_name: this.client_company_name.val
  			}).then( () => {
  				this.addProjectDialog = false;
  			});
  		}
  	},

  	created () {
  		// For debug
  		if(this.$store.getters.debug) console.log("Projects component created");
  	}
  }
</script>

<style scoped>
  .card--flex-toolbar {
    margin-top: -64px;
  }
</style>