/**
 * Uses a Vuetify data table to display all projects from the db. 
 * Uses the store to retrieve data.
 * Is used by multiple components, so the table_state prop determines what headers and fields will be displayed.
*/
<template>
  <v-container fluid>
    <!-- Container for table and filter -->
    <v-container v-if="!loading" fluid >
      <!-- Row for project filter -->
      <v-layout row v-if="table_state === 'admin_work' || table_state === 'admin_manage'">
        <v-spacer></v-spacer>
        <!-- Province filter -->
        <v-flex xs2>
          <v-select            
            :value="projectsFilter.province"
            @input="updateProvinceFilter"
            :items="provinces"
            label="Province..."
            single-line
            bottom
          ></v-select>        
        </v-flex>
        <v-spacer></v-spacer>
        <!-- Client filter -->
        <v-flex xs2>
          <v-select            
            :value="projectsFilter.client"
            @input="updateClientFilter"
            :items="clients"
            label="Client..."
            single-line
            bottom
          ></v-select>        
        </v-flex> 
        <v-spacer></v-spacer>
        <!-- Location filter -->
        <v-flex xs3>
            <v-text-field
              label="Enter part of location..."              
              :value="projectsFilter.location"
              @input="updateLocationFilter"
            ></v-text-field>        
        </v-flex> 
        <v-spacer></v-spacer>        
        <!-- Invoice status filter -->
        <v-flex xs2 v-if="table_state === 'admin_manage'">
          <v-select            
            :value="projectsFilter.invoice"
            @input="updateInvoiceFilter"
            :items="invoiceStatus"
            label="Invoice status..."
            single-line
            bottom
          ></v-select>        
        </v-flex> 
        <v-spacer></v-spacer> 
        <!-- Filter button -->        
        <v-flex xs1>
          <v-btn 
            icon
            class="mt-3" 
            v-tooltip:top="{ html: 'Filter Projects' }"
            @click="filterProjects"
          >
            <v-icon>search</v-icon>
          </v-btn>
        </v-flex> 
      </v-layout><!-- /Row for project filter -->

      <!-- Row for action buttons -->
      <v-layout row>
        <!-- Clear filter -->
        <v-flex xs2 class="ml-2">
          <v-btn @click="resetFilter" class="info">
            <v-icon left>cached</v-icon>
            Reset Filter
          </v-btn>       
        </v-flex><!-- / Clear filter -->            
      </v-layout><!-- /Row for action buttons -->

      <v-layout row v-if="table_state === 'admin_manage'">
        <projects-totals :projects="projects"></projects-totals>
      </v-layout>

      <!-- Data table -->
      <v-data-table
        :headers="headers"
        :items="projects"
        :rows-per-page-items="perPage"
        class="elevation-1 mt-4"
      >    
        <template slot="items" scope="props">
          <td>{{ props.item.id }}</td>

          <td v-if="table_state === 'admin_work' || table_state === 'admin_manage'">{{ props.item.client_company_name }}</td>

          <td v-if="table_state === 'admin_work' || table_state === 'user'">{{ props.item.province }}</td>

          <td>{{ props.item.location }}</td>



          <td v-if="table_state === 'admin_work'">
            {{ props.item.work_type }}
          </td>

          <td v-if="table_state === 'admin_manage'">
            <!-- Display different chip depending on invoice status.
            Not Invoiced chip -->
            <v-chip 
              v-if="props.item.invoiced_date === null"
              class="warning white--text"
            >
              Not Invoiced    
            </v-chip>
            <!-- Invoiced and not paid chip -->
            <v-chip 
              v-if="props.item.invoiced_date && props.item.invoice_paid_date === null"
              class="error white--text"
            >
              Not Paid   
            </v-chip>
            <!-- Invoice paid chip-->
            <v-chip 
              v-if="props.item.invoice_paid_date"
              class="success white--text"
            >
              Paid   
            </v-chip>                 
          </td>

          <td v-if="table_state === 'admin_manage'">
            {{ props.item.invoice_amount | money }}
          </td>

          <td v-if="table_state === 'admin_manage'">
            <span>{{ totalProjectCost(props.item.work_items) | money }}</span>
          </td>
          <td v-if="table_state === 'admin_manage'">
            {{ totalPaidProjectCost(props.item.work_items) | money }}
          </td>
          <td v-if="table_state === 'admin_manage'">
            {{ (parseFloat(totalProjectCost(props.item.work_items))) - (parseFloat(totalPaidProjectCost(props.item.work_items))) | money }}
          </td>          
          <!--<td v-if="table_state === 'admin_manage'">
            <span v-if="!props.item.invoice_paid_date">
              -{{ projectBottomLine(props.item.work_items, props.item.invoice_amount, props.item.invoice_paid_date) | money }}
            </span>
            <span v-else>
              {{ projectBottomLine(props.item.work_items, props.item.invoice_amount, props.item.invoice_paid_date) | money }}
            </span>
          </td>-->

          <td v-if="table_state === 'user'">{{ props.item.timesheets.length }}</td>

          <td>
            <v-btn 
              icon 
              v-tooltip:top="{ html: 'View ' + props.item.id }"
              class="success--text"
              @click.native.stop="viewProject(props.item.id)"
            >
              <v-icon>subdirectory_arrow_right</v-icon>
            </v-btn>
          </td>           
        </template>
        <template slot="pageText" scope="{ pageStart, pageStop }">
          From {{ pageStart }} to {{ pageStop }}
        </template>    
      </v-data-table><!-- /Data table -->  

      <!-- Dialog to view a full project -->
      <v-layout row justify-center>
        <v-dialog v-model="viewProjectDialog" fullscreen transition="dialog-bottom-transition" :overlay=false>
          <v-card>
            <v-toolbar dark class="primary">
              <v-btn icon @click.native="viewProjectDialog = false" dark>
                <v-icon>close</v-icon>
              </v-btn>
              <v-toolbar-title>
                Close
              </v-toolbar-title>
              <v-spacer></v-spacer>
            </v-toolbar>
          </v-card>
        </v-dialog>
      </v-layout><!-- Dialog to view a full project -->

    </v-container><!-- /Container for table and filter -->
  </v-container><!-- /Container -->

</template>

<script>
  import BusLogic from './../../resources/bus-logic';
  import ProjectsTotals from './Projects-totals';

  export default {
    // Determines what headers and fields the table should display ("admin" or "user")
    props: ['table_state'],

    components: {
      'projects-totals': ProjectsTotals
    },

    data () {
      return {
        // Loader
        loading: false,
        // Curret project id
        currentProjectId: -1,
        // For the table state switch (work or manage)
        tableStateSwitch: false,
        // The view project dialog window
        viewProjectDialog: false,
        // For data table pagination   
        perPage: [15, 30, 45, { text: "All", value: -1 }],
        // For provinces filter
        provinces: [
          { text: 'Province...', value: '' },
          { text: 'Alberta', value: 'Alberta' },
          { text: 'British Columbia', value: 'British Columbia' },
          { text: 'Saskatchewan', value: 'Saskatchewan' }
        ],
        // For invoice status
        invoiceStatus: [
          { text: 'Invoice status...', value: '' },
          { text: 'Not Invoiced', value: 'not-invoiced' },
          { text: 'Paid', value: 'paid' },
          { text: 'Outstanding', value: 'outstanding' }                 
        ]
      }
    },

    computed: {
      // Watch for projects state to update
      projects () {
        return this.$store.getters.projects;
      },

      // Watch for uniqueCLients state to update
      clients () {
        return this.$store.getters.clientsSelectList;
      },

      projectsFilter () {
        return this.$store.getters.projectsFilter;
      },

      headers () {
        if(this.table_state === 'admin_work'){
          var headers = [
            { text: 'ID', value: 'id', align: 'left' },
            { text: 'Client', value: 'client_company_name', align: 'left' },
            { text: 'Province', value: 'province', align: 'left' },
            { text: 'Location', value: 'location', align: 'left' },
            { text: 'Work', value: 'work_type', align: 'left' },
            { text: 'Actions', value: '', align: 'left' }
          ];
        }
        if(this.table_state === 'admin_manage'){
          var headers = [
            { text: 'ID', value: 'id', align: 'left' },
            { text: 'Client', value: 'client_company_name', align: 'left' },
            { text: 'Location', value: 'location', align: 'left' },
            { text: 'Invoice Status', value: 'invoice_status', align: 'left' },
            { text: 'Invoice Amount', value: 'invoice_amount', align: 'left' },
            { text: 'Crew Cost', value: 'crew_cost', align: 'left' },
            { text: 'Paid Crew', value: 'crew_paid', align: 'left' },
            { text: 'Owing Crew', value: 'crew_owing', align: 'left' },
            { text: 'Actions', value: '', align: 'left' }
          ];
        }
        if(this.table_state === 'user'){
          var headers = [
            { text: 'ID', value: 'id', align: 'left' },
            { text: 'Province', value: 'province', align: 'left' },
            { text: 'Location', value: 'location', align: 'left' },
            { text: 'Actions', value: '', align: 'left' }          
          ];
        }
        return headers;
      }

    },

    methods: {
      updateProvinceFilter (value) {
        return this.$store.commit('updateProjectsProvinceFilter', value);
      },

      updateClientFilter (value) {
        return this.$store.commit('updateProjectsClientFilter', value);
      },

      updateLocationFilter (value) {
        return this.$store.commit('updateProjectsLocationFilter', value);
      },

      updateInvoiceFilter (value) {
        return this.$store.commit('updateProjectsInvoiceFilter', value);
      },

      resetFilter () {
        return this.$store.commit('resetProjectsFilter');
      },

      filterProjects () {
        // Toggle loader
        this.loading = true;
        // Dispatch action to find projects
        this.$store.dispatch('getProjects', this.projectsFilter).then( () => {
          // Toggle loader
          this.loading = false;
        });
      },

      viewProject (id) {
        // Admin state forward
        if(this.table_state === 'admin_work' || this.table_state === 'admin_manage') this.$router.push('/projects/'+id+'/view');
        // User state forward
        if(this.table_state === 'user') this.$router.push('/projects/'+id+'/timesheets');        
      },

      totalProjectCost (workItems) {
        return BusLogic.tallyWorkItemsTotal(workItems).toFixed(2);
      },

      totalPaidProjectCost (workItems) {
        return BusLogic.tallyPaidWorkItemsTotal(workItems).toFixed(2);
      },

      projectBottomLine (workItems, invoiceAmount, invoicePaidDate) {
        if(invoicePaidDate === null){
         var total = (parseFloat(invoiceAmount) + parseFloat(BusLogic.tallyWorkItemsTotal(workItems))).toFixed(2);
        } else {
          var total = (parseFloat(invoiceAmount) - parseFloat(BusLogic.tallyWorkItemsTotal(workItems))).toFixed(2);
        }
        return total;
      }
    },

    created () {
      // For debug
      if(this.$store.getters.debug) console.log("Projects table created");
      // Toggle loader
      this.loading = true;
      // Set headers the data table will use based on the table state
      var dispatchAction = '',
          payload = false;
      if(this.table_state === 'admin_work') {
        dispatchAction = 'getProjects';
        // Populate payload
        payload = this.projectsFilter;
      } else if(this.table_state === 'admin_manage'){
        dispatchAction = 'getProjects'; 
        // Populate payload
        payload = this.projectsFilter;     
      } else if(this.table_state === 'user') {
        dispatchAction = 'getUsersProjects';
        payload = {
          user_id: this.$store.getters.user.id
        };
      }

      // Tell store to load projects
      this.$store.dispatch(dispatchAction, payload).then( () => {
        // Tell store to update unique clients
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
</style>