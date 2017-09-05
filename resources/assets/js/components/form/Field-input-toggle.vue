<template>
	<v-card class="grey lighten-5 mt-3" :flat="alt_style">

		<!-- Container for viewing field -->
		<v-container v-if="!editState">
    	<!-- Card toolbar -->
      <v-toolbar card class="white" prominent >
      	<!-- Card heading -->
        <v-toolbar-title 
        	v-if="!alt_style"
        	class="subheading grey--text"
        >				         
        	{{ label }}			          	
        </v-toolbar-title>				          				          
        <v-spacer></v-spacer>
        <!-- Edit button -->
        <v-btn 
        	icon 
        	v-tooltip:top="{ html: 'Edit Field' }"
        	@click.native="showEditInput"
        >
          <v-icon>settings</v-icon>
        </v-btn>
      </v-toolbar><!-- /Card toolbar -->
      <!-- The field value -->
      <v-card-text class="pt-0">
      	<v-layout row>
	      	<!-- If the value IS NOT a boolean -->
	      	<div v-if="!bool_field">
	      		<!-- If value then show it -->
	      		<div v-if="value" class="pl-2">
	      				<span v-if="prefix">{{ prefix }}</span>
	      				<span v-if="type != 'date'">{{ value }}</span>
	      				<span v-else>{{ value | date }}</span>
	   				</div>
	   				<!-- If no value show this -->
	      		<div v-if="!value && !alt_style" class="pl-2 error--text">
	      			N/A
	      		</div>
	   				<!-- Or show this if alt style is active -->
	      		<div v-if="!value && alt_style" class="pl-2 error--text">
	      			MILESTONE NOT COMPLETE
	      		</div>	      		
	      	</div>
	      	<!-- If IS boolean -->
	  			<div v-else>
	  				<div v-if="value === 1">Yes</div>
	  				<div v-if="value === 0">No</div>
	  			</div> 
	        <v-spacer v-if="alt_style"></v-spacer>
	  			     	
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
        	v-if="!alt_style" 
        	icon 
        	v-tooltip:top="{ html: 'Close Edit Mode' }"
        	@click.native="hideEditInput"
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
      			<v-flex xs12>
							<v-text-field
								class="pb-0"
								v-model="fieldValue"						
							  :label="label + '...'"
							  :prepend-icon="icon"
							  :prefix="prefix"
							  :error="fieldError"
							></v-text-field>      				
      			</v-flex>       			
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
      			<v-flex xs12>
							<v-text-field
								class="pb-0"
								v-model="fieldValue"						
							  :label="label + '...'"
							  :prepend-icon="icon"
							  :error="fieldError"
							  multi-line
							  :counter="char_count"
							  :max="char_count"
							></v-text-field>      				
      			</v-flex>       			
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
	      		<v-flex xs12>
			        <v-select
			        	class="pb-0"
			          v-model="fieldValue"
			          :items="select_options"
			          :label="label + '...'"
			          single-line
			          bottom
			          :error="fieldError"
			        ></v-select>  	      			
	      		</v-flex>    		
	      	</v-layout>
      		<!-- Error msg -->
      		<v-layout row>
						<p v-if="fieldError" class="caption error--text">
							{{ fieldErrorMsg }}
						</p>       			
      		</v-layout>	      	      		
      	</v-container><!-- /Select input container -->


      	<!-- Bool input container 
      	-->
      	<v-container v-if="type === 'bool'" class="pt-0 pb-0">
	      	<v-layout row>
	      		<v-flex xs12>
			        <v-select
			        	class="pb-0"
			          v-model="fieldValue"
			          :items="[
			          	{ text: 'No', value: 0 },
			          	{ text: 'Yes', value: 1 }
			          ]"
			          :label="label + '...'"
			          single-line
			          bottom
			          :error="fieldError"
			        ></v-select>  	      			
	      		</v-flex>    		
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
      			<v-flex xs12>
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
      			</v-flex>
      		</v-layout>
      	</v-container>

				<!-- Save button -->
				<v-layout row>
					<v-btn block flat outline
						:loading="loading"
						:disabled="loading"
						@click.native="updateField"
					>
		      Save					
					</v-btn>					
				</v-layout>
	      	
      </v-card-text>
		
		</v-container><!-- /Container for editing field -->

	</v-card>
</template>

<script>
	export default {

		props: {
			// For a different looking toggle
			alt_style: { type: Boolean, default: false},
			// Required. Type of input to use
			type: { type: String, required: true }, 
			// Optional. If the type is a select then it should have options to populate with.
			// Should be an object ready for a Vuetify select: { text: '...?', value: '...?' }
			select_options: { type: Array }, 
			// Required. The store action to dispatch when the save button is clicked
			action: { type: String, required: true }, 
			// Required. The db ID of the parent the field belongs to.
			id: { type: Number, required: true, default: 0 }, 
			// Required. The label text for the input and field view.
			label: { type: String, required: true }, 
			// Optional. Vuetify icon to prepend the input with
			icon: { type: String }, 			
			// Optional. A character to prepend the value with.
			prefix: { type: String }, 
			// Required. The db field name.
			field: { type: String, required: true }, 
			// Required. The current value of the field.
			value: { required: true },
			// Optional. If the field value should be interpreted as a boolean.
			bool_field: { type: Boolean, default: false },
			// Optional. The max character count a textarea should contain. Shows the counter under textarea
			char_count: { type: Number }
		},

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
				// Clear possible errors
				this.fieldError = false;
				this.fieldErrorMsg = '';				
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
				console.log(payload);
				// Dynamically add field and value to payload
				payload[this.field] = this.fieldValue;
				// Dispatch action and run cb when complete
				this.$store.dispatch(this.action, payload).then( () => {
					// Only toggle state if alt_style is NOT true
					if(!this.alt_style) {
						// Toggle state
						this.editState = false;						
					}
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

		created () {
			// If alt_style is enabled then editState is engaged
			if(this.alt_style) {
				// Enable edit state
				this.editState = true;
				// Adjust field value
				this.fieldValue = this.value;				
			}
		}



	}
</script>