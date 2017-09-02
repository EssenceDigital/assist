<template>
  <v-container fluid>
    <!-- Loading container -->
    <v-layout v-if="loading" row class="mt-5">
      <v-progress-linear v-bind:indeterminate="true"></v-progress-linear>
    </v-layout>

    <!-- Container for table and filter -->
    <v-container v-if="!loading" fluid >

      <!-- Data table -->
      <v-data-table
          :headers="headers"
          :items="users"
          :rows-per-page-items="perPage"
          class="elevation-1 mt-2"
        >    
        <template slot="items" scope="props">

          <td>{{ props.item.first + ' ' + props.item.last }}</td>
          <td>{{ props.item.company_name }}</td>
          <td>{{ props.item.email }}</td>  
          <td>{{ props.item.gst_number }}</td>        
          <td>
            <v-btn 
              icon 
              v-tooltip:top="{ html: 'View User'}"
              class="success--text"
              @click.native.stop="$router.push('/users/' + props.item.id + '/view')"
            >
              <v-icon>subdirectory_arrow_right</v-icon>
            </v-btn>            
          </td>
          
        </template>
        <template slot="pageText" scope="{ pageStart, pageStop }">
          From {{ pageStart }} to {{ pageStop }}
        </template>    
      </v-data-table><!-- /Data table -->  

    </v-container><!-- /Container for table and filter -->
  </v-container><!-- /Container -->

</template>

<script>
  import Helpers from './../../store/helpers';  

  export default {

    data () {
      return {
        // Loader
        loading: false,
        // For data table pagination   
        perPage: [15, 30, 45, { text: "All", value: -1 }],
        // The headers the data table will use
        headers: [
          { text: 'Name', value: '', align: 'left' },
          { text: 'Company', value: '', align: 'left' },
          { text: 'Email', value: '', align: 'left' },
          { text: 'GST Number', value: '', align: 'left' },
          { text: 'Actions', value: '', align: 'left' }
        ]
      }
    },

    computed: {
      users () {
        return this.$store.getters.users;
      }
    },

    created () {
      // Toggle loader
      this.loading = true;

      // Tell store to load projects
      this.$store.dispatch('getUsers').then( () => {
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