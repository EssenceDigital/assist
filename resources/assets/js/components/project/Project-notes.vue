<template>
	<v-container>

		<!-- No notes info -->
		<v-layout row v-if="notes.length === 0">
			<v-alert info value="true">
	      No notes have been added to this project
	    </v-alert>			
		</v-layout>

		<!-- Current notes. For each note create a card -->
		<v-card flat
			v-else
			v-for="note in $store.getters.currentProject.comments" :key="note.id"
			class="grey lighten-5"
		>
    	<!-- Card toolbar -->
      <v-toolbar card class="white" prominent>
      	<!-- Card heading -->
        <v-toolbar-title class="subheading grey--text">				         
        	{{ note.user.first + ' ' + note.user.last }} says:          	
        </v-toolbar-title>				          				          
        <v-spacer></v-spacer>
        <!-- Trigger for delete note dialog -->
        <v-btn 
        	v-if="note.user.id === $store.getters.user.id"
        	class="mr-0 red--text" 
        	icon 
        	v-tooltip:top="{ html: 'Delete note' }"
        	@click.native.stop="openDeleteNoteDialog(note.id)"
        >
          <v-icon>close</v-icon>
        </v-btn>

      </v-toolbar><!-- /Card toolbar -->
      <!-- The field value -->
      <v-card-text class="pt-0">
      	<v-layout row>
          <v-flex xs12>
						<v-chip xs12 class="info white--text">
							{{ note.comment }}
						</v-chip>
          </v-flex>	     			
      	</v-layout>

      </v-card-text><!-- /The field value -->
		</v-card>	<!-- /Current notes -->

		<v-divider class="mt-5 mb-5"></v-divider>

    <!-- Remove note dialog and button -->
		<v-layout row justify-center 
			class="mr-0" 
			style="position: relative;"
		>
	    <v-dialog v-model="deleteNoteDialog" width="365" lazy absolute>	    
	      <v-card>
	        <v-card-title>
	          <div class="headline grey--text">Delete Note?</div>									          
	        </v-card-title>
	        <v-divider></v-divider>
	        <v-card-text>
	        	Delete this note until the age that gave it birth comes again?
	        </v-card-text>
	        <v-card-actions>
	          <v-spacer></v-spacer>
	          <!-- Cancel delete button-->
	          <v-btn outline 
	          	class="red--text darken-1" 
	          	flat="flat" 
	          	@click.native.stop="deleteNoteDialog = false">
	          		Maybe not
	          </v-btn>
	          <!-- Confirm delete button -->
	          <v-btn outline 
	          class="green--text darken-1" 
	          flat="flat" 
	          :loading="deletingNote" 
	          :disable="deletingNote" 
	          @click.native.stop="deleteNote(selectedNoteId)">
	          	Do it
	          </v-btn>
	        </v-card-actions>
	      </v-card>
	    </v-dialog>
	  </v-layout><!-- Remove note dialog and button -->

		<!-- Add note form -->
		<v-card class="grey lighten-5 mt-3">
			<!-- Container for viewing field -->
			<v-container>
	    	<!-- Card toolbar -->
	      <v-toolbar card class="white" prominent>
	      	<!-- Card heading -->
	        <v-toolbar-title class="subheading grey--text">				         
	        	Add a Note	          	
	        </v-toolbar-title>				          				          
	        <v-spacer></v-spacer>

	      </v-toolbar><!-- /Card toolbar -->
	      <!-- The field value -->
	      <v-card-text class="pt-0">
	      	<v-layout row>
            <v-flex xs12>
              <v-text-field
              	v-model="note"
              	:error="noteErr"
                label="Enter note..."
                counter
                max="255"
                textarea
              ></v-text-field>
            </v-flex>	     			
	      	</v-layout>
      		<!-- Error msg -->
      		<v-layout v-if="noteErr" row class="caption error--text">
      			<v-flex xs12 class="text-center">
      				{{ noteErrMsg }}  
      			</v-flex>
						    			
      		</v-layout>	      	
	      	<v-layout row>
	      		<v-flex xs4 offset-xs4>
	      			<v-btn block flat outline
	      				:loading="loading"
	      				:disabled="loading"
	      				@click="addNote"
	      			>
	      				Add Note
	      			</v-btn>
	      		</v-flex>
	      	</v-layout>
	      </v-card-text><!-- /The field value -->
			</v-container><!-- /Container for viewing field -->
		</v-card>	<!-- /Add note form -->	

	</v-container>

</template>

<script>
	export default {

		data () {
			return {
				// The note on que for deletion
				selectedNoteId: '',
				// For the delete dialog
				deleteNoteDialog: false,
				// To show when a note is deleting
				deletingNote: false,
				// To show when a note is saving
				loading: false,
				// The note value
				note: '',
				// If the server returned an error when saving
				noteErr: false,
				// The error message
				noteErrMsg: ''
			}
		},

		computed: {
			// Retrieve notes for current project from store
			notes () {
				return this.$store.getters.currentProject.comments
			}
		},

		methods: {
			// Opens the delete note dialog and sets the selected note id
			openDeleteNoteDialog (id) {
				// Set the selected note id in que for deletion
				this.selectedNoteId = id;
				// Open the dialog
				this.deleteNoteDialog = true;
			},
			
			// Uses the store to add a note to the current project
			addNote () {
				// Toggle loader
				this.loading = true;
				// Dispatch the action with post payload
				this.$store.dispatch('addProjectComment', {
					project_id: this.$store.getters.currentProject.id,
					user_id: this.$store.getters.user.id,
					comment: this.note
				}).then( () => {
					// Toggle loader
					this.loading = false;
					// Clear data values
					this.note = '';
					this.noteErr = false;
					this.noteErrMsg = '';
				})
				.catch( (error) => {
					// Toggle error state and set error message
					this.noteErr = true;
					this.noteErrMsg = error.response.data.comment[0];
					this.loading = false;
				});
			},

			// Uses the store to delete selected note from the db
			deleteNote (id) {
				console.log(id);
				this.deletingNote = true;
				// Dispatch action with note id as payload
				this.$store.dispatch('deleteProjectComment', {
					id: id
				}).then( () => {
					// Toggle loaderand dialog
					this.deletingNote = false;
					this.deleteNoteDialog = false;
				});
			}
		}

	}
</script>

<style scoped>
	.chip {
		height: auto;
		white-space: normal;
		padding: 8px 16px 8px 16px;
	}
	.text-center {
		text-align: center;
	}
</style>