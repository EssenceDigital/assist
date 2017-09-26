<template>
  <v-container fluid>
      <!-- Row for invoice filter -->
      <v-layout row v-if="table_state === 'admin'">
        <!-- Province filter -->
        <v-flex xs3 class="ml-2">
          <v-select
            v-model="userFilter"
            :items="usersSelectList"
            label="User..."
            single-line
            bottom
          ></v-select>        
        </v-flex>
        <!-- Filter button -->        
        <v-flex xs1>
          <v-btn 
            icon
            class="mt-3" 
            v-tooltip:top="{ html: 'Filter Invoices' }"
            @click="filterInvoices"
          >
            <v-icon>search</v-icon>
          </v-btn>
        </v-flex> 
      </v-layout><!-- /Row for invoice filter -->

      <!-- Row for action buttons -->
      <v-layout row v-if="table_state === 'admin'">
        <!-- Province filter -->
        <v-flex xs3 class="ml-2">
          <v-btn 
          @click="markPaid"
          class="success"
          >
            <v-icon left>check_circle</v-icon>
            Mark Paid
          </v-btn>       
        </v-flex>
      </v-layout><!-- /Row for action buttons -->

    <!-- Data table -->
    <v-data-table
      :headers="headers"
      :items="invoices"
      :rows-per-page-items="perPage"
      :loading="loading"
      v-model="selected"
      select-all
      selected-key="id"      
      class="elevation-1 mt-3"
    >    
      <template slot="items" scope="props">
        <td>
          <v-checkbox
            primary
            hide-details
            v-model="props.selected"
          ></v-checkbox>
        </td>      
      	<td>{{ props.item.id }}</td>
        <td v-if="table_state === 'admin'">{{ props.item.user.first }}</td>
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
        // For the invoice filter
        userFilter: '' ,
        // Selected items
        selected: []      
      }
    },

    computed: {
      // Watch for invoices in state to update
      invoices () {
        if(this.table_state){
          if(this.table_state === 'user'){
            return this.$store.getters.invoices;
          }
          if(this.table_state === 'admin'){
            return this.$store.getters.crewInvoices;
          }
        }
      },

      usersSelectList () {
        return this.$store.getters.usersSelectList;
      },

      headers () {
        if(this.table_state === 'admin') {
          var headers = [
            { text: 'Identifier', value: 'id', align: 'left' },
            { text: 'User', value: 'user', align: 'left' },            
            { text: 'From Date', value: 'from_date', align: 'left' },
            { text: 'To Date', value: 'to_date', align: 'left' },
            { text: 'Actions', value: '', align: 'left' }
          ];
        }
        if(this.table_state === 'user') {
          var headers = [
            { text: 'Identifier', value: 'id', align: 'left' },
            { text: 'From Date', value: 'from_date', align: 'left' },
            { text: 'To Date', value: 'to_date', align: 'left' },
            { text: 'Actions', value: '', align: 'left' }
          ];          
        }

        return headers;
      }      

    },

    methods: {
      viewInvoice (id) {
        // User state forward
        if(this.table_state === 'user') this.$router.push('/my-invoices/'+id+'/view');
        // Admin state forward
        if(this.table_state === 'admin') this.$router.push('/my-invoices/'+id+'/view');  
      },

      filterInvoices () {
        // Toggle loader
        this.loading = true;
        // Dispatch action to find projects
        this.$store.dispatch('getAllInvoices', {
          user_id: this.userFilter
        })
          .then(() => {
            // Toggle loader
            this.loading = false;
          });        
      },

      markPaid () {
        // Will hold the invoice ids to be marked paid
        var selectedIds = [];
        // Populate the Ids
        this.selected.forEach((timesheet) => {
          selectedIds.push(timesheet.id);
        });

        console.log(selectedIds);
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
        dispatchAction = 'getAllInvoices';        
      }

      // Tell store to load projects
      this.$store.dispatch(dispatchAction, payload)
	      .then(() => {   
          if(dispatchAction === 'getAllInvoices'){
            this.$store.dispatch('getUsers')
              .then(() => {
                // Toggle loader
                this.loading = false;                 
              })
          } else {
            // Toggle loader
            this.loading = false;             
          }
 
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