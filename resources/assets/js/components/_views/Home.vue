/**
 * Home landing for the dashboard.
 *
 * Shows any new notifications for the authenticated user.
*/
<template>
	<!-- Uses a card layout -->
	<card-layout
		title="Central Dashboard"
		description="Welcome to your Arrow Archaeology dashboard."
		:tips="tips"
	>
		<div slot="content">
			<p>
				Notifications Here
			</p>
		</div>
	</card-layout>
</template>

<script>
	import CardLayout from './_Card-layout';

	export default {
		components: {
			'card-layout': CardLayout
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