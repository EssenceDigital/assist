<template>
	<v-container fluid v-if="crew">

		<!-- Heading and add crew member button -->
		<v-layout row>
			<div class="headline">
				Crew
			</div>
			<v-spacer></v-spacer>
      <v-btn 
      	@click.native.stop="addCrewDialog = true"
      	icon 
      	v-tooltip:top="{ html: 'Add crew member' }"
      	class="green--text mr-3"
      >
        <v-icon left>add_circle</v-icon>
        Crew
      </v-btn>						
		</v-layout>
		<v-layout row>
			<p class="subheading info--text pl-4">
				<v-icon left class="info--text">help_outline</v-icon>
        You must add a user to the project crew before they can invoice this project.       			
  		</p>			
		</v-layout>
		<!-- No crew added alert -->
		<v-layout row v-if="crew.length === 0" class="mt-5">
			<v-alert info value="true">
	      No crew members have been added to this project yet.
	    </v-alert>			
		</v-layout><!-- / No crew added alert -->

		<!-- Crew table -->
		<v-layout row v-if="crew.length > 0">
      <!-- Data table -->
      <v-data-table
          :headers="headers"
          :items="crew"
          hide-actions
          class="elevation-1 mt-5"
        >    
        <template slot="items" scope="props">
          <td class="text-xs-center">{{ props.item.first + ' ' + props.item.last }}</td>
          <td class="text-xs-center">
          	<!-- Remove crew button -->
            <v-btn 
              icon 
              v-tooltip:top="{ html: 'Remove crew'}"
              class="red--text"
              @click.native.stop="showDeleteCrewDialog(props.item.id)"
            >
              <v-icon>close</v-icon>
            </v-btn>
            <!-- View timesheets button -->          
            <v-btn 
              icon 
              v-tooltip:top="{ html: 'View timesheets'}"
              class="success--text"
            >
              <v-icon>subdirectory_arrow_right</v-icon>
            </v-btn>            
          </td>                
        </template>    
      </v-data-table><!-- /Data table -->  					
		</v-layout><!-- /Crew table -->

    <!-- Add crew member dialog and button -->
		<v-layout row justify-center 
			class="mr-0" 
			style="position: relative;"
		>
	    <v-dialog v-model="addCrewDialog" width="365" lazy absolute>	    
	      <v-card>
	        <v-card-title>
	          <div class="headline grey--text">Add Crew Member</div>									          
	        </v-card-title>
	        <v-divider></v-divider>
	        <v-card-text>
	        	<v-layout row>
				      <v-flex xs12>
				        <v-select
				          v-model="selctedUserId"
				          :items="crewSelectList"
				          label="User..."
				          single-line
				          bottom
				        ></v-select>        
				      </v-flex>
				    </v-layout>	        	
	        </v-card-text>
	        <v-card-actions>
	          <v-spacer></v-spacer>
	          <!-- Cancel button-->
	          <v-btn outline 
	          	class="red--text darken-1" 
	          	flat="flat" 
	          	@click.native.stop="addCrewDialog = false">
	          		Maybe Not
	          </v-btn>
	          <!-- Add crew button-->
	          <v-btn outline 
	          class="green--text darken-1" 
	          flat="flat" 
	          :loading="loading" 
	          :disable="loading" 
	          @click.native.stop="addCrewMember">
	          	Add
	          </v-btn>
	        </v-card-actions>
	      </v-card>
	    </v-dialog>
	  </v-layout><!-- /Add crew member dialog and button -->

    <!-- Delete crew dialog and button -->
		<v-layout row justify-center 
			class="mr-0" 
			style="position: relative;"
		>
	    <v-dialog v-model="deleteCrewDialog" width="365" lazy absolute>	    
	      <v-card>
	        <v-card-title>
	          <div class="headline grey--text">Delete crew?</div>									          
	        </v-card-title>
	        <v-divider></v-divider>
	        <v-card-text>
	        	Delete this crew member from the project?
	        </v-card-text>
	        <v-card-actions>
	          <v-spacer></v-spacer>
	          <!-- Cancel delete button-->
	          <v-btn outline 
	          	class="red--text darken-1" 
	          	flat="flat" 
	          	@click.native.stop="deleteCrewDialog = false">
	          		Maybe not
	          </v-btn>
	          <!-- Confirm delete button -->
	          <v-btn outline 
	          class="green--text darken-1" 
	          flat="flat" 
	          :loading="deletingCrew" 
	          :disable="deletingCrew" 
	          @click.native.stop="deleteCrew">
	          	Do it
	          </v-btn>
	        </v-card-actions>
	      </v-card>
	    </v-dialog>
	  </v-layout><!-- /Delete crew dialog and button -->  
		
	</v-container>	
</template>

<script>
	export default {
		props: ["crew"],
		
		data () {
			return {
				// Indicates when a crew member is saving to db
				loading: false,
				// For the add crew dialog
				addCrewDialog: false,
				// The selected user to add
				selctedUserId: '',
				// The selcted user to delete
				userToDeleteId: '',
				// For the remove crew dialog
				deleteCrewDialog: false,
				// Indicates when a crew member is deleting
				deletingCrew: false,
				// For the data table
	      headers: [
	        { text: 'Name', value: 'name', align: 'center' },
	        { text: 'Actions', value: '', align: 'center' }
	      ]				
			}
		},

		computed: {
			crewSelectList () {
				var users = this.$store.getters.users,
						crew = this.$store.getters.currentProject.users,
						crewIdList = [],
	          crewSelect = [{ text: "Select user...", value: "" }];
	      // Collect the IDs of all crew assigned to this project
	      crew.forEach(function(user){
	      	crewIdList.push(user.id);
	      });
	      // Create crew select array filtering out crew members already added to project
	      users.forEach(function(user){
	      	// If the current user id itteration is not in the crew ID list then push it
	      	// to the select list
	      	if(!crewIdList.includes(user.id)){
	      		crewSelect.push({ text: user.first + ' ' + user.last, value: user.id });
	      	}
	      });		
	      return crewSelect;							
			}
		},

		methods: {
			// Dispatch event to store that adds a user to the project
			addCrewMember () {
				console.log(this.selctedUserId);
				// Toggle loader
				this.loading = true;
				// Dispatch action to add user to project crew
				this.$store.dispatch('addProjectCrew', {
					project_id: this.$store.getters.currentProject.id,
					user_id: this.selctedUserId
				}).then( () => {
					// Toggle loader, close dialog, reset selected user id
					this.loading = false;
					this.addCrewDialog = false;
					this.selctedUserId = '';
				});
			},

			// Shows the delete crew dialog and sets the user to delete id
			showDeleteCrewDialog (id) {
				// Toggle dialog
				this.deleteCrewDialog = true;
				// Set selected user id
				this.userToDeleteId = id;
			},

			// Dispatch event to store that delets the user from the project
			deleteCrew () {
				// Toggle loader
				this.deletingCrew = true;
				// Dispatch even to store
				this.$store.dispatch('deleteProjectCrew', {
					project_id: this.$store.getters.currentProject.id,
					id: this.userToDeleteId
				}).then( () => {
					// Toggle loader
					this.deletingCrew = false;
					// Toggle dialog
					this.deleteCrewDialog = false;
				});
			}
		},

		created () {
			// Update the users in store state
			this.$store.dispatch('getUsers');
		}
	}
</script>