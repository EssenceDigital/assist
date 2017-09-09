<template>
  <!-- Totals row -->
  <v-container fluid class="pl-0 pr-0">
    <!-- Data table -->
    <v-data-table
      :headers="[
        { text: 'Total Paid Invoices', align: 'center', sortable: false },
        { text: 'Total Outstanding Invoices', align: 'center', sortable: false },
      ]"
      :items="[
        { 
          invoices_paid_total: invoicesPaidTotal,
          invoices_outstanding_total: invoicesOutstandingTotal
        }
      ]"        
      class="elevation-1 mt-1 mb-4"
      hide-actions
    >    
      <template slot="items" scope="props">
        <td class="text-xs-center">
          <v-chip class="success white--text">
            ${{ props.item.invoices_paid_total }}
          </v-chip>
        </td> 
        <td class="text-xs-center">
          <v-chip class="error white--text">
            ${{ props.item.invoices_outstanding_total }}
          </v-chip>
        </td>                                             
      </template>  
    </v-data-table><!-- /Data table -->    
  
  </v-container>
</template>

<script>
	export default {
    props: ['projects'],

    computed: {
      invoicesPaidTotal () {
        var total = 0;
        this.projects.forEach((project) => {
          if(project.invoice_paid_date != null) {
            total += parseFloat(project.invoice_amount);
          }
        });
        return total.toFixed(2);
      },

      invoicesOutstandingTotal () {
        var total = 0;
        this.projects.forEach((project) => {
          if(project.invoice_paid_date === null) {
            total += parseFloat(project.invoice_amount);
          }
        });
        return total.toFixed(2);
      }            
    }
  }
</script>