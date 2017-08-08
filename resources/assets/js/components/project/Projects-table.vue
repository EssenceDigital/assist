/**
 * Uses a Vuetify data table to display all projects from the db. 
 * Uses the store to retrieve data.
 * Is used by multiple components, so the table_state prop determines what headers and fields will be displayed.
*/
<template>
  <v-container>
    <v-layout row>
      <v-flex xs3>
        <h6>Province</h6>
        <v-select
          v-model="provinceFilter"
          :items="provinces"
          label="Select..."
          single-line
          bottom
        ></v-select>        
      </v-flex>
      <v-flex xs3>
        <h6>Client</h6>
        <v-select
          v-model="clientFilter"
          :items="clients"
          label="Select..."
          single-line
          bottom
        ></v-select>        
      </v-flex>  
      <v-flex xs2>
        <h6>Invoice Status</h6>
        <v-select
          v-model="invoiceFilter"
          :items="invoiceStatus"
          label="Select..."
          single-line
          bottom
        ></v-select>        
      </v-flex>          
      <v-flex xs1>
        <v-btn 
          flat
          class="mt-5" 
          v-tooltip:top="{ html: 'Filter Projects' }"
          @click="filterProjects"
        >
          <v-icon>search</v-icon>
          Filter
        </v-btn>
      </v-flex> 
    </v-layout>

    <v-data-table
        :headers="headers"
        :items="projects"
        :rows-per-page-items="perPage"
        class="elevation-1 mt-3"
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
            >
              <v-icon>subdirectory_arrow_right</v-icon>
            </v-btn>
          </td>                
        </td>
      </template>
      <template slot="pageText" scope="{ pageStart, pageStop }">
        From {{ pageStart }} to {{ pageStop }}
      </template>    
    </v-data-table>    
  </v-container>

</template>

<script>
  export default {
    // Determines what headers and fields the table should display ("admin" or "user")
    props: ['table_state'],

    data () {
      return {
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
          { text: 'Select...', value: '' },
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
          { text: 'Select...', value: '' },
          { text: 'Paid', value: '1' },
          { text: 'Outstanding', value: '0' }                 
        ],
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
        var clients = this.$store.getters.clients,
            clientFilter = [{ text: "Select...", value: "" }];
        clients.forEach(function(client){
          clientFilter.push({ text: client, value: client });
        });
        return clientFilter;
      }
    },

    methods: {
      filterProjects (){
        console.log(this.provinceFilter);
        this.$store.dispatch('getProjects', {
          client: this.clientFilter,
          province: this.provinceFilter,
          invoice: this.invoiceFilter
        });
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