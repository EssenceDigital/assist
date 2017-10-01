/**
 * Home landing for the dashboard.
 *
 * Shows any new notifications for the authenticated user.
*/
<template>
	<!-- Uses a card layout -->
	<card-layout
		:tips="tips"
	>
		<div slot="title">
			Central Dashboard
		</div>
		<div slot="description">
			Welcome to your Arrow Archaeology dashboard.
		</div>
		<div slot="content">

			<v-container 
				v-for="notif in notifications"
				fluid
				class="mt-1"
				:key="id"
			>
				<v-alert 
					icon="new_releases"
					success 
					value="true"
					class="pa-2"
				>
					<v-layout row>
						<v-flex xs10>
							<p class="title mt-3 mb-1">{{ notif.title }}</p>
							<p class="subheading">{{ notif.desc }}</p>
						</v-flex>	
						<v-flex xs2 class="text-xs-right mt-3">
							<v-btn outline>
								<v-icon left>close</v-icon>
								Dismiss
							</v-btn>
						</v-flex>				
					</v-layout>
		    </v-alert>				
			</v-container>

		</div>
	</card-layout>
</template>

<script>
	import CardLayout from './_Card-layout';

	export default {
		components: {
			'card-layout': CardLayout
		},

		computed: {
			notifications () {
				return this.$store.getters.notifications;
			}
		},

		data () {
			return {
				// For the card layout
				tips: [
					{ text: 'Use the top menu to navigate the application.' }
				]
			}
		}
	}
</script>

<style scoped>
  .card--flex-toolbar {
    margin-top: -64px;
  }
</style>