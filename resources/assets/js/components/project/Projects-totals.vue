<template>
  <!-- -->
  <v-container>
    <v-divider class="mb-2"></v-divider>    
    <v-layout row>
      <v-flex xs4>
        <span class="title red--text">OUTSTANDING INVOICES:</span>
      </v-flex>
      <v-spacer></v-spacer>
      <v-flex xs2 class="text-xs-right">
        <span class="title red--text">${{ invoicesOutstandingTotal }}</span>
      </v-flex>
    </v-layout>
    <v-divider class="mt-2"></v-divider>              
  </v-container><!--  -->  
</template>

<script>
	export default {
    props: ['projects'],

    computed: {
      invoicesPaidTotal () {
        var total = 0;
        this.projects.forEach((project) => {
          if(project.invoice_paid_date != null) {
            total += (parseFloat(project.invoice_amount) * 100);
          }
        });
        return new Intl.NumberFormat('en-US', { 
          minimumFractionDigits: 2,
          maximumFractionDigits: 2 
        }).format((total / 100));
      },

      invoicesOutstandingTotal () {
        var total = 0;
        this.projects.forEach((project) => {
          if(project.invoice_paid_date === null) {
            total += (parseFloat(project.invoice_amount) * 100);
          }
        });
        return new Intl.NumberFormat('en-US', { 
          minimumFractionDigits: 2,
          maximumFractionDigits: 2 
        }).format((total / 100));
      }            
    }
  }
</script>