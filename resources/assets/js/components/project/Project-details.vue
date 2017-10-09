<template>
	<v-layout row>
		<v-flex xs12>
			<!-- Client details -->
			<v-container fluid>
				<div class="headline">
					Client
				</div>
				<!-- Row one -->
				<v-layout row>
					<v-flex xs4>
						<field-input-toggle
							:type="'select'"
							:select_options="clientsSelectList"
							:action="'updateProjectField'"
							:id="project.id"
							:label="'Client Company'"
							:field="'client_company_name'"
							:value="project.client_company_name"
						></field-input-toggle>
					</v-flex>													
				</v-layout>
				<!-- Row two -->
				<v-layout row class="mt-5">
					<v-flex xs4>
						<field-input-toggle
							:type="'text'"
							:action="'updateProjectField'"
							:id="project.id"
							:label="'Company Contact'"
							:field="'client_contact_name'"
							:value="project.client_contact_name"
						></field-input-toggle>
					</v-flex>
					<v-flex xs4>
						<field-input-toggle
							:type="'text'"
							:action="'updateProjectField'"
							:id="project.id"
							:label="'Contact\'s Phone No.'"
							:icon="'phone'"
							:field="'client_contact_phone'"
							:value="project.client_contact_phone"
						></field-input-toggle>
					</v-flex>
					<v-flex xs4>
						<field-input-toggle
							:type="'text'"
							:action="'updateProjectField'"
							:id="project.id"
							:label="'Contact\'s Email'"
							:field="'client_contact_email'"
							:value="project.client_contact_email"
						></field-input-toggle>
					</v-flex>
				</v-layout>	
			</v-container><!-- /Client details -->

			<v-divider class="mt-5"></v-divider>

			<!-- Location details -->
			<v-container fluid class="mt-5">
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
							:id="project.id"
							:label="'Province'"
							:field="'province'"
							:value="project.province"
						></field-input-toggle>														
					</v-flex>
					<v-flex xs8>
						<field-input-toggle
							:type="'textarea'"
							:action="'updateProjectField'"
							:id="project.id"
							:label="'Specific Location'"
							:field="'location'"
							:value="project.location"
							:char_count="100"
						></field-input-toggle>														
					</v-flex>
				</v-layout>
			</v-container><!-- /Location details -->

			<v-divider class="mt-5"></v-divider>

			<!-- Basic details -->
			<v-container fluid class="mt-5">
				<div class="headline">
					Basics
				</div>
				<v-layout row>
					<v-flex xs12>
						<field-input-toggle
							:type="'textarea'"
							:action="'updateProjectField'"
							:id="project.id"
							:label="'Basic Details'"
							:field="'details'"
							:value="project.details"
							:char_count="750"
						></field-input-toggle>														
					</v-flex>
				</v-layout>
			</v-container><!-- /Basic details -->		

			<v-divider class="mt-5"></v-divider>

			<!-- Work details -->
			<v-container fluid class="mt-5">
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
							:id="project.id"
							:label="'Work Type'"
							:field="'work_type'"
							:value="project.work_type"
						></field-input-toggle>														
					</v-flex>
					<v-flex xs4>
						<field-input-toggle
							:type="'date'"
							:action="'updateProjectField'"
							:id="project.id"
							:label="'Respond By'"
							:field="'response_by'"
							:value="project.response_by"
						></field-input-toggle>														
					</v-flex>
					<v-flex xs4>
						<field-input-toggle
							:type="'text'"
							:action="'updateProjectField'"
							:id="project.id"
							:label="'Estimate'"
							:field="'estimate'"
							:prefix="'$'"
							:value="project.estimate"
						></field-input-toggle>														
					</v-flex>													
				</v-layout>
				<!-- Row two -->
				<v-layout row>
					<v-flex xs12>
						<field-input-toggle
							:type="'textarea'"
							:action="'updateProjectField'"
							:id="project.id"
							:label="'Work Overview'"
							:field="'work_overview'"
							:value="project.work_overview"
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
							:id="project.id"
							:label="'Work Plans'"
							:field="'plans'"
							:value="project.plans"
							:char_count="750"
						></field-input-toggle>														
					</v-flex>
				</v-layout>												
			</v-container><!-- /Basic details -->		

			<v-divider class="mt-5"></v-divider>

			<!-- Land details -->
			<v-container fluid class="mt-5">
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
							:id="project.id"
							:label="'Land Ownership'"
							:field="'land_ownership'"
							:value="project.land_ownership"
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
							:id="project.id"
							:label="'Access Granted'"
							:field="'land_access_granted'"
							:value="project.land_access_granted"
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
							:id="project.id"
							:label="'Access Granted By'"
							:field="'land_access_granted_by'"
							:value="project.land_access_granted_by"
						></field-input-toggle>														
					</v-flex>													
					<v-flex xs4>
						<field-input-toggle
							:type="'text'"
							:action="'updateProjectField'"
							:id="project.id"
							:label="'Access Contact'"
							:field="'land_access_contact'"
							:value="project.land_access_contact"
						></field-input-toggle>														
					</v-flex>	
					<v-flex xs4>
						<field-input-toggle
							:type="'text'"
							:icon="'phone'"
							:action="'updateProjectField'"
							:id="project.id"
							:label="'Contact\'s Phone'"
							:field="'land_access_phone'"
							:value="project.land_access_phone"
						></field-input-toggle>														
					</v-flex>																									
				</v-layout>
			</v-container><!-- /Land details -->

			<v-divider class="mt-5"></v-divider>

			<!-- Approval details -->
			<v-container fluid class="mt-5">
				<div class="headline">
					Approval
				</div>										
				<v-layout row>
					<v-flex xs4>
						<field-input-toggle
							:type="'date'"
							:action="'updateProjectField'"
							:id="project.id"
							:label="'Approval Date'"
							:field="'approval_date'"
							:value="project.approval_date"
						></field-input-toggle>														
					</v-flex>													
				</v-layout>
			</v-container><!-- / Approval details -->

			<v-divider class="mt-5"></v-divider>

			<!-- Invoicing details -->
			<v-container fluid class="mt-5">
				<div class="headline">
					Invoicing
				</div>
				<v-layout row class="mt-3">
					<v-flex xs12>
    				<p class="subheading info--text pl-4">
							<v-icon left class="info--text">help_outline</v-icon>
		         	These fields are used for all sorts of useful calculations so be sure to fill them as the project progresses.      			
        		</p>						
					</v-flex>
				</v-layout>												
				<v-layout row>
					<v-flex xs4>
						<field-input-toggle
							:type="'date'"
							:action="'updateProjectField'"
							:id="project.id"
							:label="'Invoiced Date'"
							:field="'invoiced_date'"
							:value="project.invoiced_date"
						></field-input-toggle>														
					</v-flex>	
					<v-flex xs4>
						<field-input-toggle
							:type="'date'"
							:action="'updateProjectField'"
							:id="project.id"
							:label="'Invoice Paid Date'"
							:field="'invoice_paid_date'"
							:value="project.invoice_paid_date"
						></field-input-toggle>														
					</v-flex>
					<v-flex xs4>
						<field-input-toggle
							:type="'text'"
							:icon="'attach_money'"
							:action="'updateProjectField'"
							:id="project.id"
							:label="'Invoice Amount'"
							:field="'invoice_amount'"
							:prefix="'$'"
							:value="project.invoice_amount"
						></field-input-toggle>														
					</v-flex>																						
				</v-layout>
			</v-container><!-- /Invoicing details -->		
			<v-divider class="mt-5"></v-divider>
		</v-flex>
	</v-layout><!-- Project view layout -->		
</template>

<script>
	import FieldInputToggle from './../form/Field-input-toggle';

	export default {
		props: ['project', 'clientsSelectList'],

		components: {
			'field-input-toggle': FieldInputToggle
		}
	}
</script>