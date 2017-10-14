<template>
  <v-container fluid>
      <!-- Row for invoice filter -->
      <v-layout row v-if="table_state === 'admin'">
        <!-- User filter -->
        <v-flex xs2 class="ml-2">
          <v-select
            :value="invoicesFilter.user"
            @input="updateUserFilter"
            :items="usersSelectList"
            label="User..."
            single-line
            bottom
          ></v-select>        
        </v-flex>
        <v-spacer></v-spacer>
        <v-flex xs3>
          <v-menu
            lazy
            :close-on-content-click="false"
            v-model="fromDateFilterMenu"
            transition="scale-transition"
            offset-y
            full-width
            :nudge-left="40"
            max-width="290px"
          >
            <v-text-field
              slot="activator"
              :value="invoicesFilter.from_date"
              @input="updateFromDateFilter"
              label="From Date..."
              prepend-icon="event"
              readonly
            ></v-text-field>
            <v-date-picker
              :value="invoicesFilter.from_date"
              @input="updateFromDateFilter"            
              no-title 
              scrollable 
              actions
            >
              <template scope="{ save, cancel }">
                <v-card-actions>
                  <v-btn flat primary @click.native="cancel()">Cancel</v-btn>
                  <v-btn flat primary @click.native="save()">Save</v-btn>
                </v-card-actions>
              </template>
            </v-date-picker>
          </v-menu>               
        </v-flex> 
        <v-spacer></v-spacer>
        <v-flex xs3>
          <v-menu
            lazy
            :close-on-content-click="false"
            v-model="toDateFilterMenu"
            transition="scale-transition"
            offset-y
            full-width
            :nudge-left="40"
            max-width="290px"
          >
            <v-text-field
              slot="activator"
              :value="invoicesFilter.to_date"
              @input="updateToDateFilter"
              label="To Date..."
              prepend-icon="event"
              readonly
            ></v-text-field>
            <v-date-picker
              :value="invoicesFilter.to_date"
              @input="updateToDateFilter"            
              no-title 
              scrollable 
              actions
            >
              <template scope="{ save, cancel }">
                <v-card-actions>
                  <v-btn flat primary @click.native="cancel()">Cancel</v-btn>
                  <v-btn flat primary @click.native="save()">Save</v-btn>
                </v-card-actions>
              </template>
            </v-date-picker>
          </v-menu>               
        </v-flex>         
        <v-spacer></v-spacer>
        <!-- Invoice status filter -->
        <v-flex xs2>
          <v-select            
            :value="invoicesFilter.invoice"
            @input="updateInvoiceFilter"
            :items="invoiceStatus"
            label="Invoice status..."
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
        <!-- Clear filter -->
        <v-flex xs2 class="ml-2">
          <v-btn @click="resetFilter" class="info">
            <v-icon left>cached</v-icon>
            Reset Filter
          </v-btn>       
        </v-flex><!-- / Clear filter -->        
        <!-- Mark paid -->
        <v-flex xs2 class="ml-2">
          <v-btn 
          @click="markPaidDialog = true"
          class="success"
          >
            <v-icon left>check_circle</v-icon>
            Mark Invoices Paid
          </v-btn>       
        </v-flex><!-- / Mark paid -->      
      </v-layout><!-- /Row for action buttons -->

    <!-- Data table -->
    <v-data-table
      :headers="headers"
      :items="invoices"
      :rows-per-page-items="perPage"
      :loading="loading"
      v-model="selected"
      :select-all="selectAll"
      selected-key="id"      
      class="elevation-1 mt-3"
    >    
      <template slot="items" scope="props">
        <td v-if="table_state === 'admin'">
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
        <td>{{ invoiceTotal(props.item.work_items) | money }}</td>
        <td v-if="table_state === 'user'">
          <v-chip 
            v-if="!props.item.is_published"
            class="red white--text"
            >Not Published
          </v-chip>
          <v-chip
            v-else
            class="green white--text"
          >
            Published
          </v-chip>
        </td>        
        <td>
          <v-chip 
            v-if="!props.item.is_paid"
            class="red white--text"
            >Not Paid
          </v-chip>
          <v-chip
            v-else
            class="green white--text"
          >
            Paid
          </v-chip>
        </td>        
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

    <!-- Confirm mark paid dialog -->
    <v-layout 
      row 
      justify-center 
      class="mr-0" 
      style="position: relative;"
    >
      <v-dialog v-model="markPaidDialog" width="365" lazy absolute persistent>      
        <v-card>
          <v-card-title>
            <div class="headline grey--text">Mark as paid?</div>                           
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            Are you sure you want to mark these invoices as paid?
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <!-- Cancel delete button-->
            <v-btn outline 
              class="red--text darken-1" 
              flat="flat" 
              @click.native.stop="markPaidDialog = false">
                Maybe not
            </v-btn>
            <!-- Confirm delete button -->
            <v-btn outline 
            class="green--text darken-1" 
            flat="flat" 
            :loading="markingPaid" 
            :disable="markingPaid" 
            @click.native.stop="markPaid">
              Do it
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout><!-- / Confirm mark paid dialog --> 

  </v-container><!-- /Container -->

</template>

<script>
  import BusLogic from "./../../resources/bus-logic";

  export default {
    // Determines what headers and fields the table should display ("admin" or "user")
    props: ['table_state'],

    data () {
      return {
        // Loader
        loading: false,
        // For data table pagination   
        perPage: [15, 30, 45, { text: "All", value: -1 }],
        // Selected items
        selected: [],
        // For the mark paid confirmation dialog
        markPaidDialog: false,
        // For mark paid button
        markingPaid: false,   
        // For from date filter
        fromDateFilterMenu: false,
        // For to date filter
        toDateFilterMenu: false,
        // For invoice status
        invoiceStatus: [
          { text: 'Invoice status...', value: '' },
          { text: 'Not Paid', value: 'not-paid' },
          { text: 'Paid', value: 'paid' }                 
        ],
        // For the select all table option - Can be changed by created hook
        selectAll: 'default'       
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

      invoicesFilter () {
        return this.$store.getters.invoicesFilter;
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
            { text: 'Amount', value: 'amount', align: 'left' },
            { text: 'Paid?', value: 'is_paid', align: 'left' },
            { text: 'Actions', value: '', align: 'left' }
          ];
        }
        if(this.table_state === 'user') {
          var headers = [
            { text: 'Identifier', value: 'id', align: 'left' },
            { text: 'From Date', value: 'from_date', align: 'left' },
            { text: 'To Date', value: 'to_date', align: 'left' },
            { text: 'Published?', value: 'is_published', align: 'left' },            
            { text: 'Paid?', value: 'is_paid', align: 'left' },
            { text: 'Actions', value: '', align: 'left' }
          ];          
        }

        return headers;
      }      

    },

    methods: {
      invoiceTotal (workItems) {
        return BusLogic.tallyWorkItemsTotal(workItems).toFixed(2);
      },

      viewInvoice (id) {
        // User state forward
        if(this.table_state === 'user') this.$router.push('/my-invoices/'+id+'/view');
        // Admin state forward
        if(this.table_state === 'admin') this.$router.push('/crew-invoices/'+id+'/view');  
      },

      updateUserFilter (value) {
        return this.$store.commit('updateInvoicesUserFilter', value);
      },

      updateInvoiceFilter (value) {
        return this.$store.commit('updateInvoicesInvoiceFilter', value);
      },

      updateFromDateFilter (value) {
        return this.$store.commit('updateInvoicesFromDateFilter', value);
      },

      updateToDateFilter (value) {
        return this.$store.commit('updateInvoicesToDateFilter', value);
      },

      resetFilter () {
        this.$store.commit("resetInvoicesFilter");
      },

      filterInvoices () {
        // Toggle loader
        this.loading = true;
        // Dispatch action to find projects
        this.$store.dispatch('getAllInvoices', this.invoicesFilter)
          .then(() => {
            // Toggle loader
            this.loading = false;
          });        
      },

      markPaid () {
        // Toggle loader
        this.markingPaid = true;
        // Will hold the invoice ids to be marked paid
        var selectedIds = { id: [] };
        // Populate the Ids
        this.selected.forEach((invoice) => {
          selectedIds.id.push(invoice.id)
        });

        // Dispatch action to store
        this.$store.dispatch("markInvoicesPaid", selectedIds)
          .then(() => {
            // Clear selected invoices
            this.selected = [];
            // Toggle loader
            this.markingPaid = false;
            // Toggle dialog
            this.markPaidDialog = false;
          })
          .catch((error) => {
            console.log(error);
          });
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
        this.selectAll = false;
      } else if(this.table_state === 'admin'){
        dispatchAction = 'getAllInvoices'; 
        payload = this.invoicesFilter;       
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