<template>
	<v-container>
		<!-- Heading and add crew member button -->
		<v-layout row>
			<div class="headline">
				Timeline
			</div>
		</v-layout>	

		<!-- No crew added alert -->
		<v-layout row v-if="projectApprovalDate === null" class="mt-5">
			<v-alert info value="true">
	      Once the project has an approval date the timeline will become available.
	    </v-alert>			
		</v-layout><!-- / No crew added alert -->

		<v-layout row v-if="projectApprovalDate != null" class="mt-5">
			<v-flex xs12>
			 	<v-stepper v-model="step" vertical non-linear>
			 		<!-- Steps -->
			 		<v-container fluid v-for="current in steps" :key="step.field">
				    <v-stepper-step :step="current.step" 
				    	style="cursor:pointer;"
				    	:complete="timeline[current.field] != null && timeline[current.field] != 0" 
				    	@click.native.stop="step = current.step"

				    >
				      {{ current.heading}}
				      <small v-if="timeline[current.field] != 0 && timeline[current.field] != 1">{{ timeline[current.field] }}</small>
				      <small v-if="timeline[current.field] === null">Milestone Not Complete</small>
				      <small v-if="timeline[current.field] === 0">No</small>
				      <small v-if="timeline[current.field] === 1">Yes</small>
				    </v-stepper-step>
				    <v-stepper-content :step="current.step">
							<v-flex xs12 md6 class="mt-2 mb-2">
								<field-input-toggle
									:alt_style="true"
									:type="current.inputType"
									:action="'updateTimelineField'"
									:id="timeline.id"
									:label="current.label"
									:field="current.field"
									:value="$store.getters.currentProject.timeline[current.field]"
								></field-input-toggle>														
							</v-flex>			    	
				    </v-stepper-content>			 			
			 		</v-container><!-- /Steps -->
			  </v-stepper>			
			</v-flex>
		</v-layout>

	</v-container>
</template>

<script>
	import FieldInputToggle from './../form/Field-input-toggle';

	export default {
		components: {
			'field-input-toggle': FieldInputToggle
		},

		computed: {
			// The project timeline
			timeline () {
				return this.$store.getters.currentProject.timeline;
			},

			// Date project was approved on (or not)
			projectApprovalDate () {
				return this.$store.getters.currentProject.approval_date;
			}
		},

		data () {
			return {
				// The current milestone the project is on
				step: -1,
				// The steps/milestones for the timeline
				steps: [
					{
						step: 1,
						field: 'permit_advised_submit',
						inputType: 'date',
						label: 'Permit Advised To Submit',
						heading: 'Applicant was advised to submit permit on:'
					},
					{
						step: 2,
						field: 'permit_applicant',
						inputType: 'text',
						label: 'Permit Applicant',
						heading: 'Permit was applied for by:'
					},	
					{
						step: 3,
						field: 'permit_application_date',
						inputType: 'date',
						label: 'Permit Applicant Date',
						heading: 'Permit was applied for on:'
					},														
					{
						step: 4,
						field: 'permit_recieved_date',
						inputType: 'date',
						label: 'Permit Recieved On',
						heading: 'Permit was recieved on:'
					},										
					{
						step: 5,
						field: 'permit_number',
						inputType: 'text',
						label: 'Permit Number',
						heading: 'Permit number is:'
					},	
					{
						step: 6,
						field: 'site_number_application_date',
						inputType: 'date',
						label: 'Site Number Application Date',
						heading: 'Site number was applied for on:'
					},	
					{
						step: 7,
						field: 'site_number_recieved_date',
						inputType: 'date',
						label: 'Site Number Recieved Date',
						heading: 'Site number was recieved on:'
					},																			
					{
						step: 8,
						field: 'site_number',
						inputType: 'text',
						label: 'Site Number',
						heading: 'Site number is:'
					},												
					{
						step: 9,
						field: 'completion_target',
						inputType: 'date',
						label: 'Completion Target',
						heading: 'Completion target is:'
					},															
					{
						step: 10,
						field: 'field_completion_target',
						inputType: 'date',
						label: 'Fieldwork Completion Target',
						heading: 'Fieldwork completion target is:'
					},												
					{
						step: 11,
						field: 'report_completion_target',
						inputType: 'date',
						label: 'Report Completion Target',
						heading: 'Report completion target is:'
					},
					{
						step: 12,
						field: 'fieldwork_scheduled',
						inputType: 'bool',
						label: 'Fieldwork Scheduled',
						heading: 'Fieldwork is scheduled?'
					},
					{
						step: 13,
						field: 'artifact_analysis',
						inputType: 'bool',
						label: 'Artifact Analysis Complete',
						heading: 'Artifact analysis is complete?'
					},
					{
						step: 14,
						field: 'mapping',
						inputType: 'bool',
						label: 'Mapping Complete',
						heading: 'Mapping is complete?'
					},					
					{
						step: 15,
						field: 'writing',
						inputType: 'bool',
						label: 'Writing Complete',
						heading: 'Writing is complete?'
					},
					{
						step: 16,
						field: 'draft_submitted',
						inputType: 'bool',
						label: 'Draft Sumbitted',
						heading: 'Draft is submitted?'
					},										
					{
						step: 17,
						field: 'draft_accepted',
						inputType: 'bool',
						label: 'Draft Accepted',
						heading: 'Draft is accepted?'
					},					
					{
						step: 18,
						field: 'final_approval',
						inputType: 'bool',
						label: 'Final Approval',
						heading: 'Approval is final?'
					}					
				]
			}
		},

		created () {
			// Determine the current step
			for(let step of this.steps){
				if(this.timeline[step.field] === null || this.timeline[step.field] === 0){
					this.step = step.step;
					break;
				}
			}
		}
	}
</script>
