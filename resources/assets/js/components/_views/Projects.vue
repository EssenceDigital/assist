/**
 * Main projects page. Shows the add project button and projects table.
*/
<template>
	<card-layout
		:tips="tips"
	>
		<div slot="title">
			Find &amp; Add Projects
		</div>
    <div slot="dialog">
      <!-- Add project dialog -->
      <v-layout row justify-center style="position: relative;" class="mr-0">
        <v-dialog v-model="addProjectDialog" width="765" lazy absolute>
          <!-- Add project button -->
          <v-btn slot="activator" flat class="success--text pr-5 mr-2" icon v-tooltip:top="{ html: 'Add Project' }">
            <v-icon left>add_circle</v-icon>
            Project
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
              <v-btn outline class="green--text darken-1" flat="flat" 
                @click.native="startProject"
                :loading="startingProject"
                :disabled="startingProject"
              >Start Project</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-layout><!-- / Add project dialog -->      
    </div>
		<div slot="description">
			This is where you can find and filter all of the projects within the system.
		</div>
    <div slot="additional">
      <v-select
        :items="[
          { text: 'Work View', value: 'admin_work' },
          { text: 'Manage View', value: 'admin_manage' }
        ]"
        v-model="tableState"
        label="Table State..."
        single-line
        auto
        prepend-icon="find_in_page"
        hide-details
        class="pt-0 pr-3"
      >
      </v-select>       
    </div>
		<div slot="content">
			<!-- Projects table component -->
			<projects-table 
				:table_state="tableState"
				class="mt-2 mb-5"
			></projects-table>			
		</div>
	</card-layout>
</template>

<script>
	import CardLayout from './_Card-layout';
	import ProjectsTable from './../project/Projects-table';

  export default {
  	components: {
  		'card-layout': CardLayout,
  		'projects-table': ProjectsTable
  	},

  	data () {
  		return {
  			// For card layout tips
  			tips: [
  				{ text: 'Add a new project with the plus icon in the upper right corner of this card.' },
          { text: 'The view filter in the upper right hand corner will switch between work and manage states.' },
  				{ text: 'You can search your projects using the filter below. The filters can be used one at a time or all at once.' } ,
          { text: "Clicking on a heading will sort the invoices accordingly." },
  				{ text: 'Click on the view projects arrow in the action column to see the entire project.' }
  			],
  			// For add project dialog
  			addProjectDialog: false,

  			tableState: 'admin_work',

  			// Project adding loader
  			startingProject: false,
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
  			// Toggle loader
  			this.startingProject = true;
  			// Dispatch action
  			this.$store.dispatch('addProject', {
  				client_company_name: this.client_company_name.val
  			}).then( () => {
  				// Toggle loader and dialog
  				this.startingProject = false;
  				this.addProjectDialog = false;
  				// Redirect
  				this.$router.push('/projects/'+this.$store.getters.currentProject.id+'/view');
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