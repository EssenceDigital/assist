/** 
 * Notify-wrapper
 *
 * Used as a wrapper for all components that pole the db for new user notifications.
*/
<template>
	<div>
		<!-- Slot that mounts content components -->
		<slot name="content"></slot>
	</div>
</template>

<script>
	export default{
		/** 
		 * Pole the db for notifications.
		*/
		created () {
			console.log('Notify-wrapper created');
			// Dispatch action to update notifications
			this.$store.dispatch('getNotifications');
		}
	}
</script>