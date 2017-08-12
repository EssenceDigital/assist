/**
 * Uses a Vuetify data table to display all projects from the db. 
 * Uses the store to retrieve data.
 * Is used by multiple components, so the table_state prop determines what headers and fields will be displayed.
*/
<template>
  <v-container fluid>
    <!-- Row for project filter -->
    <v-layout row>
      <v-spacer></v-spacer>
      <!-- Province filter -->
      <v-flex xs2>
        <v-select
          v-model="provinceFilter"
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
          v-model="clientFilter"
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
            v-model="locationFilter"
          ></v-text-field>        
      </v-flex> 
      <v-spacer></v-spacer>
      <!-- Invoice status filter -->
      <v-flex xs2>
        <v-select
          v-model="invoiceFilter"
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

    <!-- Data table -->
    <v-data-table
        :headers="headers"
        :items="projects"
        :rows-per-page-items="perPage"
        class="elevation-1 mt-2"
      >    
      <template slot="items" scope="props">
        <td v-if="table_state === 'admin'" class="text-xs-right">{{ props.item.id }}</td>
        <td v-if="table_state === 'admin'" class="text-xs-right">{{ props.item.client_company_name }}</td>
        <td v-if="table_state === 'admin'" class="text-xs-right">{{ props.item.province }}</td>
        <td v-if="table_state === 'admin'" class="text-xs-right">{{ props.item.location }}</td>
        <td v-if="table_state === 'admin'" class="text-xs-right">
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
            Invoiced|Not Paid   
          </v-chip>
          <!-- Invoice paid chip-->
          <v-chip 
            v-if="props.item.invoice_paid_date"
            class="success white--text"
          >
            Paid   
          </v-chip> 
          <td class="text-xs-right">
            <v-btn 
              icon 
              v-tooltip:top="{ html: 'View Project ' + props.item.id }"
              v-if="table_state === 'admin'"
              @click.native.stop="viewProject(props.item.id)"
            >
              <v-icon>subdirectory_arrow_right</v-icon>
            </v-btn>
          </td>                
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
          <!-- Use the project view component to display project info -->
          <project-view></project-view>
        </v-card>
      </v-dialog>
    </v-layout><!-- Dialog to view a full project -->

  </v-container>

</template>

<script>
  import ProjectView from './Project-view';

  export default {
    // Determines what headers and fields the table should display ("admin" or "user")
    props: ['table_state'],

    components: {
      'project-view': ProjectView
    },

    data () {
      return {
        // The view project dialog window
        viewProjectDialog: false,
        // For data table pagination   
        perPage: [15, 30, 45, { text: "All", value: -1 }],
        // The headers the data table will use
        headers: [],
        // Admin state headers for data table
        adminStateHeaders: [
          { text: 'Identifier', value: 'id' },
          { text: 'Company Name', value: 'client_company_name' },
          { text: 'Province', value: 'province' },
          { text: 'Location', value: 'location' },
          { text: 'Invoice Status', value: 'invoiced_date' },
          { text: 'Actions', value: '' },
        ],
        // User state headers for data table
        userStateHeaders: [

        ],
        // For provinces filter
        provinces: [
          { text: 'Province...', value: '' },
          { text: 'Alberta', value: 'Alberta' },
          { text: 'British Columbia', value: 'British Columbia' },
          { text: 'Saskatchewan', value: 'Saskatchewan' }
        ],
        // Provinces filter
        provinceFilter: '',
        // Clients filter
        clientFilter: '',
        // For invoice status
        invoiceStatus: [
          { text: 'Invoice status...', value: '' },
          { text: 'Paid', value: '1' },
          { text: 'Outstanding', value: '0' }                 
        ],
        // Location filter
        locationFilter: '',        
        // Invoice status filter
        invoiceFilter: ''
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
      }
    },

    methods: {
      filterProjects () {
        this.$store.dispatch('getProjects', {
          client: this.clientFilter,
          province: this.provinceFilter,
          location: this.locationFilter,
          invoice: this.invoiceFilter
        });
      },

      viewProject (id) {
        // Update the requested project
        this.$store.dispatch('getProject', id);
        // Show dialog
        this.viewProjectDialog = true;
      }
    },

    created () {
      // For debug
      if(this.$store.getters.debug) console.log("Projects table created");

      // Set headers the data table will use based on the table state
      if(this.table_state === 'admin') {
        this.headers = this.adminStateHeaders;
      } else if(this.table_state === 'user') {
        this.headers = this.userStateHeaders;
      }

      // Tell store to load projects
      this.$store.dispatch('getProjects');
      // Tell store to update unique clients
      this.$store.dispatch('getClients');
    }

  }
</script>