<template>
	<card-layout
		:tips="tips"
	>
		<div slot="title">
			Project Details ({{ currentProject.id }})	
		</div>
		<div slot="description">
			This is where you can edit and track the project you've selected.				
		</div>
		<div slot="content">
			<v-layout row>
				<!-- Tabs for displaying project info -->
				<v-tabs dark fixed centered v-model="active">
		    	<!-- Tab links -->
		      <v-tabs-bar class="secondary">
		        <!-- Details tab link -->
		        <v-tabs-item
		          key="1"
		          href="#tab-details"
		        >
		          Details
		        </v-tabs-item><!-- / Details tab link -->
		        <!-- Notes tab link -->
		        <v-tabs-item
		          key="2"
		          href="#tab-notes"
		        >
		          Notes / Comments
		        </v-tabs-item><!-- / Notes tab link -->	
		        <!-- Crew tab link -->
		        <v-tabs-item
		          key="3"
		          href="#tab-crew"
		        >
		          Crew Members
		        </v-tabs-item><!-- / Crew tab link -->
		        <!-- Timeline tab link -->
		        <v-tabs-item
		          key="4"
		          href="#tab-timeline"
		        >
		          Timeline
		        </v-tabs-item><!-- / Timeline tab link -->	
		        <!-- Hours / costs tab link -->
		        <v-tabs-item
		          key="5"
		          href="#tab-hours"
		        >
		          Hours / Costs
		        </v-tabs-item><!-- / Hours / costs tab link -->			        
		        <!-- Active tab indicator -->
		       	<v-tabs-slider class="primary"></v-tabs-slider>			        				        		        
		      </v-tabs-bar><!-- / Tab links -->

			    <!-- Tab items content -->
			    <v-tabs-items>
			    	<!-- Details tab content -->
			      <v-tabs-content
			        key="1"
			        id="tab-details"
			      >
			        <v-card flat>
			          <v-card-text>
			          	<project-details
			          		:project="currentProject"
			          		:clientSelectList="clientsSelectList"
			          	></project-details>
			          </v-card-text>
			        </v-card>
			      </v-tabs-content><!-- / Details tab content -->
			    	<!-- Notes tab content -->
			      <v-tabs-content
			        key="2"
			        id="tab-notes"
			      >
			        <v-card flat>
			          <v-card-text>
			          	<project-notes
			          		:notes="currentProject.comments"
			          	></project-notes>
			          </v-card-text>
			        </v-card>
			      </v-tabs-content><!-- / Notes tab content -->		
			    	<!-- Crew tab content -->
			      <v-tabs-content
			        key="3"
			        id="tab-crew"
			      >
			        <v-card flat>
			          <v-card-text>
			          	<project-crew
			          		:crew="currentProject.users"
			          	></project-crew>
			          </v-card-text>
			        </v-card>
			      </v-tabs-content><!-- / Crew tab content -->	
			    	<!-- Timeline tab content -->
			      <v-tabs-content
			        key="4"
			        id="tab-timeline"
			      >
			        <v-card flat>
			          <v-card-text>
			          	<project-timeline
			          		:timeline="currentProject.timeline"
			          		:projectApprovalDate="currentProject.approval_date"
			          	></project-timeline>
			          </v-card-text>
			        </v-card>
			      </v-tabs-content><!-- / Timeline tab content -->	
			    	<!-- Hours / costs tab content -->
			      <v-tabs-content
			        key="5"
			        id="tab-hours"
			      >
			        <v-card flat>
			          <v-card-text>
			          	<project-hours-costs
			          		:workItems="currentProject.work_items"
			          	></project-hours-costs>
			          </v-card-text>
			        </v-card>
			      </v-tabs-content><!-- / Hours / costs tab content -->			      			      			      	      
			    </v-tabs-items><!-- / Tab items content -->
				</v-tabs><!-- / Tabs for displaying project info -->				
			</v-layout>				
		</div>
	</card-layout>
</template>

<script>
	import CardLayout from './_Card-layout';
	import ProjectDetails from './../project/Project-details';
	import ProjectNotes from './../project/Project-notes';
	import ProjectCrew from './../project/Project-crew';
	import ProjectTimeline from './../project/Project-timeline';
	import ProjectHoursCosts from './../project/Project-hours-costs';

	export default {
		props: ['id'],

		components: {
			'card-layout': CardLayout,
			'project-details': ProjectDetails,
			'project-notes': ProjectNotes,
			'project-crew': ProjectCrew,
			'project-timeline': ProjectTimeline,
			'project-hours-costs': ProjectHoursCosts
		},

		data () {
			return {
				// For loading
				loading: false,
				// For tabs
				active: null,
				// Tips for card layout
				tips: [
					{ text: "To edit a field just click the gear icon next to that field. " }
				]
			}
		},

		computed: {
			// The current project
			currentProject () {
				return this.$store.getters.currentProject;
			},

      // Watch for uniqueCLients state to update
      clientsSelectList () {
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
  .card--flex-toolbar {
    margin-top: -64px;
  }	
</style>