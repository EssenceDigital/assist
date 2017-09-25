<template>
	<card-layout
		:tips="tips"
	>
		<div slot="title">
			Crew Invoices
		</div>
		<div slot="description">
			This is where you can view and interact with your crew's invoices.
		</div>
		<invoices-table 
			slot="content"
			:table_state="'admin'"
		></invoices-table>
	</card-layout>
</template>

<script>
	import CardLayout from './_Card-layout';
	import Helpers from './../../store/helpers';
	import InvoicesTable from './../invoice/Invoices-table';

  export default {
  	components: {
  		'card-layout': CardLayout,
  		'invoices-table': InvoicesTable
  	},

  	data () {
  		return {
  			tips: [
  				{ text: "Use the view button on each invoice row to view the full invoice." }
  			]
  		}
  	},

  	methods: {

  	}

  }
</script>

<style scoped>
  .card--flex-toolbar {
    margin-top: -64px;
  }
</style>