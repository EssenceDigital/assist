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
  				{ text: "Use the view button at the end of each row to see the entire invoice." },
          { text: 'You can search your crews invoices using the filter below. The filters can be used one at a time or all at once.' } ,
          { text: "Clicking on a heading will sort the invoices accordingly." },
          { text: 'You can mark an invoice as paid by checking the box at the start of the desired row and then clicking the "Mark Invoices Paid" button.' }      
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