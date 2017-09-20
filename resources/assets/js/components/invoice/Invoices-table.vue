<template>
  <v-container fluid>

    <!-- Data table -->
    <v-data-table
      :headers="headers"
      :items="invoices"
      :rows-per-page-items="perPage"
      :loading="loading"
      class="elevation-1 mt-2"
    >    
      <template slot="items" scope="props">
      	<td>{{ props.item.id }}</td>
      	<td>{{ props.item.from_date | date }}</td>
      	<td>{{ props.item.to_date | date }}</td>
      	<td>
          <v-btn 
            icon 
            v-tooltip:top="{ html: 'View ' + props.item.id }"
            class="success--text"
            @click.native.stop="viewInvoice(props.item.id)"
          >
            <v-icon>subdirectory_arrow_right</v-icon>
          </v-btn>
        </td>           
      </template>
      <template slot="pageText" scope="{ pageStart, pageStop }">
        From {{ pageStart }} to {{ pageStop }}
      </template>    
    </v-data-table><!-- /Data table -->  


  </v-container><!-- /Container -->

</template>

<script>

  export default {
    // Determines what headers and fields the table should display ("admin" or "user")
    props: ['table_state'],

    components: {

    },

    data () {
      return {
        // Loader
        loading: false,
        // For data table pagination   
        perPage: [15, 30, 45, { text: "All", value: -1 }],
				headers: [
            { text: 'Identifier', value: 'id', align: 'left' },
            { text: 'From Date', value: 'from_date', align: 'left' },
            { text: 'To Date', value: 'to_date', align: 'left' },
            { text: 'Actions', value: '', align: 'left' }
          ]        
      }
    },

    computed: {
      // Watch for invoices in state to update
      invoices () {
        return this.$store.getters.invoices;
      }

    },

    methods: {
      viewInvoice (id) {
        // Admin state forward
        if(this.table_state === 'user') this.$router.push('/invoices/'+id+'/view');       
      }
    },

    created () {
      // Toggle loader
      this.loading = true;
      // Set headers the data table will use based on the table state
      var dispatchAction = '',
          payload = false;
      // Change action depending on table state
      if(this.table_state === 'user') {
        dispatchAction = 'getUsersInvoices';
      } else if(this.table_state === 'admin'){
        dispatchAction = 'getInvoices';        
      }

      // Tell store to load projects
      this.$store.dispatch(dispatchAction, payload)
	      .then(() => {   
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
</style>