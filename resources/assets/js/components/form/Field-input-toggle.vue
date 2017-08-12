<template>
	<v-card class="grey lighten-5 mt-3">

		<!-- Container for viewing field -->
		<v-container v-if="!editState">
    	<!-- Card toolbar -->
      <v-toolbar card class="white" prominent>
      	<!-- Card heading -->
        <v-toolbar-title class="subheading grey--text">				         
        	{{ label }}			          	
        </v-toolbar-title>				          				          
        <v-spacer></v-spacer>
        <!-- Edit button -->
        <v-btn 
        	icon 
        	v-tooltip:top="{ html: 'Edit Field' }"
        	@click="showEditInput"
        >
          <v-icon>settings</v-icon>
        </v-btn>
      </v-toolbar><!-- /Card toolbar -->
      <!-- The field value -->
      <v-card-text class="pt-0">
      	<v-layout row>
      		<div v-if="value" class="pl-2">
      			<span v-if="prefix">{{ prefix }}</span>{{ value }}
      		</div>
      		<div v-else class="pl-2 error--text">
      			N/A
      		</div>
      	</v-layout>
      </v-card-text><!-- /The field value -->
		</v-container><!-- /Container for viewing field -->

		<!-- Container for editing field -->
		<v-container v-if="editState" class="pt-0 pb-0">
    	<!-- Card toolbar -->
      <v-layout row>			          
        <v-spacer></v-spacer>
        <!-- Edit button -->
        <v-btn 
        	icon 
        	v-tooltip:top="{ html: 'Close Edit Mode' }"
        	@click="hideEditInput"
        >
          <v-icon>close</v-icon>
        </v-btn>
      </v-layout><!-- /Card toolbar -->
      <v-card-text class="pt-0 pb-0">

      <!-- Input fields - Text, Select, Date 
      **************************************
      -->
      	<!-- Text input container 
      	-->
      	<v-container v-if="type === 'text'" class="pt-0 pb-0">
      		<!-- Input -->
      		<v-layout row>
						<v-text-field
							class="pb-0"
							v-model="fieldValue"						
						  :label="label + '...'"
						  :prepend-icon="icon"
						  :error="fieldError"
						></v-text-field>       			
      		</v-layout>
      		<!-- Error msg -->
      		<v-layout row>
						<p v-if="fieldError" class="caption error--text">
							{{ fieldErrorMsg }}
						</p>       			
      		</v-layout>
      	</v-container><!-- /Text input container -->

      	<!-- Text area input container 
      	-->
      	<v-container v-if="type === 'textarea'" class="pt-0 pb-0">
      		<!-- Input -->
      		<v-layout row>
						<v-text-field
							class="pb-0"
							v-model="fieldValue"						
						  :label="label + '...'"
						  :prepend-icon="icon"
						  :error="fieldError"
						  multi-line
						></v-text-field>       			
      		</v-layout>
      		<!-- Error msg -->
      		<v-layout row>
						<p v-if="fieldError" class="caption error--text">
							{{ fieldErrorMsg }}
						</p>       			
      		</v-layout>
      	</v-container><!-- /Text area input container -->

      	<!-- Select input container 
      	-->
      	<v-container v-if="type === 'select'" class="pt-0 pb-0">
	      	<v-layout row>
		        <v-select
		        	class="pb-0"
		          v-model="fieldValue"
		          :items="select_options"
		          :label="label + '...'"
		          single-line
		          bottom
		          :error="fieldError"
		        ></v-select>      		
	      	</v-layout>
      		<!-- Error msg -->
      		<v-layout row>
						<p v-if="fieldError" class="caption error--text">
							{{ fieldErrorMsg }}
						</p>       			
      		</v-layout>	      	      		
      	</v-container><!-- /Select input container -->


      	<!-- Date input container -->
      	<v-container v-if="type === 'date'" class="pt-0 pb-0">
      		<v-layout row>
						<v-menu
						  lazy
						  :close-on-content-click="false"
						  v-model="dateMenu"
						  transition="scale-transition"
						  offset-y
						  full-width
						  :nudge-left="40"
						  max-width="290px"
						>
						  <v-text-field
						    slot="activator"
						    v-model="fieldValue"
						    :label="label + '...'"
						    prepend-icon="event"
						    readonly
						  ></v-text-field>
						  <v-date-picker v-model="fieldValue" no-title scrollable actions>
						    <template scope="{ save, cancel }">
						      <v-card-actions>
						        <v-btn flat primary @click.native="cancel()">Cancel</v-btn>
						        <v-btn flat primary @click.native="save()">Save</v-btn>
						      </v-card-actions>
						    </template>
						  </v-date-picker>
						</v-menu>      			
      		</v-layout>
      	</v-container>

				<!-- Save button -->
				<v-layout row>
					<v-btn block flat outline
						:loading="loading"
						:disabled="loading"
						@click="updateField"
					>
		      Save
		      <span slot="loader" class="custom-loader">
		        <v-icon light>update</v-icon>
		      </span>					
					</v-btn>					
				</v-layout>
	      	
      </v-card-text>
		
		</v-container><!-- /Container for editing field -->

	</v-card>
</template>

<script>
	export default {

		props: ['type', 'select_options', 'icon', 'action', 'id', 'label', 'prefix', 'field', 'value'],

		data () {
			return {
				// Controls showing the field or the input
				editState: false,
				// For date picker
				dateMenu: false,
				// For loader
				loading: false,
				// The new field value
				fieldValue: '',
				fieldError: false,
				fieldErrorMsg: ''
			}
		},

		methods: {
			// Shows the field input
			showEditInput () {
				// Adjust field value
				this.fieldValue = this.value;
				// Adjust state to edit
				this.editState = true;
			},

			// Hides the field input
			hideEditInput () {
				// Toggle state
				this.editState = false;
				// Clear possible errors
				this.fieldError = false;
				this.fieldErrorMsg = '';

			},

			// Uses the store to update the field in the db
			updateField () {
				// Toggle loader
				this.loading = true;
				// Start the payload
				var payload = {
					id: this.id,
					field: this.field
				};
				// Dynamically add field and value to payload
				payload[this.field] = this.fieldValue;
				// Dispatch action and run cb when complete
				this.$store.dispatch(this.action, payload)
				.then( () => {
					// Toggle state
					this.editState = false;
					// Toggle loader
					this.loading = false;
				})
				.catch( (error) => {
					// Set error props
					this.fieldError = true;
					this.fieldErrorMsg = error.response.data[this.field][0];
					// Hide loader
					this.loading = false;					
				});
			}
		},



	}
</script>